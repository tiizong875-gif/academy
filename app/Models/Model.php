<?php
namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model {
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getDb(): PDO {
        return $this->db;
    }

    public function all(string $orderBy = 'id DESC'): array {
        $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBy(string $column, mixed $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :val LIMIT 1");
        $stmt->execute(['val' => $value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function where(string $column, mixed $value, string $orderBy = 'id DESC'): array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :val ORDER BY {$orderBy}");
        $stmt->execute(['val' => $value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $fields = array_keys($data);
        $columns = implode('`, `', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[] = "`{$key}` = :{$key}";
        }
        $setClause = implode(', ', $fields);
        $data['__id'] = $id;

        $sql = "UPDATE `{$this->table}` SET {$setClause} WHERE `{$this->primaryKey}` = :__id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(string $where = '1=1', array $params = []): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE {$where}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 10, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array {
        $offset = max(0, ($page - 1) * $perPage);
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE {$where} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}");
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        $totalPages = (int)ceil($total / $perPage);

        return [
            'items'       => $items,
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page'=> $page,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages
        ];
    }
}
