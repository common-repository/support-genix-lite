<?php /** @var Mapbd_wps_email_templates $mainobj;*/?>
<div class="clearfix form pb-3">
    <?php
    $params=Mapbd_wps_email_templates::getEmailParamList($mainobj->k_word);
    echo "<pre>";
    unset($params['site_url']);
    unset($params['site_name']);
    foreach ($params as $param=>$title){
        echo '$params["'.$param.'"]="";//'.$title."\n";
    }
    echo "</pre>";
    ?>
</div>
<div class="btn-group-md popup-footer ">
    <div class="clearfix">
        <div class="float-sm-right text-center text-sm-right ">
            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> <?php !empty($isUpdateMode)?$this->_e("Update"):$this->_e("Save"); ?></button>
            <button type="button" class="close-pop-up btn btn-sm  btn-danger"><i class="fa fa-times"></i> <?php $this->_e("Cancel"); ?></button>
        </div>
        <div class="float-sm-left text-center text-sm-left">
		    <?php echo getCustomBackButtion(); ?>
        </div>
    </div>
</div>
