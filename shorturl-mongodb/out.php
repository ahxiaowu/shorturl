<?php
include './ShortUrl.class.php';
/*
echo '<pre>';
print_r($_GET);
*/

$urlObj = new ShortUrl();


$url = $_GET['id'];

/*
echo '<pre>';
print_r($urlObj->gotoUrl($url));
*/

$res = $urlObj->gotoUrl($url);
header('Location:'.$res['oriurl'],302);



?>