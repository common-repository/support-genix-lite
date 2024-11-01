<?php /** @var Apbd_wps_settings $this */?>
<form class="apbd-module-form bv-form" role="form" id="apbd-wp-support_AJ_Apbd_wps_settings_recaptcha_v3"
      action="<?php echo esc_url($this->GetActionUrl()) ?>" method="post">
    <div class="col-sm m-0 p-0">
        <div class="card">
            <div class="card-header card-header-sm p-1">
                <div class="d-sm-flex justify-content-sm-between">
                    <div class="form-group  row ml-1">
                        <label class="col-form-label mr-1 control-label"
                               for="recaptcha_v3_status"><?php $this->_ee("reCAPTCHA v3"); ?></label>
                        <div class="mt-1">
                            <?php
                            APBD_GetHTMLSwitchButton("recaptcha_v3_status", "recaptcha_v3_status", "I", "A", $this->GetOption("recaptcha_v3_status", "I"), false, "has_depend_fld", 'bg-mat', 'material-switch-sm');
                            ?>
                        </div>
                        <span class="help-text font-italic mt-2 ml-2"><?php $this->_e("Enable it to setup recaptcha v3 settings"); ?></span>
                    </div>
                    <div class="">
                        <button class="btn btn-sm btn-success mt-1 mr-1 fld-recaptcha-v3-status fld-recaptcha-v3-status-i"
                                type="submit"><?php $this->_e("Save"); ?></button>
                    </div>
                </div>
            </div>
            <div class="card-body pl-3 pr-3 pb-3 pt-2 fld-recaptcha-v3-status fld-recaptcha-v3-status-a ">
                <div class="row pt-2">
                    <div class="col-sm-7">
                        <div class="card">
                            <div class="card-header card-header-sm p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <?php $this->_e("reCAPTCHA v3 Settings"); ?>
                                </div>
                                <div class="text-right">
                                    <?php
                                    APBD_GetHTMLSwitchButtonInline("recaptcha_v3_hide_badge", "recaptcha_v3_hide_badge", "N", "Y", $this->GetOption("recaptcha_v3_hide_badge", "N"), false, "", 'bg-mat', 'material-switch-xs inline');
                                    ?>
                                    <label for="recaptcha_v3_hide_badge"
                                           class="col-form-label p-0"><?php $this->_ee("Hide reCAPTCHA Badge"); ?></label>
                                </div>
                            </div>
                            <card class="body p-2">
                                <div class="form-row">
                                    <div class="form-group col-sm">
                                        <label class="col-form-label"
                                               for="recaptcha_v3_site_key"><?php $this->_ee("Site Key"); ?></label>
                                        <input class="form-control form-control-sm" type="text" maxlength="150"
                                               value="<?php echo esc_attr($this->GetOption("recaptcha_v3_site_key")); ?>"
                                               id="recaptcha_v3_site_key" name="recaptcha_v3_site_key"
                                               placeholder="<?php $this->_ee("Site Key"); ?>" data-bv-notempty="true"
                                               data-bv-notempty-message="<?php $this->_ee("%s is required", "Site Key"); ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm">
                                        <label class="col-form-label"
                                               for="recaptcha_v3_secret_key"><?php $this->_ee("Secret Key"); ?></label>
                                        <input class="form-control form-control-sm" type="password" maxlength="150"
                                               value="<?php echo esc_attr($this->GetOption("recaptcha_v3_secret_key")); ?>"
                                               id="recaptcha_v3_secret_key" name="recaptcha_v3_secret_key"
                                               placeholder="<?php $this->_ee("Secret Key"); ?>" data-bv-notempty="true"
                                               data-bv-notempty-message="<?php $this->_ee("%s is required", "Secret Key"); ?>">
                                    </div>
                                </div>
                            </card>
                        </div>

                    </div>
                    <div class="col-sm pl-0">
                        <div class="form-row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header card-header-sm p-2">
                                        <?php $this->_e("Where to enable in frontend?"); ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row pl-2">
                                            <div class="col-sm-1 mt-3">
                                                <?php
                                                APBD_GetHTMLSwitchButton("captcha_on_login_form", "captcha_on_login_form", "N", "Y", $this->GetOption("captcha_on_login_form", "Y"), false, "", 'bg-mat', 'material-switch-xs ');
                                                ?>
                                            </div>
                                            <label for="captcha_on_login_form"
                                                   class="col-sm col-form-label mt-1 ml-1"><?php $this->_ee("On Login Form"); ?></label>
                                        </div>
                                        <hr class="m-0">
                                        <div class="form-group row pl-2">
                                            <div class="col-sm-1 mt-3">
                                                <?php
                                                APBD_GetHTMLSwitchButton("captcha_on_create_tckt", "captcha_on_create_tckt", "N", "Y", $this->GetOption("captcha_on_create_tckt", "Y"), false, "", 'bg-mat', 'material-switch-xs ');
                                                ?>
                                            </div>
                                            <label for="captcha_on_create_tckt"
                                                   class="col-sm col-form-label mt-1 ml-1 "><?php $this->_ee("On Create Ticket (If not logged in)"); ?></label>
                                        </div>
                                        <hr class="m-0">
                                        <div class="form-group row pl-2">
                                            <div class="col-sm-1 mt-3">
                                                <?php
                                                APBD_GetHTMLSwitchButton("captcha_on_reg_form", "captcha_on_reg_form", "N", "Y", $this->GetOption("captcha_on_reg_form", "Y"), false, "", 'bg-mat', 'material-switch-xs ');
                                                ?>
                                            </div>
                                            <label for="captcha_on_reg_form"
                                                   class="col-sm col-form-label mt-1 ml-1 "><?php $this->_ee("On Registration Ticket"); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer fld-recaptcha-v3-status fld-recaptcha-v3-status-a">
                <button type="submit" class="btn btn-sm btn-success float-right"><?php $this->_e("Save"); ?></button>
            </div>
        </div>
    </div>
</form>