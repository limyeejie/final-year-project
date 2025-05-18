<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI chatbot</title>
    <link rel="stylesheet" href="../css/chatbot_styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>
    <script type="importmap">
        {
            "imports": {
                "@google/generative-ai": "https://esm.run/@google/generative-ai"
            }
        }
    </script>

    <button class="chatbot-toggler">
        <span class="material-symbols-outlined">mode_comment</span>
        <span class="close-btn material-symbols-outlined">close</span>
    </button>
    <div class="chatbot">
        <div class="header">
            <h2>Chatbot</h2>
            <span class="material-symbols-outlined">close</span>
        </div>
        <ul class="chatbox">
            <li class="chat incoming">
                <span class="material-symbols-outlined">smart_toy</span>
                <p>Hi there! <br/>How can I help you today?</p>
            </li>
        </ul>
        <div class="chat-input">
            <textarea placeholder="Enter a message..." required></textarea>
            <span id="send-btn" class="material-symbols-outlined">send</span>
        </div>
    </div>

    <script type="module" src="../js/chatbotscript.js"></script>
</body>
</html>