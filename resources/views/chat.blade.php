<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
</head>
<body>
<div id="app">
    <h1>Live Chat</h1>
    <div id="messages">
        <!-- Messages will appear here -->
    </div>
    <input type="text" id="message-input" placeholder="Type a message...">
    <button id="send-message">Send</button>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    document.getElementById('send-message').addEventListener('click', function () {
        let message = document.getElementById('message-input').value;
        axios.post('/send-message', {
            message: message,
            conversation_id: 1 // Replace with dynamic conversation ID
        }).then(response => {
            console.log(response.data);
        });
    });

    window.Echo.private('chat.1') // Replace with dynamic conversation ID
        .listen('MessageSent', (e) => {
            let messageElement = document.createElement('div');
            messageElement.innerText = e.message.message;
            document.getElementById('messages').appendChild(messageElement);
        });
</script>
</body>
</html>
