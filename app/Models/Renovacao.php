<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Renovacao extends Model {
    protected $table = 'renovacoes';

    public function __construct() {
        parent::__construct();
        $this->ensureColumns();
    }

    private function ensureColumns() {
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN asaas_payment_id TEXT"); } catch (\Exception $e) {}
    }

    public function create(array $data) {
        $sql = "INSERT INTO {$this->table} (ilpi_id, plano_id, data_renovacao, data_vencimento, valor, asaas_payment_id) 
                VALUES (:ilpi_id, :plano_id, :data_renovacao, :data_vencimento, :valor, :asaas_payment_id)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':ilpi_id', $data['ilpi_id']);
        $stmt->bindValue(':plano_id', $data['plano_id']);
        $stmt->bindValue(':data_renovacao', $data['data_renovacao']);
        $stmt->bindValue(':data_vencimento', $data['data_vencimento']);
        $stmt->bindValue(':valor', $data['valor']);
        $stmt->bindValue(':asaas_payment_id', $data['asaas_payment_id'] ?? null);
        
        return $stmt->execute();
    }

    public function findByIlpiId($ilpiId) {
        $sql = "SELECT r.*, p.nome as plano_nome 
                FROM {$this->table} r 
                JOIN planos p ON r.plano_id = p.id 
                WHERE r.ilpi_id = :ilpi_id 
                ORDER BY r.data_vencimento DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ilpi_id', $ilpiId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithDetails() {
        $sql = "SELECT r.*, i.nome as ilpi_nome, i.cnpj, p.nome as plano_nome 
                FROM {$this->table} r 
                JOIN ilpis i ON r.ilpi_id = i.id 
                JOIN planos p ON r.plano_id = p.id 
                ORDER BY r.data_renovacao DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
