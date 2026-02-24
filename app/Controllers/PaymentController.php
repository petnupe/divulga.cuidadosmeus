<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transacao;
use App\Services\AsaasService;
use App\Models\ILPI;
use App\Models\Plano;
use App\Models\Renovacao;

class PaymentController extends Controller
{
    public function generatePix()
    {
        if (!isset($_SESSION['ilpi_id'])) {
            $this->redirect('/ilpi/login');
        }

        $ilpiId = $_SESSION['ilpi_id'];
        $isAjax = isset($_GET['ajax']) && $_GET['ajax'] == '1';
        $transacaoModel = new Transacao();
        $pending = null;
        $currentPlanoNome = null;
        $currentValor = null;
        $currentDueDate = null;

        try {
            $asaas = new AsaasService();
            $allPendings = $transacaoModel->findAllPendingByIlpiId($ilpiId);
            if (!empty($allPendings)) {
                foreach ($allPendings as $tx) {
                    if (!empty($tx['asaas_id'])) {
                        try {
                            $asaas->cancelPayment($tx['asaas_id']);
                        } catch (\Exception $e) {}
                    }
                    $transacaoModel->updateStatus($tx['id'], 'CANCELLED');
                }
            }
            {
                $ilpiModel = new ILPI();
                $planoModel = new Plano();
                $ilpi = $ilpiModel->getWithDetails($ilpiId);
                $requestedPlanoId = isset($_GET['plano_id']) ? (int)$_GET['plano_id'] : null;
                $plano = $requestedPlanoId ? $planoModel->find($requestedPlanoId) : $planoModel->find($ilpi['plano_id']);

                if ($plano && isset($plano['valor']) && $plano['valor'] > 0) {
                    $customer = $asaas->createCustomer($ilpi);
                    if (isset($customer['id'])) {
                        $renovacaoModel = new Renovacao();
                        $historico = $renovacaoModel->findByIlpiId($ilpiId);
                        $ultima = $historico[0] ?? null;
                        $hoje = date('Y-m-d');
                        $dueDate = $ultima && $ultima['data_vencimento'] >= $hoje
                            ? $ultima['data_vencimento']
                            : date('Y-m-d', strtotime('+3 days'));

                        $tipo = $ultima ? 'RENOVACAO' : 'ADESAO';
                        $descricao = ($tipo === 'RENOVACAO' ? 'Renovação Plano ' : 'Adesão Plano ') . $plano['nome'];
                        $currentPlanoNome = $plano['nome'];
                        $currentValor = $plano['valor'];
                        $currentDueDate = $dueDate;

                        $payment = $asaas->createPixPayment($customer['id'], $plano['valor'], $descricao, $dueDate);
                        if (isset($payment['id'])) {
                            $transacaoModel->create([
                                'ilpi_id' => $ilpiId,
                                'plano_id' => $plano['id'],
                                'asaas_id' => $payment['id'],
                                'asaas_customer_id' => $customer['id'],
                                'valor' => $plano['valor'],
                                'status' => $payment['status'] ?? 'PENDING',
                                'url_pagamento' => $payment['invoiceUrl'] ?? null,
                                'pix_payload' => null,
                                'pix_qr_base64' => null,
                                'tipo' => $tipo
                            ]);
                             $pending = $transacaoModel->findPendingByIlpiId($ilpiId);
                        }
                    }
                }
            }
            if ($pending && !empty($pending['asaas_id'])) {
                $pix = $asaas->getPixQrCode($pending['asaas_id']);
                if (isset($pix['payload']) || isset($pix['encodedImage'])) {
                    $transacaoModel->updatePixData($pending['id'], $pix['payload'] ?? null, $pix['encodedImage'] ?? null);
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'ok' => true,
                            'payload' => $pix['payload'] ?? null,
                            'qr' => $pix['encodedImage'] ?? null,
                            'invoiceUrl' => $pending['url_pagamento'] ?? null,
                            'transacaoId' => $pending['id'],
                            'planoNome' => $currentPlanoNome,
                            'valor' => $currentValor,
                            'dueDate' => $currentDueDate,
                        ]);
                        return;
                    } else {
                        $this->redirect('/ilpi/dashboard?pix=ok');
                        return;
                    }
                }
            }
        } catch (\Exception $e) {
            $msg = urlencode(substr($e->getMessage(), 0, 120));
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'ok' => false,
                    'error' => $e->getMessage(),
                ]);
                return;
            } else {
                $this->redirect('/ilpi/dashboard?pix_error=1&msg=' . $msg);
                return;
            }
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'ok' => false,
                'error' => 'Não foi possível gerar o PIX.',
            ]);
            return;
        } else {
            $this->redirect('/ilpi/dashboard?pix_error=1');
        }
    }

    public function status()
    {
        if (!isset($_SESSION['ilpi_id'])) {
            $this->jsonResponse(['ok' => false, 'error' => 'not_authenticated'], 401);
        }
        $ilpiId = $_SESSION['ilpi_id'];
        $transacaoId = isset($_GET['transacao_id']) ? (int)$_GET['transacao_id'] : null;
        $transacaoModel = new Transacao();
        $tx = null;
        if ($transacaoId) {
            $tx = $transacaoModel->find($transacaoId);
        } else {
            $tx = $transacaoModel->findPendingByIlpiId($ilpiId);
        }
        $status = $tx['status'] ?? null;
        $ilpiModel = new ILPI();
        $ilpi = $ilpiModel->find($ilpiId);
        $ilpiStatus = $ilpi['status'] ?? null;
        $this->jsonResponse([
            'ok' => true,
            'status' => $status,
            'ilpi_status' => $ilpiStatus
        ]);
    }
}

