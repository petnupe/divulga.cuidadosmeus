<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cidade;

class ApiController extends Controller
{
    public function getCidades($estadoId)
    {
        $cidadeModel = new Cidade();
        $cidades = $cidadeModel->getByEstadoId($estadoId);
        
        $this->jsonResponse($cidades);
    }
}
