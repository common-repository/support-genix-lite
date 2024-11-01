<?php
		/** @var AppsBDBaseModule $this */
$grid=new jQGrid();
$grid->url =$this->GetActionUrl("access-data");
$grid->width = "auto";
$grid->height = "auto";
$grid->rowNum = 200;
$grid->pager = "#pagerb";
$grid->container = "#role-tab-container";
$grid->ShowReloadButtonInTitle=true;
$grid->ShowDownloadButtonInTitle=true;
$grid->AddGroupColumn("group_title",false);
$grid->AddModelNonSearchable("Capabilities", "title", 100 ,"Left");
$grid->SetXSCombindeField("title");
$grid->AddHiddenProperty("group_title");
$roles=Mapbd_wps_role::GetRoleList();
foreach ($roles as $role) {
    $grid->AddModelNonSearchable($role->name,$role->slug,100,"center");
}
?>

    <div class="p-0 grid-body min-height-335">
	    <?php $grid->show();?>
    </div>

<script type="text/javascript">
 APPSBDAPPJS.core.AddOnOnTabActive("<?php echo esc_attr($this->GetModuleId()) ?>",<?php echo esc_attr( $grid->ResizeMethod()); ?>);

</script>
