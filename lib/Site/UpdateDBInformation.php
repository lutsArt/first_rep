<?php

namespace Site;

use Kneu\Api;
use Site\Database;

class UpdateDBInformation {
	protected $accessToken;
	protected $tokenType;
	protected $tokenExpiresIn;

	function __construct() {
		$kneuApi = new Api();
		$client_id = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['client_id'];
		$client_secret = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['client_secret'];

		$oAccess = $kneuApi->serverToken($client_id, $client_secret, 'client_credentials');

		$this->accessToken 		= $oAccess->access_token;
		$this->tokenType 		= $oAccess->token_type;
		$this->tokenExpiresIn 	= $oAccess->expires_in;

		$this->updateFac($kneuApi->getFaculties());
		$this->updateDepartaments($kneuApi->getDepartments());
		$this->updateTeachers($kneuApi->getTeachers());
	}

	protected function updateFac($oFaculties) {
		$db = new Database();
		$db->query('SET NAMES utf8');

		$existedFaculties = array();
		$facQuery = $db->query('SELECT name from faculty');
		while($obj = $facQuery->fetch_object()){
			$existedFaculties[] = $obj->name;
		}
		$facQuery->close();


		if ($oFaculties) {
			foreach ($oFaculties as $faculty) {
				if (!in_array($faculty->name, $existedFaculties)) {
					$db->query('
						INSERT INTO faculty (id, name, pass)
						VALUES ("'.$faculty->id.'", "'.$faculty->name.'", "'.$this->randomPassword().'")
					');
				}
			}
		}

		$db->close_database();
	}

	protected function updateDepartaments($oDepartaments) {
		$db = new Database();
		$db->query('SET NAMES utf8');

		$existedDepartaments = array();
		$depQuery = $db->query('SELECT name, faculty_id from kafedra');
		while($obj = $depQuery->fetch_object()){
			$existedDepartaments[] = $obj->name;
		}
		$depQuery->close();

		if ($oDepartaments) {
			foreach ($oDepartaments as $departament) {
				if (!in_array($departament->name, $existedDepartaments)) {
					$db->query('
						INSERT INTO kafedra (id, name, faculty_id)
						VALUES ("'.$departament->id.'", "'.$departament->name.'", "'.$departament->faculty_id.'")
					');
				}
			}
		}

		$db->close_database();
	}

	protected function updateTeachers($oTeachers) {
		$db = new Database();
		$db->query('SET NAMES utf8');

		$depInfo = array();
		$query = $db->query('select id, faculty_id, name from kafedra');
		while($obj = $query->fetch_object()){
			$depInfo[$obj->id]['id'] = $obj->id;
			$depInfo[$obj->id]['faculty_id'] = $obj->faculty_id;
			$depInfo[$obj->id]['name'] = $obj->name;
		}
		$query->close();

		$person = array();
		$query = $db->query('select id, fname, lname from person');
		while($obj = $query->fetch_object()){
			$person[$obj->id]['id'] = $obj->id;
			$person[$obj->id]['fname'] = $obj->fname;
			$person[$obj->id]['lname'] = $obj->lname;
		}
		$query->close();

		if ($oTeachers) {
			foreach ($oTeachers as $teacher) {
				if ($teacher->id != $person[$teacher->id]['id'] && $teacher->first_name != $person[$teacher->id]['fname'] && $teacher->last_name != $person[$teacher->id]['lname']) {
					$depId = $teacher->department_id;
					$facId = $depInfo[$depId]['faculty_id'];
					$posadaId = isset($teacher->posada_id) ? $teacher->posada_id : 4;

					$db->query('
					insert into person (id, fname, lname, faculty_id, kafedra_id, posada_id)
					VALUES (
						"'.$teacher->id.'",
						"'.$teacher->first_name.'",
						"'.$teacher->last_name.'",
						"'.$facId.'",
						"'.$depId.'",
						"'.$posadaId.'"
					)
				');
				}
			}
		}

		$db->close_database();
	}

	private function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}

		return implode($pass);
	}
}