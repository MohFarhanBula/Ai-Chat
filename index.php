<?php
    include 'functions/api.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chatbot Gemma AI - By CreativeKodeID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .chat-container {
            background: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 20px;
            max-width: 700px;
            width: 100%;
            margin: auto;
        }
        .chat-box {
            height: 60vh;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            background: #fefefe;
        }
        .message {
            margin-bottom: 15px;
            line-height: 1.5;
            white-space: normal;
        }
        .message.user {
            text-align: right;
            color: #ffffff;
        }
        .message span {
            display: inline-block;
            max-width: 70%;
            word-wrap: break-word;
        }
        .message span br {
            line-height: 1.2;
            margin: 0;
        }
        .message.user span {
            display: inline-block;
            background-color: #0d6efd;
            padding: 10px 15px;
            border-radius: 15px 15px 0 15px;
            max-width: 70%;
        }
        .message.bot {
            text-align: left;
            color: #212529;
        }
        .message.bot span {
            display: inline-block;
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 15px 15px 15px 0;
            max-width: 70%;
        }
        .chat-footer input {
            border-radius: 30px;
        }
        .chat-footer button {
            border-radius: 30px;
        }
        img {
            margin-top: 10px;
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="chat-container">
            <h4 class="text-center text-primary mb-4">🤖 Script Chatbot AI - Gemma AI - By CreativeKodeID</h4>

            <?php if (!empty($_SESSION['info_msg'])): ?>
                <div class="alert alert-info text-center"><?= $_SESSION['info_msg']; unset($_SESSION['info_msg']); ?></div>
            <?php endif; ?>

            <div class="chat-box mb-4" id="chatMessages">
                <?php foreach ($_SESSION['chat_history'] as $msg):
                    $role = $msg['role'] === 'user' ? 'user' : 'bot';
                    $content = htmlspecialchars($msg['content']);
                    $content = preg_replace('/!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1" class="img-fluid">', $content);
                    $content = nl2br($content);
                    echo '<div class="message ' . $role . '"><span>' . $content . '</span></div>';
                endforeach; ?>
            </div>

            <form method="POST" class="chat-footer d-flex gap-2 mb-3" id="chatForm">
                <input type="text" name="message" id="messageInput" class="form-control" placeholder="Ketik pesan..." required autocomplete="off">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>

            <form method="POST" class="text-center">
                <button type="submit" name="new_chat" class="btn btn-outline-danger">Mulai Chat Baru</button>
            </form>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
        document.getElementById('messageInput').focus();
    </script>
</body>
</html>
