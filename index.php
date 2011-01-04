<?php
	/*
	 * Plugin Name: Webcam Archive
	 * Plugin URI: https://www.github.com/davemasse/webcam-archive/
	 * Description: A WordPress plugin for managing and displaying an archive of webcam photos.
	 * Author: Dave Masse
	 * Version: 0.1
	 * Author URI: http://www.rudemoose.com/
	 */
	
	// Manage webcam-related XML-RPC requests
	class WebcamArchive {
		function xmlrpc_callback($args) {
			global $wpdb;
			
			$wp_xmlrpc_server = new wp_xmlrpc_server;
			
			$blog_id = $args[0];
			$username = $args[1];
			$password = $args[2];
			$image = $args[3];
			$meta = $args[4];
			
			$timestamp = current_time('timestamp');
			
			if (!$wp_xmlrpc_server->login($username, $password))
				return $wp_xmlrpc_server->error;
			
			$upload_path = wp_upload_dir();
			$upload_path = $upload_path['basedir'];
			
			// Generate filename for temp image
			$filename = get_class() . time() . '.jpg';
			
			$file = fopen($upload_path . '/' . $filename, 'wb');
			fwrite($file, base64_decode($image));
			fclose($file);
			
			$wpdb->query($wpdb->prepare("
				INSERT INTO
					" . $wpdb->prefix . "webcam_archive
				(entry_date)
				VALUES
				(FROM_UNIXTIME(%s))
			", $timestamp));
			$entry_id = $wpdb->insert_id;
			
			if (is_array($meta)) {
				foreach ($meta as $key => $value) {
					if ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "webcam_archive_meta WHERE id = %d", $key)) == 1) {
						$wpdb->query($wpdb->prepare("
							INSERT INTO
								" . $wpdb->prefix . "webcam_archive_meta_entry
							(entry_id, meta_id, value)
							VALUES
							(%d, %d, '%s')
						", $entry_id, $key, $value));
					}
				}
			}
			
			// Load all active photo sizes
			$sizes = $wpdb->get_results("
				SELECT
					id,
					width,
					height
				FROM
					" . $wpdb->prefix . "webcam_archive_size
				WHERE
					deleted = 0
				ORDER BY
					width ASC,
					height ASC
			");
			
			// Create directory structure with intermediate directories, if necessary
			$output_dir = $upload_path . '/webcam/' . date('Y/m/d/', current_time('timestamp')) . $timestamp . '/';
			mkdir($output_dir, 0777, true);
			
			// Create image object from uploaded image
			$image_obj = imagecreatefromjpeg($upload_path . '/' . $filename);
			
			// Get image dimensions of uploaded image
			list($image_width, $image_height) = getimagesize($upload_path . '/' . $filename);
			
			// Iterate through sizes, creating resized images
			foreach ($sizes as $size) {
				// Set resized image width
				if ($size->width == 0)
					$resized_width = $image_width;
				else
					$resized_width = $size->width;
				
				// Set resized image height
				if ($size->height == 0)
					$resized_height = $image_height;
				else
					$resized_height = $size->height;
				
				// Generate placeholder for new image
				$resized_obj = imagecreatetruecolor($resized_width, $resized_height);
				
				// Copy uploaded image to resized image object
				imagecopyresized($resized_obj, $image_obj, 0, 0, 0, 0, $resized_width, $resized_height, $image_width, $image_height);
				
				// Output resized image
				imagejpeg($resized_obj, $output_dir . $size->id . '.jpg');
				
				$wpdb->query($wpdb->prepare("
					INSERT INTO
						" . $wpdb->prefix . "webcam_archive_size_entry
					(entry_id, size_id)
					VALUES
					(%d, %d)
				", $entry_id, $size->id));
			}
			
			// Delete temp file
			unlink($upload_path . '/' . $filename);
			
			return array(
				'error' => '',
			);
		}
		
		function xmlrpc_methods($methods) {
			$methods['webcamarchive.upload'] = array('WebcamArchive', 'xmlrpc_callback');
			return $methods;
		}
	}
	
	// Add a custom callback for XML-RPC requests
	add_filter('xmlrpc_methods', array('WebcamArchive', 'xmlrpc_methods'));
	
	class WebcamArchiveAdmin {
		const capability = 'manage_options';
		const db_version_key = 'webcam_archive_version';
		const db_version = 0.1;
		const shortcode_tag = 'webcam_archive';
		const help_text = <<<EOF
			<p>TODO: Help text.</p>
EOF;
		
		function install() {
			global $wpdb;
			
			// Get current plugin schema version
			$installed_version = get_option(self::db_version_key);
			
			// Set default version if this is the first instantiation
			if ($installed_version === false)
				$installed_version = 0;
			
			// Always pass through table structure adjustments if the installed
			// database version is lower than the current plugin version
			if ($installed_version < self::db_version) {
				// Include supporting code since a database update is needed
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				
				// Photo instances
				$sql = "CREATE TABLE " . $wpdb->prefix . "webcam_archive (
						id INT(11) AUTO_INCREMENT,
						entry_date TIMESTAMP NOT NULL,
						PRIMARY KEY (id)
					);";
				dbDelta($sql);
				
				// Photo sizes
				// These cannot be redefined since older photos may have already been
				// created with these dimensions. Instead, a 'deleted' flag will be
				// set and used to determine which sizes are available for new photos.
				$sql = "CREATE TABLE " . $wpdb->prefix . "webcam_archive_size (
						id INT(11) NOT NULL AUTO_INCREMENT,
						width INT(11) NOT NULL,
						height INT(11) NOT NULL,
						deleted BIT DEFAULT 0 NOT NULL,
						PRIMARY KEY (id)
					);";
				dbDelta($sql);
				
				// Meta information
				$sql = "CREATE TABLE " . $wpdb->prefix . "webcam_archive_meta (
						id INT(11) NOT NULL AUTO_INCREMENT,
						name VARCHAR(200) NOT NULL,
						sort INT(11) NOT NULL,
						deleted BIT DEFAULT 0 NOT NULL,
						PRIMARY KEY (id)
					);";
				dbDelta($sql);
				
				// Size<->entry mapping
				$sql = "CREATE TABLE " . $wpdb->prefix . "webcam_archive_size_entry (
						entry_id INT(11) NOT NULL,
						size_id INT(11) NOT NULL,
						PRIMARY KEY (entry_id, size_id)
					);";
				dbDelta($sql);
				
				// Meta<->entry mapping
				$sql = "CREATE TABLE " . $wpdb->prefix . "webcam_archive_meta_entry (
						entry_id INT(11) NOT NULL,
						meta_id INT(11) NOT NULL,
						value TEXT NOT NULL,
						PRIMARY KEY (entry_id, meta_id)
					);";
				dbDelta($sql);
			}
			
			// Update database schema version
			update_option(self::db_version_key, self::db_version);
		}
		
		function admin_menu() {
			add_menu_page(__('Webcam Archive'), __('Webcam Archive'), self::capability, __FILE__);
			$hook = add_submenu_page(__FILE__, __('Settings'), __('Settings'), self::capability, __FILE__, array('WebcamArchiveAdmin', 'display_admin'));
			add_contextual_help($hook, self::help_text);
		}
		
		function display_admin() {
			global $wpdb;
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$params = $_POST['param'];
				
				// Save photo sizes
				foreach ($params as $key => $val) {
					// New photo size
					if ($key == 0) {
						if (preg_match('/^(0|[1-9][0-9]+)$/', $val['width']) == 0 || preg_match('/^(0|[1-9][0-9]+)$/', $val['height']) == 0) {
							continue;
						}
						
						$wpdb->query($wpdb->prepare("
							INSERT INTO
								" . $wpdb->prefix . "webcam_archive_size
							(width, height)
							VALUES
							('%s', '%s')
						", $val['width'], $val['height']));
					// Delete existing photo size
					} elseif ($key > 0) {
						$wpdb->query($wpdb->prepare("
							UPDATE
								" . $wpdb->prefix . "webcam_archive_size
							SET
								deleted = %d
							WHERE
								id = %s
						", (isset($val['delete']) ? 1 : 0), $key));
					}
				}
				
				$metas = $_POST['meta'];
				
				// Save meta fields
				foreach ($metas as $key => $val) {
					// New meta field
					if ($key == 0) {
						if (strlen($val['name']) == 0) {
							continue;
						}
						
						$wpdb->query($wpdb->prepare("
							INSERT INTO
								" . $wpdb->prefix . "webcam_archive_meta
							(name, sort)
							SELECT '%s', IFNULL(MAX(sort), 0) + 1 FROM " . $wpdb->prefix . "webcam_archive_meta WHERE deleted = 0
						", substr($val['name'], 0, 200)));
					// Update existing meta field
					} elseif ($key > 0) {
						$wpdb->query($wpdb->prepare("
							UPDATE
								" . $wpdb->prefix . "webcam_archive_meta
							SET
								name = '%s',
								sort = %s,
								deleted = %d
							WHERE
								id = %s
						", $val['name'], $val['sort'], (isset($val['delete']) ? 1 : 0), $key));
					}
				}
			}
			
			// Load all active photo sizes
			$sizes = $wpdb->get_results("
				SELECT
					id,
					width,
					height
				FROM
					" . $wpdb->prefix . "webcam_archive_size
				WHERE
					deleted = 0
				ORDER BY
					width ASC,
					height ASC
			");
			
			// Load all active meta fields
			$metas = $wpdb->get_results("
				SELECT
					id,
					name,
					sort
				FROM
					" . $wpdb->prefix . "webcam_archive_meta
				WHERE
					deleted = 0
				ORDER BY
					sort ASC,
					name ASC
			");
			
			include 'display_admin.php';
		}
		
		function handle_shortcode($attrs = array()) {
			global $wpdb;
			
			$gmt_offset = 0;#get_option('gmt_offset') * 3600;
			
			$upload_path = wp_upload_dir();
			$upload_path = $upload_path['baseurl'];
			
			$entry_date = $wpdb->get_var("
				SELECT
					DATE_FORMAT(wa.entry_date, '%Y%m%d')
				FROM
					" . $wpdb->prefix . "webcam_archive wa
				ORDER BY
					wa.entry_date DESC
				LIMIT 1
			");
			
			$sizes = $wpdb->get_results($wpdb->prepare("
				SELECT
					wa.id,
					(UNIX_TIMESTAMP(wa.entry_date) + %d) AS entry_date,
					was.id,
					was.width,
					was.height
				FROM
					" . $wpdb->prefix . "webcam_archive wa
					INNER JOIN " . $wpdb->prefix . "webcam_archive_size_entry wase ON wa.id = wase.entry_id
					INNER JOIN " . $wpdb->prefix . "webcam_archive_size was ON wase.size_id = was.id
				WHERE
					DATE_FORMAT(entry_date, '%%Y%%m%%d') = '%s'
				ORDER BY
					was.width ASC,
					was.height ASC
			", $gmt_offset, $entry_date));
			
			$metas = $wpdb->get_results($wpdb->prepare("
				SELECT
					(UNIX_TIMESTAMP(wa.entry_date) + %d) AS entry_date,
					wam.name,
					wame.value
				FROM
					" . $wpdb->prefix . "webcam_archive wa
					INNER JOIN " . $wpdb->prefix . "webcam_archive_meta_entry wame ON wa.id = wame.entry_id
					INNER JOIN " . $wpdb->prefix . "webcam_archive_meta wam ON wame.meta_id = wam.id
				WHERE
					DATE_FORMAT(entry_date, '%%Y%%m%%d') = '%s'
			", $gmt_offset, $entry_date));
			
			foreach ($sizes as $size) {
				$size_array = array(
					'id' => $size->id,
					'width' => $size->width,
					'height' => $size->height,
				);
				$entry_array[$size->entry_date]['sizes'][] = $size_array;
			}
			
			unset($sizes);
			
			foreach ($metas as $meta) {
				$meta_array = array(
					'name' => $meta->name,
					'value' => $meta->value,
				);
				$entry_array[$meta->entry_date]['metas'][] = $meta_array;
			}
			
			unset($metas);
			
			ob_start();
			
			include 'display_frontend.php';
			
			return ob_get_clean();
		}
	}
	
	// Run install on every load in case the database needs to be updated (quick version check)
	add_action('admin_init', array('WebcamArchiveAdmin', 'install'));
	
	// Register admin menu
	add_action('admin_menu', array('WebcamArchiveAdmin', 'admin_menu'));
	
	// Shortcode to put menu in pages and posts
	add_shortcode(WebcamArchiveAdmin::shortcode_tag, array('WebcamArchiveAdmin', 'handle_shortcode'));
?>