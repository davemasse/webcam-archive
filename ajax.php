<?php
	// Request must include a year (YYYY) and month (MM)
	$archive_year = (isset($_GET['year']) ? $_GET['year'] : null);
	$archive_month = (isset($_GET['month']) ? $_GET['month'] : null);
	
	// Kill any invalid requests
	if ($_SERVER['REQUEST_METHOD'] != 'GET' || !preg_match('/^\d{4}$/', $archive_year) || !preg_match('/^\d{2}$/', $archive_month)) {
		header('HTTP/1.1 403 Forbidden');
		die('Invalid request');
	}
	
	// Include WordPress
	require('../../../wp-blog-header.php');
	
	// Override any preset status codes
	$wp_query->is_404 = false;
	status_header(200);
	
	// Get all entry dates for this year and month
	$rows = $wpdb->get_results($wpdb->prepare("
		SELECT
			DISTINCT DATE_FORMAT(entry_date, '%%Y-%%m-%%d') AS entry_date
		FROM
			" . $wpdb->prefix . "webcam_archive
		WHERE
			YEAR(entry_date) = %s
			AND MONTH(entry_date) = %s
	", $archive_year, $archive_month));
	
	// Create a clean array of the available dates
	$dates = array();
	foreach ($rows as $row) {
		$dates[] = $row->entry_date;
	}
	
	// Set JSON header
	header('Content-type: application/x-json');
	echo json_encode($dates);
?>