<?php
	// add default settings
	add_filter('wp_nav_menu_args', function ($args) {
		if ($args['theme_location'] === 'header_menu') {
			$args['container'] = 'nav';
			$args['container_class'] = $args['container_class'] ? 'nav ' . $args['container_class'] : 'nav';
			$args['container_id'] = $args['container_id'] ? $args['container_id'] : 'navigation';

			$args['menu_class'] = $args['menu_class'] ? 'nav__list ' . $args['menu_class'] : 'nav__list';
			$args['menu_id'] = $args['menu_id'] ? $args['menu_id'] : 'navigation__list';

			$args['fallback_cb'] = '';
			$args['item_spacing'] = 'discard';
		}
		return $args;
	});

	// remove id from menu item
	add_filter('nav_menu_item_id', function ($menu_id, $item, $args, $depth) {
		return $args->theme_location === 'header_menu' ? '' : $menu_id;
	}, 10, 4);
	
	// update classes for menu item/subitem
	add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {
		if ($args->theme_location === 'header_menu') {
			if (
				in_array( 'current-menu-item', $classes ) ||
				in_array( 'current-menu-ancestor', $classes ) ||
				in_array( 'current-menu-parent', $classes ) ||
				in_array( 'current_page_parent', $classes ) ||
				in_array( 'current_page_ancestor', $classes )
			) {
				if (in_array( 'current-menu-item', $classes )) $classes = ['is-active'];
				if (in_array( 'current-menu-ancestor', $classes )) $classes = ['is-active-ancestor'];
				if (in_array( 'current-menu-parent', $classes )) $classes = ['is-active-parent'];
				if (in_array( 'current_page_parent', $classes )) $classes = ['current_page_parent'];
				if (in_array( 'current_page_ancestor', $classes )) $classes = ['current_page_ancestor'];
			} else $classes = [];

			if ($depth === 0) {
				array_push($classes, 'nav__item');
			} else {
				array_push($classes, 'nav__subitem');
				array_push($classes, 'nav__subitem_lvl_' . $depth);
				return $classes;
			}

			if ($item->current) {
				array_push($classes, 'is-active');
			}
		}

		return $classes;
	}, 10, 4);
	// update classes for menu link/sublink
	add_filter('nav_menu_link_attributes', function ($attrs, $item, $args, $depth) {
		if ($args->theme_location === 'header_menu') {
			if ($depth === 0) {
				$attrs['class'] = 'nav__link';
			} else {
				$attrs['class'] = 'nav__sublink nav__sublink_lvl_' . $depth;
			}

			if ($item->current) {
				$attrs['class'] .= ' is-active';
			}
		}
		return $attrs;
	}, 10, 4);
	// update classes for submenu
	add_filter('nav_menu_submenu_css_class', function ($classes, $args, $depth) {
		if ($args->theme_location === 'header_menu') {
			$classes = [
				'nav__submenu',
				'nav__submenu_lvl_' . ($depth + 1)
			];

			if ($args->submenu_classes && $args->submenu_classes[$depth]) {
				array_push($classes, $args->submenu_classes[$depth]);
			}
		}

		return $classes;
	}, 10, 4);
?>