
<?php
	/** @var AppsBDBaseModule $this */
    if(empty($mainobj)){
        $mainobj=new Mapbd_wps_custom_field();
        $this->AddError("Main object has not initialized in controller");
    }
    $except=array();
    $disabled=array();
?>
<div class="clearfix form pb-3">
    <?php
    $mainobj->GetAddForm();
    ?>
</div>
<div class="btn-group-md popup-footer text-right">
    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> <?php !empty($isUpdateMode)?$this->_e("Update"):$this->_e("Save"); ?></button>
    <button type="button" class="close-pop-up btn btn-sm  btn-danger"><i class="fa fa-times"></i> <?php $this->_e("Cancel"); ?></button>
</div>


