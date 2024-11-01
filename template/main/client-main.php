<?php /** @var Apbd_wps_settings $this */ ?>
<!DOCTYPE html><html lang=""><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="<?php echo esc_url($this->GetOption("app_favicon",$this->get_client_url("img/favicon32x32.png"))); ?>">
    <link rel="icon" type="image/png" href="<?php echo esc_url($this->GetOption("app_favicon",$this->get_client_url("img/favicon180x180.png"))); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($this->GetOption("app_favicon",$this->get_client_url("img/favicon180x180.png"))); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url($this->GetOption("app_favicon",$this->get_client_url("img/favicon32x32.png"))); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url($this->GetOption("app_favicon",$this->get_client_url("img/favicon16x16.png"))); ?>">
    <title><?php echo esc_html(get_the_title()); ?></title>
	<?php do_action('apbd-wps/action/client-header'); ?>
</head><body><noscript><strong><?php $this->_e("We're sorry but wp-support doesn't work properly without JavaScript enabled. Please enable it to continue") ; ?>.</strong></noscript><div id="support-genix"></div><script src="<?php echo esc_url($this->get_client_url("js/wp-support.js"));?>"></script></body></html>