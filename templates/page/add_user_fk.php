<?php
use Site\Database;

$db = new Database();
$db->query('SET NAMES UTF8');
?>
<div class="brand__content" style="margin: 0px auto;">

	<div id="" class="">
		<?php
		if (isset($model['status'])) {
			echo '
				<div class="drag drag--md drag--login">
					<div class="drag__panel">
						<div class="drag__caption">
			';
				if ($model['status'] == 0) {
					echo $model['error'];
					echo '
					<div class="drag__btn-group">
						<a href="/add_user_fk" class="drag__btn register" style="background-color: rgb(31, 74, 117);text-align: center; text-decoration: none;">Back</a>
					</div>
					';
				} else {
					echo '
						'.$model['login'].' '.$model['email'].', you have been registered successfully!
					';
					echo '
					<div class="drag__btn-group">
						<a href="/" class="drag__btn register" style="background-color: rgb(31, 74, 117);text-align: center; text-decoration: none;">Back</a>
					</div>
					';
				}
			echo '
					</div><br/>
				</div>
			</div>
			';
		} else {
		?>
		<h5>Админістратор кафедри не матиме доступу до кафедр не свого факультету, так як пароль доступу до данних факультету надається адміністратором програми.</h5>
		<h6>При реєстрації користувача, обирайте свій факультет та кафедру</h6><br/>
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