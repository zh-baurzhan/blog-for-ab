<?php

use Smarty\Smarty;
class TemplateRenderer
{
    private static ?Smarty $smarty = null;

    private static function smarty(): Smarty
    {
        if (self::$smarty === null) {
            $sm = new Smarty();
            $sm->setTemplateDir(ROOT . '/templates');
            $sm->setCompileDir(ROOT . '/var/templates_c');
            $sm->setCacheDir(ROOT . '/var/cache');
            $sm->setEscapeHtml(true);
            self::$smarty = $sm;
        }
        return self::$smarty;
    }

    /**
     * @throws \Smarty\Exception
     */
    public static function render(string $template, array $data = []): string
    {
        $sm = self::smarty();
        $sm->assign($data);
        return $sm->fetch($template);
    }

    public static function notFound(): string
    {
        http_response_code(404);
        return self::render('404.tpl', ['title' => 'Не найдено']);
    }

}
