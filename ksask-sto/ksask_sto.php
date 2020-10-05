<?php
/*
Plugin Name: KSA STO Search
Plugin URI: https://ksask.net/
Description:Search auto service.
Author: Sergii
Author URI: https://ksask.net/
Text Domain: ksask.net
Domain Path: /languages/
Version: 1.0
*/

// code of a successful response from the API for comparison
define( 'KSA_STO_P_API_SUCCESS', 200 );

// minimum number of digits in postcode
define( 'KSA_STO_P_POST_CODE_SIZE', 5 );


define( 'KSA_STO', 1);

define( 'KSA_STO_P', __FILE__);

define( 'KSA_STO_P_BASENAME', plugin_basename( KSA_STO_P ) );

define( 'KSA_STO_P_DIR', untrailingslashit(dirname(KSA_STO_P)));

define( 'KSA_STO_P_URI', plugin_dir_url( __FILE__ ) );

require_once KSA_STO_P_DIR . '/settings.php';

register_activation_hook(__FILE__, 'ksa_sto_install');
function ksa_sto_install()
{
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'ksa_sto_deactivation');
function ksa_sto_deactivation()
{
    flush_rewrite_rules();
    delete_option('ksa_sto_api_url');
    delete_option('ksa_sto_api_key');
    delete_option('ksa_sto_api_code');
    delete_option('ksa_sto_post_code_min_length');
}

function ksa_sto_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=ksa-sto-plugin">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'ksa_sto_settings_link' );