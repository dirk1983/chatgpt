<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/event-stream");
session_start();
$postData = $_SESSION['data'];
$_SESSION['response'] = "";
$ch = curl_init();
$OPENAI_API_KEY = "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";
if ((isset($_SESSION['key'])) && (!empty($_POST['key']))) {
    $OPENAI_API_KEY = $_SESSION['key'];
}
$headers  = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY
];

setcookie("errcode", ""); //EventSource无法获取错误信息，通过cookie传递
setcookie("errmsg", "");

$callback = function ($ch, $data) {
    $l = strlen($data);
    $parts = explode(PHP_EOL.PHP_EOL, $data);

    foreach($parts as $v){ 
        $new_data = str_replace("data: ", "", $v);
        $complete = json_decode(trim($new_data));
        if(empty($complete)){
            echo $v . PHP_EOL . PHP_EOL;
        }elseif(isset($complete->error)) {
            setcookie("errcode", $complete->error->code);
            setcookie("errmsg", $v);
            if (strpos($complete->error->message, "Rate limit reached") === 0) { //访问频率超限错误返回的code为空，特殊处理一下
                setcookie("errcode", "rate_limit_reached");
            }
        } else {
            unset($complete->id,$complete->object,$complete->created,$complete->model);
            $data_str = "data: " . json_encode($complete) . PHP_EOL . PHP_EOL;
            echo $data_str;
            $_SESSION['response'] .= $data;
        }
    }

    return $l;
};

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
//curl_setopt($ch, CURLOPT_PROXY, "http://127.0.0.1:1081");

curl_exec($ch);

$answer = "";
$responsearr = explode("data: ", $_SESSION['response']);

foreach ($responsearr as $msg) {
    $contentarr = json_decode(trim($msg), true);
    if (isset($contentarr['choices'][0]['delta']['content'])) {
        $answer .= $contentarr['choices'][0]['delta']['content'];
    }
}

$questionarr = json_decode($_SESSION['data'], true);
$filecontent = $_SERVER["REMOTE_ADDR"] . " | " . date("Y-m-d H:i:s") . "\n";
$filecontent .= "Q:" . end($questionarr['messages'])['content'] .  "\nA:" . trim($answer) . "\n----------------\n";
$myfile = fopen(__DIR__ . "/chat.txt", "a") or die("Writing file failed.");
fwrite($myfile, $filecontent);
fclose($myfile);
curl_close($ch);
