<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ILPI;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\GrauDependencia;

class HomeController extends Controller
{
    public function divulgue()
    {
        $landingPath = __DIR__ . '/../../public/landing/index.html';

        if (!file_exists($landingPath)) {
            http_response_code(404);
            echo 'Landing page not found.';
            return;
        }

        header('Content-Type: text/html; charset=UTF-8');
        readfile($landingPath);
        exit;
    }

    public function sitemap()
    {
        header('Content-Type: application/xml; charset=utf-8');
        
        $baseUrl = 'https://cuidadosmeus.com.br/divulga';
        $today = date('Y-m-d');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Home
        $xml .= '<url>';
        $xml .= '<loc>' . $baseUrl . '/</loc>';
        $xml .= '<lastmod>' . $today . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';
        
        // Landing Page
        $xml .= '<url>';
        $xml .= '<loc>' . $baseUrl . '/divulgue</loc>';
        $xml .= '<lastmod>' . $today . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
        
        $xml .= '</urlset>';
        
        echo $xml;
    }

    public function index()
    {
        $ilpiModel = new ILPI();
        $estadoModel = new Estado();
        $grauModel = new GrauDependencia();
        $cidadeModel = new Cidade();

        $cidadeId = isset($_GET['cidade_id']) && !empty($_GET['cidade_id']) ? $_GET['cidade_id'] : null;
        $grauId = isset($_GET['grau_id']) && !empty($_GET['grau_id']) ? $_GET['grau_id'] : null;

        $ilpis = $ilpiModel->getAllPublic($cidadeId, $grauId);
        $estados = $estadoModel->getAll();
        $graus = $grauModel->getAll();
        
        // If cidade_id is selected, get cities for the filter dropdown (implied we need to know the state or list all cities if feasible, 
        // but better to load cities if state is known. For simplicity in filter, maybe just list all cities or use AJAX.
        // Let's pass empty cities initially or filtered if logic permits. 
        // For MVP, maybe just load all cities if not too many, or rely on AJAX.
        // To keep it simple, let's load all cities for now or handle via AJAX in view.
        
        $this->view('home', [
            'ilpis' => $ilpis,
            'estados' => $estados,
            'graus' => $graus,
            'selectedCidadeId' => $cidadeId,
            'selectedGrauId' => $grauId
        ]);
    }
}
