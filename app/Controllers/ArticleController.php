<?php

class ArticleController
{
    /**
     * @throws \Smarty\Exception
     */
    public function show(string $slug): string
    {
        $article = Article::findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            return TemplateRenderer::render('404.tpl', ['title' => 'Не найдено']);
        }

        Article::incrementViews((int) $article['id']);
        $article['views']++;

        return TemplateRenderer::render('article.tpl', [
            'title'   => $article['title'],
            'article' => $article,
            'similar' => Article::similar((int) $article['id'], 3),
        ]);
    }
}