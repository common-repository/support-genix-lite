<?php
/**
 * @since: 27 August 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_edd extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'EDD' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'Easy Digital Downloads' );
    }

    function GetMenuIcon() {
        return "fa fa-download";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}