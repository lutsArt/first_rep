function sendToAminKaf() {
	if (confirm('Після відправки даних адміністратору кафедри, подальше редагування даних буде не можливе! Надіслати дані?')) {

	}
}

$(document).ready(function(){
//	Register form
	$('#faculty').on('change', function() {
		$('#kafedra').find('option').hide();
		$('#kafedra').val('');
		$('#kafedra').find('option[data-faculty="'+$(this).val()+'"]').show();
	});

//	Sort docs
	$('.sort-docs').on('click', function () {
		var block = $(this).text();
		$('#dosc_form').find('.docs__row').hide();
		$('#dosc_form').find('.docs__row[data-id="'+block+'"]').show();
	});
	$('.show-all').on('click', function() {
		$('#dosc_form').find('.docs__row').show();
	});

//	Activate checkbox on block click
	$('#dosc_form, #dosc_selected_form').find('.check').on('click', function(e) {
		e.preventDefault();
		var checkBoxes = $($(this).find('input:checkbox'));
		checkBoxes.prop("checked", !checkBoxes.prop("checked"));
		if (checkBoxes.prop('checked')) {
			$(this).css('background', '#129134');
			$(this).css('color', 'white');
		} else {
			$(this).css('background', '');
			$(this).css('color', '');
		}
	});

// Submit forms by "a" click
	$('.name-field').on('click', function() {
		var fname = $(this).data('fname');
		var lname = $(this).data('lname');

		var form = document.getElementById('pib-form');
		$('input[name="fname"]').val(fname);
		$('input[name="lname"]').val(lname);
		form.submit();
	});

	$('.kafedra-field').on('click', function() {
		var kafedra = $(this).data('kafedra');

		var form = document.getElementById('kafedra-form');
		$('select[name="kafedra"]').val(kafedra);
		form.submit();
	});

	$('.faculty-field').on('click', function() {
		var faculty = $(this).data('faculty');

		var form = document.getElementById('faculty-form');
		$('select[name="faculty"]').val(faculty);
		form.submit();
	});

// Open faculty on admin panel
	$('.fac-name').on('click', function() {
		$(this).parent().find('.notes__entry').toggle();
	});
//Note hover event
	$('.docs__row').mouseenter(
		function () {
		$(this).find('.note').show();
	}).mouseleave(
		function () {
		$(this).find('.note').hide();
	});
});

