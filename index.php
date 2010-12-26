<?php
	/*
	 * Plugin Name: Webcam Archive
	 * Plugin URI: https://www.github.com/davemasse/webcam-archive/
	 * Description: A WordPress plugin for managing and displaying an archive of webcam photos.
	 * Author: Dave Masse
	 * Version: 0.1
	 * Author URI: http://www.rudemoose.com/
	 */
	class WebcamArchive {
		function xmlrpc_callback($args) {
			$wp_xmlrpc_server = new wp_xmlrpc_server;
			
			$blog_id = $args[0];
			$username = $args[1];
			$password = $args[2];
			$image = $args[2];
			
			if (!$wp_xmlrpc_server->login($username, $password))
				return $wp_xmlrpc_server->error;
			
			return array(
				count($args)
			);
		}
		
		function xmlrpc_methods($methods) {
			$methods['webcamarchive.upload'] = 'xmlrpc_callback';
			return $methods;
		}
	}
	
	add_filter('xmlrpc_methods', array('WebcamArchive', 'xmlrpc_methods'));
?>