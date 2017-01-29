<?php

$dir = dirname($_SERVER["SCRIPT_FILENAME"]);
define('ROOT_PATH', $dir ? $dir : '.');

require_once (ROOT_PATH . '/lib/bootstrap.php');

$app = new App();
$app->run();
