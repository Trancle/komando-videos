/*
 * PDDP JS
 */
var mm, dd, yy, hh, mn;

jQuery(document).ready(function(){
	jQuery("#timestampdiv p").append('<a href="" class="pddp button">Hide Datepicker</a>');
	jQuery("#timestampdiv p").after('<div id="pddp_datepicker"></div>');

	jQuery("#misc-publishing-actions a.edit-timestamp").click( function(){
		mm = jQuery(this).parent().parent().find("#mm option").filter(":selected").val();
		dd = jQuery(this).parent().parent().find("#jj").val();
		yy = jQuery(this).parent().parent().find("#aa").val();
		hh = jQuery(this).parent().parent().find("#hh").val();
		mn = jQuery(this).parent().parent().find("#mn").val();

		jQuery( '#pddp_datepicker' ).datetimepicker({
				controlType: 'select',
				dateFormat: "mm/dd/yy",
				yearRange: "1900:2100",
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				defaultDate: mm+"/"+dd+"/"+yy,
				hour: parseInt(hh),
				minute: parseInt(mn),
				onSelect: function( datetimeText, inst ) {
					add_reset_button();
					datetimeText = datetimeText.split(" ")
					dateText = datetimeText[0].split("/")
					var dd1 = jQuery(this).parent().parent().find("#jj").val();
					if( dd1 != dateText[1] )
						jQuery("#timestampdiv").find("#jj").val(dateText[1]);
					timeText = datetimeText[1].split(":")
					var hh1 = jQuery(this).parent().parent().find("#hh").val();
					if( hh1 != timeText[0] )
						jQuery("#timestampdiv").find("#hh").val(timeText[0]);
					var mn1 = jQuery(this).parent().parent().find("#mn").val();
					if( mn1 != timeText[1] )
						jQuery("#timestampdiv").find("#mn").val(timeText[1]);
				},
				onChangeMonthYear: function( year, month, inst ) {
					add_reset_button();
					var mm1 = jQuery("#timestampdiv").find("#mm option").filter(":selected").val();
					var yy1 = jQuery("#timestampdiv").find("#aa").val();
					if(yy1 != year){
						jQuery("#timestampdiv").find("#aa").val(year);
					}
					month = (month < 10) ? '0'+month : month;
					if(mm1 != month){
						jQuery("#timestampdiv").find("#mm").val( month ).attr('selected',true);
					}
				}
			});
			add_reset_button();
			jQuery.datepicker._gotoToday = function(id) {
							var target = jQuery(id);
							var inst = this._getInst(target[0]);
							if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
									inst.selectedDay = inst.currentDay;
									inst.drawMonth = inst.selectedMonth = inst.currentMonth;
									inst.drawYear = inst.selectedYear = inst.currentYear;
							}
							else {
									var date = new Date();
									inst.selectedDay = date.getDate();
									inst.drawMonth = inst.selectedMonth = date.getMonth();
									inst.drawYear = inst.selectedYear = date.getFullYear();
									// the below two lines are new
									this._setDateDatepicker(target, date);
									this._selectDate(id, this._getDateDatepicker(target));
							}
							this._notifyChange(inst);
							this._adjustDate(target);
							var current_date = jQuery( '#pddp_datepicker .ui-datepicker-today .ui-state-highlight').html();
							current_date = (current_date < 10) ? '0'+current_date : current_date;
							jQuery("#timestampdiv").find("#jj").val(current_date);
			}
	});

	var is_pddp_open = true;
	jQuery("#timestampdiv p a.pddp").click(function(event){
		event.preventDefault();
		if( is_pddp_open ){
			jQuery(this).html('Show Datepicker');
			is_pddp_open = false;
		}else{
			jQuery(this).html('Hide Datepicker');
			is_pddp_open = true;
		}

		jQuery( '#pddp_datepicker' ).slideToggle();
	});
});

function add_reset_button(){
	setTimeout(function(){
		if(!jQuery( '#pddp_datepicker .ui-datepicker-buttonpane .ui-datepicker-reset').length){
				jQuery( '#pddp_datepicker .ui-datepicker-buttonpane').append('<button type="button" class="ui-datepicker-reset ui-state-default ui-priority-secondary ui-corner-all" data-handler="reset" data-event="click">Reset</button>');
				jQuery( '#pddp_datepicker .ui-datepicker-buttonpane .ui-datepicker-reset').click(function(event){
							jQuery("#timestampdiv").find("#jj").val(dd);
							jQuery("#timestampdiv").find("#aa").val(yy);
							jQuery("#timestampdiv").find("#mm").val( mm ).attr('selected',true);
							jQuery("#timestampdiv").parent().parent().find("#hh").val(hh);
							jQuery("#timestampdiv").parent().parent().find("#mn").val(mn);
				});
		}
		},1);
}