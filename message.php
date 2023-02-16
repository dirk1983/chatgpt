<?php
header( "Content-Type: application/json" );
$context = json_decode( $_POST['context'] ?: "[]" ) ?: [];
$prompt = "";
if( empty( $context ) ) {
    $please_use_above = "";
} else {
    $context = array_slice( $context, -5 );
    foreach( $context as $message ) {
       $prompt .= "问题：\n" . $message[0] . "\n\n答案：\n" . $message[1] . "\n\n";
    }
    $please_use_above = "。请使用上面的问题和答案作为前后文进行回答。";
}
$prompt .= "问题：\n" . $_POST['message'] . $please_use_above . "\n\n答案：\n\n";

$dTemperature = 0.9;
$iMaxTokens = 1024;
$top_p = 1;
$frequency_penalty = 0.0;
$presence_penalty = 0.0;
$OPENAI_API_KEY = "sk-PXQ0A35RLCQaImgLujPST3blbkFJ2d7Kaa9aJjUqzvYwwkqd";
$sModel = "text-davinci-003";
$ch = curl_init();
$headers  = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY . ''
];

$postData = [
    'model' => $sModel,
    'prompt' => str_replace('"', '', $prompt),
    'temperature' => $dTemperature,
    'max_tokens' => $iMaxTokens,
    'top_p' => $top_p,
    'frequency_penalty' => $frequency_penalty,
    'presence_penalty' => $presence_penalty,
    'stop' => '[" Human:", " AI:"]',
];

//echo json_encode($postData);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$result = curl_exec($ch);
$complete = json_decode($result);

if( isset( $complete->choices[0]->text ) ) {
    $text = str_replace( "\\n", "\n", $complete->choices[0]->text );
} elseif( isset( $complete->error->message ) ) {
    $text = "服务器返回错误信息：".$complete->error->message;
} else {
    $text = "抱歉，我不知道如何回答.";
}



echo json_encode( [
     "message" => $text,
     "raw_message" => $text,
     "status" => "success",
 ] );

$content2 = $_SERVER["REMOTE_ADDR"]." | ".date("Y-m-d H:i:s")."\n";
$content2 .= "Q:".$_POST['message']."\nA:".$text."\n----------------\n";
$myfile = fopen(__DIR__ . "/chat.txt", "a") or die("Writing file failed.");
fwrite($myfile, $content2);
fclose($myfile);
?>