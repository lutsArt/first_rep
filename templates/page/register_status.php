<div class="drag drag--md drag--login">
	<div class="drag__panel">
		<div class="drag__caption">
			<?php
				if ($model['status'] == 0) {
					echo $model['error'];
					echo '
					<div class="drag__btn-group">
						<a href="/register" class="drag__btn register" style="background-color: rgb(31, 74, 117);text-align: center; text-decoration: none;">Back</a>
					</div>
					';
				} else {
					echo '
						'.$model['name'].' '.$model['lname'].', you have been registered successfully!
					';
					echo '
					<div class="drag__btn-group">
						<a href="/" class="drag__btn register" style="background-color: rgb(31, 74, 117);text-align: center; text-decoration: none;">Back</a>
					</div>
					';
				}
			?>
		</div>
		<br/>
	</div>
</div>