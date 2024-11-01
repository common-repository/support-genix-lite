<?php
		/** @var AppsBDBaseModule $this */
$grid=new jQGrid();
$grid->url =$this->GetActionUrl("data");
$grid->width = "auto";
$grid->height = "auto";
$grid->rowNum = 20;
$grid->pager = "#pagerb";
$grid->container = "#role-tab-container";
$grid->ShowReloadButtonInTitle=true;
$grid->ShowDownloadButtonInTitle=true;
$grid->setReloadEvent("el_wps_no_need_evt");
$grid->AddTitleRightHtml('<a data-effect="mfp-move-from-top" class="popupformWR btn btn-xs btn-info" href="'.$this->GetActionUrl("add").'" ><i class="fa fa-plus"></i>'.$this->__('Add New').'</a>');
$grid->AddModelNonSearchable("Name", "name", 100 ,"center");
$grid->SetXSCombindeField("name");
$grid->AddModelNonSearchable("Status", "status", 100 ,"center");
$grid->AddModelNonSearchable("Support Genix Agent", "is_agent", 100 ,"center");
$grid->AddModelNonSearchable("Action", "action", 100 ,"center");
?>
<div class="p-0 grid-body min-height-335">
	    <?php $grid->show();?>
    </div>
<script type="text/javascript">
 APPSBDAPPJS.core.AddOnOnTabActive("<?php echo esc_attr($this->GetModuleId()) ?>",<?php echo esc_attr( $grid->ResizeMethod()); ?>);
 APPSBDAPPJS.core.AddOnCloseLightboxWithEvent("<?php echo esc_attr($this->GetReloadEvent()) ?>",function(){
     APPSBDAPPJS.core.ReloadSiteUrl();
 });
 function APBD_support_genix_role_delete(rdata){
     APPSBDAPPJS.core.CallOnReloadWithEvent("<?php echo esc_attr($this->GetReloadEvent()) ?>");
 }
</script>
