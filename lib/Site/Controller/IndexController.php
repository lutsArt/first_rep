<?php

namespace Site\Controller;

use Site\Database;
use Site\UpdateDBInformation;
use Kneu\Api;

class IndexController extends SiteController
{
	public $user = false;

	public function execute()
	{
		$client_id = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['client_id'];
		$isSsl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443;
		$redirect_url = 'http' . ($isSsl ? 's' : '') . '%3A%2F%2F' . $_SERVER['HTTP_HOST'];
		$kneuOauthUrl = 'https://auth.kneu.edu.ua/oauth?response_type=code&client_id='.$client_id.'&redirect_uri=' . $redirect_url;

		if (isset($_REQUEST['code'])) {
			$this->login($_REQUEST['code'], $redirect_url);
		}

		if (isset($_SESSION['user']) && !isset($_SESSION['user_super'])) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /main");
		} elseif (!isset($_REQUEST['admin'])) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 301 Moved');
			header("Location: " . $kneuOauthUrl);
		}

		if (isset($_SESSION['user_super'])) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /automatizatioin_center");
		}

		if (isset($_SESSION['user_access'])) {
			if (preg_match("/adm-(.*)-/", $_SESSION['user_access']['admin'], $matches)) {
				header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
				header("Location: /admin");
			}
		}

		if ($_POST) {
			$this->login();
		} else {
			parent::template('page/index.php');
		}
	}

	protected function login($code = '', $redirect_url = '') {
		if ($_REQUEST['name'] && $_REQUEST['pass']) {
			$db = new Database();
			$db->query('SET NAMES utf8');

			$name = $_REQUEST['name'];
			$pass = $_REQUEST['pass'];
			$name = strtolower(trim($name));

			$db->close_database();
			if ($this->admin($name, $pass)) {
				return $this->template('page/admin.php');
			}
			if ($this->automatizationCenter($name, $pass)) {
				return $this->template('page/automat_center.php');
			}
			$model = array(
				'status' => 0,
				'error' => 'Введені дані не вірні!'
			);
			$this->template('page/index.php', $model);
		} else {
			$code = filter_input(INPUT_GET, 'code') ? filter_input(INPUT_GET, 'code') : $code;
			$kneuApi = new Api();
			$client_id = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['client_id'];
			$client_secret = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['client_secret'];
			$kneuApi->oauthToken($client_id, $client_secret, $code, $redirect_url);

			$user = $kneuApi->getUser();
			if ($user && $user->type == 'teacher') {
				$_SESSION['user_access'] = array(
					'access' => isset($user->dostup_id) ? $user->dostup_id : 4,
					'email' => $user->email
				);

				$db = new Database();
				$query = $db->query('insert into person (email) values ("'.$user->email.'") where person.id = "'.$user->teacher_id.'"');
				$query->close();
				$db->close_database();

				$_SESSION['user'] = true;

				header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
				header("Location: /main");
			}
		}
	}

	protected function admin($login, $pass) {
		$db = new Database();
		$pass = md5($pass);
		$query = $db->query("
			select email, administrators.faculty_id, administrators.kafedra_id from administrators, faculty
			WHERE login = "."'$login'"."
			  and administrators.faculty_id = faculty.id
			  AND faculty.pass = "."'$pass'".";
		");
		if ($query && mysqli_num_rows($query) > 0) {
			while($obj = $query->fetch_object()){
				$_SESSION['user_access'] = array(
					'admin' => rand(10, 9999) . 'adm-' . $obj->faculty_id . '-' . $obj->kafedra_id,
					'email' => $obj->email
				);
			}
			$query->close();
			$db->close_database();

			$_SESSION['user'] = true;
			$_SESSION['user_admin_kaf'] = true;

			return true;
		} else {
			$query->close();
			$db->close_database();
		}

		return false;
	}

	protected function automatizationCenter($login, $pass)
	{
		$db = new Database();
		$pass = md5($pass);

		$query = $db->query("
				select email from automatisation_center
				WHERE login = "."'$login'"."
				  AND pass = "."'$pass'".";
			");

		if ($query && mysqli_num_rows($query) > 0) {
			while ($obj = $query->fetch_object()) {
				$_SESSION['user_access'] = array(
					'email' => $obj->email
				);
			}
			$query->close();
			$db->close_database();

			$_SESSION['user'] = true;
			$_SESSION['user_super'] = true;

			return true;
		}

		$query->close();
		$db->close_database();

		return false;
	}

	public static function logout()
	{
		new UpdateDBInformation();
		$_SESSION['user'] = false;
		$_SESSION['user_admin_kaf'] = false;
		$_SESSION['user_super'] = false;
		session_unset();
		session_destroy();
		header("Location: https://kneu.edu.ua/");
	}
}
