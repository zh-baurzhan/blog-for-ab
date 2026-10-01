<?php

class IndexController
{
    /**
     * @throws \Smarty\Exception
     */
    public function index(): string
    {
        $categories = Category::withArticles();

        foreach ($categories as $i => $category) {
            $categories[$i]['articles'] = Article::byCategory((int) $category['id'], 'date', 3);
        }

        return TemplateRenderer::render('index.tpl', [
            'title'      => 'Блог',
            'categories' => $categories,
        ]);
    }
}
