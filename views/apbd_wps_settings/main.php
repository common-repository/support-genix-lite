<?php /** @var Apbd_wps_settings $this */?>
<div class="card apsbd-default-card">
    <div class="card-header pt-0 pb-0 pl-0">
        <div class="row">
            <div class="col">
                <ul class="app-card-tab nav justify-content nav-tabs" id="wpsettingsTab" role="tablist" >
                    <li class="nav-item ">
                        <a id="show_general_link" class="nav-link active"  data-toggle="tab" href="#show_general_link_info" >
                            <i class="fa fa-cogs"></i>
                            <?php $this->_e("General"); ?>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="show_ticket_status_link" class="nav-link"  data-toggle="tab" href="#show_ticket_status_info" >
                            <i class="fa fa-ticket"></i>
                            <?php $this->_e("Ticket Statuses"); ?>
                            <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="padding: 0.1em 0.4em;"><?php $this->_e( 'Pro' ); ?></span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="show_file_upload" class="nav-link"  data-toggle="tab" href="#show_file_upload_info" >
                            <i class="fa fa-file"></i>
                            <?php $this->_e("File Upload"); ?>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="show_recaptcha_upload" class="nav-link" data-toggle="tab" href="#show_recaptcha_upload_info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="apbd-svg" viewBox="0 0 383.84 383.84">
                                <path fill="#16a4d8"
                                      d="M383.84,191.92H241.35l-.45-.81c1.53-1.4,3.11-2.75,4.58-4.22,9-9,18-18,27-27,1.37-1.36,2.06-2.42,1.08-4.45-14.14-29.34-37.11-46.86-69.36-52a61.81,61.81,0,0,0-12.3-.74q0-51.35,0-102.68h15c4.31,1.74,9,1.39,13.43,2.08a190.28,190.28,0,0,1,101.12,48.3,194.23,194.23,0,0,1,28.69,32.9l33.68-34.55Z"/>
                                <path fill="#4acffe"
                                      d="M191.92,0q0,51.35,0,102.68V142.4c-1.66.2-2.16-1.18-2.94-2-9.65-9.58-19.3-19.15-28.81-28.87-1.79-1.82-3.12-2.1-5.43-1-29.34,14.44-46.67,37.62-51.47,70.08a110.36,110.36,0,0,0-.7,11.18L0,191.92V177.68c1-6.22,1.8-12.46,2.92-18.66,9.21-51.13,35.14-91.86,76.79-122.67,2.42-1.79,2.67-2.62.45-4.8-10.57-10.42-21-21-31.43-31.55Z"/>
                                <path fill="#cfcccc"
                                      d="M0,191.92l102.59-.06h39.92l.52.93c-1.62,1.49-3.29,2.93-4.85,4.48-8.85,8.82-17.64,17.7-26.54,26.47-1.62,1.59-2.17,2.79-1.12,5.09a88.23,88.23,0,0,0,36.88,40.54,83.21,83.21,0,0,0,41.19,11.7c3.06,0,3.38,1.29,3.37,3.82q-.09,49.47,0,98.95H177.68a3.4,3.4,0,0,0-4.5,0h-1.5c-.08-1.41-1.13-1.37-2.15-1.47a180.57,180.57,0,0,1-52.76-13.91c-32.87-14-59.6-35.73-80.57-64.58-1.69-2.32-2.5-2.24-4.36-.35C22.48,313,13,322.45,3.55,331.86c-1,1-1.79,2.49-3.55,2.5Z"/>
                            </svg>
                            <?php $this->_e("reCAPTCHA v3"); ?>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a id="show_custom_css" class="nav-link"  data-toggle="tab" href="#show_custom_css_info" >
                            <i class="fa fa-file"></i>
                            <?php $this->_e("Customization"); ?>
                            <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="padding: 0.1em 0.4em;"><?php $this->_e( 'Pro' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="tab-content pt-0" id="wpsettingsTabContent">
            <div class="tab-pane fade active show" id="show_general_link_info" role="tabpanel" aria-labelledby="show_general_link">
                <?php $this->LoadView('apbd_wps_settings/general'); ?>
            </div>
            <div class="tab-pane fade" id="show_ticket_status_info" role="tabpanel" aria-labelledby="show_ticket_status_link">
                <div class="text-center mt-4 mb-4">
                    <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
                    <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
                </div>
            </div>
            <div class="tab-pane fade" id="show_file_upload_info" role="tabpanel" aria-labelledby="show_file_upload">
                <?php $this->LoadView('apbd_wps_settings/fileupload'); ?>
            </div>
            <div class="tab-pane fade" id="show_recaptcha_upload_info" role="tabpanel" aria-labelledby="show_recaptcha_upload">
                <?php $this->LoadView('apbd_wps_settings/recaptcha_v3'); ?>
            </div>
            <div class="tab-pane fade" id="show_custom_css_info" role="tabpanel" aria-labelledby="show_custom_css">
                <div class="text-center mt-4 mb-4">
                    <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
                    <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
