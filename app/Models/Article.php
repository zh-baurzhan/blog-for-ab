<?php

class Article
{
    private const string LIST_FIELDS = 'a.id, a.title, a.slug, a.image, a.description, a.views, a.published_at';
    public const array SORTS = [
        'date'  => 'a.published_at DESC',
        'views' => 'a.views DESC, a.published_at DESC',
    ];

    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::getPDO()->prepare('SELECT * FROM articles WHERE slug = ?');
        $stmt->execute([$slug]);
        $article = $stmt->fetch();

        return $article ? self::withCategories([$article])[0] : null;
    }

    // +1 просмотр
    public static function incrementViews(int $id): void
    {
        Database::getPDO()
            ->prepare('UPDATE articles SET views = views + 1 WHERE id = ?')
            ->execute([$id]);
    }

    // похожие статьи
    public static function similar(int $articleId, int $limit = 3): array
    {
        $stmt = Database::getPDO()->prepare(
            'SELECT ' . self::LIST_FIELDS . ', COUNT(*) AS common
             FROM articles a
             JOIN article_category ac ON ac.article_id = a.id
             WHERE ac.category_id IN (
                     SELECT category_id FROM article_category WHERE article_id = ?
                   )
               AND a.id <> ?
             GROUP BY a.id
             ORDER BY common DESC, a.published_at DESC
             LIMIT ' . (int) $limit
        );
        $stmt->execute([$articleId, $articleId]);

        return self::withCategories($stmt->fetchAll());
    }

    // add список категорий
    private static function withCategories(array $articles): array
    {
        if (!$articles) {
            return [];
        }

        $ids = array_column($articles, 'id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = Database::getPDO()->prepare(
            "SELECT ac.article_id, c.name, c.slug
             FROM article_category ac
             JOIN categories c ON c.id = ac.category_id
             WHERE ac.article_id IN ($placeholders)
             ORDER BY c.name"
        );
        $stmt->execute($ids);

        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[$row['article_id']][] = ['name' => $row['name'], 'slug' => $row['slug']];
        }

        foreach ($articles as $i => $article) {
            $articles[$i]['categories'] = $map[$article['id']] ?? [];
        }

        return $articles;
    }

    // для пагинации
    public static function countByCategory(int $categoryId): int
    {
        $stmt = Database::getPDO()->prepare('SELECT COUNT(*) FROM article_category WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }

    public static function byCategory(int $categoryId, string $sort = 'date', int $limit = 3, int $offset = 0): array
    {
        $orderBy = self::SORTS[$sort] ?? self::SORTS['date'];

        $stmt = Database::getPDO()->prepare(
            'SELECT ' . self::LIST_FIELDS . '
             FROM articles a
             JOIN article_category ac ON ac.article_id = a.id
             WHERE ac.category_id = ?
             ORDER BY ' . $orderBy . '
             LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset
        );
        $stmt->execute([$categoryId]);

        return self::withCategories($stmt->fetchAll());
    }

}