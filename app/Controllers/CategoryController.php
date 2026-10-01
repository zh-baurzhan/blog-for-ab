<?php

class CategoryController
{
    private const int PER_PAGE = 6;

    /**
     * @throws \Smarty\Exception
     */

    public function show(string $slug): string
    {
        $category = Category::findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            return TemplateRenderer::render('404.tpl', ['title' => 'Не найдено']);
        }

        $sort = $_GET['sort'] ?? 'date';

        if (!isset(Article::SORTS[$sort])) {
            $sort = 'date';
        }

        $total = Article::countByCategory((int) $category['id']);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        writeLog($pages, $page, ($page - 1) * self::PER_PAGE);


        $articles = Article::byCategory(
            (int) $category['id'],
            $sort,
            self::PER_PAGE,
            ($page - 1) * self::PER_PAGE
        );

        return TemplateRenderer::render('category.tpl', [
            'title'    => $category['name'],
            'category' => $category,
            'articles' => $articles,
            'sort'     => $sort,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
    }
}