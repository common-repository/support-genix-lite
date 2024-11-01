<?php
/**
 * @since: 9 May 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_report extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'Report' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'Report & Statistics' );
    }

    function GetMenuIcon() {
        return "fa fa-bar-chart";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}