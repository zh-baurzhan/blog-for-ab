<?php

class Category
{
    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::getPDO()->prepare('SELECT * FROM categories WHERE slug = ?');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public static function withArticles(): array
    {
        return Database::getPDO()->query(
            'SELECT c.* FROM categories c
             WHERE EXISTS (SELECT 1 FROM article_category ac WHERE ac.category_id = c.id)
             ORDER BY c.name'
        )->fetchAll();
    }

}
