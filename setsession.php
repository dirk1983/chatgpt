<?php
$context = json_decode($_POST['context'] ?: "[]") ?: [];
if (mb_substr($_POST["message"], 0, 1, 'UTF-8') === '画') {
    $postData = [
        "model" => "dall-e-2", //如果您的APIKEY有dall-e-3的权限，可以修改为dall-e-3，目前只有能访问gpt-4模型的APIKEY才有dall-e-3权限。
        "prompt" => $_POST['message'],
        "n" => 1,
        "size" => "1024x1024"
    ];
} else {
    $postData = [
        "model" => "gpt-3.5-turbo", //这里可以修改成gpt-4，gpt-4-1106-preview等，如果您的APIKEY有权限就可以使用GPT4模型
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
