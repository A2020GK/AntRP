{extends "base.tpl"}

{block "styles" append}
    <link rel="stylesheet" href="/css/character-view.css">
{/block}

{if $character}{assign "title" $character->name}{else}{assign "title" "Персонаж не найден"}{/if}

{block "content"}
    {if $character}
        <a class="edit" href="{route name="character.edit" id=$character->id}"><i class="fa-solid fa-pencil"></i></a>
        <p><b>Имя:</b> {$character->name}</p>
        <h4>Описание:</h4>
        <p class="character-description">
            {$character->description}
        </p>
        <p><b>Владелец:</b> {$character->owner->username}</p>
    {else}
        <p>Персонаж не найден :(</p>
        <p><a href="{route name="home"}">Главная</a></p>
    {/if}
{/block}