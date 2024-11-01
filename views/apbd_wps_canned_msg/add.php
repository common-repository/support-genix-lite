
<?php
	/** @var AppsBDBaseModule $this */
    if(empty($mainobj)){
        $mainobj=new Mapbd_wps_canned_msg();
        $this->AddError("Main object has not initialized in controller");
    }
    $except=array();
    $disabled=array();
?>
<div class="clearfix form pb-3">
    <div class="form-row">
        <div class="form-group col-sm">
            <label class="col-form-label" for="title"><?php $this->_ee("Title"); ?></label>
            <input class="form-control form-control-sm" type="text" maxlength="150"
                   value="<?php echo esc_attr($mainobj->GetPostValue("title")); ?>" id="title" name="title"
                   placeholder="<?php $this->_ee("Title"); ?>" data-bv-notempty="true"
                   data-bv-notempty-message="<?php $this->_ee("%s is required", "Title"); ?>">
        </div>
        <div class="form-group col-sm">
            <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>
            <?php
            APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
            ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-sm">
            <label class="col-form-label" for="canned_msg"><?php $this->_ee("Canned Msg"); ?></label>
            <textarea class="form-control form-control-sm apd-wp-editor h-250px" type="text" id="canned_msg"
                      name="canned_msg"
                      placeholder="<?php $this->_ee("Canned Msg"); ?>"><?php echo wp_kses_post($mainobj->GetPostValue("canned_msg")); ?></textarea>
        </div>
        <div class="col-sm-4">
            <?php
            $paramlist = Mapbd_wps_canned_msg::getParamList(); ?>
            <div class="card border-success mt-69">
                <div class="card-header  bg-success text-white border-success">
                    <?php $this->_e("Properties"); ?>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped m-0 table-sm">
                        <thead>
                        <tr class="">
                            <th class="w120px"></th>
                            <th class="w120px"><?php $this->_e("Property"); ?></th>
                            <th><?php $this->_e("Description"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($paramlist as $key => $des) { ?>
                            <tr>
                                <th><i title="<?php $this->_ee("Click to insert {{%s}} to editor",esc_attr($key)) ; ?>"
                                       class="apbd-editor-insert-btn ap ap-insert app-ins-btn text-green text-bold fz16"
                                       data-tooltip-position="left" data-tooltip-delay="2000"
                                       data-text="{{<?php echo esc_attr($key); ?>}}"></i></th>
                                <th>{{<?php echo esc_html($key); ?>}}</th>
                                <td><?php $this->_e($des); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="btn-group-md popup-footer ">
        <div class="clearfix">

            <div class="float-sm-right text-center text-sm-right ">
                <button type="submit" class="btn btn-sm btn-success"><i
                            class="fa fa-save"></i> <?php !empty($isUpdateMode)?$this->_e("Update"):$this->_e("Save"); ?>
                </button>
                <button type="button" class="close-pop-up btn btn-sm  btn-danger"><i
                            class="fa fa-times"></i> <?php $this->_e("Cancel"); ?></button>
            </div>
            <div class="float-sm-left text-center text-sm-left">
                <?php echo getCustomBackButtion(); ?>
            </div>
        </div>
    </div>