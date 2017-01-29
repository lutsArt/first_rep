<?php include ROOT_PATH . '/templates/partials/leftbar.php';

use Site\Database;

$db = new Database();
$db->query('SET NAMES UTF8');
?>

<div class="brand__content admin-panel" style="margin: 0px auto;">

	<?php if (isset($model['result'])) { ?>
	<div class="drag__flex">
		<div class="drag drag--full drag--notes">
			<div class="drag__panel">
				<div class="drag__caption">Результат запиту. Натісніть на ім'я, факультет або на назву кафедри для перегляду по імені/факультету/кафедрі.</div>
			</div>
			<div class="drag__flex">
			<?php 	if (!isset($model['person'])) { ?>

				<div class="notes__partial" style="width: 100%;">
					<div id="notes__list">
						<div class="notes__entry" style="height: auto;">
							<div class="notes__data" style="width: 50%; text-align: center;">
								<p>ПІБ</p>
							</div>
							<div class="notes__data" style="width: 50%; text-align: center;">
								<p>Факультет</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Кафедра</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Рейтинг</p>
							</div>
						</div>

						<form action="" method="post" id="dosc_selected_form">
						<?php
							foreach ($model['result'] as $fac_id => $persons) {
								$index = 0;
								echo '
 								<div class="admin-faculty">
									<div class="fac-name">'.$fac_id.'
										<span>(загальний рейтинг - ' . $model['result'][$fac_id]['rating']['all']. ' )</span>
										<span>(середній рейтинг - ' . $model['result'][$fac_id]['rating']['average']. ' )</span>

										<p>Показати всіх</p>
									</div>
								';

								foreach ($persons as $name => $person) {
									$rating = array();
									foreach ($person as $key => $value) {
										if ($key == 'rating') {
											$rating['all'] = $person['rating']['all'];
											$rating['average'] = $person['rating']['average'];
										}
									}

									$block = reset($person);
									if (gettype($block) == 'array') {
										echo '
										<div class="notes__entry" style="height: auto; display: none; padding-bottom: 3px;">';
										echo '
											<div class="notes__data" style="width: 50%;">
												<p style="line-height: 19px; padding-top: 10px;">
													<a href="javascript:void(0);" class="name-field" data-fname="' . $block['fname'] . '" data-lname="' . $block['lname'] . '">
														' . $block['fname'] . ' ' . $block['lname'] . '
													</a>
												</p>
											</div>
											<div class="notes__data" style="width: 50%;">
												<a href="javascript:void(0);" style="color: rgb(215, 103, 32);text-align: center;" class="faculty-field" data-faculty="' . $block['fac_id'] . '">
													<p style="line-height: 19px; padding-top: 10px;">' . $block['fac_name'] . '</p>
												</a>
											</div>
											<div class="notes__data" style="width: 80%;text-align: center;">
												<p style="line-height: 19px; padding-top: 10px;">' . $block['name'] . '</p>
											</div>
											<div class="notes__data" style="width: 80%;text-align: center;">
												<p style="line-height: 19px; padding-top: 10px;text-align: center;">' . $rating['all'] . ' (cередній за вид дiяльностi - '.$rating['average'].')</p>
											</div>
										</div>
										';
									}
								}
							}
						?>
						</form>
					</div>
				</div>
			</div>
		<?php
			} else {
				echo '
				<div class="notes__partial" style="width: 100%;">
					<div id="notes__list">
						<div class="notes__entry" style="height: auto;">
							<div class="notes__data" style="width: 50%; text-align: center;">
								<p>ПІБ</p>
							</div>
							<div class="notes__data" style="width: 50%; text-align: center;">
								<p>Факультет</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Кафедра</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Назва документу</p>
							</div>
							<div class="notes__data" style="width: 80%; text-align: center;">
								<p>Рейтинг</p>
							</div>
						</div>
				';
					foreach ($model['result'] as $fac_id => $persons) {
						foreach ($persons as $name => $person) {
							foreach ($person as $key => $block) {
								if ($key == 'rating') {
									$rating = $person['rating']['all'];
									$ratingAverage = $person['rating']['average'];
								}
								if ($key === 0) {
								echo '
									<div class="notes__entry" style="height: auto; background-color: rgb(250, 255, 220)">
										<div class="notes__data" style="width: 100%;">
											<p style="line-height: 50px;text-align: center; font-family: initial; font-size: 20px;">
												' . $name . '      Рейтинг - ' . $rating . '
											</p>
										</div>
									</div>
								';
								}
								if ($key !== 'rating') {
									echo '
									<div class="notes__entry" style="height: auto;">
									';
									echo '
										<div class="notes__data" style="width: 50%;">
											<p style="line-height: 19px; padding-top: 10px;">
												<a href="javascript:void(0);" class="name-field" data-fname="' . $block['fname'] . '" data-lname="' . $block['lname'] . '">
													' . $block['fname'] . ' ' . $block['lname'] . '
												</a>
											</p>
										</div>
										<div class="notes__data" style="width: 50%;">
											<a href="javascript:void(0);" style="color: rgb(215, 103, 32);" class="faculty-field" data-faculty="' . $block['fac_id'] . '">
												<p style="line-height: 19px; padding-top: 10px;">' . $block['fac_name'] . '</p>
											</a>
										</div>
										<div class="notes__data" style="width: 80%;">
											<a href="javascript:void(0);" style="color: rgb(215, 103, 32);" class="kafedra-field" data-kafedra="' . $block['kafedra_id'] . '">
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
										</div>
									';
								}
								if ($key == 'all' || $key == 'average') {
									continue;
								}
							}
						}
					}

				echo '</div>';
			}
		?>
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
	?>

	<div class="drag drag--md drag--request drag--community" style="height: 400px;">
		<div class="drag__panel">
			<div class="drag__caption">Центр автоматизації університету</div>
		</div>
		<div class="drag__flex">

			<form class="drag__form--request" action="" method="post">
				<div class="input-group">
					<label for="drag__select" class="drag__label--select">Показати рейтинг по всім факультетам</label>
					<input type="text" name="all-fac" value="1" style="display: none">
					<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="float: right;">Пошук</button>
				</div>
			</form>

			<form class="drag__form--request" id="faculty-form" action="" method="post">
				<div class="input-group">
					<label for="faculty" class="drag__label--select">Показати рейтинг по факультету</label>
					<div class="drag__select">
					<select name="faculty" id="faculty" class="drag__pass" style="width:192px;max-width: 192px;" required>
						<option selected disabled>Виберіть факультет</option>
						<?php
						$query = $db->query('select * from faculty');
						while($obj = $query->fetch_object()) {
							echo '
								<option value="'.$obj->id.'">'.$obj->name.'</option>
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

			<form class="drag__form--request" id="pib-form" method="post" action="">
				<div class="drag__input-group--separate">
					<label for="drag__input--referal" class="drag__label--referal">Показати рейтинг викладача. Введіть, будь-ласка, ім'я та прізвище</label>
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