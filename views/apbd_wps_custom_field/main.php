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
$grid->AddTitleRightHtml('<a data-effect="mfp-move-from-top" class="ConfirmAjaxWR btn btn-xs btn-warning"  data-msg="'.$this->__("Are you sure to reset order?").'" href="'.$this->GetActionUrl("reset_order").'" ><i class="fa fa-refresh"></i>'.$this->__('Reset Order').'</a>');
$grid->AddModelNonSearchable("Label", "field_label", 150 ,"left");
$grid->SetXSCombindeField("field_label");
$grid->AddModelNonSearchable("Slug", "field_slug", 100 ,"left");
$grid->AddModelNonSearchable("Category", "choose_category", 100 ,"left");
$grid->AddModelNonSearchable("Order", "fld_order", 50 ,"center");
$grid->AddModelNonSearchable("Action", "action", 50 ,"center");
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
