webcam_archive_root = '';

jQuery(function() {
	// Hacky method to get webcam archive root dynamically using JavaScript
	webcam_archive_root = jQuery('script[src*="jquery.lightbox"]').attr('src').replace(/js\/jquery\.lightbox.*/, '');
	
	// Add tooltip to thumbnail image
	jQuery('#webcam_archive .thumb').tooltip({
		offset: [-6, 0],
		delay: 100
	});
	
	// Add lightbox to display larger image
	jQuery('#webcam_archive .thumb a').lightBox({
		imageBlank: webcam_archive_root + 'images/lightbox-blank.gif',
		imageLoading: webcam_archive_root + 'images/lightbox-ico-loading.gif',
		imageBtnClose: webcam_archive_root + 'images/lightbox-btn-close.gif',
		imageBtnPrev: webcam_archive_root + 'images/lightbox-btn-prev.gif',
		imageBtnNext: webcam_archive_root + 'images/lightbox-btn-next.gif'
	});
});