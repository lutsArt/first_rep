<?php

namespace Site\Controller;

class AddDocController extends SiteController
{
    public function execute()
    {
		$login = $_SESSION['user_access']['email'];
		$docFile = ROOT_PATH . '/etc/documents.php';
		$documents = file_exists($docFile) ? require $docFile : array();
		$docName = isset($_REQUEST['doc_name']) ? $_REQUEST['doc_name'] : '';
		$docCost = isset($_REQUEST['doc_cost']) ? $_REQUEST['doc_cost'] : '';

		$model['bootstrap'] = true;
		$newBlock = 4;

		if ($_POST) {
			$documents[$newBlock] = !empty($documents[$newBlock]) ? $documents[$newBlock] : array();

			foreach ($documents[$newBlock] as $block) {
				if ($block['name'] == $docName) {
					$model['add_error'] = true;
					return parent::template('page/add_doc.php', $model);
				}
			}

			$newBlockSize = sizeof($documents[$newBlock]);
			$documents[$newBlock][$newBlockSize] = array();
			$documents[$newBlock][$newBlockSize] = array(
				'name' => $docName,
				'key' => '500' . $newBlockSize,
				'asc' => array(1,2,3,4),
				'user' => $login,
				'cost' => (int)($docCost)
			);

			file_put_contents($docFile, '<?php return ' . var_export($documents, true) . ';' . PHP_EOL, LOCK_EX);
			$model['add_doc'] = true;

			parent::template('page/add_doc.php', $model);
		} else {
			parent::template('page/add_doc.php');
		}
	}
}