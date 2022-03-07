<?php

namespace cBuilder\Classes;

class CCBAdminNotices {

	public static function initWrongVersion() {
		add_action( 'admin_notices', [self::class, 'ccb_show_admin_wrong_version_notice'] );
	}

	public static function ccb_show_admin_wrong_version_notice() {
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', esc_html__('Cost Calculator Builder Pro plugin update required. We added new features, and need to update your plugin to the latest version!', 'cost-calculator-builder') );
	}
}