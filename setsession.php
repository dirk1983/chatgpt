<?php
$context = json_decode($_POST['context'] ?: "[]") ?: [];
if (mb_substr($_POST["message"], 0, 1, 'UTF-8') === '画') {
    $postData = [
        "prompt" => $_POST['message'],
        "n" => 1,
        "size" => "1024x1024"
    ];
} else {
    $postData = [
        "model" => "gpt-3.5-turbo",
        "temperature" => 0,
        "stream" => true,
        "messages" => [],
    ];
    if (!empty($context)) {
        $context = array_slice($context, -5);
        foreach ($context as $message) {
            $postData['messages'][] = ['role' => 'user', 'content' => $message[0]];
            $postData['messages'][] = ['role' => 'assistant', 'content' => $message[1]];
        }
    }
    $postData['messages'][] = ['role' => 'user', 'content' => $_POST['message']];
}
$postData = json_encode($postData);
session_start();
$_SESSION['data'] = $postData;
if ((isset($_POST['key'])) && (!empty($_POST['key']))) {
    $_SESSION['key'] = $_POST['key'];
}
echo '{"success":true}';
