<?php

require_once "vendor/autoload.php";

$class = new \Aneed\TronAPI\AneedTronAPI();

var_dump($class->createdAddress(100));
