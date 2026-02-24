<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;
use App\Models\ILPI;
use App\Models\Renovacao;
use App\Models\Plano;
use App\Models\Transacao;
use App\Services\AsaasService;

class AdminController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/dashboard');
        }
        $this->view('admin/login');
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $adminModel = new Admin();
        $admin = $adminModel->findByEmail($email);

        if ($admin && password_verify($password, $admin['senha'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nome'];
            $this->redirect('/admin/dashboard');
        } else {
            $this->view('admin/login', ['error' => 'Credenciais inválidas.']);
        }
    }

    public function dashboard()
    {
        $this->checkAuth();
        $ilpiModel = new ILPI();
        
        // Metrics
        // Simple counts can be optimized but for MVP fetching all is okay or specific count methods
        // Let's assume fetching all for now to count in PHP or add count methods later if slow.
        $allIlpis = $ilpiModel->getAllWithStatus(null);
        
        $stats = [
            'total' => count($allIlpis),
            'pendente' => 0,
            'ativo' => 0,
            'rejeitado' => 0
        ];
        
        foreach ($allIlpis as $ilpi) {
            if (isset($stats[$ilpi['status']])) {
                $stats[$ilpi['status']]++;
            }
        }

        $this->view('admin/dashboard', ['stats' => $stats]);
    }

    public function ilpis()
    {
        $this->checkAuth();
        $ilpiModel = new ILPI();
        
        $status = $_GET['status'] ?? null;
        $ilpis = $ilpiModel->getAllWithStatus($status);

        $this->view('admin/ilpis', ['ilpis' => $ilpis, 'currentStatus' => $status]);
    }

    public function approve($id)
    {
        $this->checkAuth();
        $ilpiModel = new ILPI();
        $ilpi = $ilpiModel->find($id);

        if ($ilpi) {
            $ilpiModel->update($id, ['status' => 'ativo']);

            // Insert initial renovation
            $renovacaoModel = new Renovacao();
            $renovacaoModel->create([
                'ilpi_id' => $id,
                'plano_id' => $ilpi['plano_id'],
                'data_renovacao' => date('Y-m-d'),
                'data_vencimento' => date('Y-m-d', strtotime('+30 days')),
                'valor' => 0.00
            ]);
        }
        
        $this->redirect('/admin/ilpis');
    }

    public function reject($id)
    {
        $this->checkAuth();
        $ilpiModel = new ILPI();
        $ilpiModel->update($id, ['status' => 'rejeitado']);
        $this->redirect('/admin/ilpis');
    }
    
    public function renovacoes()
    {
        $this->checkAuth();
        $renovacaoModel = new Renovacao();
        $renovacoes = $renovacaoModel->getAllWithDetails();
        
        $ilpiModel = new ILPI();
        $ilpis = $ilpiModel->getAllWithStatus('ativo');
        
        // Add last expiration date to ILPIs
        foreach ($ilpis as &$ilpi) {
            $ilpiRenovacoes = $renovacaoModel->findByIlpiId($ilpi['id']);
            $ilpi['last_expiration'] = !empty($ilpiRenovacoes) ? $ilpiRenovacoes[0]['data_vencimento'] : null;
        }
        
        $planoModel = new Plano();
        $planos = $planoModel->getAll();
        
        $this->view('admin/renovacoes', [
            'renovacoes' => $renovacoes,
            'ilpis' => $ilpis,
            'planos' => $planos
        ]);
    }
    
    public function storeRenovacao()
    {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $valor = str_replace(['.', ','], ['', '.'], $_POST['valor']);

            $data = [
                'ilpi_id' => $_POST['ilpi_id'],
                'plano_id' => $_POST['plano_id'],
                'data_renovacao' => $_POST['data_renovacao'],
                'data_vencimento' => $_POST['data_vencimento'],
                'valor' => $valor
            ];
            
            $renovacaoModel = new Renovacao();
            $renovacaoModel->create($data);
            
            $this->redirect('/admin/renovacoes');
        }
    }

    public function logout()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        $this->redirect('/admin/login');
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
        }
    }

    public function password()
    {
        $this->checkAuth();
        $this->view('admin/password');
    }

    public function updatePassword()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/password');
        }
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new !== $confirm) {
            $this->view('admin/password', ['error' => 'As senhas não conferem.']);
            return;
        }

        $adminModel = new Admin();
        $admin = $adminModel->find($_SESSION['admin_id']);
        if (!$admin || !password_verify($current, $admin['senha'])) {
            $this->view('admin/password', ['error' => 'Senha atual incorreta.']);
            return;
        }

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $adminModel->updatePassword($_SESSION['admin_id'], $hashed);
        $this->view('admin/password', ['success' => 'Senha atualizada com sucesso.']);
    }

    public function transacoes()
    {
        $this->checkAuth();
        $transacaoModel = new Transacao();
        $statusParam = $_GET['status'] ?? 'pending';
        $map = [
            'pending' => ['PENDING','PENDING_PAYMENT'],
            'confirmado' => ['PAYMENT_CONFIRMED','PAYMENT_RECEIVED'],
            'cancelado' => ['CANCELLED'],
            'todos' => null
        ];
        $statuses = $map[$statusParam] ?? $map['pending'];
        if ($statusParam === 'todos') {
            $transacoes = $transacaoModel->getAllWithDetails();
        } else {
            $transacoes = $transacaoModel->getWithDetailsByStatuses($statuses);
        }
        $selectedId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $selectedTx = null;
        if ($selectedId) {
            foreach ($transacoes as $row) {
                if ((int)$row['id'] === $selectedId) {
                    $selectedTx = $row;
                    break;
                }
            }
            if (!$selectedTx) {
                $selectedTx = $transacaoModel->find($selectedId);
            }
        }
        $this->view('admin/transacoes', ['transacoes' => $transacoes, 'currentStatus' => $statusParam, 'selectedTx' => $selectedTx]);
    }

    public function planos()
    {
        $this->checkAuth();
        $planoModel = new Plano();
        $planos = $planoModel->getAll();
        $this->view('admin/planos', ['planos' => $planos]);
    }

    public function updatePlano($id)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/planos');
        }
        $planoModel = new Plano();
        $valor = str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'valor' => (float)$valor,
            'limite_leitos' => (int)($_POST['limite_leitos'] ?? 0),
            'limite_fotos' => (int)($_POST['limite_fotos'] ?? 0),
            'exibir_redes_sociais' => isset($_POST['exibir_redes_sociais']) ? 1 : 0
        ];
        $planoModel->updatePlan($id, $data);
        $this->redirect('/admin/planos?updated=1');
    }

    public function gerarPixTransacao($id)
    {
        $this->checkAuth();
        $transacaoModel = new Transacao();
        $tx = $transacaoModel->find($id);
        if (!$tx || empty($tx['asaas_id'])) {
            $this->redirect('/admin/transacoes');
            return;
        }
        try {
            $asaas = new AsaasService();
            $pix = $asaas->getPixQrCode($tx['asaas_id']);
            $transacaoModel->updatePixData($id, $pix['payload'] ?? null, $pix['encodedImage'] ?? null);
            $this->redirect('/admin/transacoes?pix=ok&id=' . $id);
        } catch (\Exception $e) {
            $msg = urlencode(substr($e->getMessage(), 0, 120));
            $this->redirect('/admin/transacoes?pix_error=1&msg=' . $msg);
        }
    }

    public function cancelarTransacao($id)
    {
        $this->checkAuth();
        $transacaoModel = new Transacao();
        $tx = $transacaoModel->find($id);
        if (!$tx || empty($tx['asaas_id'])) {
            $this->redirect('/admin/transacoes');
            return;
        }
        try {
            $asaas = new AsaasService();
            $info = $asaas->getPayment($tx['asaas_id']);
            $status = isset($info['status']) ? strtoupper($info['status']) : null;
            if ($status === 'PENDING' || $status === 'PENDING_PAYMENT') {
                $asaas->cancelPayment($tx['asaas_id']);
                $transacaoModel->updateStatus($id, 'CANCELLED');
                $this->redirect('/admin/transacoes?cancel=ok');
            } else {
                $msg = urlencode("Cobrança não pode ser cancelada (status atual: " . ($status ?: 'desconhecido') . ")");
                $this->redirect('/admin/transacoes?cancel_error=1&msg=' . $msg);
            }
        } catch (\Exception $e) {
            $msg = urlencode(substr($e->getMessage(), 0, 120));
            $this->redirect('/admin/transacoes?cancel_error=1&msg=' . $msg);
        }
    }
}
