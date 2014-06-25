<?php


$mongoClient = new MongoClient('mongodb://127.0.0.1:27017');

$mongodb = $mongoClient->test;

$mongoColl = $mongodb->selectCollection('cnt');

$arr = $mongoColl->findAndModify(
	array('_id'=>1),
	array('$inc'=>array('sn'=>1))
);

echo '<pre>';

var_dump($arr);


?> 