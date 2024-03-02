
window.addEventListener('load', init);

function init(e) {
    let container1 = document.querySelector('#chat-1-container');
    let container2 = document.querySelector('#chat-2-container');

    container1.appendChild(makeChat(1));
    container2.appendChild(makeChat(2));
}

function makeChat(num) {
    let chat = document.newNode('div', {"class": "chat", "data-num": num});
    let bar = document.newNode('div', {"class": "bar"});
    let messageContainer = document.newNode('div', {"class": "message-container"});
    let inputBox = document.newNode('div', {"class": "input-box"});
    let input = document.newNode('input', {
        "placeholder": "Pregunta algo..."
    });
    let button = document.newNode('button', {
        "class": "button"
    }, {"click": e=>{ console.log("cambur"); }});
    button.innerHTML = "Enviar";

    chat.appendChild(bar);
    chat.appendChild(messageContainer);
    inputBox.appendChild(input);
    inputBox.appendChild(button);
    chat.appendChild(inputBox);

    return chat;
}

document.newNode = (tag, attrs={}, events={}) => {
    let node = document.createElement(tag);
    for ( let k in attrs ) {
        node.setAttribute(k, attrs[k]);
    }
    for ( let k in events ) {
        node.addEventListener(k, events[k]);
    }

    return node;
}