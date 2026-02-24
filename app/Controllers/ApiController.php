<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cidade;
use App\Models\ILPI;

class ApiController extends Controller
{
    public function getCidades($estadoId)
    {
        $cidadeModel = new Cidade();
        $cidades = $cidadeModel->getByEstadoId($estadoId);
        
        $this->jsonResponse($cidades);
    }

    public function track()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $type = $data['type'] ?? null;
        $ilpiId = isset($data['ilpi_id']) ? (int)$data['ilpi_id'] : null;
        if (!$type || !$ilpiId) {
            $this->jsonResponse(['ok' => false, 'error' => 'invalid_input'], 400);
        }
        $ilpiModel = new ILPI();
        $ok = $ilpiModel->incrementMetric($ilpiId, $type);
        $this->jsonResponse(['ok' => (bool)$ok]);
    }
}
