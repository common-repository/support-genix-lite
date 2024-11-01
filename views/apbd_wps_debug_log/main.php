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
$grid->AddTitleRightHtml('<a data-msg="Are you sure?" class="ConfirmAjaxWR btn btn-xs btn-danger" href="'.$this->GetActionUrl("clean_data").'" ><i class="fa fa-trash"></i>'.$this->__('Clear Log').'</a>');
$grid->AddModelNonSearchable("Log Type", "log_type", 40 ,"center");
$grid->SetXSCombindeField("log_type");
$grid->AddModelNonSearchable("Title", "title", 150 ,"center");
$grid->AddModelNonSearchable("Status", "status", 50 ,"center");
$grid->AddModelNonSearchable("Entry Type", "entry_type", 50 ,"center");
$grid->AddModelNonSearchable("Entry Time", "entry_time", 60 ,"center");


$grid->AddModelNonSearchable("Action", "action", 60 ,"center");

?>
<div class="card apsbd-default-card">
    <div class="card-header"><i class="app-mod-icon <?php echo esc_attr($this->GetMenuIcon()); ?>"></i> <?php echo esc_html( $this->GetMenuTitle() ); ?></div>
    <div class="card-body p-3 grid-body min-height-335">
	    <?php $grid->show();?>
    </div>
</div>
<script type="text/javascript">
 APPSBDAPPJS.core.AddOnOnTabActive("<?php echo esc_attr($this->GetModuleId()) ?>",<?php echo esc_attr( $grid->ResizeMethod()); ?>);

</script>
