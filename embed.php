<?php
	// Include WordPress
	require('../../../wp-blog-header.php');
	
	// Override any preset status codes
	$wp_query->is_404 = false;
	status_header(200);
	
	// Ensure embedding is allowed
	if (get_option(WebcamArchiveAdmin::allow_embed) != true) {
		header('HTTP/1.1 403 Forbidden');
		die('Embedding is disabled');
	};
	
	// Ensure size ID is a number
	$size_id = (isset($_GET['id']) ? $_GET['id'] : null);
	if ($size_id == null || !is_numeric($size_id)) {
		header('HTTP/1.1 403 Forbidden');
		die('Invalid request');
	}
	
	// Ensure size ID exists in the database
	$size_count = $wpdb->get_var($wpdb->prepare("
		SELECT
			COUNT(*)
		FROM
			" . $wpdb->prefix . "webcam_archive_size was
		WHERE
			id = %d
			AND deleted = FALSE
	", $size_id));
	if ($size_count == 0) {
		header('HTTP/1.1 403 Forbidden');
		die('Invalid request');
	}
	
	// Get latest image info
	$size = $wpdb->get_results($wpdb->prepare("
		SELECT
			(UNIX_TIMESTAMP(wa.entry_date) + %d) AS entry_date,
			was.id
		FROM
			" . $wpdb->prefix . "webcam_archive wa
			INNER JOIN " . $wpdb->prefix . "webcam_archive_size_entry wase ON wa.id = wase.entry_id
			INNER JOIN " . $wpdb->prefix . "webcam_archive_size was ON wase.size_id = was.id
		WHERE
			was.id = %d
			AND was.deleted = FALSE
		ORDER BY
			entry_date DESC
		LIMIT 1
	", $gmt_offset, $size_id));
	$size = $size[0];
	
	// Get full path to requested image file
	$upload_path = wp_upload_dir();
	$upload_path = $upload_path['basedir'];
	$image = $upload_path . '/' . WebcamArchive::upload_dir . '/' . date('Y/m/d/', $size->entry_date) . '/' . $size->entry_date . '/' . $size->id . '.jpg';
	
	// Return image to browser
	$imginfo = getimagesize($image);
	header('Content-Type: ' . $imginfo['mime']);
	readfile($image);
?>