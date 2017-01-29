<?php

namespace Site\Controller;

use Site\Database;

class AutomatizationCenterController extends SiteController
{
	public function execute()
	{
		if (!isset($_SESSION['user']) && !isset($_SESSION['user_super'])) {
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /");
		}
		if ($_POST) {
			$this->showInfo();
		} else {
			parent::template('page/automat_center.php');
		}
	}

	public function showInfo()
	{
		$all = isset($_REQUEST['all-fac']) ? true : false;
		$faculty = isset($_REQUEST['faculty']) ?  $_REQUEST['faculty'] : false;
		$byName = isset($_REQUEST['fname']) ?  strtolower($_REQUEST['fname']) : false;
		$bySurname = isset($_REQUEST['lname']) ?  strtolower($_REQUEST['lname']) : false;

		if ($all) {
			$query = "select person.id, person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name, faculty.name as fac_name, faculty.id as fac_id
						from documents,person,kafedra,faculty
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and faculty.id = person.faculty_id
						ORDER BY documents.cost DESC
						";

			return $this->result($query);
		}

		if ($faculty) {
			$query = "select person.id, person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name, faculty.name as fac_name, faculty.id as fac_id
						from documents,person,kafedra, faculty
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and faculty.id = ".$faculty."
							and person.faculty_id = " . $faculty . " ORDER BY documents.cost DESC";

			return $this->result($query);
		}

		if ($byName && $bySurname) {
			$query = "select person.id, person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name, faculty.name as fac_name, faculty.id as fac_id
						from documents,person,kafedra, faculty
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and person.faculty_id = faculty.id
							and lower(person.fname) = " . "'$byName'" . "
							and lower(person.lname) = " . "'$bySurname'";

			return $this->result($query, true);
		}

	}

	public function result ($query, $searchByName = false)
	{
		$db = new Database();
		$db->query('SET NAMES UTF8');

		$result = $db->query($query);
		$model = array();
		while ($obj = mysqli_fetch_assoc($result)) {
			$name = $obj['fname'] . $obj['lname'];
			$model['result'][$obj['fac_name']][$name][] = $obj;
		}

		if (!empty($model)) {
			foreach ($model['result'] as $facName => $persons) {
				$rating_arr = array();

				foreach ($persons as $name => $person) {
					$person_rating_arr = array();

					foreach ($person as $key => $block) {
						$model['documents'][$block['doc_key']] = $this->getDocumentById($block['doc_key']);
						$rating_arr[] = $block['cost'];
						$person_rating_arr[] = $block['cost'];
					}
					$model['result'][$facName][$name]['rating']['all'] = array_sum($person_rating_arr);
					$model['result'][$facName][$name]['rating']['average'] = round(array_sum($person_rating_arr) / sizeof($model['result'][$facName][$name]), 2);

					$model['result'][$facName]['rating']['all'] = array_sum($rating_arr);
					$model['result'][$facName]['rating']['average'] = round(array_sum($rating_arr) / sizeof($model['result'][$facName]), 2);
				}
			}
			$model['all_documents'] = file_exists(ROOT_PATH . '/etc/documents.php') ? require ROOT_PATH . '/etc/documents.php' : array();

			if ($searchByName == true) {
				$model['person'] = true;
			}

			$result->close();
			$db->close_database();
			return $this->template('page/automat_center.php', $model);
		} else {
			$model['error_empty'] = true;
			return $this->template('page/automat_center.php', $model);
		}
	}

	public function getDocumentById($id)
	{
		$docFile = ROOT_PATH . '/etc/documents.php';
		$documents = file_exists($docFile) ? require $docFile : array();

		$docArray = array();
		foreach ($documents as $key => $block) {
			foreach ($block as $blockKey => $doc) {
				if ($doc['key'] == $id) {
					$docArray = $doc;
				}
			}
		}

		return $docArray;
	}
}