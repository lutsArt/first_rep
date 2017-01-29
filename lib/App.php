<?php

use Site\Session;

class App
{
    public function run()
    {
		if ( !session_start() ) {
			throw new \Exception("Can't to create a new session");
		}

        $this->unregisterGlobals();
		$view = new Dispatch;
        $view->execute();
    }

    private function setLogs()
    {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT_PATH .'/tmp/log/main.log');
    }

    private function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

//	private function dbConnect () {
//		$db = new Database();
//		$query = $db->query('select * from users');
//		while($obj = $query->fetch_object()){
//			var_dump($obj->name);
//			var_dump($obj->id);
//		}
//
//		$query->close();
//	}
}

