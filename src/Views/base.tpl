<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} | AntRP</title>
    <meta name="description" content="Web-chat for roleplaying games. Powered by AntWebKernel, developed by Antony (A2020GK)">
    {block "styles"}
        <link href="/css/default.css" rel="stylesheet">
    {/block}
    {block "scripts"}
        <script src="/js/default.js" defer></script>
    {/block}
</head>

<body>
    <div class="app">
        <header>
            <div class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
            <aside class="sidebar">
                <div class="menu-close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <ul>
                    <li><a href="{route name="home"}"><b><i class="fa-solid fa-house"></i> Главная</b></a></li>
                    <li><a href="{route name="character.list"}"><i class="fa-solid fa-smile"></i> Персонажи</a></li>
                    <li><a href="{route name="chat"}"><i class="fa-solid fa-comment"></i> Чат</a></li>
                </ul>
            </aside>
            <h2>{$title}</h2>
        </header>
        <main>
            <div class="content">
                {block "content"}This page is empty (~ AntWebKernel from base.tpl){/block}
            </div>
        </main>
        <footer>
            <p>&copy; <a style="color:white" href="https://github.com/A2020GK">Antony</a> {$smarty.now|date_format:"Y"}
            </p>
        </footer>
    </div>
</body>

</html>