<?php

namespace Site\Controller;

class SiteController
{
    public $_controller;

    public function __construct()
    {
		if (isset($_SESSION['admin'])) {
			$this->template('page/admin.php');
		}
//		$this->checkAuth();
	}

    public function execute()
    {
	}

    public static function template($controller, $model = array()) {
		if (is_array($model)) {
			extract($model);
		}
        if (file_exists(ROOT_PATH . '/templates/partials/header.php')) {
            include (ROOT_PATH . '/templates/partials/header.php');
        }

        include (ROOT_PATH . '/templates/' . $controller);

        if (file_exists(ROOT_PATH . '/templates/partials/footer.php')) {
            include (ROOT_PATH . '/templates/partials/footer.php');
        }

		return false;
    }

	protected function checkAuth () {
		if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/logout' || $_SERVER['REQUEST_URI'] === '/register' || $_SERVER['REQUEST_URI'] === '/admin') {
			return;
		}
		if (preg_match("/adm-(.*)-/", $_SESSION['user_access']['admin'], $matches) && $_SERVER['REQUEST_URI'] === '/') {
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /admin");
		}
//		else if (isset($_SESSION['user'])) {
////			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
////			header("Location: /");
//		}
		else {
//			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
//			header("Location: /main");
		}
	}
}