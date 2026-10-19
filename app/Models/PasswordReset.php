<?php
namespace App\Models;

class PasswordReset extends Model {
    protected string $table = 'password_resets';

    public function createToken(string $email): string {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Delete any existing tokens for this email
        $del = $this->db->prepare("DELETE FROM `{$this->table}` WHERE email = :email");
        $del->execute(['email' => $email]);

        $this->create([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expiresAt
        ]);

        return $token;
    }

    public function findValidToken(string $token): ?array {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE token = :t AND expires_at >= :now LIMIT 1");
        $stmt->execute(['t' => $token, 'now' => $now]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function deleteToken(string $token): void {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE token = :t");
        $stmt->execute(['t' => $token]);
    }
}
