<?php
include 'connect.php';
session_start();


// Ambil API Key dari database
function get_api_key($koneksi) {
    $sql = "SELECT setting_value FROM settings WHERE setting_key = 'api_key' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['setting_value'] ?? '';
}

function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

if (isset($_POST['new_chat'])) {
    $_SESSION['chat_history'] = [];
    $_SESSION['info_msg'] = 'Chat baru berhasil dibuat. Silakan mulai ngobrol!';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['new_chat'])) {
    $api_key = get_api_key($koneksi); // Ambil dari database
    $url = "https://router.huggingface.co/novita/v3/openai/chat/completions";
    $user_message = trim($_POST['message'] ?? '');

    if ($user_message !== '') {
        $_SESSION['chat_history'][] = ['role' => 'user', 'content' => $user_message];

        $postData = [
            "model" => "google/gemma-3-27b-it",
            "messages" => $_SESSION['chat_history'],
            "stream" => false
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $api_key",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($_GET['debug'])) {
            debug($result);
        }

        if ($httpcode === 200 && isset($result['choices'][0]['message']['content'])) {
            $bot_reply = $result['choices'][0]['message']['content'];
        } elseif (isset($result['error'])) {
            $bot_reply = "Error dari API: " . $result['error'];
        } else {
            $bot_reply = "Maaf, saya tidak mengerti.";
        }

        $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $bot_reply];
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
