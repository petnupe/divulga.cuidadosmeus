<?php

namespace App\Models;

use App\Core\Model;

class FotoLeito extends Model
{
    protected $table = 'fotos_leito';

    public function getByLeitoId($leitoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE leito_id = :leito_id ORDER BY created_at DESC");
        $stmt->execute(['leito_id' => $leitoId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (leito_id, url_foto, nome_arquivo) VALUES (:leito_id, :url_foto, :nome_arquivo)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function countByLeitoId($leitoId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE leito_id = :leito_id");
        $stmt->execute(['leito_id' => $leitoId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
