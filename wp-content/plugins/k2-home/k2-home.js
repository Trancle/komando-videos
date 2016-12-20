jQuery(document).ready(function($){

	var custom_uploader;
	var active_button;

	$('.upload_image_button').on('click', function(e) {

		active_button = $(this);
		e.preventDefault();

		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$(active_button).parent().find('.featured-link-image').val(attachment.url);
		});

		//Open the uploader dialog
		custom_uploader.open();

	});

	var startDateTextBox0 = $('#featured-link-start-0');
	var endDateTextBox0 = $('#featured-link-end-0');

	startDateTextBox0.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (endDateTextBox0.val() != '') {
				var testStartDate = startDateTextBox0.datetimepicker('getDate');
				var testEndDate = endDateTextBox0.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox0.datetimepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox0.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox0.datetimepicker('option', 'minDate', startDateTextBox0.datetimepicker('getDate') );
		}
	});

	endDateTextBox0.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (startDateTextBox0.val() != '') {
				var testStartDate = startDateTextBox0.datetimepicker('getDate');
				var testEndDate = endDateTextBox0.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox0.datetimepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox0.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox0.datetimepicker('option', 'maxDate', endDateTextBox0.datetimepicker('getDate') );
		}
	});

	var startDateTextBox1 = $('#featured-link-start-1');
	var endDateTextBox1 = $('#featured-link-end-1');

	startDateTextBox1.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (endDateTextBox1.val() != '') {
				var testStartDate = startDateTextBox1.datetimepicker('getDate');
				var testEndDate = endDateTextBox1.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox1.datetimepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox1.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox1.datetimepicker('option', 'minDate', startDateTextBox1.datetimepicker('getDate') );
		}
	});

	endDateTextBox1.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (startDateTextBox1.val() != '') {
				var testStartDate = startDateTextBox1.datetimepicker('getDate');
				var testEndDate = endDateTextBox1.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox1.datetimepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox1.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox1.datetimepicker('option', 'maxDate', endDateTextBox1.datetimepicker('getDate') );
		}
	});

	var startDateTextBox2 = $('#featured-link-start-2');
	var endDateTextBox2 = $('#featured-link-end-2');

	startDateTextBox2.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (endDateTextBox2.val() != '') {
				var testStartDate = startDateTextBox2.datetimepicker('getDate');
				var testEndDate = endDateTextBox2.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox2.datetimepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox2.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox2.datetimepicker('option', 'minDate', startDateTextBox2.datetimepicker('getDate') );
		}
	});

	endDateTextBox2.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (startDateTextBox2.val() != '') {
				var testStartDate = startDateTextBox2.datetimepicker('getDate');
				var testEndDate = endDateTextBox2.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox2.datetimepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox2.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox2.datetimepicker('option', 'maxDate', endDateTextBox2.datetimepicker('getDate') );
		}
	});

	var startDateTextBox3 = $('#featured-link-start-3');
	var endDateTextBox3 = $('#featured-link-end-3');

	startDateTextBox3.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (endDateTextBox3.val() != '') {
				var testStartDate = startDateTextBox3.datetimepicker('getDate');
				var testEndDate = endDateTextBox3.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox3.datetimepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox3.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox3.datetimepicker('option', 'minDate', startDateTextBox3.datetimepicker('getDate') );
		}
	});

	endDateTextBox3.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (startDateTextBox3.val() != '') {
				var testStartDate = startDateTextBox3.datetimepicker('getDate');
				var testEndDate = endDateTextBox3.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox3.datetimepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox3.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox3.datetimepicker('option', 'maxDate', endDateTextBox3.datetimepicker('getDate') );
		}
	});

	var startDateTextBox4 = $('#featured-link-start-4');
	var endDateTextBox4 = $('#featured-link-end-4');

	startDateTextBox4.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (endDateTextBox4.val() != '') {
				var testStartDate = startDateTextBox4.datetimepicker('getDate');
				var testEndDate = endDateTextBox4.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox4.datetimepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox4.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox4.datetimepicker('option', 'minDate', startDateTextBox4.datetimepicker('getDate') );
		}
	});

	endDateTextBox4.datetimepicker({
		timeFormat: 'HH:mm',
		onClose: function(dateText, inst) {
			if (startDateTextBox4.val() != '') {
				var testStartDate = startDateTextBox4.datetimepicker('getDate');
				var testEndDate = endDateTextBox4.datetimepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox4.datetimepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox4.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox4.datetimepicker('option', 'maxDate', endDateTextBox4.datetimepicker('getDate') );
		}
	});

});