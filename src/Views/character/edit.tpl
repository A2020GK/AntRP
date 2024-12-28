{extends "base.tpl"}

{if $character}{assign "title" "Редактировать:{$character->name}"}{else}{assign "title" "Персонаж не найден"}{/if}

{block "styles" append}
    <link rel="stylesheet" href="/css/character-view.css">
{/block}

{block "content"}
    {if $character}
        <form method="post">
            <p><b>Имя:</b> {$character->name}</p>
            <h4>Описание:</h4>
            <textarea rows="30" cols="40" class="character-description">{$character->description}</textarea>
            <p><b>Владелец:</b> {$character->owner->username}</p>
            <p><button type="submit">Сохранить</button></p>
        </form>
    {else}
        <p>Персонаж не найден :(</p>
        <p><a href="{route name="home"}">Главная</a></p>
    {/if}
{/block}