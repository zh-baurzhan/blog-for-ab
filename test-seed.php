<?php
// Заполняет базу тестовыми данными.
// Запуск: docker compose exec app php test-seed.php

require_once __DIR__ . '/app/Database.php';

$pdo = Database::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE article_category');
$pdo->exec('TRUNCATE articles');
$pdo->exec('TRUNCATE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$categories = [
    'novosti'    => ['Новости',    'Главные события и новости.'],
    'biznes'   => ['Бизнес',   'Рубрика Бизнес.'],
    'tehnologii' => ['Технологии', 'Программирование, гаджеты и IT.'],
];

$insertCat = $pdo->prepare('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)');
$categoryIds = [];
foreach ($categories as $slug => [$name, $description]) {
    $insertCat->execute([$name, $slug, $description]);
    $categoryIds[] = (int) $pdo->lastInsertId();
}

$insertArt = $pdo->prepare(
    'INSERT INTO articles (image, title, slug, description, body, views, published_at)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$link = $pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');

$total = 30;
for ($i = 1; $i <= $total; $i++) {
    $slug = "statya-$i";

    $insertArt->execute([
        "/uploads/images/" . (($i - 1) % 10 + 1) . ".jpg",
        "Статья номер $i",
        $slug,
        "Краткое описание статьи $i.",
        "Полный текст статьи $i.\n\nВторой абзац статьи.",
        random_int(0, 500),
        date('Y-m-d H:i:s', strtotime("-$i days")),
    ]);
    $articleId = (int) $pdo->lastInsertId();

    // 1 или 2 случайные категории
    $picked = (array) array_rand(array_flip($categoryIds), random_int(1, 2));
    foreach ($picked as $categoryId) {
        $link->execute([$articleId, $categoryId]);
    }
}

echo 'Готово: ' . count($categories) . " категории, $total статей\n";
