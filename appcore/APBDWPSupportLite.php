<?php
/**
 * Elite POS
 * Author: S M Sarwar Hasan
 * A Product of appsbd.com
 */

APBD_LoadCore("AppsBDKarnelLite","AppsBDKarnelLite",__FILE__);
class APBDWPSupportLite extends AppsBDKarnelLite {
    function __construct( $pluginBaseFile, $version = '1.0.0' ) {

                $this->pluginFile       = $pluginBaseFile;
        $this->pluginSlugName   = 'support-genix-lite';
        $this->pluginName       = 'Support Genix Lite';
        $this->pluginVersion    = $version;
        $this->bootstrapVersion = '4.6.1';
        parent::__construct( $pluginBaseFile, $version );
	    SupportGNInput::sanitize_all_input_data();

    }

    public static function get_client_url($link,$ver="1.0.0")
    {
        return plugins_url("template/main/".$link."?v=".$ver, self::GetInstance()->pluginFile);
    }
    public function initialize() {
        parent::initialize();

        $this->SetIsLoadJqGrid(true);
        $this->SetPluginIconClass("dashicons-apbd-support-genix",'dashicons-apbd-support-genix');
        $this->setSetActionPrefix("apbd_wps");
        $this->AddLiteModule("Apbd_wps_role");
        $this->AddLiteModule("Apbd_wps_ticket_category");
        $this->AddLiteModule("Apbd_wps_ticket_assign_rule");
        $this->AddLiteModule("APBD_wps_email_template");
        $this->AddLiteModule("Apbd_wps_canned_msg");
        $this->AddLiteModule("Apbd_wps_custom_field");
        $this->AddLiteModule("Apbd_wps_woocommerce");
        $this->AddLiteModule("Apbd_wps_edd");
        $this->AddLiteModule("Apbd_wps_fluentcrm");
        $this->AddLiteModule("Apbd_wps_whatsapp");
        $this->AddLiteModule("Apbd_wps_slack");
        $this->AddLiteModule("Apbd_wps_envato_system");
        $this->AddLiteModule("Apbd_wps_elite_licenser");
        $this->AddLiteModule("Apbd_wps_tutorlms");
        $this->AddLiteModule("Apbd_wps_betterdocs");
        $this->AddLiteModule("Apbd_wps_webhook");
        $this->AddLiteModule("Apbd_wps_incoming_webhook");
        $this->AddLiteModule("Apbd_wps_mailbox");
        $this->AddLiteModule("Apbd_wps_settings");
        $this->AddLiteModule("Apbd_wps_report");
        $this->AddLiteModule("Apbd_wps_debug_log");
	    jQGrid::setTranslatorMethod([$this,"__"]);
    }
    function _myautoload_method($class)
    {
        $basepath = plugin_dir_path($this->pluginFile);

        $filename = $basepath . "api/{$class}.php";
        if (file_exists($filename)) {
            APBD_Load_Any($filename, $class);
        } else {
            $isFound = false;
            foreach (['v1'] as $subpath) {
                $filename = $basepath . "api/{$subpath}/{$class}.php";

                if (file_exists($filename)) {
                    $isFound = true;
                    APBD_LoadPluginAPI($class, $subpath);
                }
            }
            if (!$isFound) {
                parent::_myautoload_method($class);
            }
        }
    }
    public function OnInit()
    {
	    load_plugin_textdomain('support-genix-lite', FALSE, basename( dirname( $this->pluginFile ) ) . '/languages/');
        parent::OnInit();
        add_action('rest_api_init', function () {
            header("Access-Control-Allow-Origin: *");
            $namespace = self::getNamespaceStr();
            new APBDWPSHeartBitAPI($namespace);
            new APBDWPSUserAPI($namespace);
            new APBDWPSTicketAPI($namespace);
            new APBDWPSAPIConfig($namespace);
        });
    }
	function __( $string, $parameter = NULL, $_ = NULL ) {
		$args = func_get_args();
		array_splice( $args, 1, 0, array( 'support-genix-lite' ) );

		return call_user_func_array( "APBD_Lan__", $args );
	}
    function OnAdminAppStyles()
    {
        $this->AddAdminStyle('wp-color-picker');
        $this->AddAdminStyle("font-awesome-4.7.0", "uilib/font-awesome/4.7.0/css/font-awesome.min.css", true);
        $this->AddAdminStyle("apboostrap_validatior_css", "uilib/bootstrapValidation/css/bootstrapValidator.min.css", true);
        $this->AddAdminStyle("apboostrap_sgnofi_css1", "uilib/sliding-growl-notification/css/notify.css", true);
        $this->AddAdminStyle("apboostrap_sweetalertcss", "uilib/sweetalert/sweetalert.css", true);
        $this->AddAdminStyle("apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.css", true);
        $this->AddAdminStyle("apboostrap_boostrap_select", "uilib/boostrap-select/css/bootstrap-select-bundle.css", true);
        $this->AddAdminStyle("appsbd-icon", "uilib/icon/style.css", true);
        $this->AddAdminStyle("jquery-grid", "uilib/grid/css/ui.jqgrid.css", true);
        $this->AddAdminStyle("appsbdcore", "admin-core-style.css");
        $this->AddAdminStyle("bootstrap-material-css","uilib/material/material.css",true);
        $this->AddAdminStyle( "select2", "uilib/select2/css/select2.min.css",true);
        $this->AddAdminStyle( 'select2-boostrap', 'uilib/select2/css/select2-bootstrap.min.css', true );
        $this->AddAdminStyle( "apbd-tg-input", "uilib/input-tags/css/amsify.suggestags.css",true);
        $this->AddAdminStyle( "apbd-ed-select", "uilib/editable-select/jquery-editable-select.min.css",true);
        foreach ($this->moduleList as $moduleObject) {
            $moduleObject->AdminStyles();
        }
    }
    function OnAdminAppScripts()
    {
        $this->AddAdminScript("apboostrap_main_bundle", "main.bundle.min.js");
        $this->AddAdminScript("apboostrap_validatior_js", "uilib/bootstrapValidation/js/bootstrapValidator4.min.js", true);
        $this->AddAdminScript("apboostrap_sgnofi_js", "uilib/sliding-growl-notification/js/notify.min.js", true);
        $this->AddAdminScript("apboostrap_sweetalertjs", "uilib/sweetalert/sweetalert.min.js", true);
        $this->AddAdminScript("apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.js", true);
        $this->AddAdminScript("apboostrap_boostrap_select", "uilib/boostrap-select/js/bootstrap-select.min.js", true);
        $this->AddAdminScript("apboostrap_ajax_boostrap_select", "uilib/boostrap-select/js/ajax-bootstrap-select.js", true);
        $this->AddAdminScript("apd-main-js", "main.min.js", false, ['wp-color-picker']);

        $this->AddAdminScript("jquery-grid-js-118n", "uilib/grid/js/i18n/grid.locale-en.js", true, ['jquery']);
        $this->AddAdminScript("jquery-grid-js", "uilib/grid/js/jquery.jqGrid.src.min.js", true, ['jquery']);

        wp_enqueue_editor();
        wp_enqueue_media();
        $this->AddAdminScript("select2", "uilib/select2/js/select2.min.js", true, ['jquery']);
        $this->AddAdminScript("apbd-ed-select", "uilib/editable-select/jquery-editable-select.min.js", true);
        $this->AddAdminScript("apbd-tg-input", "uilib/input-tags/js/jquery.amsify.suggestags.min.js", true);

        foreach ($this->moduleList as $moduleObject) {
            $moduleObject->AdminScripts();
        }
    }
    static function getNamespaceStr(){
        return "apbd-wps/v1";
    }

