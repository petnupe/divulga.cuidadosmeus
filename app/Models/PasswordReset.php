<?php

namespace App\Models;

use App\Core\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    public function createToken($email)
    {
        $token = bin2hex(random_bytes(32));
        
        // Remove existing tokens for this email
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        // Insert new token
        $sql = "INSERT INTO {$this->table} (email, token) VALUES (:email, :token)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'token' => $token]);
        
        return $token;
    }

    public function findByToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }
    
    public function deleteToken($email)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = :email");
        return $stmt->execute(['email' => $email]);
    }
}
