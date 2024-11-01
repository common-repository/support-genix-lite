<?php
/**
 * @since: 18 August 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_betterdocs extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'BetterDocs' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'BetterDocs Settings' );
    }

    function GetMenuIcon() {
        return "fa fa-book";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}