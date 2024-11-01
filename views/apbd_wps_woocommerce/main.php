<?php /** @var Apbd_wps_woocommerce $this */
echo APBD_GetMsg();
?>
<div class="card apsbd-default-card">
    <div class="card-header pt-0 pb-0 pl-0">
        <div class="row">
            <div class="col">
                <ul class="app-card-tab nav justify-content nav-tabs" role="tablist" >
                    <li class="nav-item ">
                        <a id="woocommerce-integration-link" class="nav-link active"  data-toggle="tab" href="#woocommerce-integration" >
                            <i class="fa fa-sitemap"></i>
                            <?php $this->_e("Integrations"); ?>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="woocommerce-settings-link" class="nav-link"  data-toggle="tab" href="#woocommerce-settings" >
                            <i class="fa fa-cogs"></i>
                            <?php $this->_e("Settings"); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="tab-content pt-0" >
            <div class="tab-pane fade active show" id="woocommerce-integration" role="tabpanel" aria-labelledby="woocommerce-integration-link">
                <div class="text-center mt-4 mb-4">
                    <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
                    <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
                </div>
            </div>
            <div class="tab-pane fade" id="woocommerce-settings" role="tabpanel" aria-labelledby="woocommerce-settings-link">
                <div class="text-center mt-4 mb-4">
                    <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
                    <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
