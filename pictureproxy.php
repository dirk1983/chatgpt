<?php
if ((isset($_GET['url'])) && (strpos($_GET['url'], 'http') === 0)) {
    $headers = get_headers($_GET['url'], 1);
    if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image/') !== false) {
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        $image = file_get_contents($_GET['url'], false, $context);
        header("Content-type: image/jpeg");
        echo $image;
    } else {
        echo "Invalid request: not an image file";
    }
} else {
    echo "Invalid request";
}
