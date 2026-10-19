<?php
namespace App\Models;

class SiteSetting extends Model {
    protected string $table = 'site_settings';
    private static ?array $cachedSettings = null;

    public function getAllAsKeyValue(): array {
        if (self::$cachedSettings === null) {
            $all = $this->all();
            $settings = [];
            foreach ($all as $item) {
                $settings[$item['setting_key']] = $item['setting_value'];
            }
            self::$cachedSettings = $settings;
        }
        return self::$cachedSettings;
    }

    public function get(string $key, mixed $default = null): mixed {
        $settings = $this->getAllAsKeyValue();
        return $settings[$key] ?? $default;
    }

    public function set(string $key, ?string $value, string $group = 'general'): void {
        $stmt = $this->db->prepare("INSERT INTO `{$this->table}` (`setting_key`, `setting_value`, `group_name`) 
                                    VALUES (:key, :val, :grp)
                                    ON DUPLICATE KEY UPDATE `setting_value` = :val2, `group_name` = :grp2");
        $stmt->execute([
            'key'  => $key,
            'val'  => $value,
            'grp'  => $group,
            'val2' => $value,
            'grp2' => $group
        ]);
        self::$cachedSettings = null;
    }
}
