<?php

class Category
{
    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::getPDO()->prepare('SELECT * FROM categories WHERE slug = ?');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

}
