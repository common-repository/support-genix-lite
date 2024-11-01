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
$grid->ShowDownloadButtonInTitle=false;
$grid->DisableAutoInit();
$grid->setReloadEvent($this->GetReloadEvent());
$grid->AddGroupColumn("grp",false);
$grid->AddHiddenProperty("grp");
$grid->AddModelNonSearchable("Title", "title", 150 ,"left");
$grid->SetXSCombindeField("title");
$grid->AddModelNonSearchable("Subject", "subject", 250 ,"left");
$grid->AddModelNonSearchable("Status", "status", 80 ,"center");
$grid->AddModelNonSearchable("Action", "action", 80 ,"center");
?>
<div class="card apsbd-default-card">
    <div class="card-header"><i class="app-mod-icon <?php echo esc_attr($this->GetMenuIcon()); ?>"></i> <?php echo esc_html( $this->GetMenuTitle() ); ?></div>
    <div class="card-body p-3 grid-body min-height-335">
	    <?php $grid->show();?>
    </div>
</div>
<script type="text/javascript">
    APPSBDAPPJS.core.AddOnOnTabActive("<?php echo esc_attr($this->GetModuleId()) ?>",function(){
		<?php echo esc_attr($grid->GetInitMethod()); ?>();
		<?php echo esc_attr( $grid->ResizeMethod()); ?>();
    });
</script>
