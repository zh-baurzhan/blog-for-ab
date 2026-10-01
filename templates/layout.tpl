<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title}</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <a class="logo" href="/">Мой блог</a>
    </div>
</header>

<main class="container">
    {block name="content"}{/block}
</main>

<footer class="site-footer">
    <div class="container">&copy; {$smarty.now|date_format:"%Y"}</div>
</footer>
</body>
</html>
