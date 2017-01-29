<?php include ROOT_PATH . '/templates/partials/leftbar.php';

use Site\Database;

$db = new Database();
$db->query('SET NAMES UTF8');

$faculty_id = '';
if (preg_match("/adm-(.*)-/", $_SESSION['user_access']['admin'], $matches)) {
	$faculty_id = $matches[1];
}

?>

<div class="brand__content admin-panel" style="margin: 0px auto;">

	<?php if (isset($model['result'])) { ?>
	<div class="drag__flex">
		<div class="drag drag--full drag--notes">
			<div class="drag__panel">
				<div class="drag__caption">Результат запиту. Натісніть на ім'я або на назву кафедри для перегляду по імені/кафедрі.</div>
			</div>
			<div class="drag__flex">
				<div class="notes__partial" style="width: 100%;">
					<div id="notes__list">
						<div class="notes__entry" style="height: auto;">
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>ПІБ</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Кафедра</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Назва документу</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Вартість документу</p>
							</div>
							<div class="notes__data" style="width: 30%; text-align: center;">
								<div class="drag__btn-group" style="padding-right: 13px;">
									<a class="drag__btn drag__btn--clip"
									   data-clipboard-target="#drag__input--referal"
									   style="text-align: center; background-color: rgb(176, 67, 67);"
										onclick="if (confirm('Видалити відмічені роботи?') && $('#dosc_selected_form input[type=checkbox]:checked').length) {document.getElementById('dosc_selected_form').submit();}"
									>Видалити</a>
								</div>
							</div>
						</div>

						<form action="" method="post" id="dosc_selected_form">
							<?php
							foreach ($model['result'] as $key => $block) {
								echo '
									<div class="notes__entry" style="height: auto;">
										<div class="notes__data" style="width: 80%;">
											<p style="line-height: 19px; padding-top: 10px;">
												<a href="javascript:void(0);" class="name-field" data-fname="'.$block['fname'].'" data-lname="'.$block['lname'].'">
													' . $block['fname'] .' '. $block['lname'] . '
												</a>
											</p>
										</div>
										<div class="notes__data" style="width: 80%;">
											<a href="javascript:void(0);" style="color: rgb(215, 103, 32);" class="kafedra-field" data-kafedra="'.$block['kafedra_id'].'">
												<p style="line-height: 19px; padding-top: 10px;">' . $block['name'] . '</p>
											</a>
										</div>
								';

								foreach ($model['documents'] as $doc) {
									$parentDoc = array_map(function ($documents) use ($doc) {
										foreach ($documents as $document) {
											if ($document['key'] == substr($doc['key'], 0, 3)) {
												return $document['name'];
											}
										}
									}, $model['all_documents']);
									$parentDoc = !empty($parentDoc) ? array_filter($parentDoc) : array();
									$parentDoc = array_shift($parentDoc);

									if ($doc['key'] == $block['doc_key']) {
										echo '
											<div class="notes__data" style="width: 80%;">
												<p style="line-height: 19px; padding-top: 10px;">' . $parentDoc . $doc['name'] . '</p>
											</div>
											<div class="notes__data" style="width: 80%; text-align: center;">
												<p style="line-height: 19px; padding-top: 10px;">' . $doc['cost'] . '</p>
											</div>
										';
									}
								}

								echo '
										<div class="docs__tc check" style="width: 30%; text-align: center; cursor: pointer;">
											<div class="input-group">
												<div class="checker">
													<input name="remove_doc['.$block['id'].'][]" type="checkbox" value="'.$block['doc_key'].'">
													<label for="remove_doc" style="font-size:11px;">Видалити</label>
												</div>
											</div>
										</div>
									</div>
								';
							}
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php } ?>

	<?php
	if (isset($model['error_empty'])) {
		echo '
		<div class="drag__flex" style="background-color: rgb(224, 220, 156);">
			<div class="drag drag--full drag--notes">
				<div class="drag__panel">
					<div class="drag__caption">За вашим запитом нічого не знайдено!</div>
				</div>
			</div>
		</div>
		';
	}
	if (isset($model['success_delete'])) {
		echo '
		<div class="drag__flex" style="background-color: rgb(189, 224, 156)">
			<div class="drag drag--full drag--notes">
				<div class="drag__panel">
					<div class="drag__caption">Записи видалено!</div>
				</div>
			</div>
		</div>
		';
	}
	?>

	<div class="drag drag--md drag--request drag--community" style="height: 400px;">
		<div class="drag__panel">
			<div class="drag__caption">Пошук користувачів</div>
		</div>
		<div class="drag__flex">

			<form class="drag__form--request" action="" method="post">
				<div class="input-group">
					<label for="drag__select" class="drag__label--select">Показати всіх по факультету</label>
					<input type="text" name="all" value="1" style="display: none">
					<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="float: right;">Пошук</button>
				</div>
			</form>

			<form class="drag__form--request" id="kafedra-form" action="" method="post">
				<div class="input-group">
					<label for="kafedra" class="drag__label--select">Показати всіх по кафедрі</label>
					<div class="drag__select">
					<select name="kafedra" id="kafedra" class="drag__pass" style="width:192px;max-width: 192px;" required>
						<option selected disabled>Виберіть кафедру</option>
						<?php
						$query = $db->query('select * from kafedra where faculty_id = ' . $faculty_id);
						while($obj = $query->fetch_object()){
							echo '
										<option value="'.$obj->id.'" data-faculty="'.$obj->faculty_id.'">'.$obj->name.'</option>
									';
						}
						$query->close();
						?>
					</select>
					</div>
					<div class="drag__btn-group">
						<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal">Пошук</button>
					</div>
				</div>
			</form>

			<form class="drag__form--request" id="pib-form" method="post" action="" accept-charset="UTF-8">
				<div class="drag__input-group--separate">
					<label for="drag__input--referal" class="drag__label--referal">Показати по користувачу. Введіть, будь-ласка, ім'я та прізвище</label>
					<input type="text" name="fname" class="drag__input--referal" required="required" id="drag__input--referal" placeholder="Ім'я" value="" data-clipboard-target="#drag__input--referal">
					<input type="text" name="lname" class="drag__input--referal" required="required" id="drag__input--referal" placeholder="Прізвище" value="" data-clipboard-target="#drag__input--referal">
				</div>
				<div class="drag__btn-group" style="padding-right: 13px;">
					<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal">Пошук</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php $db->close_database(); ?>