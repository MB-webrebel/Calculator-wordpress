<?php
/**
 * Plugin Name: Cost Calculator Builder
 * Plugin URI: https://wordpress.org/plugins/cost-calculator-builder/
 * Description: WP Cost Calculator helps you to build any type of estimation forms on a few easy steps. The plugin offers its own calculation builder.
 * Author: StylemixThemes
 * Author URI: https://stylemixthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cost-calculator-builder
 * Version: 2.2.7
 */

if (!defined('ABSPATH')) exit;

define('CALC_DIR', __DIR__);
define('CALC_FILE', __FILE__);
define('CALC_VERSION', '2.2.7');
define('CALC_DB_VERSION', '2.2.6');
define('CALC_PATH', dirname(CALC_FILE));
define('CALC_URL', plugins_url('', CALC_FILE));


if (is_admin()) {
    require_once CALC_PATH . '/includes/admin/enqueue.php';
    require_once CALC_PATH . '/includes/admin/item-announcements.php';
    require_once CALC_PATH . '/includes/classes/CCBBuilderAdminMenu.php';
}

require_once CALC_PATH . '/includes/functions.php';
require_once CALC_PATH . '/includes/classes/CCBUpdates.php';
require_once CALC_PATH . '/includes/classes/CCBUpdatesCallbacks.php';
require_once CALC_PATH . '/includes/classes/CCBSettingsData.php';
require_once CALC_PATH . '/includes/classes/CCBAjaxCallbacks.php';
require_once CALC_PATH . '/includes/classes/CCBAjaxAction.php';
require_once CALC_PATH . '/includes/classes/CCBCalculators.php';
require_once CALC_PATH . '/includes/classes/CCBExportImport.php';
require_once CALC_PATH . '/includes/classes/CCBTemplate.php';
require_once CALC_PATH . '/includes/classes/CCBTranslations.php';
require_once CALC_PATH . '/includes/classes/CCBFrontController.php';
require_once CALC_PATH . '/includes/classes/CCBCustomFields.php';
require_once CALC_PATH . '/includes/classes/CCBCustomFields.php';
require_once CALC_PATH . '/includes/classes/CCBOrderController.php';
require_once CALC_PATH . '/includes/classes/vendor/DataBaseModel.php';
require_once CALC_PATH . '/includes/classes/database/Orders.php';
require_once CALC_PATH . '/includes/helpers/CCBConditionsHelper.php';
require_once CALC_PATH . '/includes/helpers/CCBFieldsHelper.php';
require_once CALC_PATH . '/widgets/CCB_VC.php';
require_once CALC_PATH . '/includes/widget.php';
require_once CALC_PATH . '/includes/install.php';
require_once CALC_PATH . '/includes/init.php';

if ( defined('CCB_PRO_VERSION') !== false && version_compare(CCB_PRO_VERSION, '2.1.9','<') ) {
	require_once( dirname( __FILE__ )  . '/includes/classes/CCBAdminNotices.php' );
	\cBuilder\Classes\CCBAdminNotices::initWrongVersion();
	return;
}