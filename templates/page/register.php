<?php
use Site\Database;

$db = new Database();
$db->query('SET NAMES UTF8');
?>
<div class="brand__content" style="margin: 0px auto;">
	<div id="" class="notify-node"></div>
	<div id="" class="drag__flex">
		<?php
		if (isset($model['status'])) {
			include ROOT_PATH . '/templates/page/register_status.php';
		} else {
		?>
		<div class="drag drag--md drag--login">
			<div class="drag__panel">
				<div class="drag__caption">Реєстрація</div>
				<br/>
			</div>
			<div class="drag__flex">
				<form action="" method="post" id="register_form" class="drag__form--login">
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Login:</label>
						<input type="text" name="login" class="drag__input--login" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Ім'я:</label>
						<input type="text" name="name" class="drag__input--login" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Прізвище:</label>
						<input type="text" name="lname" class="drag__input--login" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Пароль:</label>
						<input type="password" name="pass" id="" class="drag__pass" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">E-mail:</label>
						<input type="email" name="email" id="" class="drag__pass" required>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Факультет:</label>
						<select name="faculty" id="faculty" class="drag__pass" style="width:192px;max-width: 192px;" required>
							<option selected disabled></option>
							<?php
								$query = $db->query('select * from faculty');
								while($obj = $query->fetch_object()){
									echo '
										<option value="'.$obj->id.'">'.$obj->name.'</option>
									';
								}
								$query->close();
							?>
						</select>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Кафедра:</label>
						<select name="kafedra" id="kafedra" class="drag__pass" style="width:192px;max-width: 192px;" required>
							<option selected disabled></option>
							<?php
							$query = $db->query('select * from kafedra');
							while($obj = $query->fetch_object()){
								echo '
										<option value="'.$obj->id.'" data-faculty="'.$obj->faculty_id.'" hidden>'.$obj->name.'</option>
									';
							}
							$query->close();
							?>
						</select>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Посада:</label>
						<select name="posada" id="posada" class="drag__pass" style="width:192px;max-width: 192px;" required>
							<option selected disabled></option>
							<?php
							$query = $db->query('select * from posada');
							while($obj = $query->fetch_object()){
								echo '
										<option value="'.$obj->id.'">'.$obj->name.'</option>
									';
							}
							$query->close();
							?>
						</select>
					</div>
					<div class="drag__input-group--separate">
						<label for="drag__input--login" class="drag__label--login">Додаткова інформація:</label>
						<textarea name="additional_info" class="drag__input--login" maxlength="500" style="border: 1px solid #ccc;width: 300px;height: 100px;"></textarea>
					</div>
					<div class="drag__btn-group">
						<button type="submit" class="drag__btn" style="background-color: rgb(31, 117, 34);">Реєстрація</button>
						<a href="/" class="drag__btn register" style="background-color: rgb(31, 74, 117);text-align: center; text-decoration: none;">Назад</a>
					</div>
				</form>
			</div>
		</div>
		<?php
		}
		?>
	</div>
</div>