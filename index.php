<?php

require 'vendor/autoload.php';
require 'routes/route.php';

session_start();

$router->run(($argv??[]));
