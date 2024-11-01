<?php /** @var Apbd_wps_settings $this */?>
<?php $multiLangIndicator = ( $this->multiLangActive ? ' <span style="color: #17a2b8;">(<i class="fa fa-globe"></i> ' . strtoupper( $this->multiLangCode ) . ')</span>' : '' ); ?>
<form class="apbd-module-form bv-form" role="form" id="apbd-wp-support_AJ_Apbd_wps_settings_general"
      action="<?php echo esc_url($this->GetActionUrl()) ?>" method="post">
    <div class="card">
        <div class="card-header card-header-sm">
            <?php $this->_e("General Settings"); ?>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="form-row g-3">
		                <?php
		                $ticket_page = $this->GetOption("ticket_page", "");
		                ?>
                        <div class="form-group col-lg mr-3">
                            <div class="row">
                                <div class="col">
                                    <label class="col-form-label" for="ticket_page"><?php $this->_ee("Ticket Page"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                                    <select class="app-select2-picker form-control-sm" id="ticket_page" name="ticket_page">
                                        <option value=""><?php $this->_e("Select Ticket Page"); ?></option>
                                        <?php
                                        $pages = get_pages();
                                        foreach ($pages as $page) {
                                            APBD_GetHTMLOption($page->ID, $page->post_title, $ticket_page);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="col-form-label" for="ticket_page_shortcode"><?php $this->_ee("Shortcode"); ?></label>
                                    <?php
                                    APBD_GetHTMLSwitchButton("ticket_page_shortcode", "ticket_page_shortcode", "N", "Y", $this->GetOption("ticket_page_shortcode",'N'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg">
                            <label class="col-form-label" for="client_role"><?php $this->_ee("Client Role"); ?></label>
			                <?php $options_parent_role = Mapbd_wps_role::FetchAllKeyValue("id", "name");
			                $roles = get_editable_roles();
			                $selected_client_role = $this->GetOption("client_role", 'subscriber');
			                ?>
                            <select class="form-control custom-select form-control-sm" id="client_role" name="client_role">
				                <?php
				                foreach ($roles as $value => $role) {
					                APBD_GetHTMLOption($value, $role['name'], $selected_client_role);
				                }
				                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row g-3 mt-3">
                        <div class="form-group col-lg mr-3">
                            <label class="col-form-label" for="img_url"><?php $this->_ee("Client & Reg. Page Image"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary"
                                            onclick="document.getElementById('img_url').value = ''"
                                            type="button"><?php $this->_e("Clear"); ?></button>
                                </div>
                                <input class="form-control form-control-sm" type="text" maxlength="255"
                                       value="<?php echo esc_attr($this->GetOption("img_url")); ?>" id="img_url" name="img_url"
                                       placeholder="<?php $this->_ee("Img Url"); ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary apd-wp-image-chooser" data-target="#img_url"
                                            type="button"><?php $this->_e("Browse"); ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg">
                            <label class="col-form-label" for="dash_img_url"><?php $this->_ee("Dashboard Image"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary"
                                            onclick="document.getElementById('dash_img_url').value = ''"
                                            type="button"><?php $this->_e("Clear"); ?></button>
                                </div>
                                <input class="form-control form-control-sm" type="text" maxlength="255"
                                       value="<?php echo esc_attr($this->GetOption("dash_img_url")); ?>" id="dash_img_url"
                                       name="dash_img_url" placeholder="<?php $this->_ee("Img Url"); ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary apd-wp-image-chooser" data-target="#dash_img_url"
                                            type="button"><?php $this->_e("Browse"); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row g-3 mt-3">
                        <div class="form-group col-sm mr-3">
                            <label class="col-form-label" for="app_loading_text"><?php $this->_ee("App Loader Text"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>

                                <input class="form-control form-control-sm" type="text" maxlength="255"
                                       value="<?php echo esc_attr($this->GetOption("app_loading_text",'')); ?>" id="app_loading_text" name="app_loading_text"
                                       placeholder="<?php echo get_option('blogname'); ?>">
                            <i class="form-text text-muted">
		                        <?php $this->_e("Keep empty for default") ; ?>
                            </i>

                        </div>
                        <div class="form-group col-sm">

                        </div>
                    </div>
                    <div class="form-group mt-3 mr-3">
                        <label class="col-form-label" for="is_wp_login_reg"><?php $this->_ee("Enable Wordpress Login Register"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_wp_login_reg", "is_wp_login_reg", "N", "Y", $this->GetOption("is_wp_login_reg",'N'),false,"has_depend_fld");
		                ?>
                    </div>
                    <div class="card mt-3 fld-is-wp-login-reg fld-is-wp-login-reg-y">
                        <div class="card-body p-3">
                            <div class="form-group">
                                <label class="col-form-label" for="login_page"><?php $this->_ee("Login Page Link"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                                <input placeholder="<?php echo wp_login_url(); ?>" class=" form-control form-control-sm" value="<?php echo esc_url( $this->GetOption("login_page","") ); ?>" id="login_page" name="login_page"/>
                                <i class="text-muted help-text form-text"><?php $this->_e("Keep blank to get default login page") ; ?></i>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="reg_page"><?php $this->_ee("Registration Link"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                                <input placeholder="<?php echo wp_registration_url(); ?>" class=" form-control form-control-sm" value="<?php echo esc_url( $this->GetOption("reg_page","") ); ?>" id="reg_page" name="reg_page"/>
                                <i class="text-muted help-text form-text"><?php $this->_e("Keep blank to get default registration page") ; ?></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 mr-3">
                        <label class="col-form-label" for="is_wp_profile_link"><?php $this->_ee("Enable Wordpress Profile Link"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_wp_profile_link", "is_wp_profile_link", "N", "Y", $this->GetOption("is_wp_profile_link",'N'),false,"has_depend_fld");
		                ?>
                    </div>
                    <div class="card mt-3 fld-is-wp-profile-link  fld-is-wp-profile-link-y">
                        <div class="card-body p-3">
                            <div class="form-group">
                                <label class="col-form-label" for="wp_profile_link"><?php $this->_ee("WP Profile Link"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                                <input placeholder="<?php echo admin_url("profile.php"); ?>" class=" form-control form-control-sm" value="<?php echo esc_url( $this->GetOption("wp_profile_link","") ); ?>" id="wp_profile_link" name="wp_profile_link"/>
                                <i class="text-muted help-text form-text"><?php $this->_e("Keep blank to get default profile page") ; ?></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="is_seq_track_id">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Enable Sequential Ticket Track ID"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <div class="d-flex align-items-center">
                                    <?php APBD_GetHTMLSwitchButton("is_seq_track_id", "is_seq_track_id", "N", "Y", "N"); ?>
                                    <span class="ml-2 help-text text-muted"><?php $this->_e("To maintain the sequential ticket ID, enable this. For random alpha-numeric, leave it disabled.") ; ?></span>
                                </div>
                            </div>
                        </div>
		            </div>
                    <div class="form-row g-3 mt-3">
                        <div class="form-group col-sm mr-3 apbd-pro-field">
                            <div class="apbd-pro-btn">
                                <label class="col-form-label" for="footer_cp_text">
                                    <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                    <span class="apbd-pro-field-label"><?php $this->_ee( "Footer Copyright Text" ); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></span>
                                </label>
                                <div class="apbd-pro-field-wrap">
                                    <input class="form-control form-control-sm" type="textarea" maxlength="255" value="" id="footer_cp_text" name="footer_cp_text" placeholder="<?php echo esc_attr( sprintf( $this->__( 'Copyright %s © %s' ), '[site_link]', '[year]' ) ); ?>">
                                    <i class="form-text text-muted">
                                        <?php $this->_e( "Keep empty for default." ); ?>
                                        <?php $this->_e( "Dynamic data:" ); ?>
                                        <code style="display: inline-block; margin-bottom: 0.2rem;">[site_title]</code>
                                        <code style="display: inline-block; margin-bottom: 0.2rem;">[site_url]</code>
                                        <code style="display: inline-block; margin-bottom: 0.2rem;">[site_link]</code>
                                        <code style="display: inline-block; margin-bottom: 0.2rem;">[year]</code>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm"></div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="is_hide_cp_text"><?php $this->_ee("Remove Powered By"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_hide_cp_text", "is_hide_cp_text", "N", "Y", $this->GetOption("is_hide_cp_text",'N'));
		                ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="is_public_ticket_opt_on_creation"><?php $this->_ee("Enable public ticket option (on creation)"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_public_ticket_opt_on_creation", "is_public_ticket_opt_on_creation", "N", "Y", $this->GetOption("is_public_ticket_opt_on_creation",'N'));
		                ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="is_public_ticket_opt_on_details"><?php $this->_ee("Enable public ticket option (on details)"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_public_ticket_opt_on_details", "is_public_ticket_opt_on_details", "N", "Y", $this->GetOption("is_public_ticket_opt_on_details",'N'));
		                ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="is_public_tickets_menu"><?php $this->_ee("Enable to show public tickets"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("is_public_tickets_menu", "is_public_tickets_menu", "N", "Y", $this->GetOption("is_public_tickets_menu",'N'));
		                ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="disable_registration_form"><?php $this->_ee("Disable Registration Form"); ?></label>
                        <?php
                        APBD_GetHTMLSwitchButton("disable_registration_form", "disable_registration_form", "N", "Y", $this->GetOption("disable_registration_form",'N'));
                        ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="disable_guest_ticket_creation"><?php $this->_ee("Disable Guest Ticket Creation"); ?></label>
		                <?php
		                APBD_GetHTMLSwitchButton("disable_guest_ticket_creation", "disable_guest_ticket_creation", "N", "Y", $this->GetOption("disable_guest_ticket_creation",'N'));
		                ?>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="close_ticket_opt_for_customer">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Enable ticket close option for customer"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <?php APBD_GetHTMLSwitchButton("close_ticket_opt_for_customer", "close_ticket_opt_for_customer", "N", "Y", "N"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="auto_close_ticket">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Enable auto ticket close"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <?php APBD_GetHTMLSwitchButton("auto_close_ticket", "auto_close_ticket", "N", "Y", "N"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="disable_closed_ticket_reply">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Disable closed ticket reply"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <?php APBD_GetHTMLSwitchButton("disable_closed_ticket_reply", "disable_closed_ticket_reply", "N", "Y", "N"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="show_other_tickets_in_ticket_details_page"><?php $this->_ee("Show other tickets in ticket details page"); ?></label>
                        <?php
                        APBD_GetHTMLSwitchButton("show_other_tickets_in_ticket_details_page", "show_other_tickets_in_ticket_details_page", "N", "Y", $this->GetOption("show_other_tickets_in_ticket_details_page",'N'));
                        ?>
                    </div>
                    <div class="form-group mt-3">
                        <label class="col-form-label" for="hide_ticket_details_info_by_default"><?php $this->_ee("Hide ticket details info by default"); ?></label>
                        <?php
                        APBD_GetHTMLSwitchButton("hide_ticket_details_info_by_default", "hide_ticket_details_info_by_default", "N", "Y", $this->GetOption("hide_ticket_details_info_by_default",'N'));
                        ?>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="disable_ticket_search_by_custom_field">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Disable ticket search by value of custom field"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <?php APBD_GetHTMLSwitchButton("disable_ticket_search_by_custom_field", "disable_ticket_search_by_custom_field", "N", "Y", "N"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 apbd-pro-field">
                        <div class="apbd-pro-btn">
                            <label class="col-form-label" for="disable_auto_scroll_to_latest_response">
                                <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="font-size: 80%;"><?php $this->_e( 'Pro' ); ?></span>
                                <span class="apbd-pro-field-label"><?php $this->_ee("Disable auto-scroll to the latest response on ticket details"); ?></span>
                            </label>
                            <div class="apbd-pro-field-wrap">
                                <?php APBD_GetHTMLSwitchButton("disable_auto_scroll_to_latest_response", "disable_auto_scroll_to_latest_response", "N", "Y", "N"); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="form-row">
                        <div class="form-group col-sm">
                            <label class="col-form-label"><?php $this->_ee("Support Logo"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                            <div data-target="#app_logo" class="apd-wp-image-chooser  border p-3 rounded text-center  h-auto" data-on-select="apbd_on_app_logo_select">
                                <?php $app_logo_remove_icon = ( "" === esc_url( strval( $this->GetOption( "app_logo", "" ) ) ) ) ? " d-none" : ""; ?>
                                <span id="app_logo_remove" class="img-remove-icon<?php echo esc_attr( $app_logo_remove_icon ); ?>"><i class="fa fa-trash"></i></span>
                                <input id="app_logo" type="hidden" value="<?php echo esc_url( $this->GetOption("app_logo","") ); ?>" name="app_logo" />
                                <img id="app_logo_img" class="img-fluid  mh-68" src="<?php echo esc_url( $this->GetOption("app_logo",$this->get_client_url("img/logo.png")) ); ?>" alt="Logo">
                            </div>
                            <i class="form-text text-muted">
                                <?php $this->_e("Click image to change it. Recommended height: 40 px.") ; ?>
                            </i>
                        </div>
                        <div class="form-group col-sm-4 ">
                            <label class="col-form-label w-100 text-center"><?php $this->_ee("Favicon"); ?></label>
                            <div data-target="#app_favicon" class="apd-wp-image-chooser p-3 border rounded  text-center" data-on-select="apbd_on_favicon_select">
                                <?php $app_favicon_remove_icon = ( "" === esc_url( strval( $this->GetOption( "app_favicon", "" ) ) ) ) ? " d-none" : ""; ?>
                                <span id="app_favicon_remove" class="img-remove-icon<?php echo esc_attr( $app_favicon_remove_icon ); ?>"><i class="fa fa-trash"></i></span>
                                <input id="app_favicon" type="hidden" value="<?php echo esc_url( $this->GetOption("app_favicon","") ); ?>" name="app_favicon" />
                                <img id="app_favicon_img" class="img-fluid mh-32 " src="<?php echo esc_url( $this->GetOption("app_favicon",$this->get_client_url("img/favicon180x180.png")) ); ?>" alt="Favicon">
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-sm">
                            <label class="col-form-label" for="is_app_loader"><?php $this->_ee("Enable App Loader"); ?></label>
                            <?php
                            APBD_GetHTMLSwitchButton("is_app_loader", "is_app_loader", "N", "Y", $this->GetOption("is_app_loader",'Y'));
                            ?>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-sm">
                            <label class="col-form-label"><?php $this->_ee("App Loader Image"); ?><?php echo wp_kses_post( $multiLangIndicator ); ?></label>
                            <div data-target="#app_loader" class="apd-wp-image-chooser  border p-3 rounded text-center  h-auto" data-on-select="apbd_on_app_loader_select">
                                <?php $app_loader_remove_icon = ( "" === esc_url( strval( $this->GetOption( "app_loader", "" ) ) ) ) ? " d-none" : ""; ?>
                                <span id="app_loader_remove" class="img-remove-icon <?php echo esc_attr( $app_loader_remove_icon ); ?>"><i class="fa fa-trash"></i></span>
                                <input id="app_loader" type="hidden" value="<?php echo esc_url( $this->GetOption("app_loader","") ); ?>" name="app_loader" />
                                <img id="app_loader_img" class="img-fluid  mh-68" src="<?php echo esc_url( $this->GetOption("app_loader",$this->get_client_url("app-loader.svg")) ); ?>" alt="App Loader">
                            </div>
                            <i class="form-text text-muted">
				                <?php $this->_e("Click image to change it.") ; ?>
                            </i>
                        </div>
                        <div class="form-group col-sm-4 ">

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer pr-3 text-right">
            <button type="submit" class="btn btn-sm btn-success"><?php $this->_e("Save"); ?></button>
        </div>
    </div>
</form>
<script type="text/javascript">
    function apbd_on_favicon_select(attachment){
        try {
            jQuery("#app_favicon_img").attr("src", attachment.url);
            jQuery("#app_favicon_remove").removeClass("d-none").show();
        }catch(e){
            console.log(e.message);
        }
    }
    function apbd_on_app_loader_select (attachment){
       try {
           jQuery("#app_loader_img").attr("src", attachment.url);
           jQuery("#app_loader_remove").removeClass("d-none").show();
       }catch(e){
           console.log(e.message);
       }
    }
    function apbd_on_app_logo_select(attachment){
        try {
            jQuery("#app_logo_img").attr("src", attachment.url);
            jQuery("#app_logo_remove").removeClass("d-none").show();
        }catch(e){
            console.log(e.message);
        }
    }
    jQuery( document ).ready(function( $ ) {
        jQuery("#app_logo_remove").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery("#app_logo_img").attr("src", "<?php echo esc_url( $this->get_client_url( "img/logo.png" ) ); ?>");
            jQuery("input#app_logo").val("");
            $(this).hide();
        });
        jQuery("#app_favicon_remove").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Clicked");
            jQuery("#app_favicon_img").attr("src", "<?php echo esc_url( $this->get_client_url( "img/favicon180x180.png" ) ); ?>");
            jQuery("input#app_favicon ").val("");
            $(this).hide();
        });
        jQuery("#app_loader_remove").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery("#app_loader_img").attr("src", "<?php echo esc_url( $this->get_client_url( "img/favicon180x180.png" ) ); ?>");
            jQuery("input#app_loader ").val("");
            $(this).hide();
        });
    });
</script>

