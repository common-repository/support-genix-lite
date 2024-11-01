<?php /** @var AppsBDBaseModule $this */ ?>
<div class="card apsbd-default-card">
    <div class="card-header pt-0 pb-0 pl-0">
        <div class="row">
            <div class="col">
                <ul class="app-card-tab nav justify-content nav-tabs" id="envatoTab" role="tablist">
                    <li class="nav-item">
                        <a id="envato-integration-link" class="nav-link active show"  data-toggle="tab" href="#envato-integration">
                            <i class="fa fa-sitemap"></i>
                            <?php $this->_e( 'Integration' ); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="envato-login-link" class="nav-link "  data-toggle="tab" href="#envato-login">
                            <i class="fa fa-sign-in"></i>
                            <?php $this->_e( 'Login with Envato' ); ?>
                            <span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash" style="padding: 0.1em 0.4em;"><?php $this->_e( 'Pro' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div id="envato-tab-container" class="tab-content p-0">
            <div class="tab-pane fade active show" id="envato-integration" role="tabpanel" aria-labelledby="envato-integration-link">
                <?php $this->LoadView( 'apbd_wps_envato_system/integration' ); ?>
            </div>
            <div class="tab-pane fade" id="envato-login" role="tabpanel" aria-labelledby="envato-login-link">
                <div class="text-center mt-4 mb-4">
                    <h4 class="text-info mb-3"><?php echo $this->___('To unlock this feature you need %1$s!', '<span style="display: inline-block;">Support Genix Pro</span>') ; ?></h4>
                    <a target="_blank" href="https://supportgenix.com/getpro" class="btn btn-primary"><?php $this->_e("Get Pro Now") ; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
