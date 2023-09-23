<?php

session_start();

require 'vendor/autoload.php';
require 'routes/route.php';

const ROOT = __DIR__;

$router->run(($argv??[]));
