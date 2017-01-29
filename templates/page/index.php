<div class="brand__content" style="margin: 0px auto;">
	<div id="" class="notify-node"></div>
	<div id="" class="drag__flex">
		<div class="drag drag--md drag--login">
			<div class="drag__panel">
				<div class="drag__caption">Вхід</div>
				<br/>
				<?php
				if (isset($model['status']) and $model['status'] == 0) {
					echo '
						<div class="drag__caption">'.$model['error'].'</div>
					';
				}
				?>
			</div>
			<div class="drag__flex">
				<form action="" method="post" id="login_form" class="drag__form--login">
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Логін:</label>
						<input type="text" name="name" class="drag__input--login" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Пароль:</label>
						<input type="password" name="pass" id="" class="drag__pass" required>
					</div>
					<div class="drag__input-group--separate">
						<a href="#" class="pass-restore">Забув пароль</a>
					</div>
					<div class="drag__btn-group">
						<button type="submit" class="drag__btn">Увійти</button>
						<a href="register" class="drag__btn register" style="background-color: rgba(91, 135, 64, 0.8);text-align: center; text-decoration: none;">Реєстрація</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>