<?php
/** @var AppsBDBaseModule $this */

if(empty($cboxWidth)){$cboxWidth=325;}
$cboxWidth=strtolower($cboxWidth)=="auto"?"auto":$cboxWidth."px";
$col_class=!empty($__col_class)?$__col_class:"col-md-6";
if(empty($method)){$method="post";}
if(!isset($isPopupFormMultiPath)){$isPopupFormMultiPath=$this->isMultipartForm();}
if(!isset($formtype)){$formtype="";}
?>

<div id="popup-container" class="mfp-with-anim mfp-dialog  <?php echo esc_attr($col_class);?> clearfix pt-2">
<?php if(!empty($__icon_class)){?><div class="dialog-icon "> <i class="fa <?php echo esc_attr($__icon_class);?>"></i></div><?php }?>
<div class="apbd-lb-container" >
	<div class="lightboxWraper">
		<div class="lightboxWaiting text-center" id="waiting">
            <img src="<?php echo plugins_url("images/lighboxloader.svg",$this->pluginFile); ?>" alt="...">
            <br>
            <h4 data-default-msg="<?php $this->_e("Processing") ; ?>"></h4>
		</div>
	</div>
	<div id="LightBoxBody" class="lightbox-body">
		<?php if(!empty($_title)){?>
			<div class="apd-lg-title">
			<h3><?php echo ($_title);?>
			<?php $bkbtn=APBD_GetValue("bbtn","");
			if(!empty($bkbtn)){
			?>
			<a href="<?php echo esc_url($bkbtn);?>" data-effect="mfp-move-from-top" class="popupformWR btn btn-sm btn-outline-secondary pull-right apbd-lb-backbtn" > <i class="fa fa-angle-double-left"></i> <?php $this->_e("Back") ; ?></a>
			<?php }?>
			</h3>
            <?php if(!empty($_subTitle)){?>           
            <h5 class="p-0 m-t-0"><?php echo esc_html($_subTitle);?></h5>
            <?php }	?>
            <hr class="apbd-lb-hr" />
		</div>
		<?php }?>
		<div class="w-100 pl-3 pr-3">
		    <div class="clearfix apbd-lb-msg"><?php echo APBD_GetMsg();?></div>
        </div>
		<?php if(empty($__disable_form)){?><form class="form app-lb-ajax-form <?php echo esc_attr($formtype);?>" <?php echo wp_kses_post($isPopupFormMultiPath?' data-multipart="true" enctype="multipart/form-data" ':' data-multipart="false" ');?>  action="<?php echo esc_url(APBD_CurrentUrl()); ?>" method="<?php echo esc_attr($method);?>">  <?php }?>
			<div class=" <?php echo !empty($__disable_form)?" form ":""?>">
			  
		 		<?php 
		 			APBD_GetHiddenFieldsHTML();
		 			echo wp_kses_no_null($output); ?>
		 	 
		 	</div>	
		 	<?php if(empty($__disable_form)){?></form>	<?php }?>

        <script type="text/javascript">
	        <?php if(APPSBD_IsPostBack){?>
                APPSBDAPPJS.core.SetAjaxChangeStatus(true);
                <?php if(!empty($_relaod_event)){
                    ?>
                APPSBDAPPJS.core.SetAjaxChangeEvent("<?php echo esc_attr($_relaod_event); ?>");
                    <?php
                } ?>
			<?php }?>
            window.IsValid=true;
			<?php if(!empty($__close_popup_disable)){?>
            $(function(){
                $(".mfp-close").remove();
            });
			<?php }?>
        </script>
	 	</div>
	</div>
</div>
</div>
