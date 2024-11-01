<?php /** @var Apbd_wps_incoming_webhook $this */
echo APBD_GetMsg();
?>
<div class="card apsbd-default-card">
    <div class="card-header">
        <i class="app-mod-icon <?php echo esc_attr($this->GetMenuIcon()); ?>"></i>
        <?php echo esc_html( $this->GetMenuTitle() ); ?>
    </div>
    <div class="card-body p-3">
        <div class="text-center mt-4 mb-4">
            <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
            <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
        </div>
    </div>
</div>
