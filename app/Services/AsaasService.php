<?php

namespace App\Services;

class AsaasService
{
    private $apiKey;
    private $baseUrl;
    private $logFile;

    public function __construct()
    {
        $envBase = $_ENV['ASAAS_BASE_URL'] ?? $_SERVER['ASAAS_BASE_URL'] ?? getenv('ASAAS_BASE_URL');
        $envKey = $_ENV['ASAAS_API_KEY'] ?? $_SERVER['ASAAS_API_KEY'] ?? getenv('ASAAS_API_KEY');
        $envEnv = $_ENV['ASAAS_ENV'] ?? $_SERVER['ASAAS_ENV'] ?? getenv('ASAAS_ENV') ?: 'sandbox';
        $envLog = $_ENV['ASAAS_LOG_PATH'] ?? $_SERVER['ASAAS_LOG_PATH'] ?? getenv('ASAAS_LOG_PATH');

        if ($envBase) {
            $this->baseUrl = rtrim(trim($envBase), '/');
        } else {
            $this->baseUrl = $envEnv === 'production'
                ? 'https://www.asaas.com/api/v3'
                : 'https://api-sandbox.asaas.com/v3';
        }

        $this->apiKey = trim($envKey ?: '');
        $this->logFile = $envLog ?: (__DIR__ . '/../../storage/logs/asaas.log');
        $this->initLogPath();
        if (empty($this->apiKey)) {
            $this->logError('Config', 'ASAAS_API_KEY não configurada nas variáveis de ambiente');
            throw new \Exception('ASAAS_API_KEY não configurada nas variáveis de ambiente.');
        }
    }

    public function createCustomer($data)
    {
        $payload = [
            'name' => $data['nome'],
            'cpfCnpj' => $data['cnpj'],
            'email' => $data['email'],
            'phone' => $data['telefone'],
            'postalCode' => $data['cep'],
            'address' => $data['endereco'],
            'addressNumber' => $data['numero'],
            'complement' => $data['complemento'] ?? '',
            'province' => $data['bairro'],
            'externalReference' => $data['id']
        ];

        return $this->request('POST', '/customers', $payload);
    }

    public function createPayment($customerId, $amount, $description, $dueDate)
    {
        $payload = [
            'customer' => $customerId,
            'billingType' => 'UNDEFINED',
            'value' => $amount,
            'dueDate' => $dueDate,
            'description' => $description
        ];

        return $this->request('POST', '/payments', $payload);
    }

    public function createPixPayment($customerId, $amount, $description, $dueDate)
    {
        $payload = [
            'customer' => $customerId,
            'billingType' => 'PIX',
            'value' => $amount,
            'dueDate' => $dueDate,
            'description' => $description
        ];
        return $this->request('POST', '/payments', $payload);
    }

    public function getPixQrCode($paymentId)
    {
        return $this->request('GET', '/payments/' . $paymentId . '/pixQrCode');
    }

    public function getPayment($paymentId)
    {
        return $this->request('GET', '/payments/' . $paymentId);
    }

    public function cancelPayment($paymentId)
    {
        return $this->request('POST', '/payments/' . $paymentId . '/cancel');
    }

    private function request($method, $endpoint, $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $headers = [
            'Content-Type: application/json',
            'access_token: ' . $this->apiKey
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            $this->logError('Curl', $error, ['endpoint' => $endpoint, 'method' => $method]);
            throw new \Exception("Curl Error: $error");
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            $msg = isset($decoded['errors'][0]['description']) ? $decoded['errors'][0]['description'] : ($decoded['message'] ?? 'HTTP Error ' . $httpCode);
            $this->logError('HTTP', $msg, ['endpoint' => $endpoint, 'code' => $httpCode, 'response' => $this->truncate($response)]);
            throw new \Exception($msg);
        }

        if (isset($decoded['errors']) && !empty($decoded['errors'])) {
            $msg = $decoded['errors'][0]['description'] ?? 'Erro na API Asaas';
            $this->logError('API', $msg, ['endpoint' => $endpoint, 'response' => $this->truncate($response)]);
            throw new \Exception($msg);
        }

        return $decoded;
    }

    private function initLogPath()
    {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        if (!file_exists($this->logFile)) {
            @file_put_contents($this->logFile, '');
        }
    }

    private function logError($context, $message, array $meta = [])
    {
        $entry = [
            'time' => date('Y-m-d H:i:s'),
            'context' => $context,
            'message' => $message,
            'meta' => $meta
        ];
        $line = json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        @file_put_contents($this->logFile, $line, FILE_APPEND);
    }

    private function truncate($str, $len = 2000)
    {
        if (!is_string($str)) return '';
        return strlen($str) > $len ? substr($str, 0, $len) . '...' : $str;
    }
}
