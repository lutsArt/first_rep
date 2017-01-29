<?php

namespace Site\Controller;

use Site\Database;

class AdminRegController extends SiteController
{
    public function execute()
    {
		if (isset($_SESSION['user'])) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /");
		}
		if ($_POST) {
			$this->register();
		} else {
			parent::template('page/add_user_fk.php');
		}
    }

	public function register()
	{
		$login 		= $_REQUEST['login'] ?: '';
		$email 		= $_REQUEST["email"] ?: '';
		$faculty 	= $_REQUEST['faculty'] ?: '';
		$kafedra 	= $_REQUEST['kafedra'] ?: '';

		if ($_POST && !empty($login)
			&& !empty($email)
			&& !empty($faculty)
			&& !empty($kafedra)
		) {
			$db = new Database();
			$db->query('SET NAMES utf8');

			$query = "select * from administrators where login ='".$login."' and email = '".$email."'";
			$result = $db->query($query);
			if ($result && mysqli_num_rows($result) > 0) {
				$db->close_database();
				$model = array(
					'status' => 0,
					'error' => 'Користувач з таким ім\'ям вже зареєстрований!'
				);
				$this->template('page/add_user_fk.php', $model);
			} else {
				$query = "INSERT INTO administrators".
					"(login,email,faculty_id, kafedra_id)"."VALUES".
					"('$login','$email','$faculty',	'$kafedra')";
				$result = $db->query($query);

				if ($result) {
					$db->close_database();
					$model = array(
						'login' => $login,
						'email' => $email,
						'status' => true
					);
					$this->template('page/add_user_fk.php', $model);
				}
			}
		}
	}
}