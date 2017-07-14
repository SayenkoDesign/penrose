<?php

function fca_pc_upgrade_menu() {
	$page_hook = add_submenu_page(
		'fca_pc_settings_page',
		__('Upgrade to Premium', 'pixel-cat'),
		__('Upgrade to Premium', 'pixel-cat'),
		'manage_options',
		'pixel-cat-upgrade',
		'fca_pc_upgrade_ob_start'
	);
	add_action('load-' . $page_hook , 'fca_pc_upgrade_page');
}
add_action( 'admin_menu', 'fca_pc_upgrade_menu' );

function fca_pc_upgrade_ob_start() {
    ob_start();
}

function fca_pc_upgrade_page() {
    wp_redirect('https://fatcatapps.com/convertcat?utm_medium=plugin&utm_source=Pixel%20Cat%20Free&utm_campaign=free-plugin', 301);
    exit();
}

function fca_pc_upgrade_to_premium_menu_js() {
    ?>
    <script type="text/javascript">
    	jQuery(document).ready(function ($) {
            $('a[href="admin.php?page=pixel-cat-upgrade"]').on('click', function () {
        		$(this).attr('target', '_blank')
            })
        })
    </script>
    <style>
        a[href="admin.php?page=pixel-cat-upgrade"] {
            color: #6bbc5b !important;
        }
        a[href="admin.php?page=pixel-cat-upgrade"]:hover {
            color: #7ad368 !important;
        }
    </style>
    <?php 
}
add_action( 'admin_footer', 'fca_pc_upgrade_to_premium_menu_js');

function fca_pc_upgrade_admin_notice() {

	if ( isset ( $_GET['fca_pc_dismiss_upgrade_notice'] ) ) {
		update_option('fca_pc_dismissed_upgrade_notice', true );
	} else if ( get_option( 'fca_pc_dismissed_upgrade_notice' ) != true ) {		
		echo '<div class="notice notice-info">';
			echo '<img height="120" width="120" style="display: inline-block; margin:12px 16px 8px 0;" src="' . FCA_PC_PLUGINS_URL . '/assets/pixelcat_icon_128_128_360.png' . '">';
			echo '<div style="display: inline-block; vertical-align: top; max-width: calc(100% - 160px);">';
				echo '<p><strong>' . __('Get ConvertCat Premium', 'quiz-cat' ) . '</strong></p>';
				echo '<p>' . __("Thanks for using our Facebook Pixel Manager plugin. We've just launched our Pro version, which comes with a powerful Event Builder, WooCommerce integration & much more.", 'pixel-cat' );
				echo '<p>' . __("<strong>Launch discount</strong>: Buy this week to get $20 off.", 'pixel-cat' ) . '</p>';
				echo '<p><a class="button button-primary" style="margin-right: 16px;" target="_blank" href="https://fatcatapps.com/convertcat">' .  __('Click here to learn more', 'pixel-cat' ) . '</a>';
				echo "<a href='" . esc_url( add_query_arg( 'fca_pc_dismiss_upgrade_notice', 'true' ) ) . "'>" . __('Dismiss', 'pixel-cat' ) . '</a></p>';
			echo '</div>';		
		echo '</div>';		
	}
}
add_action( 'admin_notices', 'fca_pc_upgrade_admin_notice' );	