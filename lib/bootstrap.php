<?php
namespace {
    define('SESSION_SAVE_PATH', ROOT_PATH . '/tmp/session');
    define('SESSION_EXPIRE', 86400);
    define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST']);

    $path = realpath(ROOT_PATH . '/lib/');
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);

    /* Autoload register */
    spl_autoload_register(function ($className) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        include $file;
        if (!class_exists($className) && !interface_exists($className)) {
            throw new RuntimeException("Class $className could not be found");
        }
        return true;
    });
}