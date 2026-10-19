<?php
namespace App\Models;

class User extends Model {
    protected string $table = 'users';

    public function findById(int $id): ?array {
        return $this->find($id);
    }

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function register(string $name, string $email, string $password, ?string $phone = null): int {
        return $this->create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone'    => $phone,
            'role'     => 'student',
            'status'   => 'active'
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    public function getStudents(int $page = 1, int $perPage = 15, string $search = ''): array {
        $where = "role = 'student'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (name LIKE :s OR email LIKE :s OR phone LIKE :s)";
            $params['s'] = "%{$search}%";
        }

        return $this->paginate($page, $perPage, $where, $params, 'id DESC');
    }

    public function getInstructors(): array {
        $stmt = $this->db->query("SELECT * FROM `{$this->table}` WHERE `role` IN ('instructor', 'admin') AND `status` = 'active' ORDER BY `name` ASC");
        return $stmt->fetchAll();
    }
}
