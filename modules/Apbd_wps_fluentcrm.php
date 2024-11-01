<?php
/**
 * @since: 25 March 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_fluentcrm extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'FluentCRM' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'FluentCRM Settings' );
    }

    function GetMenuIcon() {
        return "fa fa-address-book";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}