<?php

namespace App\Models;

use App\Core\Model;

class Admin extends Model
{
    protected $table = 'admins';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function updatePassword($id, $hashed)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET senha = :senha WHERE id = :id");
        return $stmt->execute(['senha' => $hashed, 'id' => $id]);
    }
}
