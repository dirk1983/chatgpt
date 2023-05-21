<?php
if (isset($_GET['url'])) {
    $image = file_get_contents($_GET['url']);
    header("Content-type: image/jpeg");
    echo $image;
} else {
    echo "Invalid request";
}
