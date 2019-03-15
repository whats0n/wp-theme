<!DOCTYPE html>
<html lang="en">
<head>
	<?php wp_head();?>
</head>
<body>
	<!-- begin out -->
	<div class="out">
		<!-- begin header -->
		<header class="header">
			<div class="header__container">
				<?php
					if (has_custom_logo()) {
						the_custom_logo();
					}
				?>
				<?php wp_nav_menu(array(
					'theme_location' => 'header_menu',
					'container_class' => 'header__nav'
				)); ?>
			</div>
		</header>
		<!-- end header -->