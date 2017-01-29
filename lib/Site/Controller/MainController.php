<?php

namespace Site\Controller;

use Site\Database;

class MainController extends SiteController
{
	public static $blocks = array(
		'Дослідження і наукова робота',
		'Навчально-методична робота',
		'Інші види інноваційної діяльності'
	);

    public function execute() {
		$block = isset($_REQUEST['b']) ? $_REQUEST['b'] : '';
		if ($block == '') {
			$model['static_main_page'] = 1;
			parent::template('page/main.php', $model);
		}
	}

	public static function displayDocuments() {
		$docFile = ROOT_PATH . '/etc/documents.php';
		$documents = file_exists($docFile) ? require $docFile : array();

		$db = new Database();
		$db->query('SET NAMES utf8');
		$login = $_SESSION['user_access']['email'];
		$query = $db->query("select doc_key FROM documents, person WHERE person.id = documents.person_id and person.email = '".$login."'");
		$docKeys = array();
		while ($obj = $query->fetch_object()) {
			$docKeys[] = $obj->doc_key;
		}
		$selectedDocs = array();
		foreach ($documents as $key => $docBlock) {
			foreach ($docBlock as $blockKey => $doc) {
				if (in_array($doc['key'],$docKeys)) {
					$selectedDocs[] = $doc;
					unset($documents[$key][$blockKey]);
				}
			}
		}

		$block = $_REQUEST['b'];
		if ($block == 9) {
			$selectedBlock = array();
			$model['selected_documents'] = $selectedDocs;
		} else {
			$selectedBlock[] = $documents[$block - 1];
		}
		$model['documents'] = $selectedBlock;
		$model['all_documents'] = $documents;
		$model['documents_block'] = $block - 1;
		$model['documents_block_name'] = self::$blocks;

		if ($_POST) {
			self::addDocs($documents, $block);
		} else {
			parent::template('page/main.php', $model);
		}
	}

	private static function addDocs($documents, $block)
	{
		$login 		= $_SESSION['user_access']['email'];
		$docs 		= isset($_REQUEST['doc']) ? $_REQUEST['doc'] : array();
		$multiplier = isset($_REQUEST['multiplier']) ? $_REQUEST['multiplier'] : array();
		$removeDocs = isset($_REQUEST['remove_doc']) ? $_REQUEST['remove_doc'] : array();
		$db = new Database();
		$db->query('SET NAMES utf8');

		$success = false;
		if ($docs) {
			foreach ($documents as $docBlocks) {
				foreach ($docBlocks as $doc) {
					if (in_array($doc['key'], $docs)) {
						$docName = $doc['key'];
						$docCost = $doc['cost'];
						if (isset($multiplier[$docName]) && $multiplier[$docName] != '') {
							$docCost = $doc['cost'] * $multiplier[$docName];
						}
						$query = "INSERT INTO documents ".
							"(doc_key, person_id, faculty_id, kafedra_id, cost)".
							"SELECT"."'$docName'"."AS doc_key,id,faculty_id,kafedra_id, "."'$docCost'"."AS cost from person WHERE email = "."'$login'";

						$result = $db->query($query);

						if (!$result) {
							$db->close_database();
						} else {
							$success = true;
						}
					}
				}
			}
		} elseif ($removeDocs) {
			foreach ($removeDocs as $dKey) {
				$query = "DELETE FROM documents WHERE doc_key="."'$dKey'"." AND person_id IN
                            (
                                select id from person WHERE email = "."'$login'"."
                            )";

				$result = $db->query($query);

				if (!$result) {
					$db->close_database();
				} else {
					$success = true;
				}
			}
		}

		if ($success) {
			$db->close_database();
			header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
			header("Location: /main?b=" . $block);
		}

		header($_SERVER["SERVER_PROTOCOL"] . ' 302 Moved');
		header("Location: /main?b=" . $block);
	}
}