    function GetHeaderHtml() {
            }

    function GetFooterHtml() {
            }


    function WPAdminCheckDefaultCssScript( $src ) {
        if(!parent::WPAdminCheckDefaultCssScript($src)){
            if ( empty( $src ) || $src == 1 || preg_match( "/\/plugins\/query-monitor\//", $src ) ) {
                return true;
            }
        }else{
            return true;
        }

    }
    public function OnAdminGlobalStyles() {
        parent::OnAdminGlobalStyles();
        $currentScreen = get_current_screen();
        if(!empty($currentScreen->base) && $currentScreen->base=="dashboard"){
            $this->AddAdminStyle("elite-dashboard-css","dashboard.css");
        }
    }
    static function StartApp( $fileName ) {

    }
    function OptionFormCore() {
        $isMenuOpen = $this->isMenuOpen();
        ?>

        <div id="APPSBDWP" class="APPSBDWP" data-cookie-id="<?php echo esc_attr($this->pluginSlugName); ?>">
            <div class="apsbd-main-container container-fluid">

                <div class="apsbd-main-card card">
                    <div class="card-header">
                        <?php if ( $this->isTabMenu ) { ?>
                            <button type="button" id="apd-main-btn"
                                    class="btn btn-default pull-left <?php echo esc_attr($isMenuOpen) ? ' mini-menu on-pre-mini ' : ''; ?>">
                                <i class="fa fa-align-justify"></i>
                            </button>
                        <?php } ?>
                        <h2 class="apd-app-title">
                            <?php if ( empty( $this->pluginIconClass ) ) { ?>
                                <img class="apd-plugin-logo"
                                     src="<?php echo esc_url(plugins_url( "images/logo.svg", $this->pluginFile )); ?>"
                                     alt="<?php echo esc_attr($this->pluginName); ?>">
                            <?php } else { ?>
                                <i class="apnd-plugin-main-icon <?php echo esc_attr($this->pluginIconClass); ?>"></i> <?php echo esc_attr($this->pluginName); ?>
                            <?php } ?>
                        </h2>
                        <?php
                        if ( $this->is_countable($this->moduleList) && count( $this->_topmenu ) > 0 ) {
                            ?>
                            <div class="app-right-menu">
                                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                            data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                                            aria-expanded="false" aria-label="Toggle navigation">
                                        <i class="fa fa-align-justify"></i>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                        <div class="navbar-nav nav-tabs nav">
                                            <?php foreach ( $this->_topmenu as $skey => $topmenu ) {
                                                ?>
                                                <a class="<?php echo esc_attr($topmenu->class); ?> nav-item nav-link top-tab-link" <?php if ( $topmenu->istab ) { echo 'data-toggle="tab"'; } ?>
                                                   href="<?php echo esc_attr(( $topmenu->istab && is_callable( $topmenu->func ) )? '#_t_tab' . $skey : $topmenu->func); ?>" <?php echo esc_attr($topmenu->attr); ?> ><?php if ( ! empty( $topmenu->icon ) ) { ?>
                                                        <i class="<?php echo esc_attr($topmenu->icon); ?>"></i> <?php }
                                                    echo wp_kses_html($topmenu->title); ?></a>
                                                <?php
                                            } ?>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="card-body ">
                        <?php

                        if ( $this->isTabMenu ){ ?>
                        <div class="wrapper">
                            <?php $this->getMenuTab(); ?>
                            <!-- Page Content  -->
                            <div id="content" class="pos-relative pt-0">
                                <?php } ?>
                                <div id="apbd-app-loader" class="apbd-app-loader ">
                                    <div class="text-center" id="apbd-app-waiting">
                                        <img src="<?php echo plugins_url( "images/lighboxloader.svg", $this->pluginFile ); ?>"
                                             style="max-height: 50px;" alt="<?php $this->_e( "Loading.." ); ?>">
                                        <br>
                                        <h4 data-default-msg="<?php $this->_e( "Loading" ); ?>"></h4>
                                    </div>
                                </div>
                                <!-- Tab panes -->
                                <div class="<?php echo esc_attr( $this->isTabMenu ? ' tab-content ' : ' col-md ' ) ?>">
                                    <?php
                                    if ( $this->is_countable($this->moduleList) && count( $this->moduleList ) > 0 ) {
                                        $activeClassId = $this->getActiveModuleId();
                                        foreach ( $this->moduleList as $moduleObject ) {
                                            if ( $moduleObject->isHiddenModule() ) {
                                                continue;
                                            }
                                            $currentModuleId = $moduleObject->GetModuleId();
                                            ?>
                                            <div class="apbd-module-container pt-3 <?php echo esc_attr(( $this->isTabMenu )? ( ' tab-pane ' . ( $activeClassId == $currentModuleId ? ' active ' : '' ) ): ''); ?>"
                                                 id="<?php echo esc_attr( $currentModuleId); ?>">
                                                <?php
                                                if ( ! $moduleObject->isDontAddDefaultForm() ){
                                                ?>
                                                <form class="apbd-module-form <?php echo esc_attr($moduleObject->getFormClass()); ?>"
                                                      role="form"
                                                      id="<?php echo esc_attr($moduleObject->GetMainFormId()); ?>"
                                                      action="<?php echo esc_url($moduleObject->GetActionUrl( "" )); ?>"
                                                      method="post" <?php if ( $moduleObject->isMultipartForm() ) { echo 'enctype="multipart/form-data"'; } ?>>

                                                    <?php
                                                    }
                                                    if ( $this->isTabMenu ) {
                                                        ob_start();
                                                        $moduleObject->OptionFormHeader();
                                                        $mheader = ob_get_clean();
                                                        if ( ! empty( $mheader ) ) {
                                                            ?>
                                                            <div class="app-module-title clearfix">
                                                                <?php
                                                                echo wp_kses_html($mheader);
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="">
                                                        <?php
                                                        $moduleObject->SettingsPage(); ?>
                                                    </div>
                                                    <?php if ( ! $moduleObject->isDontAddDefaultForm() ){ ?>
                                                </form>
                                            <?php } ?>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        No module added
                                        <?php
                                    }

                                    foreach ( $this->_topmenu as $skey => $topmenu ) {
                                        if ( $topmenu->istab && is_callable( $topmenu->func ) ) {
                                            ?>
                                            <div class="tab-pane fade" id="_t_tab<?php echo esc_attr($skey); ?>">
                                                <?php call_user_func( $topmenu->func ); ?>
                                            </div>
                                            <?php
                                        }
                                    } ?>
                                </div>


                                <?php if ( $this->isTabMenu ){ ?>
                            </div>
                        </div>
                    <?php }

                    do_action($this->getHookActionStr("app-content-footer"));

                    ?>
                    </div>
                    <div class="card-footer text-muted text-center">
		                <?php echo esc_html($this->pluginName); ?>, <?php $this->_e("Copyright"); ?> © <?php echo date( 'Y' ); ?> <a
                                href="https://supportgenix.com">supportgenix.com</a>. <?php $this->_e("All rights reserved"); ?>.
                        <span class="pull-right"><?php $this->_e("Version"); ?>: <?php echo esc_html($this->pluginVersion); ?></span>
                    </div>
                </div>

            </div>
            <?php do_action($this->getHookActionStr("app-footer")); ?>
        </div>
        <?php
    }

}