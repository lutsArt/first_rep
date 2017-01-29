<?php include ROOT_PATH . '/templates/partials/leftbar.php' ?>

<div class="brand__content">
	<?php
	if (isset($model['add_doc']) && $model['add_doc'] == true) {
		echo '
			<div class="alert alert-success" role="alert" >Документ додано</div>
		';
	}
	if (isset($model['add_error']) && $model['add_error'] == true) {
		echo '
			<div class="alert alert-danger" role="alert" >Документ з таким ім\'ям вже існує</div>
		';
	}
	?>
	<div id="notify-node" class="notify-node"></div>
	<div id="drag__flex" class="drag__flex">
		<div class="drag drag--md drag--login">
			<div class="drag__panel">
				<div class="drag__caption">Додати документ до переліку документів. <br/> Додані документи будуть доступні для всіх користувачів системи</div>
			</div>
			<div class="drag__flex">
				<form action="" method="post" class="drag__form--login">
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Вартість документа:</label>
						<input type="number" class="drag__input--login" name="doc_cost" required max="100" />
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Назва документа:</label>
						<textarea class="drag__input--login" name="doc_name" required maxlength="500" style="border: 1px solid #ccc;width: 300px;height: 100px;"></textarea>
					</div>
					<div class="drag__btn-group">
						<button type="submit" class="drag__btn">Додати</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
