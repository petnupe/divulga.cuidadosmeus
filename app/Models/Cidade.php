<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Cidade extends Model
{
    protected $table = 'cidades';

    public function getByEstadoId($estadoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE estado_id = :estado_id ORDER BY nome");
        $stmt->execute(['estado_id' => $estadoId]);
        return $stmt->fetchAll();
    }
}
