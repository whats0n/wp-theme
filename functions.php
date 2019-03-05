<?php
	/* Add Custom scripts and styles */
	add_action( 'wp_enqueue_scripts', function () {
		wp_enqueue_style('custom', get_template_directory_uri() . '/css/custom.css');
	});
	

	/* Register Menus */
	add_action('after_setup_theme', function () {
		/* Header menu config */
		locate_template(array('inc/header_nav.php'), true, true);
		/* Register menus */
		register_nav_menus(array(
			'header_menu' => 'Header Menu',
			'footer_menu' => 'Footer Menu'
		));
	});
?>