<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ILPI;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\GrauDependencia;

class HomeController extends Controller
{
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
