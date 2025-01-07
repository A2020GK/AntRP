document.querySelector(".options-toggle").addEventListener("click", event => {
    document.querySelector(".chat-options").classList.add("active");
});
document.querySelector(".chat-options .menu-close").addEventListener("click", event => {
    document.querySelector(".chat-options").classList.remove("active");
});

const maxOverflow = 4;

document.querySelector("textarea.message-text").addEventListener("input", function (event) {
    const textarea = event.target;
    const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight) || 20; // Fallback to 20px if NaN
    const maxHeight = lineHeight * maxOverflow + 2; // Adding a small buffer for better visibility
    // console.log(`Line Height: ${lineHeight}, Max Height: ${maxHeight}`); // Debugging log

    textarea.style.height = 'auto'; // Reset height to auto to calculate new height
    const newHeight = textarea.scrollHeight;

    if (newHeight > maxHeight) {
        textarea.style.height = `${maxHeight}px`;
        textarea.style.overflowY = 'scroll'; // Enable scrolling
    } else {
        textarea.style.height = `${newHeight}px`;
        textarea.style.overflowY = 'hidden'; // Hide scrolling
    }
    textarea.style.resize = 'none'; // Prevent manual resizing

});

class Message {
    constructor(text, type, sender, time, reply = null) {
        this.text = text;
        this.type = type;
        this.sender = sender;
        this.time = time;
        this.reply = reply;
    }
    renderContent() {
        let c = document.createElement("div");
        c.className = "message-content";
        c.innerHTML += `<p>${this.text}</p>`;
        return c;
    }
    renderAsReplyBlock() {
        let d = document.createElement("div");
        d.className = "reply-block";
        d.classList.add(this.type);
        d.innerHTML += `<div class="sender">${this.sender}</div>`;
        d.appendChild(this.renderContent());
        return d;
    }
    render() {
        let m = document.createElement("div");
        m.className = "message";
        m.classList.add(this.type);
        m.innerHTML += `<div class="sender">${this.sender}</div>`;

        let c = this.renderContent();
        if (this.reply !== null) {
            c.innerHTML = (this.reply.renderAsReplyBlock()).outerHTML + c.innerHTML;
        }
        m.appendChild(c);

        m.innerHTML += `<span class="timestamp">${this.time}</span>`;

        return m;
    }
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

let ws = new WebSocket(`ws://${location.hostname}:8080`);
function sendPackage(package) {
    ws.send(JSON.stringify(package));
}

ws.addEventListener("open", function (event) {
    console.log(`Connected to ${ws.url}`);
    console.log(`Trying to auth with token...`);
    sendPackage({
        type: "auth",
        token: getCookie("session_token")
    });
});

let user=null;

function serverMessage(event) {
    let data=event.data;
    data=JSON.parse(data);
    console.log(data);

    if(data.type=="auth" && data.ok) {
        user=data.user;
    } else if(data.type == "message") {
        let msg = data;
        msg = new Message(msg.text, msg.sender == user.username ? "sent":"received", msg.sender, msg.time);
        console.log(msg);
        document.querySelector(".chat-container").appendChild(msg.render());
    }

}

ws.addEventListener("message",serverMessage);