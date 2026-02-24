<?php

namespace App\Models;

use App\Core\Model;

class Transacao extends Model
{
    protected $table = 'transacoes';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    private function ensureTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS transacoes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ilpi_id INTEGER NOT NULL,
            plano_id INTEGER,
            asaas_id VARCHAR(255),
            asaas_customer_id VARCHAR(255),
            valor DECIMAL(10, 2) NOT NULL,
            status VARCHAR(50) NOT NULL,
            url_pagamento TEXT,
            pix_payload TEXT,
            pix_qr_base64 TEXT,
            tipo VARCHAR(50) DEFAULT 'ADESAO',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (ilpi_id) REFERENCES ilpis(id)
        )";
        $this->db->exec($sql);
        try {
            $this->db->exec("ALTER TABLE transacoes ADD COLUMN pix_payload TEXT");
        } catch (\Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE transacoes ADD COLUMN pix_qr_base64 TEXT");
        } catch (\Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE transacoes ADD COLUMN plano_id INTEGER");
        } catch (\Exception $e) {}
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (ilpi_id, plano_id, asaas_id, asaas_customer_id, valor, status, url_pagamento, pix_payload, pix_qr_base64, tipo) 
                VALUES (:ilpi_id, :plano_id, :asaas_id, :asaas_customer_id, :valor, :status, :url_pagamento, :pix_payload, :pix_qr_base64, :tipo)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function findByAsaasId($asaasId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE asaas_id = :asaas_id");
        $stmt->execute(['asaas_id' => $asaasId]);
        return $stmt->fetch();
    }
    
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function findPendingByIlpiId($ilpiId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ilpi_id = :ilpi_id AND status IN ('PENDING', 'PENDING_PAYMENT') ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ilpi_id' => $ilpiId]);
        return $stmt->fetch();
    }

    public function findAllPendingByIlpiId($ilpiId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ilpi_id = :ilpi_id AND status IN ('PENDING', 'PENDING_PAYMENT') ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ilpi_id' => $ilpiId]);
        return $stmt->fetchAll();
    }

    public function updatePixData($id, $payload, $encodedImage)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET pix_payload = :payload, pix_qr_base64 = :image, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute(['payload' => $payload, 'image' => $encodedImage, 'id' => $id]);
    }

    public function getPendingWithDetails()
    {
        $sql = "SELECT t.*, i.nome as ilpi_nome, p.nome as plano_nome
                FROM {$this->table} t
                JOIN ilpis i ON t.ilpi_id = i.id
                LEFT JOIN planos p ON t.plano_id = p.id
                WHERE t.status IN ('PENDING','PENDING_PAYMENT')
                ORDER BY t.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getWithDetailsByStatuses($statuses = null)
    {
        $base = "SELECT t.*, i.nome as ilpi_nome, p.nome as plano_nome
                 FROM {$this->table} t
                 JOIN ilpis i ON t.ilpi_id = i.id
                 LEFT JOIN planos p ON t.plano_id = p.id";
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = implode(',', array_fill(0, count($statuses), '?'));
            $sql = $base . " WHERE t.status IN ($placeholders) ORDER BY t.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($statuses);
            return $stmt->fetchAll();
        } else {
            $sql = $base . " ORDER BY t.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
    }

    public function getAllWithDetails()
    {
        $sql = "SELECT t.*, i.nome as ilpi_nome, p.nome as plano_nome
                FROM {$this->table} t
                JOIN ilpis i ON t.ilpi_id = i.id
                LEFT JOIN planos p ON t.plano_id = p.id
                ORDER BY t.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

