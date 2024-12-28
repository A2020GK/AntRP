{extends file="base.tpl"}

{assign "title" "Главная"}

{block content}
    <p>Добро пожаловать!</p>
    <p>...какой-то текст бла бла бла</p>
    {if not $user}
        <p><a class="link" href="{route name="user.login"}">Войти</a></p>
    {else}
        <p>Здравствуйте, {$user->name}!</p>
        <div class="break-line"></div>
        <p><a href="{route name="chat"}" class="link"><i class="fa-solid fa-comment"></i> Чат</a></p>
        <p>Вам доступны следующие персонажи:</p>
        <ul>
            {foreach  $characters as $character}
                <li><a href="{route name="character.view" id=$character->id}">{$character->name}</a></li>
            {/foreach}
        </ul>
    {/if}
{/block}