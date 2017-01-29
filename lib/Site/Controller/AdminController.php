<?php

namespace Site\Controller;

use Site\Database;

class AdminController extends SiteController
{
    public function execute()
    {
		if (isset($_SESSION['user_access'])) {
			if (!preg_match("/adm-(.*)-/", $_SESSION['user_access']['admin'], $matches)) {
				header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
				header("Location: /main");
			}
		}

		if ($_POST) {
			return $this->showTeachers();
		} else {
			return parent::template('page/admin.php');
		}
    }

	private function showTeachers()
	{
		$all = isset($_REQUEST['all']) ? true : false;
		$kafedra = isset($_REQUEST['kafedra']) ?  $_REQUEST['kafedra'] : false;
		$byName = isset($_REQUEST['fname']) ?  strtolower($_REQUEST['fname']) : false;
		$bySurname = isset($_REQUEST['lname']) ?  strtolower($_REQUEST['lname']) : false;
		$faculty_id = '';
		if (preg_match("/adm-(.*)-/", $_SESSION['user_access']['admin'], $matches)) {
			$faculty_id = $matches[1];
		}
		$remove = isset($_REQUEST['remove_doc']) ? $_REQUEST['remove_doc'] : array();

		if ($all) {
			$query = "select person.id, person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name
						from documents,person,kafedra
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and person.faculty_id
							and documents.faculty_id = " . "'$faculty_id'";

			return $this->result($query);
		}

		if ($kafedra) {
			$query = "select person.id, person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name
						from documents,person,kafedra
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and person.faculty_id
							and documents.kafedra_id = " . $kafedra;

			return $this->result($query);
		}

		if ($byName && $bySurname) {
			$query = "select person.id,person.fname, person.lname, person.kafedra_id, documents.doc_key, documents.cost, kafedra.name
						from documents,person,kafedra
						where documents.person_id = person.id
							and kafedra.id = person.kafedra_id
							and person.faculty_id
							and lower(person.fname) = " . "'$byName'" . "
							and lower(person.lname) = " . "'$bySurname'";

			return $this->result($query);
		}

		if ($remove) {
			$db = new Database();

			foreach ($remove as $person_id => $dKeys) {
				foreach ($dKeys as $key) {
					$query = "DELETE FROM documents WHERE doc_key=" . "'$key'" . " AND person_id =" . "'$person_id'";
					$result = $db->query($query);
					if ($result) {
						$db->close_database();
						$model['success_delete'] = true;
						return $this->template('page/admin.php', $model);
					}
				}
			}
		}

		return false;
	}

	public function result ($query)
	{
		$db = new Database();
		$db->query('SET NAMES UTF8');

		$result = $db->query($query);
		$model = array();

		while ($obj = mysqli_fetch_assoc($result)) {
			$model['result'][] = $obj;
		}

		if (!empty($model)) {
			foreach ($model['result'] as $block) {
				$model['documents'][$block['doc_key']] = $this->getDocumentById($block['doc_key']);
			}
			$model['all_documents'] = file_exists(ROOT_PATH . '/etc/documents.php') ? require ROOT_PATH . '/etc/documents.php' : array();
			$result->close();
			$db->close_database();
			return $this->template('page/admin.php', $model);
		} else {
			$model['error_empty'] = true;
			return $this->template('page/admin.php', $model);
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