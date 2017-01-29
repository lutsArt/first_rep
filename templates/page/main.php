<div class="brand__flex">
	<?php include ROOT_PATH . '/templates/partials/leftbar.php' ?>

	<?php if (!empty($model['static_main_page'])) { ?>
		<div class="brand__content">
			<p class="btext">
				Технологія здійснення моніторингової оцінки результатів діяльності кафедр і
				науково-педагогічних працівників визначається Положенням «Про моніторинг
				результатів інноваційної діяльності кафедр та науково-педагогічних працівників»,
				затверджене наказом ректора від 15 грудня 2016 р. № 910 (далі — Положення).
			</p>
			<p class="btext">
				Моніторингова оцінка — кількісний (баловий) показник результатів роботи
				кафедри та науково-педагогічного працівника за визначеними напрямами діяльності, що
				мають інноваційну складову. Уведення моніторингової оцінки інноваційної діяльності
				основних учасників освітнього процесу є невід’ємним елементом запровадження
				системи моніторингу якості вищої освіти (п.1.4. Положення). Моніторингова оцінка
				здійснюється на основі системи показників Індивідуального моніторинг-листа
				інноваційної діяльності науково-педагогічного працівника (дод. 1 до Положення).
			</p>
			<p class="btext">
				Моніторингова оцінка результатів інноваційної діяльності науково-
				педагогічного працівника визначається на основі інформації, наданої самим викладачем
				за кожним показником моніторингу в режимі он-лайн через Інформаційно-аналітичну
				систему обліку та оцінки професійної діяльності викладачів KNEU-MONITORING-LIST,
				вхід до якої здійснюється через особистий кабінет викладача на сайті <a href="https://kneu.edu.ua/">КНЕУ</a> (п.2.3.
				Положення).
			</p>
			<p class="btext">
				Інформаційно-аналітична система KNEU-MONITORING-LIST розроблена на
				факультеті інформаціних систем і технологій.
			</p>
		</div>
	<?php } ?>

	<div class="brand__content">
		<?php if (!empty($model['selected_documents'])) { ?>
		<div class="drag__flex">
			<div class="drag drag--full drag--notes">
				<div class="drag__panel">
					<div class="drag__caption">Обрані види інноваційних робіт</div>
				</div>
				<div class="drag__flex">
					<div class="notes__partial" style="width: 100%;">
						<div id="notes__list">
							<form action="" method="post" id="dosc_selected_form">
								<?php
									$summCost = 0;
									foreach ($model['selected_documents'] as $key => $block) {
										$summCost = $summCost + $block['cost'];
										$parentDoc = array_map(function ($document) use ($block) {
											foreach ($document as $doc) {
												if ($doc['key'] == substr($block['key'], 0, 3)) {
													return $doc['name'];
												}
											}
										}, $model['all_documents']);
										$parentDoc = !empty($parentDoc) ? array_filter($parentDoc) : array();
										$parentDoc = array_shift($parentDoc);
										echo '
											<div class="notes__entry" style="height: auto; line-height: 0;">
												<div class="notes__icon" style="margin: 25px auto;">' . ($key + 1) . '</div>
												<div class="notes__data" style="width: 80%;">
													<p style="line-height: 19px; padding-top: 10px;">' . $parentDoc .' '.  $block['name'] . '</p>
												</div>
												<div class="docs__tc check" style="background: rgb(242, 242, 242) none repeat scroll 0% 0%; width: 10%; text-align: center; padding-top: 20px; cursor:pointer;">
													<div class="input-group input-group--sex">
														<div class="input-group">
															<div class="search-option search-option-radio-row">
																<div class="checker">
																	<input style="display: none;" name="remove_doc[]" type="checkbox" value="'.$block['key'].'">
																	<label for="remove_doc" style="font-size:14px;">Видалити</label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										';
									}
								echo '
									<div style="margin: 17px;font-weight: 600;">
										Загальна кількість балів - '. $summCost. '
									</div>
								';
								?>
							</form>

						</div>
					</div>
					<div class="drag__btn-group">
						<a href="javascript:void(0);" onclick="$('#dosc_selected_form').submit();" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="width: 100%;text-align: center">Видалити обрані документи</a>
					</div>
					<div class="drag__btn-group">
						<a href="javascript:void(0);" onclick="sendToAminKaf();" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="width: 100%;text-align: center;background-color: #129134">Надіслати адміністратору кафедри</a>
					</div>
				</div>
			</div>
		</div>

		<?php } elseif ($_REQUEST['b'] == 9) {
			echo '
			<div class="drag__flex">
				<div class="drag drag--full drag--notes">
					<div class="drag__panel">
						<div class="drag__caption">Не обрано жодного виду інноваційних робіт</div>
					</div>
				</div>
			</div>
			';
		} ?>

		<?php if (!empty($model['documents'])) { ?>

		<div class="drag drag--md drag--docs">
			<div class="drag__panel">
				<div class="drag__caption">
					<?php
						echo $model['documents_block_name'][$model['documents_block']];
					?>
				</div>
			</div>
			<div class="drag__flex">
				<div class="docs__body">
					<form action="" method="post" id="dosc_form">
						<div class="drag__btn-group">
							<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="width: 100%;">Зберегти дані</button>
						</div>
						<?php
							foreach ($model['documents'] as $blockKey => $docBlock) {
								if ($blockKey != 0) {
									echo '<div class="docs__row" data-id="'.($blockKey + 1).'" style="height: 22px; background-color: #3b5342;"></div>';
								}
								foreach ($docBlock as $key => $block) {
									$name = $block['name'];
									if (in_array($_SESSION['user_access']['access'], $block['asc'])) {
										echo '
										<div class="docs__row" data-id="'.($blockKey + 1).'">
											<div class="docs__tc">';
											if (isset($block['note']) && $block['note']) {
												echo '<img src="/images/doc.png" width="23px" height="23px" />';
											}
											if ($key > 100) {
												echo '<b style="font-style: italic;">'.$name.'</b>';
											} else {
												echo '<b style="font-weight: 500;">'.$name.'</b>';
											}
											if (isset($block['multiplier'])) {
												echo '
												<div>
													<label for="multiplier">Кількість робіт</label>
													<input name="multiplier['.$block['key'].']" type="number" min="0" max="25" style="border: 1px dotted; border-color: #3b5342;" />
												</div>
												';
											}
											if (isset($block['note']) && $block['note']) {
												echo '
													<div class="note" style="display: none; color: red;">'.$block['note'].'</div>
													';
											}
										echo '
										</div>
										';
										if ($block['cost'] > 0) {
											echo '
											<div class="docs__tc check" style="cursor: pointer;">
												<div class="input-group input-group--sex">
													<div class="input-group">
														<div class="search-option search-option-radio-row">
															<div class="checker">
																<input style="display: none;" name="doc[]" type="checkbox" value="'.$block['key'].'">
																<label for="doc">Обрати (Вагова оцінка - '.$block['cost'].')</label>
															</div>
														</div>
													</div>
												</div>
											</div>
											';
										}
										echo '
										</div>
										';
									}
								}
							}

						?>
						<div class="drag__btn-group">
							<button type="submit" class="drag__btn drag__btn--clip" data-clipboard-target="#drag__input--referal" style="width: 100%;">Зберегти дані</button>
						</div>
					</form>
				</div>

			</div>
		</div>

		<?php } ?>

		<a href="#" class="your-class">
			<img src="/images/up.png" alt="" width="20px" height="20px" style="margin: -3px"/> Вгору
		</a>
	</div>
</div>
