<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

session_start();

use App\Core\Router;

$router = new Router();

// Public Routes
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/divulgue', 'HomeController', 'divulgue');
$router->add('GET', '/sitemap.xml', 'HomeController', 'sitemap');

// API Routes
$router->add('GET', '/api/cidades/{estadoId}', 'ApiController', 'getCidades');
$router->add('POST', '/api/track', 'ApiController', 'track');
$router->add('POST', '/api/webhook/asaas', 'WebhookController', 'asaas');
$router->add('GET', '/api/webhook/asaas', 'WebhookController', 'asaas');

// Auth Routes
$router->add('GET', '/ilpi/login', 'AuthController', 'login');
$router->add('POST', '/ilpi/authenticate', 'AuthController', 'authenticate');
$router->add('GET', '/ilpi/register', 'AuthController', 'register');
$router->add('POST', '/ilpi/store', 'AuthController', 'store');
$router->add('GET', '/ilpi/logout', 'AuthController', 'logout');
$router->add('GET', '/ilpi/forgot-password', 'PasswordResetController', 'forgotPassword');
$router->add('POST', '/ilpi/forgot-password', 'PasswordResetController', 'sendResetLink');
$router->add('GET', '/ilpi/reset-password', 'PasswordResetController', 'resetPassword');
$router->add('POST', '/ilpi/reset-password', 'PasswordResetController', 'updatePassword');

// Dashboard Routes
$router->add('GET', '/ilpi/dashboard', 'DashboardController', 'index');
$router->add('GET', '/ilpi/profile', 'DashboardController', 'profile');
$router->add('POST', '/ilpi/profile', 'DashboardController', 'updateProfile');
$router->add('POST', '/ilpi/delete-account', 'DashboardController', 'deleteAccount');
$router->add('GET', '/ilpi/payment/pix', 'PaymentController', 'generatePix');
$router->add('GET', '/ilpi/payment/status', 'PaymentController', 'status');

// Admin Routes
$router->add('GET', '/admin/login', 'AdminController', 'login');
$router->add('POST', '/admin/authenticate', 'AdminController', 'authenticate');
$router->add('GET', '/admin/dashboard', 'AdminController', 'dashboard');
$router->add('GET', '/admin/ilpis', 'AdminController', 'ilpis');
$router->add('GET', '/admin/approve/{id}', 'AdminController', 'approve');
$router->add('GET', '/admin/reject/{id}', 'AdminController', 'reject');
$router->add('GET', '/admin/renovacoes', 'AdminController', 'renovacoes');
$router->add('POST', '/admin/renovacoes/store', 'AdminController', 'storeRenovacao');
$router->add('GET', '/admin/password', 'AdminController', 'password');
$router->add('POST', '/admin/password', 'AdminController', 'updatePassword');
$router->add('GET', '/admin/transacoes', 'AdminController', 'transacoes');
$router->add('GET', '/admin/transacoes/pix/{id}', 'AdminController', 'gerarPixTransacao');
$router->add('GET', '/admin/transacoes/cancel/{id}', 'AdminController', 'cancelarTransacao');
$router->add('GET', '/admin/logout', 'AdminController', 'logout');
$router->add('GET', '/admin/planos', 'AdminController', 'planos');
$router->add('POST', '/admin/planos/update/{id}', 'AdminController', 'updatePlano');
// Admin ILPI Plan Management
$router->add('POST', '/admin/ilpis/update-plan/{id}', 'AdminController', 'updateIlpiPlan');

// Leito Routes
$router->add('GET', '/ilpi/leitos/create', 'LeitoController', 'create');
$router->add('POST', '/ilpi/leitos/store', 'LeitoController', 'store');
$router->add('GET', '/ilpi/leitos/edit/{id}', 'LeitoController', 'edit');
$router->add('POST', '/ilpi/leitos/update/{id}', 'LeitoController', 'update');
$router->add('GET', '/ilpi/leitos/delete/{id}', 'LeitoController', 'delete');
$router->add('GET', '/ilpi/leitos/delete-photo/{id}', 'LeitoController', 'deletePhoto');

// Dispatch
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);
