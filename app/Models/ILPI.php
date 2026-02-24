<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ILPI extends Model
{
    protected $table = 'ilpis';

    public function __construct()
    {
        parent::__construct();
        $this->ensureColumns();
    }

    private function ensureColumns()
    {
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN termos_aceitos INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN termos_aceitos_em DATETIME"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN descricao TEXT"); } catch (\Exception $e) {}
        try { $this->db->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_ilpis_cnpj ON {$this->table}(cnpj)"); } catch (\Exception $e) {}
        try { $this->db->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_ilpis_email ON {$this->table}(email)"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN whatsapp_clicks INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN facebook_clicks INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN map_clicks INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN photos_open INTEGER DEFAULT 0"); } catch (\Exception $e) {}
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (nome, cnpj, status, cidade_id, estado_id, telefone, responsavel, email, senha, plano_id, cep, endereco, numero, complemento, bairro, facebook, instagram, termos_aceitos, termos_aceitos_em, descricao) 
                VALUES 
                (:nome, :cnpj, :status, :cidade_id, :estado_id, :telefone, :responsavel, :email, :senha, :plano_id, :cep, :endereco, :numero, :complemento, :bairro, :facebook, :instagram, :termos_aceitos, :termos_aceitos_em, :descricao)";
        
        $stmt = $this->db->prepare($sql);
        
        // Hash password
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        
        return $stmt->execute($data);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function findByCnpj($cnpj)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE cnpj = :cnpj");
        $stmt->execute(['cnpj' => $cnpj]);
        return $stmt->fetch();
    }
    
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldsStr = implode(', ', $fields);
        $data['id'] = $id;

        $sql = "UPDATE {$this->table} SET $fieldsStr WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getWithDetails($id) {
        $sql = "SELECT i.*, p.nome as plano_nome, p.limite_leitos, p.limite_fotos, p.exibir_redes_sociais,
                       c.nome as cidade_nome, e.uf as estado_uf
                FROM {$this->table} i
                JOIN planos p ON i.plano_id = p.id
                JOIN cidades c ON i.cidade_id = c.id
                JOIN estados e ON i.estado_id = e.id
                WHERE i.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getActiveBedsCount($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM leitos WHERE ilpi_id = :id AND status = 'disponivel'");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result['count'];
    }

    public function getAllPublic($cidadeId = null, $grauDependenciaId = null) {
        $sql = "SELECT i.*, c.nome as cidade_nome, e.uf as estado_uf, p.exibir_redes_sociais,
                       MIN(l.valor) as valor_minimo,
                       (SELECT url_foto FROM fotos_leito fl 
                        JOIN leitos l2 ON fl.leito_id = l2.id 
                        WHERE l2.ilpi_id = i.id AND l2.status = 'disponivel' 
                        LIMIT 1) as foto_capa,
                       (SELECT GROUP_CONCAT(fl.url_foto) 
                        FROM fotos_leito fl 
                        JOIN leitos l3 ON fl.leito_id = l3.id 
                        WHERE l3.ilpi_id = i.id AND l3.status = 'disponivel') as todas_fotos
                FROM {$this->table} i
                JOIN cidades c ON i.cidade_id = c.id
                JOIN estados e ON i.estado_id = e.id
                JOIN planos p ON i.plano_id = p.id
                JOIN leitos l ON i.id = l.ilpi_id
                WHERE l.status = 'disponivel' AND i.status = 'ativo'
                AND EXISTS (
                    SELECT 1 FROM renovacoes r 
                    WHERE r.ilpi_id = i.id 
                    AND r.data_vencimento >= DATE('now')
                )";

        $params = [];

        if (!empty($cidadeId)) {
            $sql .= " AND i.cidade_id = :cidade_id";
            $params['cidade_id'] = $cidadeId;
        }

        if (!empty($grauDependenciaId)) {
            $sql .= " AND l.grau_dependencia_id = :grau_dependencia_id";
            $params['grau_dependencia_id'] = $grauDependenciaId;
        }

        $sql .= " GROUP BY i.id ORDER BY i.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getAllWithStatus($status = null) {
        $sql = "SELECT i.*, c.nome as cidade_nome, e.uf as estado_uf, p.nome as plano_nome
                FROM {$this->table} i
                JOIN cidades c ON i.cidade_id = c.id
                JOIN estados e ON i.estado_id = e.id
                JOIN planos p ON i.plano_id = p.id";
        
        $params = [];
        if ($status) {
            $sql .= " WHERE i.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY i.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function incrementMetric($id, $type)
    {
        $allowed = [
            'whatsapp' => 'whatsapp_clicks',
            'facebook' => 'facebook_clicks',
            'instagram' => 'facebook_clicks', // consolidar em facebook_clicks por simplicidade
            'map' => 'map_clicks',
            'photos_open' => 'photos_open'
        ];
        if (!isset($allowed[$type])) {
            return false;
        }
        $column = $allowed[$type];
        $sql = "UPDATE {$this->table} SET {$column} = COALESCE({$column},0) + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            // Get leitos IDs to delete photos
            $stmt = $this->db->prepare("SELECT id FROM leitos WHERE ilpi_id = :id");
            $stmt->execute(['id' => $id]);
            $leitos = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (!empty($leitos)) {
                $leitosIds = implode(',', $leitos);
                $this->db->exec("DELETE FROM fotos_leito WHERE leito_id IN ($leitosIds)");
                $this->db->exec("DELETE FROM leitos WHERE ilpi_id = $id");
            }
            
            // Delete renewals
            $stmt = $this->db->prepare("DELETE FROM renovacoes WHERE ilpi_id = :id");
            $stmt->execute(['id' => $id]);
            
            // Get email for password reset deletion
            $stmt = $this->db->prepare("SELECT email FROM ilpis WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $email = $stmt->fetchColumn();
            
            if ($email) {
                $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = :email");
                $stmt->execute(['email' => $email]);
            }
            
            // Delete ILPI
            $stmt = $this->db->prepare("DELETE FROM ilpis WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
