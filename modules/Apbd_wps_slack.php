<?php
/**
 * @since: 25 March 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_slack extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'Slack' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'Slack Settings' );
    }

    function GetMenuIcon() {
        return "fa fa-slack";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}