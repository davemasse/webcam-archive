webcam_archive = {
	'root': '',
	'lightbox_root': '',
	'ready': function() {
		this.root = jQuery('script[src*="webcam_archive.js"]').attr('src').replace(/js\/webcam_archive\.js.*/, '');
		
		// Hacky method to get webcam archive lightbox root dynamically using JavaScript
		this.lightbox_root = jQuery('script[src*="jquery.lightbox"]').attr('src').replace(/js\/jquery\.lightbox.*/, '');

		// Add tooltip to thumbnail image
		jQuery('#webcam_archive .thumb').tooltip({
			offset: [0, 0],
			delay: 100,
			position: 'bottom center'
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
			dateFormat: 'yy-mm-dd'
		});
	}
}

jQuery(function() {
	webcam_archive.ready();
});