<?php 
		/** @var AppsBDBaseModule $this */
$grid=new jQGrid();
$grid->url =$this->GetActionUrl("data");
$grid->width = "auto";
$grid->height = "auto";
$grid->rowNum = 20;
$grid->pager = "#pagerb";
$grid->container = "#".$this->GetModuleId()." .grid-body";;
$grid->ShowReloadButtonInTitle=true;
$grid->ShowDownloadButtonInTitle=true;
$grid->AddTitleRightHtml('<a data-effect="mfp-move-from-top" class="popupformWR btn btn-xs btn-info" href="'.$this->GetActionUrl("add").'" ><i class="fa fa-plus"></i>'.$this->__('Add New').'</a>');
$grid->AddModelNonSearchable("Categories", "cat_ids", 100 ,"left");
$grid->SetXSCombindeField("cat_ids");
$grid->AddModelNonSearchable("Rule Type", "rule_type", 100 ,"center");
$grid->AddModelNonSearchable("User or Role", "rule_id", 100 ,"center");
$grid->AddModelNonSearchable("Status", "status", 100 ,"center");
$grid->AddModelNonSearchable("Action", "action", 100 ,"center");

?>
<div class="card apsbd-default-card">
    <div class="card-header"><i class="app-mod-icon <?php echo esc_attr($this->GetMenuIcon()); ?>"></i> <?php echo esc_html( $this->GetMenuTitle() ); ?></div>
    <div class="card-body p-3 grid-body min-height-335" >
	    <?php $grid->show();?>
    </div>
</div>
<script type="text/javascript">
 APPSBDAPPJS.core.AddOnOnTabActive("<?php echo esc_attr($this->GetModuleId()) ?>",<?php echo esc_attr( $grid->ResizeMethod()); ?>);

</script>
