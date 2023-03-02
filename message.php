<?php
header( "Access-Control-Allow-Origin: *" );
header( "Content-Type: application/json" );
$context = json_decode( $_POST['context'] ?: "[]" ) ?: [];
$prompt = "";
if( !empty( $context ) ) {
    $context = array_slice( $context, -5 );
    foreach( $context as $message ) {
       $prompt .= '{"role":"user","content":"' . str_replace("\n","\\n",$message[0]) . '"},{"role":"assistant","content":"' . str_replace("\n","\\n",$message[1]) . '"},';
    }
}
$prompt .= '{"role":"user","content":"' . addslashes($_POST['message']) . '"}';

$ch = curl_init();
$OPENAI_API_KEY = "sk-PXQ0A35RLCQaImgLujPST3blbkFJ2d7Kaa9aJjUqzvYwwkqd";
$headers  = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY . ''
];

$postData = '{"model":"gpt-3.5-turbo","messages":['.$prompt.']}';

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$result = curl_exec($ch);
$complete = json_decode($result);

if( isset( $complete->choices[0]->message->content ) ) {
    $text = trim(str_replace( "\\n", "\n", $complete->choices[0]->message->content ),"\n");
} elseif( isset( $complete->error->message ) ) {
    $text = "服务器返回错误信息：".$complete->error->message;
} else {
    $text = "服务器超时或返回异常消息。";
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
