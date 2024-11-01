<?php
/**
 * @since: 25 March 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_elite_licenser extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'Elite Licenser' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'Elite Licenser Settings' );
    }

    function GetMenuIcon() {
        return "ap ap-elite-licenser";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}