<?php

namespace Site\Controller;

use Site\Database;

class RegistrationController extends SiteController
{
    public function execute()
    {
		if ($_POST) {
			$this->register();
		} else {
			parent::template('page/register.php');
		}
    }

	public function register()
	{
		$login 		= $_REQUEST['login'] ?: '';
		$name 		= $_REQUEST['name'] ?: '';
		$lname 		= $_REQUEST['lname'] ?: '';
		$email 		= $_REQUEST["email"] ?: '';
		$pass 		= $_REQUEST['pass'] ?: '';
		$faculty 	= $_REQUEST['faculty'] ?: '';
		$kafedra 	= $_REQUEST['kafedra'] ?: '';
		$posada 	= $_REQUEST['posada'] ?: '';
		$addInfo 	= $_REQUEST['additional_info'] ?: '';

		if ($_POST && !empty($name)
			&& !empty($login)
			&& !empty($lname)
			&& !empty($email)
			&& !empty($pass)
			&& !empty($faculty)
			&& !empty($kafedra)
			&& !empty($posada)
		) {
			$db = new Database();
			$db->query('SET NAMES utf8');

			$query = "select * from person where fname ='".$name."' and fname = '".$name."' and lname = '".$lname."'";
			$result = $db->query($query);
			if ($result && mysqli_num_rows($result) > 0) {
				$db->close_database();
				$model = array(
					'status' => 0,
					'error' => 'Користувач з таким ім\'ям вже зареєстрований!'
				);
				$this->template('page/register.php', $model);
			} else {
				$pass = md5($pass);
				$query = "INSERT INTO person".
					"(fname, lname, email, pass, faculty_id, kafedra_id, posada_id, additional_info, login)"."VALUES".
					"('$name','$lname','$email','$pass','$faculty',	'$kafedra',	'$posada','$addInfo','$login')";
				$result = $db->query($query);

				if ($result) {
					$db->close_database();
					$model = array(
						'name' => $name,
						'lname' => $lname,
						'status' => true
					);
					$this->template('page/register.php', $model);
				}
			}
		}
	}
}