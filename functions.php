<?php
	/* Add Custom scripts and styles */
	add_action('wp_enqueue_scripts', function () {
		wp_enqueue_style('custom', get_template_directory_uri() . '/assets/css/app.css');
	});
	

	add_action('after_setup_theme', function () {
		/* Custom Logo */
		add_filter( 'get_custom_logo', function () {
			$custom_logo_id = get_theme_mod('custom_logo');
			$html = sprintf(
				'<a href="%1$s" class="logo" rel="home">%2$s</a>',
				esc_url(home_url('/')),
				wp_get_attachment_image($custom_logo_id, 'full', false, array(
					'class' => 'logo__img',
				))
			);
			return $html;   
		});
		add_theme_support('custom-logo', array(
			'width' => 200,
			'height' => 28,
			'flex-width' => true,
			'flex-height' => true,
			'header-text' => array('site-title', 'site-description')
		));
		/* Dynamic title tag */
		add_theme_support('title-tag');
		/* Header menu config */
		locate_template(array('inc/header_nav.php'), true, true);
		/* Register menus */
		register_nav_menus(array(
			'header_menu' => 'Header Menu',
			'footer_menu' => 'Footer Menu'
		));
	});
?>