<?php
/**
 * @since: 25 March 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_woocommerce extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'WooCommerce' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'WooCommerce Settings' );
    }

    function GetMenuIcon() {
        return 'fa fa-shopping-bag';
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}