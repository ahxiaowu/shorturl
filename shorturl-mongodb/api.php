<?php
include './ShortUrl.class.php';

$urlObj = new ShortUrl();

$oriurl = $_POST['oriurl'];

echo $urlObj->addUrl($oriurl);

exit;
?>