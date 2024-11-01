<div class="clearfix form-horizontal p-0">
    <?php
    if(empty($mainobj)){
        $mainobj=new Mapbd_wps_debug_log();
        APBD_AddError("Main object has not initialized in controller");
    }

    ?>
    <table class="table table-bordered m-b-0 mt-9-">
        <tr>
            <th><?php $this->_e("Entry Type") ; ?></th>
            <td><?php echo wp_kses_post($mainobj->getTextByKey("entry_type"));?></td>
        </tr>
        <tr>
            <th><?php $this->_e("Title") ; ?></th>
            <td><?php echo esc_html($mainobj->title);?></td>
        </tr>
        <tr>
            <th><?php $this->_e("Log Type") ; ?></th>
            <td><?php echo wp_kses_post($mainobj->getTextByKey("log_type"));?></td>
        </tr>
        <tr>
            <th><?php $this->_e("Status") ; ?></th>
            <td><?php echo wp_kses_post($mainobj->getTextByKey("status"));?></td>
        </tr>
        <tr>
            <th colspan="2"><?php $this->_e("Details Log") ; ?>
                <hr>
                <div contenteditable="true" class="debug-log"><?php !empty($mainobj->log_data)?wp_kses_post(print_r($mainobj->log_data,true)):$this->_e("No Log data");?></div>
            </th>
        </tr>

    </table>

</div>
<div class="btn-group-md popup-footer text-right">
    <button type="button" class="close-pop-up btn btn-sm  btn-danger"><i class="fa fa-times"></i> <?php $this->_e("Cancel"); ?></button>
</div>
