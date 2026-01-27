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
}
