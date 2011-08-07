/*
 * Copyright 2011, Dave Masse
 * GPL v2.0
 * http://www.gnu.org/licenses/gpl-2.0.txt
 */

webcam_archive = {
	root: '',
	lightbox_root: '',
	dates: [],
	getDates: function(year, month) {
		var data, url;
		
		// Format year and month according to how PHP will expect them
		year = year.toString();
		month = month.toString();
		if (month.length == 1) {
			month = '0' + month;
		}
		
		// Build data structure
		data = {
			year: year,
			month: month
		};
		
		jQuery.ajax({
			type: 'GET',
			url: this.root + 'ajax.php',
			data: data,
			success: function(data, textStatus, jqXHR) {
				webcam_archive.dates = data;
			},
			dataType: 'json',
			async: false
		});
	},
	ready: function() {
		var currentDate, month, year;
		
		this.root = jQuery('script[src*="webcam_archive.js"]').attr('src').replace(/js\/webcam_archive\.js.*/, '');
		
		// Hacky method to get webcam archive lightbox root dynamically using JavaScript
		this.lightbox_root = jQuery('script[src*="jquery.lightbox"]').attr('src').replace(/js\/jquery\.lightbox.*/, '');

		// Add tooltip to thumbnail image
		jQuery('#webcam_archive .thumb').tooltip({
			offset: [0, 0],
			delay: 100,
			position: 'bottom center',
			relative: true
		});

		// Add lightbox to display larger image
		jQuery('#webcam_archive .thumb a').lightBox({
			imageBlank: this.lightbox_root + 'images/lightbox-blank.gif',
			imageLoading: this.lightbox_root + 'images/lightbox-ico-loading.gif',
			imageBtnClose: this.lightbox_root + 'images/lightbox-btn-close.gif',
			imageBtnPrev: this.lightbox_root + 'images/lightbox-btn-prev.gif',
			imageBtnNext: this.lightbox_root + 'images/lightbox-btn-next.gif'
		});
		
		jQuery('#webcam_archive .datepicker').datepicker({
			buttonImage: this.root + 'images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			showOn: 'button',
			beforeShowDay: function(date) {
				var fullDate, showDay, year, month, day;
				
				year = date.getFullYear();
				month = (date.getMonth() + 1).toString();
				if (month.length == 1) {
					month = '0' + month;
				}
				day = date.getDate().toString();
				if (day.length == 1) {
					day = '0' + day;
				}
				fullDate = year + '-' + month + '-' + day;
				
				if (jQuery.inArray(fullDate, webcam_archive.dates) >= 0) {
					showDay = Array(true, 'hasEntry', '');
				} else {
					showDay = Array(false, '', '');
				}
				
				return showDay;
			},
			onChangeMonthYear: function(year, month, inst) {
				webcam_archive.getDates(year, month);
			},
			onSelect: function(dateText, inst) {
				jQuery('#datepicker').parents('form:first').submit();
			}
		});
		
		jQuery('#datepicker').hide();
		
		currentDate = new Date(jQuery('#datepicker').val());
		year = currentDate.getFullYear();
		month = currentDate.getMonth() + 1;
		webcam_archive.getDates(year, month);
	}
}

jQuery(function() {
	webcam_archive.ready();
});