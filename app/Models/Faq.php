<?php
namespace App\Models;

class Faq extends Model {
    protected string $table = 'faqs';

    public function getActive(): array {
        return $this->where('is_active', 1, 'sort_order ASC, id ASC');
    }
}
