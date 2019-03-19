<?php
	add_action('wp_enqueue_scripts', function () {
		/* Add Custom scripts and styles */
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

	add_action('init', function () {
		// Creat Post Type
	  $labels = array(
	    'name' => __( 'Abouts' ),
	    'singular_name' => __( 'Abouts' ),
	    'add_new' => __( 'New About' ),
	    'add_new_item' => __( 'Add New About' ),
	    'edit_item' => __( 'Edit About' ),
	    'new_item' => __( 'New About' ),
	    'view_item' => __( 'View About' ),
	    'search_items' => __( 'Search Abouts' ),
	    'not_found' =>  __( 'No Abouts Found' ),
	    'not_found_in_trash' => __( 'No Abouts found in Trash' ),
	  );
	  $args = array(
	    'labels' => $labels,
	    'has_archive' => true,
	    'public' => true,
	    'hierarchical' => false,
	    'menu_position' => 5,
	    'supports' => array(
	      'title',
	      'editor',
	      'excerpt',
	      'custom-fields',
	      'thumbnail'
	    ),
	  );
	  register_post_type('abouts', $args);
	});
?>