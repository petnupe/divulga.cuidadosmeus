<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ILPI;
use App\Models\Estado;
use App\Models\Plano;

class AuthController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['ilpi_id'])) {
            $this->redirect('/ilpi/dashboard');
        }
        $this->view('auth/login');
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ilpi/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $ilpiModel = new ILPI();
        $ilpi = $ilpiModel->findByEmail($email);

        if ($ilpi && password_verify($password, $ilpi['senha'])) {
            if ($ilpi['status'] === 'rejeitado') {
                $this->view('auth/login', ['error' => 'Cadastro rejeitado. Entre em contato com o suporte.']);
                return;
            }

            // Regenerate session ID for security
            session_regenerate_id(true);
            
            $_SESSION['ilpi_id'] = $ilpi['id'];
            $_SESSION['ilpi_name'] = $ilpi['nome'];
            $_SESSION['ilpi_plan'] = $ilpi['plano_id'];
            $_SESSION['ilpi_status'] = $ilpi['status'];
            
            $this->redirect('/ilpi/dashboard');
        } else {
            $this->view('auth/login', ['error' => 'Email ou senha inválidos.']);
        }
    }

    public function register()
    {
        if (isset($_SESSION['ilpi_id'])) {
            $this->redirect('/ilpi/dashboard');
        }

        $estadoModel = new Estado();
        $planoModel = new Plano();
        
        $estados = $estadoModel->getAll();
        $planos = $planoModel->getAll();

        $this->view('auth/register', [
            'estados' => $estados,
            'planos' => $planos
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ilpi/register');
        }

        // Basic Validation
        $required = ['nome', 'cnpj', 'cidade_id', 'estado_id', 'telefone', 'responsavel', 'email', 'senha', 'confirm_senha', 'plano_id', 'cep', 'endereco', 'numero', 'bairro'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $this->registerWithError('Preencha todos os campos obrigatórios.');
                return;
            }
        }

        if ($_POST['senha'] !== $_POST['confirm_senha']) {
            $this->registerWithError('As senhas não conferem.');
            return;
        }

        $ilpiModel = new ILPI();
        
        // Check if email exists
        if ($ilpiModel->findByEmail($_POST['email'])) {
            $this->registerWithError('Email já cadastrado.');
            return;
        }
        
        // Check if CNPJ exists
        if ($ilpiModel->findByCnpj($_POST['cnpj'])) {
            $this->registerWithError('CNPJ já cadastrado.');
            return;
        }

        // Validate CNPJ Digits
        if (!$this->validateCNPJ($_POST['cnpj'])) {
            $this->registerWithError('CNPJ inválido.');
            return;
        }

        $data = [
            'nome' => $_POST['nome'],
            'cnpj' => $_POST['cnpj'],
            'status' => 'pendente',
            'cidade_id' => $_POST['cidade_id'],
            'estado_id' => $_POST['estado_id'],
            'telefone' => $_POST['telefone'],
            'responsavel' => $_POST['responsavel'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha'],
            'plano_id' => $_POST['plano_id'],
            'cep' => $_POST['cep'],
            'endereco' => $_POST['endereco'],
            'numero' => $_POST['numero'],
            'complemento' => $_POST['complemento'] ?? '',
            'bairro' => $_POST['bairro'],
            'facebook' => $_POST['facebook'] ?? '',
            'instagram' => $_POST['instagram'] ?? ''
        ];

        if ($ilpiModel->create($data)) {
            // Get the created ILPI to login
            $ilpi = $ilpiModel->findByEmail($data['email']);
            
            // Login automatically
            session_regenerate_id(true);
            $_SESSION['ilpi_id'] = $ilpi['id'];
            $_SESSION['ilpi_name'] = $ilpi['nome'];
            $_SESSION['ilpi_plan'] = $ilpi['plano_id'];
            $_SESSION['ilpi_status'] = $ilpi['status'];
            
            $this->redirect('/ilpi/dashboard');
        } else {
            $this->registerWithError('Erro ao cadastrar. Tente novamente.');
        }
    }

    private function registerWithError($message)
    {
        $estadoModel = new Estado();
        $planoModel = new Plano();
        
        $this->view('auth/register', [
            'estados' => $estadoModel->getAll(),
            'planos' => $planoModel->getAll(),
            'error' => $message,
            'old' => $_POST
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/ilpi/login');
    }

    private function validateCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
            
        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
