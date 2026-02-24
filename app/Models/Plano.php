<?php

namespace App\Models;

use App\Core\Model;

class Plano extends Model
{
    protected $table = 'planos';

    public function __construct()
    {
        parent::__construct();
        $this->ensureColumns();
    }

    private function ensureColumns()
    {
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN nome VARCHAR(255)"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN valor DECIMAL(10,2) DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN limite_leitos INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN limite_fotos INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN exibir_redes_sociais INTEGER DEFAULT 0"); } catch (\Exception $e) {}
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function updatePlan($id, $data)
    {
        $fields = [];
        foreach ($data as $k => $v) {
            $fields[] = "$k = :$k";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
