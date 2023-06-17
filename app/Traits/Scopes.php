<?php

namespace App\Traits;

trait Scopes
{
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->today());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('created_at', now()->yesterday());
    }

    public function scopeLimitOffset($query, $post)
    {
        $page = isset($post['page']) ? $post['page'] : 1;
        $limit = isset($post['limit']) ? $post['limit'] : 30;
        $offset = ($page - 1) * $limit;
        return $query->take($limit)->offset($offset);
    }
}
