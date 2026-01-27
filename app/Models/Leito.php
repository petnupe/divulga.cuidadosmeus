<?php

namespace App\Models;

use App\Core\Model;

class Leito extends Model
{
    protected $table = 'leitos';

    public function getByIlpiId($ilpiId)
    {
        $sql = "SELECT l.*, g.nome as grau_dependencia_nome 
                FROM {$this->table} l 
                JOIN graus_dependencia g ON l.grau_dependencia_id = g.id
                WHERE l.ilpi_id = :ilpi_id 
                ORDER BY l.updated_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ilpi_id' => $ilpiId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (ilpi_id, tipo, grau_dependencia_id, valor, status) VALUES (:ilpi_id, :tipo, :grau_dependencia_id, :valor, :status)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET tipo = :tipo, grau_dependencia_id = :grau_dependencia_id, valor = :valor, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
