<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ILPI;
use App\Models\Leito;
use App\Models\Renovacao;
use App\Models\Estado;
use App\Models\Cidade;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['ilpi_id'])) {
            $this->redirect('/ilpi/login');
        }
    }

    public function index()
    {
        $ilpiId = $_SESSION['ilpi_id'];
        
        $ilpiModel = new ILPI();
        $leitoModel = new Leito();
        $renovacaoModel = new Renovacao();

        $ilpi = $ilpiModel->getWithDetails($ilpiId);
        $leitos = $leitoModel->getByIlpiId($ilpiId);
        $activeBeds = $ilpiModel->getActiveBedsCount($ilpiId);

        // Check renewal status
        // Get latest renewal (assuming getByIlpiId orders by date DESC as implemented in Renovacao model)
        $renovacoes = $renovacaoModel->findByIlpiId($ilpiId);
        $latestRenovacao = !empty($renovacoes) ? $renovacoes[0] : null;
        
        $isExpired = false;
        
        // If active but expired, block it
        if ($ilpi['status'] == 'ativo') {
             if (!$latestRenovacao || strtotime($latestRenovacao['data_vencimento']) < strtotime(date('Y-m-d'))) {
                 $ilpiModel->update($ilpiId, ['status' => 'bloqueado']);
                 $ilpi['status'] = 'bloqueado'; // Update local var for view
                 $isExpired = true;
             }
        } elseif ($ilpi['status'] == 'bloqueado') {
             // Check if it's because of expiration (it might be blocked manually by admin, but let's assume expiration logic here)
             if (!$latestRenovacao || strtotime($latestRenovacao['data_vencimento']) < strtotime(date('Y-m-d'))) {
                 $isExpired = true;
             }
        }

        $this->view('dashboard/index', [
            'ilpi' => $ilpi,
            'leitos' => $leitos,
            'activeBeds' => $activeBeds,
            'isExpired' => $isExpired,
            'dataVencimento' => $latestRenovacao ? $latestRenovacao['data_vencimento'] : null
        ]);
    }

    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ilpi/dashboard');
        }

        $ilpiId = $_SESSION['ilpi_id'];
        $ilpiModel = new ILPI();
        
        if ($ilpiModel->delete($ilpiId)) {
            session_destroy();
            $this->redirect('/');
        } else {
            // Ideally flash message here, but simple redirect for now or show error
            // As we don't have flash messages easily implemented without session helper
            // We just redirect.
            $this->redirect('/ilpi/dashboard?error=delete_failed');
        }
    }

    public function profile()
    {
        $ilpiId = $_SESSION['ilpi_id'];
        $ilpiModel = new ILPI();
        $ilpi = $ilpiModel->getWithDetails($ilpiId);
        
        $estadoModel = new Estado();
        $cidadeModel = new Cidade();
        
        $estados = $estadoModel->getAll();
        $cidades = $cidadeModel->getByEstadoId($ilpi['estado_id']);
        
        $this->view('dashboard/profile', [
            'ilpi' => $ilpi,
            'estados' => $estados,
            'cidades' => $cidades
        ]);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ilpi/profile');
        }

        $ilpiId = $_SESSION['ilpi_id'];
        $ilpiModel = new ILPI();
        
        // Basic validation could be improved
        $data = [
            'nome' => $_POST['nome'],
            'responsavel' => $_POST['responsavel'],
            'telefone' => $_POST['telefone'],
            'cep' => $_POST['cep'],
            'estado_id' => $_POST['estado_id'],
            'cidade_id' => $_POST['cidade_id'],
            'endereco' => $_POST['endereco'],
            'numero' => $_POST['numero'],
            'complemento' => $_POST['complemento'] ?? '',
            'bairro' => $_POST['bairro'],
            'facebook' => $_POST['facebook'] ?? '',
            'instagram' => $_POST['instagram'] ?? ''
        ];

        // Only update password if provided
        if (!empty($_POST['senha'])) {
            if ($_POST['senha'] === $_POST['confirm_senha']) {
                $data['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            } else {
                $ilpi = $ilpiModel->getWithDetails($ilpiId);
                $this->view('dashboard/profile', [
                    'ilpi' => $ilpi,
                    'error' => 'As senhas não conferem.'
                ]);
                return;
            }
        }

        if ($ilpiModel->update($ilpiId, $data)) {
            $_SESSION['ilpi_name'] = $data['nome'];
            $this->redirect('/ilpi/profile?success=1');
        } else {
            $ilpi = $ilpiModel->getWithDetails($ilpiId);
            $this->view('dashboard/profile', [
                'ilpi' => $ilpi,
                'error' => 'Erro ao atualizar perfil.'
            ]);
        }
    }
}
