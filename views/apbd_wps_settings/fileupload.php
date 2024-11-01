<?php
/** @var Apbd_wps_settings $this */
$default_type = [ "image", "docs", "text", "pdf" ];
$allowed_type = $this->GetOption( "allowed_type", $default_type );
$allowed_type = ( is_array( $allowed_type ) ? $allowed_type : $default_type );
?>
<form class="apbd-module-form bv-form" role="form" id="apbd-wp-support_AJ_Apbd_wps_settings_file_upload"
      action="<?php echo esc_url($this->GetActionUrl()) ?>" method="post">
    <div class="card">
        <div class="card-header card-header-sm">
            <?php $this->_e("File Upload Settings"); ?>
        </div>
        <div class="card-body pt-2">
            <div class="form-group row ml-1">
                <label class="col-form-label col-sm-3 textright mt-1 mr-1 control-label"
                       for="ticket_file_upload"><?php $this->_ee("Ticket File Upload"); ?><span
                            class="text-danger"> *</span></label>
                <div class="w-auto ml-2 mt-3">
                    <?php
                    APBD_GetHTMLSwitchButton("ticket_file_upload", "ticket_file_upload", "I", "A", $this->GetOption("ticket_file_upload", "A"), false, "", 'bg-mat', 'material-switch-xs');
                    ?>
                </div>
                <span class="col-sm text-secondary font-italic mt-2 ml-2"><?php $this->_e("If you enable it Then user can upload file when they open ticket and reply any ticket"); ?></span>
            </div>
            <hr class="mt-0 mb-2">
            <div class="form-group row pt-1 ml-1">
                <label class="col-form-label textright col-sm-3 mt-1 mr-1"
                       for="file_upload_size"><?php $this->_ee("Max Upload File Size"); ?><span
                            class="text-danger"> *</span></label>
                <div class="col-sm-4">
                    <div class="input-group input-group-sm ">
                        <input class="form-control  col-sm-4 rounded-left" type="text" maxlength="255"
                               value="<?php echo esc_attr($this->GetOption("file_upload_size", 2)); ?>"
                               id="file_upload_size" name="file_upload_size"
                               placeholder="<?php $this->_ee("File Upload Size"); ?>" data-bv-notempty="true"
                               data-bv-notempty-message="<?php $this->_ee("%s is required", "File Upload Size"); ?>">
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control-sm rounded-right"><?php $this->_e("MB"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row pt-1 ml-1">
                <label class="col-form-label text-right col-sm-3 mt-1 mr-1"><?php $this->_ee("Allowed File Types"); ?><span class="text-danger"> *</span></label>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[image]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[image]" name="allowed_type[image]"<?php checked( in_array( "image", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "Photos" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(JPG, JPEG, PNG, WEBP, GIF)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[docs]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[docs]" name="allowed_type[docs]"<?php checked( in_array( "docs", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "Docs" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(DOC, DOCX, XLS, XLSX)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[text]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[text]" name="allowed_type[text]"<?php checked( in_array( "text", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "Text" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(TXT)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[csv]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[csv]" name="allowed_type[csv]"<?php checked( in_array( "csv", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "CSV" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(CSV)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[pdf]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[pdf]" name="allowed_type[pdf]"<?php checked( in_array( "pdf", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "PDF" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(PDF)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[zip]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[zip]" name="allowed_type[zip]"<?php checked( in_array( "zip", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "Zip" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(ZIP)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label pb-0" for="allowed_type[json]" style="display: inline-flex; align-items: center;">
                            <input type="checkbox" value="1" id="allowed_type[json]" name="allowed_type[json]"<?php checked( in_array( "json", $allowed_type ) ); ?>>
                            <span style="margin-left: 7px;"><?php $this->_ee( "JSON" ); ?></span>
                            <span style="font-weight: 400; margin-left: 5px;">(JSON)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-sm btn-success"><?php $this->_e("Save"); ?></button>
        </div>
    </div>
</form>
