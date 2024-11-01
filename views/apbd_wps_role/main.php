<?php /** @var Apbd_wps_role $this */?>
<div class="card apsbd-default-card">
    <div class="card-header pt-0 pb-0 pl-0">
        <div class="row">
            <div class="col">
                <ul class="app-card-tab nav justify-content nav-tabs" id="settingsTab" role="tablist" >
                    <li class="nav-item">
                        <a id="el-wps-role-list-link" class="nav-link active show"  data-toggle="tab" href="#el-wps-role-list" >
                            <i class=" ap ap-licence"></i>
                            <?php $this->_e("Role List"); ?>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="el-wps-role-access-link" class="nav-link "  data-toggle="tab" href="#el-wps-role-access" >
                            <i class=" <?php echo esc_attr($this->GetMenuIcon()); ?>"></i>
                            <?php $this->_e("Role Access") ; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="card-body p-3">
        <div id="role-tab-container" class="tab-content p-0">


            <div class="tab-pane fade active show" id="el-wps-role-list" role="tabpanel" aria-labelledby="el-wps-role-list-link">

                    <?php $this->LoadView('apbd_wps_role/role-list') ?>

            </div>
            <div class="tab-pane fade " id="el-wps-role-access" role="tabpanel" aria-labelledby="el-wps-role-access-link">
                <?php $this->LoadView('apbd_wps_role/role-access') ?>
            </div>
            <div class="tab-pane fade " id="el-wps-role-setting" role="tabpanel" aria-labelledby="el-wps-role-setting-link">
                <?php $this->LoadView('apbd_wps_role/role-setting') ?>
            </div>

        </div>

    </div>

</div>
