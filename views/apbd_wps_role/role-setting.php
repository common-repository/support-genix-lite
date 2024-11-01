<?php 
		/** @var AppsBDBaseModule $this */
?>
<div class="card">
    <div class="card-header card-header-sm">
        <?php $this->_e("Role Settings") ; ?>
    </div>
    <div class="card-body">
        <div class="form-group col-sm">
            <label for="wp_role_manage" class="col-form-label">
                <?php $this->_ee("WordPress Role Manage"); ?>
            </label>
            <div class="input-group input-group-sm form-row ml-0">
                <input  class="form-control form-control-sm col-sm p-0" type="text" maxlength="100" name="wp_role_manage"   value="<?php echo  esc_attr($this->GetOption("wp_role_manage"));?>" id="wp_role_manage" data-bv-notempty="true" 	data-bv-notempty-message="<?php  $this->_ee("%s is required",__("Role"));?>"   placeholder="<?php $this->_ee("Role");?>" >
                <div class="input-group-append col-sm p-0">
                    <div class="input-group-text text-left">
                        <?php
                        APBD_GetHTMLSwitchButton("role_enable","role_enable","I","A",$this->GetOption("role_enable"),false,"",'bg-mat','material-switch-xs');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>