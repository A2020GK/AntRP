{extends "base.tpl"}

{assign "title" "Список персонажей"}
{block "content"}
    <p>Здесь вы можете ознакомится со списком персонажей и посмотреть информацию о каждом из них.</p>
    <ul>
        {foreach $characters as $ch}
            <li><a href="{route name="character.view" id=$ch->id}">{$ch->name}</a></li>
        {/foreach}
    </ul>
{/block}