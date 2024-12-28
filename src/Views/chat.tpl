{extends "base.tpl"}

{assign "title" "Чат"}

{block "styles" append}
    <link href="/css/chat.css" rel="stylesheet">
{/block}

{block "scripts" append}
    <script src="/js/chat.js" defer></script>
{/block}

{block "content"}

    {* <div class="chat-container">

        <div class="message received">

            <div class="sender">Alice</div>

            <div class="message-content">

                <p>Hello! How are you?</p>

            </div>

            <span class="timestamp">10:00 AM</span>

        </div>

        <div class="message sent">

            <div class="sender">You</div>

            <div class="message-content">

                <p>I'm good, thanks! How about you?</p>

        </div>

        <span class="timestamp">10:01 AM</span>

    </div>

    <div class="message received">

        <div class="sender">Alice</div>

        <div class="message-content">

            <p>I'm doing well, thank you!</p>

            </div>

            <span class="timestamp">10:02 AM</span>

        </div>

        <div class="message sent">

            <div class="sender">You</div>


            <div class="message-content">
            <div class="reply-block">
                <div class="sender">Alice</div>
                <div class="message-content">Ляляля</div>
            </div>
                <p>What are you up to today?</p>

            </div>

            <span class="timestamp">10:03 AM</span>

        </div>

    </div> *}

    <div class="chat-container">

    </div>

    <div class="input-container">
        <button class="options-toggle">
        <i class="fa-solid fa-bars"></i>
        
        </button>
        <textarea placeholder="Type a message..."></textarea>

        <button><i class="fa-solid fa-arrow-right"></i></button>

    </div>

    <div class="chat-options">
        <div class="menu-close">
            <i class="fa-solid fa-xmark"></i>
        </div>
    </div>

{/block}