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

	$('body').on('focus', '.ad-start-time', function() {

		var start_time = $(this);
		var end_time = start_time.parentsUntil('.section-ad-holder').find('.ad-end-time');

		start_time.datetimepicker({
			timeFormat: "HH:mm",
			onClose: function(dateText, inst) {
				if (end_time.val() != "") {
					var testStartDate = start_time.datetimepicker("getDate");
					var testEndDate = end_time.datetimepicker("getDate");
					if (testStartDate > testEndDate)
						end_time.datetimepicker("setDate", testStartDate);
				} else {
					end_time.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				end_time.datetimepicker("option", "minDate", start_time.datetimepicker("getDate"));
			}
		});

		end_time.datetimepicker({
			timeFormat: "HH:mm",
			onClose: function(dateText, inst) {
				if (start_time.val() != "") {
					var testStartDate = start_time.datetimepicker("getDate");
					var testEndDate = end_time.datetimepicker("getDate");
					if (testStartDate > testEndDate)
						start_time.datetimepicker("setDate", testEndDate);
				} else {
					start_time.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				start_time.datetimepicker("option", "maxDate", end_time.datetimepicker("getDate"));
			}
		});
	});

	$('body').on('focus', '.ad-end-time', function() {

		var end_time = $(this);
		var start_time = end_time.parentsUntil('.section-ad-holder').find('.ad-start-time');

		start_time.datetimepicker({
			timeFormat: "HH:mm",
			onClose: function(dateText, inst) {
				if (end_time.val() != "") {
					var testStartDate = start_time.datetimepicker("getDate");
					var testEndDate = end_time.datetimepicker("getDate");
					if (testStartDate > testEndDate)
						end_time.datetimepicker("setDate", testStartDate);
				} else {
					end_time.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				end_time.datetimepicker("option", "minDate", start_time.datetimepicker("getDate"));
			}
		});

		end_time.datetimepicker({
			timeFormat: "HH:mm",
			onClose: function(dateText, inst) {
				if (start_time.val() != "") {
					var testStartDate = start_time.datetimepicker("getDate");
					var testEndDate = end_time.datetimepicker("getDate");
					if (testStartDate > testEndDate)
						start_time.datetimepicker("setDate", testEndDate);
				} else {
					start_time.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				start_time.datetimepicker("option", "maxDate", end_time.datetimepicker("getDate"));
			}
		});
	});

});