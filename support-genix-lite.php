<?php
/*
Plugin Name: Support Genix Lite
Plugin URI: http://supportgenix.com
Description: Client ticketing app for Wordpress
Version: 1.3.16
Author: Support Genix
Author URI: https://supportgenix.com
Text Domain: support-genix-lite
Domain Path: /languages/
*/

global $wpdb;
$appWpSUpportLiteLoad = false;
$appWpSUpportLiteFile = __FILE__;
$appWpSUpportLitePath = dirname( $appWpSUpportLiteFile );
$appWpSUpportLiteVersion = '1.3.16';

include_once ABSPATH . 'wp-admin/includes/plugin.php';
include_once $appWpSUpportLitePath . '/appcore/APBDWPDiagnosticData.php';
include_once $appWpSUpportLitePath . '/appcore/APBDWPLoaderLite.php';
include_once $appWpSUpportLitePath . '/appcore/APBDWPOfferLite.php';

$appWpSUpportLiteLoad = APBDWPLoaderLite::isReadyToLoad( $appWpSUpportLiteFile, $appWpSUpportLiteVersion );

if ( true === $appWpSUpportLiteLoad ) {
    include_once $appWpSUpportLitePath . '/core/helper_lite.php';
    include_once $appWpSUpportLitePath . '/appcore/plugin_helper.php';
    include_once $appWpSUpportLitePath . '/appcore/APBDWPSupportLite.php';

	$appWpSUpportLitePos = new APBDWPSupportLite( $appWpSUpportLiteFile, $appWpSUpportLiteVersion );
	$appWpSUpportLitePos->StartPlugin();
}
