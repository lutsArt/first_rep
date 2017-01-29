<div class="sidenav"
		style="
			border-left: 1px solid #c3ddca;
			border-right: 1px solid #c3ddca;
			background: #fbf6eb;
		"
	>
	<ul>
		<li>
			<a href="/">
				<i class="sidenav__icon sidenav__icon--panel"></i>
				<span class="sidenav__caption">Головна</span>
			</a>
		</li>
		<?php if (!isset($_SESSION['user_super']) && !isset($_SESSION['user_admin_kaf'])) { ?>
		<li>
			<i class="sidenav__icon sidenav__icon--struct"></i>
			<a href="#">
				<span class="sidenav__caption">Види інноваційних робіт</span>
			</a><br/>
				<p style="padding-left: 8px;">
					<a href="/main?b=1">
						<span class="sidenav__caption">1. Дослідження і наукова робота</span><br/>
					</a>
					<a href="/main?b=2">
						<span class="sidenav__caption">2. Навчально-методична робота</span><br/>
					</a>
					<a href="/main?b=3">
						<span class="sidenav__caption">3. Інші види інноваційної <br/> діяльності</span><br/>
					</a>
				</p>
		</li>
		<li>
			<a href="/main?b=9">
				<i class="sidenav__icon sidenav__icon--document"></i>
				<span class="sidenav__caption">Індивідуальний моніторинг лист</span>
			</a>
		</li>
		<?php } ?>
		<li>
			<a href="/faq">
				<i class="sidenav__icon sidenav__icon--faq"></i>
				<span class="sidenav__caption">Задати питання</span>
			</a>
		</li>
		<li>
			<i class="sidenav__icon sidenav__icon--notes"></i>
			<a href="#">
				<span class="sidenav__caption">Нормативна база</span>
			</a><br/>
			<p style="padding-left: 8px;">
				<a href="/polojennya.pdf">
					<span class="sidenav__caption">1. Положення</span><br/>
				</a>
				<a href="/dodatok1.pdf">
					<span class="sidenav__caption">2. Додаток 1</span><br/>
				</a>
				<a href="/dodatok2.pdf">
					<span class="sidenav__caption">3. Додаток 2</span><br/>
				</a>
				<a href="/instrukcia.pdf">
					<span class="sidenav__caption">4. Інструкція користувача</span><br/>
				</a>
			</p>
		</li>
		<li>
			<a href="/logout">
				<i class="sidenav__icon sidenav__icon--exit"></i>
				<span class="sidenav__caption">Вихід</span>
			</a>
		</li>
	</ul>
</div>