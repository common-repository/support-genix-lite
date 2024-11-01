<div class="row">
    <div class="col-sm-7 pr-0">
        <div class="card">
            <div class="card-header card-header-sm p-1">
                <div class="d-sm-flex justify-content-sm-between">
                    <div class="form-group  row ml-1">
                        <label class="col-form-label mr-1 control-label" for="envato_status"><?php $this->_ee("Envato"); ?></label>
                        <div class="mt-1">
                            <?php
                            APBD_GetHTMLSwitchButton("envato_status","envato_status","I","A",$this->GetOption("envato_status"),false,"has_depend_fld",'bg-mat','material-switch-sm');
                            ?>
                        </div>
                        <span class="help-text font-italic mt-2 ml-2"><?php $this->_e("Enable it to setup api settings") ; ?></span>
                    </div>
                    <div class="">
                        <button class="btn btn-sm btn-success mt-1 mr-1 fld-envato-status fld-envato-status-i" type="submit"><?php $this->_e("Save") ; ?></button>
                    </div>
                </div>
            </div>
            <div class="card-body pl-3 pr-3 pb-3 pt-2 fld-envato-status fld-envato-status-a ">
                <div class="form-row">
                    <div class="form-group col-sm">
                        <label class="col-form-label" for="env_api_token"><?php $this->_ee("API Token"); ?></label>
                        <input class="form-control form-control-sm" type="text" maxlength="150"
                            value="<?php echo esc_attr( $this->GetOption("api_token") ); ?>"
                            id="env_api_token" name="api_token"
                            placeholder="<?php $this->_ee("API Token"); ?>" data-bv-notempty="true"
                            data-bv-notempty-message="<?php $this->_ee("%s is required", "API Token"); ?>">
                        <span class="text-warning form-text"><?php $this->_e("Generate an API key in your envato account and enter here") ; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col pb-2">
                        <div class="card">
                            <div class="card-header card-header-sm p-2">
                                <?php $this->_e("Option") ; ?>
                            </div>
                            <div class="card-body">
                                <div class="form-group row pl-2">
                                    <div class="col-sm-1 mt-3">
                                        <?php
                                        APBD_GetHTMLSwitchButton("env_is_required","is_required","N","Y",$this->GetOption("is_required","Y"),false,"",'bg-mat','material-switch-xs ');
                                        ?>
                                    </div>
                                    <label for="env_is_required" class="col-sm col-form-label mt-1 ml-1"><?php $this->_ee("Is Required"); ?></label>
                                </div>
                                <hr class="m-0">
                                <div class="form-group row pl-2">
                                    <div class="col-sm-1 mt-3">
                                        <?php
                                        APBD_GetHTMLSwitchButton("env_support_expiry","support_expiry","N","Y",$this->GetOption("support_expiry","Y"),false,"",'bg-mat','material-switch-xs ');
                                        ?>
                                    </div>
                                    <label for="env_support_expiry" class="col-sm col-form-label mt-1 ml-1 "><?php $this->_ee("Check Support Expiry"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header card-header-sm p-2">
                                <?php $this->_e("Display On") ; ?>
                            </div>
                            <div class="card-body">
                                <div class="form-group row pl-2">
                                    <div class="col-sm-1 mt-3">
                                        <?php
                                        APBD_GetHTMLSwitchButton("env_show_in_tckt_form","show_in_tckt_form","N","Y",$this->GetOption("show_in_tckt_form","Y"),false,"",'bg-mat','material-switch-xs ');
                                        ?>
                                    </div>
                                    <label for="env_show_in_tckt_form" class="col-sm col-form-label mt-1 ml-1"><?php $this->_ee("Show in Ticket Form"); ?></label>
                                </div>
                                <hr class="m-0">
                                <div class="form-group row pl-2">
                                    <div class="col-sm-1 mt-3">
                                        <?php
                                        APBD_GetHTMLSwitchButton("env_show_in_reg_form","show_in_reg_form","N","Y",$this->GetOption("show_in_reg_form","N"),false,"",'bg-mat','material-switch-xs ');
                                        ?>
                                    </div>
                                    <label for="env_show_in_reg_form" class="col-sm col-form-label mt-1 ml-1"><?php $this->_ee("Show in Registration Form"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer fld-envato-status fld-envato-status-a">
                <button type="submit" class="btn btn-sm btn-success float-right"><?php $this->_e("Save") ; ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm">
        <div class="card">
            <div class="card-header card-header-sm p-2">
                <strong><?php $this->_e("API Description (Personal API Key [Recommended])") ; ?></strong>
            </div>
            <div class="card-body p-2">
                <div class="card-body">
                    <ol class="p-l-5 pt-2">
                        <li><?php $this->_e("Go to") ; ?> <a class="text-primary app-ins" target="blank" href="https://build.envato.com/">https://build.envato.com/</a></li>
                        <li><?php $this->_e("Sign In and go to") ; ?> <a class="text-primary app-ins" target="blank" href="https://build.envato.com/"><?php $this->_e("My App") ; ?></a></li>
                        <li><?php $this->_e('Then scroll down to heading <br>"Your personal tokens"') ; ?></li>
                        <li><?php $this->_e("Then Click the button named") ; ?> <br><a class="text-primary app-ins" target="blank" href="https://build.envato.com/create-token/"><?php $this->_e("Create a new token") ; ?></a></li>
                        <li><?php $this->_e("Then you will see a") ; ?> <span class="app-popover-html text-primary app-ins added-popov" data-tooltip-position="left" data-trigger="hover" data-custom-content="#form" data-placement="right" data-original-title="" title=""><?php $this->_e("form") ; ?> </span> </li>
                        <li><?php $this->_e("Then please choose minimum") ; ?> <span class="app-popover-html text-primary app-ins added-popov" data-tooltip-position="right" data-trigger="hover" data-custom-content="#form2" data-placement="right" data-original-title="" title=""><?php $this->_e("Permission") ; ?> </span>.<br>
                            <small>
                                <em><?php $this->_e("For some bug of CodeCanyon API it required these permission which is shown in the hover image or") ; ?>
                                    <a class="text-bold btn btn-xs btn-info added-ripples" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><?php $this->_e("Click here") ; ?></a> <?php $this->_e("to show") ; ?>
                                </em>
                            </small>
                            <div class="collapse apbd-clps" id="collapseExample">
                                <ul class="p-l-10">
                                    <li><?php $this->_e("View and search Envato sites") ; ?></li>
                                    <li><?php $this->_e("View your Envato Account username") ; ?></li>
                                    <li><?php $this->_e("View your email address") ; ?></li>
                                    <li><?php $this->_e("View your account profile details") ; ?></li>
                                    <li><?php $this->_e("View your account financial history") ; ?></li>
                                    <li><?php $this->_e("Download your purchased items") ; ?></li>
                                    <li><?php $this->_e("View your items' sales history") ; ?></li>
                                    <li><?php $this->_e("Verify purchases of your items") ; ?></li>
                                    <li><?php $this->_e("List purchases you've made") ; ?></li>
                                    <li><?php $this->_e("Verify purchases you've made") ; ?></li>
                                    <li><?php $this->_e("View your purchases of the app creator's items") ; ?></li>
                                </ul>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>