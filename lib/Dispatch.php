<?php

class Dispatch
{
    private $_defaultController = null;

    public function execute()
    {
        $controller = $this->getRequest();
		if (strpos($_SERVER['REQUEST_URI'], 'register')) {
			$controller = 'RegistrationController';
		} elseif (!isset($_SESSION['user']) || $_SESSION['user'] == false) {
			$controller = 'IndexController';
		}
		$this->dispatchController($controller);
    }

    protected function getRequest()
    {
        $defaultRoutes = array(
            'IndexController' => '',
            'MainController' => 'main',
            'RegistrationController' => 'register',
            'AddDocController' => 'add_document',
            'AdminRegController' => 'add_user_fk',
            'AdminController' => 'admin',
            'AutomatizationCenterController' => 'automatizatioin_center',
        );

        $url = $_SERVER['REQUEST_URI'];

		if (preg_match('/main\?b=[0-9]{1}$/', trim($url, '/'))) {
			call_user_func(__NAMESPACE__ .'Site\Controller\MainController::displayDocuments');
			return 'MainController';
		}

        foreach ($defaultRoutes as $controller => $pattern) {
			if (trim($url, '/') == $pattern) {
				return $controller;
            }
        }

		if (trim($url, '/') == 'login') {
			call_user_func(__NAMESPACE__ .'Site\Controller\IndexController::login');
		}
		if (trim($url, '/') == 'logout') {
			call_user_func(__NAMESPACE__ .'Site\Controller\IndexController::logout');
		}
        if (trim($url, '/') == 'register/submit') {
            call_user_func(__NAMESPACE__ .'Site\Controller\RegistrationController::register');
            return 'RegistrationController';
        }
		if (trim($url, '/') == 'register/register_status') {
			call_user_func(__NAMESPACE__ .'Site\Controller\RegistrationController::status');
			return 'RegistrationController';
		}

        $this->_defaultController = 'IndexController';
        return $this->_defaultController;
    }

    protected function dispatchController($controller)
    {
        if (!is_array($controller)) {
            $controller = array_filter(array(
                $controller,
            ));
        }
        if (empty($controller)) {
            $controller[] = 'IndexController';
        }
        if (count($controller) == 1) {
            $controller[] = 'execute';
        }
        $view = null;
        $className = 'Site\\Controller\\' . array_shift($controller);
        $actionName = array_shift($controller);
        $controller = new $className();
        $action = array(
            $controller,
            $actionName,
        );
        if (is_callable($action)) {
            $view = call_user_func_array($action, array());
        }
    }

}