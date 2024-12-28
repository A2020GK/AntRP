{extends "base.tpl"}

{assign "title" "Вход"}

{block "content"}
    <form method="post">
        <h3>Вход</h3>
        <p><label for="username"><i class="fa-solid fa-user"></i> <input required type="text" placeholder="Имя пользователя" value="{$previousLogin}" name="username" id="username"></p>
        <p><label for="password"><i class="fa-solid fa-key"></i> <input required type="password" placeholder="Пароль" name="password" id="password"></p>
        {if $error}
            <p><span class="error"><i class="fa-solid fa-xmark"></i> Неверное имя пользователя или пароль.</span></p>
        {/if}
        <p><button type="submit">Войти</button></p>
    </form>
{/block}