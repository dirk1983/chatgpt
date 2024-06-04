<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $headers = get_headers($url, 1);
    if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image/')!== false) {
        $image = file_get_contents($url);
        header("Content-type: image/jpeg");
        echo $image;
    } else {
        echo "Invalid request: not an image file";
    }
} else {
    echo "Invalid request";
}
