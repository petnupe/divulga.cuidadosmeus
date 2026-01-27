<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;
use App\Models\ILPI;
use App\Models\Renovacao;
use App\Models\Plano;

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
}
