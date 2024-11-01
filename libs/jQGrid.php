<?php

/**
 * Class jQGrid
 * Copyright is reserved by appsbd.com
 * Do not use without permission
 */
class jQGrid{
	static $counter=0;
	static $isShowCalledOnce=false;
	static $botostrapVersion=4;
	private $GridId="";
	public $IsCSVDownload=false;
	public $width="100%";
	public $height="400px";
	public $url="";
	public $datatype= "json";
	public $colNames="";
	public $afterInsertRow="";
	public $container="";
	public $multiselect=false;
	public $multisearch=false;
	private $MultiSearchOperator=array();
	public $ShowAdvanceSearch=false;
	public $isColumnChoseable=false;
	/**
	 * Enter description here ...
	 * @var GridModel
	 */
	public $colModel=array();
	public $rowNum=15;
	public $rowList=array(5,10,20,50,100,200);
	public $mtype="POST";
	public $caption=null;
	public $rownumbers= true;
	public $pager='';
	public $sortname='';
	public $viewrecords= true;
	public $sortorder="asc" ;
	public $jsonReader;
	public $autowidth=false;
	public $SRCObj;
	public $searchbtn="";

	public $loadComplete;
	private $onLoadFunc='';
	public $OnSelectAll;
	public $onInitGrid;
	public $hidecaption=true;
	public $hidegrid=true;
	public $shrinkToFit=true;
	public $postData=null;
	public $CustomSearchOnTopGrid=true;
	public $ShowDefaultSearch=false;
	public $emptySetText="No Record Found";
	public $TotalColumn=0;
	public $isShowNoRecordMsg=true;
	public $IsColAutoWidth=false;
	public $minWidth=200;
	public $rightPadding=0;
	public $ShowReloadButtonInTitle=false;
	public $ShowDownloadButtonInBottom=false;
	public $ShowDownloadButtonInTitle=false;
	public $DownloadFileName="";
	public $TextwDownloadButtonInTitle="";
	public $TextReloadButtonInTitle="";
	public $auto_height_records=14;


	public $toolbar=array(false,"top");
	public $toolbarControl="";
	public $toolbarHeight=null;
	public $toolbarCSS="";
	public $attach_form="";


	public $isForBootstrap=true;

	private $TitleRightHtml="";
	private $custom_searchoption=array();
	private $CustomPostArray=array();
	private $tempIndex=array();
	private $jsMethods=array();
	public $beforeSelectRow="";
	private $leftButtons="";
	private $modelTooltips=array();
	public $hasDefaultvalue=false;
	private $modeltooltopPlacement="bottom";
	private static $isLoadedCSSJS=false;
	private $hidecols=array("xs"=>array(),"sm"=>array());
	private $set_javascript_console_log=false;
	private $xs_combind_field="";
	private $xs_combind_field_auto=true;
	public  $visible_fields=array();
	public  $custom_hidden_fields=array();
	private $isDisableAutoInit=false;
	private $isDisabledOnCloseEventCall=false;
	public static $_recordtext="View {0} - {1} of {2}";
	public static $_emptyrecords="";
	public static $_loadtext='<i class="fa fa-spinner fa-spin"></i> Loading...';
	public static $_pgtext='"Page {0} of {1}"';

	private static $tran_method;
	private $_set_reload_event="";


	function __construct($gid=""){
		if(!self::$isLoadedCSSJS){
			self::$isLoadedCSSJS=true;
		}
		$this->emptySetText=$this->__("No Record Found");
		$this->TextwDownloadButtonInTitle=$this->___("%s CSV",'<span class="d-none d-sm-inline">'.$this->__("Download").'</span>');
		$this->TextReloadButtonInTitle=$this->__("Reload");
		$this->jsonReader=new stdClass();
		$this->jsonReader->root="rowdata";
		$this->jsonReader->repeatitems= false;
		$this->jsonReader->id=0;
		if(!empty($gid)){
			$this->GridId=$gid;
		}else{
			$this->GridId="tab".(self::$counter)."_".time();
		}
		self::$counter++;
		$this->postData=new stdClass();
		$this->CustomPostArray['jsf']=array();
		$this->CustomPostArray['value']=array();

		$this->groupingView = new stdClass();
		$this->groupingView->groupField=array();
		$this->groupingView->groupColumnShow=array();
	}

	/**
	 * @param int $botostrapVersion
	 */
	public static function setBotostrapVersion( $botostrapVersion ) {
		self::$botostrapVersion = $botostrapVersion;
	}

	/**
	 * @param callable $_method
	 */
	public static function setTranslatorMethod( $_method ) {
		self::$tran_method = $_method;
	}

	function DisableAutoInit(){
		$this->isDisableAutoInit=true;
	}
	function SetJavascriptLog($status){
		$this->set_javascript_console_log=$status;
	}
	function AddGroupColumn($columnProperty,$isShowColumn=false){
		$this->rownumbers=true;
		$this->grouping=true;
		$this->groupingView->groupField[]= $columnProperty;
		$this->groupingView->groupColumnShow[]=$isShowColumn;
	}
	function SetXSCombindeField($Property){
		$this->xs_combind_field_auto=false;
		$this->xs_combind_field=$Property;
	}
	function addModelTooltips(array $tooltips,$placement="bottom"){
		$this->modelTooltips=$tooltips;
		$this->modeltooltopPlacement=$placement;
	}
	function getButtonClass(){
		return self::$botostrapVersion==3 ? "btn btn-xs ":"btn btn-sm";
	}
	function __($string, $parameter = null, $_ = null) {
		$args=func_get_args();
		if(is_callable(self::$tran_method)){
			return call_user_func_array(self::$tran_method,$args);
        }else{
			return call_user_func_array("sprintf",$args);
        }
	}
	function _e($string, $parameter = null, $_ = null) {
		$args = func_get_args();
		echo call_user_func_array( [ $this, '__' ], $args );

	}
	function DisplayTooltip(){
		if(count($this->modelTooltips)==0)return;
		?>
		try{
		<?php
		foreach ($this->modelTooltips as $key=>$tooltip){
			if(isset($this->tempIndex[$key])){
				$gridid=$this->GetGridId()."_".$key;
				?>
				$("<?php echo esc_attr($gridid);?>").addClass("gs-col-tooltop").attr("title", "<?php echo addslashes($tooltip);?>");
				<?php
			}
		}
		?>
		$('.gs-col-tooltop').tooltip({container:'body',placement:'<?php echo esc_attr($this->modeltooltopPlacement);?>'});
		}catch(e){

		}
		<?php

	}
	function __call($func,$arg){
		if($func=="AddSearhProperty"){
			call_user_func_array(array($this, "AddSearchProperty"), $arg);
		}
	}
	function AddResponsiveHidden($col_id,$bootstrapSizeKey="xs"){
		if(!isset($this->hidecols[$bootstrapSizeKey])){
			return;
		}
		$hmodels=explode(",", $col_id);
		foreach ($hmodels as $hc){
			$this->hidecols[$bootstrapSizeKey][]=$hc;
		}
	}
	function AddTitleLeftHtml($str){
		$this->leftButtons.=$str." ";
	}
	function AddTitleRightHtml($str){
		$this->TitleRightHtml.=$str." ";
	}
	function AddMultisearchOperator($property){
		$this->MultiSearchOperator[]=$property;
	}
	private function GetJson($isPrint=true){
		$colNames=array();
		foreach ($this->colModel as $cm){
			array_push($colNames, $cm->Title);
		}
		$this->colNames=$colNames;
		foreach ($this->CustomPostArray['jsf'] as $key=>$cp){
			$this->postData->$key="";
		}
		foreach ($this->CustomPostArray['value'] as $key=>$cp){
			$this->postData->$key=$cp;
		}
        if($isPrint){
            echo wp_json_encode($this);
        }else {
            return wp_json_encode($this);
        }
	}

	function GetGridId($noHash=false){
		if($noHash){
			return $this->GridId;
		}else{
			return "#".$this->GridId;
		}
	}
	function AddCustomPostData($key,$strFun,$isJavascriptFunc=false){
		if($isJavascriptFunc){
			$this->CustomPostArray['jsf'][$key]=$strFun;
		}else{
			$this->CustomPostArray['value'][$key]= $strFun;
		}
	}
	function ReloadMethod(){
		return "Grid_".$this->GridId."_reload";
	}
	function GetInitMethod(){
		return $this->GridId."_init_grid";
	}
	function CallInitMethod(){
		echo esc_attr($this->GetInitMethod()."();\n");
	}
	function ResizeMethod(){
		return $this->GridId."_ResizeGrid";
	}
	function DownloadCSVMethod(){
		return "Grid_".$this->GridId."_download";
	}
	function AddCustomPropertyOfModel($index,$property,$value){
		if(isset($this->tempIndex[$index])){
			$rowid=$this->tempIndex[$index];
			$this->colModel[$rowid]->$property=$value;
		}
	}
	function SetDefaultValue($name,$default,$default2=''){
		$this->hasDefaultvalue=true;
		$this->AddCustomPropertyOfModel($name, "dvalue", $default);
		$this->AddCustomPropertyOfModel($name, "dvalue2", $default2);
	}
	function ExtendModel($name,$jsonString){
		$this->custom_searchoption[$name]=$jsonString;
	}
	function AddModelCSSClass($name,$classStr){
		if(!empty($this->tempIndex[$name])){
			$rowid=$this->tempIndex[$name];
			$this->colModel[$rowid]->classes=$classStr;
		}
	}
	function addCustomHiddenFields($keyes){
		if(is_array($keyes)){
			foreach ($this->colModel as &$obj){
				if(in_array($obj->index,$keyes)){
					$obj->viewable=false;
					$this->custom_hidden_fields[]=$obj->index;
				}
			}
		}
	}
	function BeforeSelectRow($jsmethod){
		$this->beforeSelectRow=$jsmethod;
	}
	private function checkViewable() {
		foreach ($this->colModel as &$obj){
			if(!$obj->viewable && !in_array($obj->index,$this->custom_hidden_fields)){
				$this->custom_hidden_fields[]=$obj->index;
			}
		}

	}
	function show($srcJquerySelector=""){
		$this->checkViewable();
		$https=isset($_SERVER['HTTPS'])?sanitize_text_field($_SERVER['HTTPS'] ):'';
		$base_url =  (($https== "on") ?  "https" : "http");
		$base_url.=  "://".sanitize_text_field($_SERVER['HTTP_HOST']);
		$base_url.=  str_replace(basename(sanitize_text_field($_SERVER['SCRIPT_NAME'])),"",sanitize_text_field($_SERVER['SCRIPT_NAME']));
		$permanent_unique_id="ag_".hash("crc32b",sanitize_text_field($_SERVER['REQUEST_URI'])."_".sanitize_text_field($_SERVER['REQUEST_METHOD'])."_".str_replace($base_url,"",$this->url));

		if($this->ShowReloadButtonInTitle || $this->ShowDownloadButtonInTitle){
			$this->hidecaption=false;
		}
		if(empty($this->DownloadFileName)){
			global $pageTitle;
			$this->DownloadFileName=!empty($this->caption)?$this->caption:"download_";
		}
		if($this->IsColAutoWidth){
			foreach ($this->colModel as &$cmodel){
				$cmodel->shrinktofit=true;
			}
		}

		$tableId=$this->GridId;
		$addedSpanEnd=false;
		if(!empty($this->ShowReloadButtonInTitle) || !empty($this->ShowDownloadButtonInTitle)){
			$reload=$this->ShowReloadButtonInTitle?'<a onclick="'.($this->ReloadMethod()).'()" class="'.$this->getButtonClass().' btn-primary" ><i class="fa fa-refresh"></i> '.$this->TextReloadButtonInTitle.'</a> ':"";
			$download=$this->ShowDownloadButtonInTitle?'<a onclick="'.($this->DownloadCSVMethod()).'()" class="'.$this->getButtonClass().' btn-success" ><i class="fa fa-download"></i> '.$this->TextwDownloadButtonInTitle.'</a>':"";

			$this->caption.='</span><span class="gridtitle left jd-fl">'.$this->leftButtons." ".$reload.'</span>';
			$this->AddTitleRightHtml($download);
			$addedSpanEnd=true;
		}elseif(!empty($this->leftButtons)){
			$this->caption.='</span><span  class="gridtitle left jd-fl">'.$this->leftButtons.'</span>';
		}
		$this->AddTitleRightHtml('<span data-gridid="mc_'.$tableId.'" class="full-screen '.$this->getButtonClass().' btn-info"><i class="fa fa-expand "></i></span>');
		if(!empty($this->TitleRightHtml)){
			if(!$addedSpanEnd){
				$this->caption.='</span>';
			}
			$this->caption.='<span class="gridtitle text-right ">&nbsp;'.$this->TitleRightHtml;

		}
		$srcDivId="src_$tableId";
		$this->pager="#pager_".$tableId;
		if(!empty($srcJquerySelector)){
			$this->searchbtn=$srcJquerySelector;
		}
		if($this->CustomSearchOnTopGrid){
			if($this->HasSearchProperty()){
				$this->GetCustomSearch();
			}
		}
		?>
		<script type="text/javascript">
            var is_init_<?php echo esc_attr($tableId);?>=false;
            var sbtnclick_<?php echo esc_attr($tableId);?>=false;
            var is_resized_<?php echo esc_attr($tableId);?>=false;
            var minwidth_<?php echo esc_attr($tableId);?>=<?php echo esc_attr($this->minWidth);?>;
			<?php if(!self::$isShowCalledOnce){
			self::$isShowCalledOnce=true;
			?>
            function app_grid_log(e){
				<?php if($this->set_javascript_console_log){?>
                if (typeof console != "undefined" && typeof console.log==="function") {
                    console.log(e);
                }
				<?php } ?>
            }
            function col_cellattr(ts, rowId, tv, rawObject, cm, rdata) {
                if(rawObject.name=="action" || rawObject.name=="action2"){
                    return ' title=" "';
                }
            }
			<?php }?>
            jQuery(function ($){

                var srcDivId="#<?php echo esc_attr($srcDivId)?>";
                jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").change(function(){
                    if(jQuery("#autosearch_<?php echo esc_attr($tableId);?>").is(':checked')){
                        Grid_<?php echo esc_attr($tableId);?>_custom_reload();
                    }
                });
                var timeoutHnd_<?php echo esc_attr($tableId);?>=null;
                jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").keydown(function(e){
                    var s=jQuery(this).val();
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if((s.length==1 && code==8)||jQuery("#autosearch_<?php echo esc_attr($tableId);?>").is(':checked')){
                        if(timeoutHnd_<?php echo esc_attr($tableId);?>)clearTimeout(timeoutHnd_<?php echo esc_attr($tableId);?>);
                        timeoutHnd_<?php echo esc_attr($tableId);?> = setTimeout(Grid_<?php echo esc_attr($tableId);?>_reload,500);
                    }else if(code==13){
                        Grid_<?php echo esc_attr($tableId);?>_custom_reload();
                    }
                });


                jQuery("#autosearch_<?php echo esc_attr($tableId);?>").click(function(){
                    if(jQuery("#autosearch_<?php echo esc_attr($tableId);?>").is(':checked')){
                        jQuery("#<?php echo esc_attr($srcDivId)?> .srcButton").hide();
                        Grid_<?php echo esc_attr($tableId);?>_reload();
                    }else{
                        jQuery("#<?php echo esc_attr($srcDivId)?> .srcButton").show();
                    }
                });

                jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").change(function(e){
                    SetSearchOption_<?php echo esc_attr($srcDivId)?>();
                });
                jQuery("#mc_<?php echo esc_attr($tableId);?>.gs-jq-grid").on("click",".full-screen:not(.exit-full-screen)",function(e){
                    $('body').addClass("f-screen").addClass("s-note-fs");
                    e.preventDefault();
                    $(this).find(".fa-expand").removeClass("fa-expand").addClass("fa-compress");

                    var panelid=$(this).data("gridid");
                    var gridh=jQuery("#<?php echo esc_attr($tableId);?>").getGridParam("height");
                    $(this).attr("lasth",gridh);

                    if(jQuery("<?php echo esc_attr($this->container);?>").length>0) {
                        jQuery("<?php echo esc_attr($this->container);?>").addClass("grid-panel-full-screen");
                    }else{
                        $("#"+panelid).addClass("grid-panel-full-screen")
                    }
                    $(this).addClass("exit-full-screen");
                    var wheight=$(window).height();
                    var wwidth=$(window).width();
                    var offset= jQuery("<?php echo esc_attr($this->pager);?>").height();
                    if(offset<=0){
                        offset=130;
                    }else{
                        offset+=140;
                    }
                    jQuery("#<?php echo esc_attr($tableId);?>").setGridWidth(wwidth-35);
                    jQuery("#<?php echo esc_attr($tableId);?>").setGridHeight(wheight-offset);

                });
                jQuery("#mc_<?php echo esc_attr($tableId);?>.gs-jq-grid").on("click",".exit-full-screen",function(e){
                    e.preventDefault();
                    $('body').removeClass("f-screen").removeClass("s-note-fs");
                    $(this).find(".fa-compress").removeClass("fa-compress").addClass("fa-expand");
                    var panelid=$(this).data("gridid");
                    if(jQuery("<?php echo esc_attr($this->container);?>").length>0) {
                        jQuery("<?php echo esc_attr($this->container);?>").removeClass("grid-panel-full-screen");
                    }else{
                        $("#"+panelid).removeClass("grid-panel-full-screen")
                    }
                    $(this).removeClass("exit-full-screen");
                    var lastheight= $(this).attr("lasth");
                    $(this).removeAttr("lasth");
                    jQuery("#<?php echo esc_attr($tableId);?>").setGridHeight(lastheight);
                    try{
                        jQuery("<?php echo esc_attr($this->container);?>").removeClass('grid-full-screen');
                    }catch(e){}
					<?php echo esc_attr($tableId);?>_ResizeGrid();

                });
				<?php if($this->CustomSearchOnTopGrid){?>
                SetSearchOption_<?php echo esc_attr($srcDivId)?>();
				<?php } ?>
            });

            function SetSearchOption_<?php echo esc_attr($srcDivId)?>(){
                try{APPSBDAPPJS.datetimepicker.UnsetDateGridPicker();}catch(e){}
                var stype=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList option:selected").attr("stype");
                var slectedSearchOption=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList option:selected");
                if(stype=="select"){
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").hide();

                    var selectOption=slectedSearchOption.attr("data");
                    selectOption=jQuery.parseJSON(atob(selectOption));
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue option").remove();
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").append('<option value=""> Select '+slectedSearchOption.text()+'</option>');
                    for(var i in selectOption){
                        jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").append("<option value='"+i+"'>"+selectOption[i]+"</option>");
                    }
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").removeClass("hidden").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_form").addClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").show();

                }else if(stype=="date" || stype=="dateonly" || stype=="time" || stype=="datetime" ){
                    jQuery("#<?php echo esc_attr($srcDivId)?>_from .gs-date-picker-grid-options").attr("data-type",stype);
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").addClass("hidden").addClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_form").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_from").removeClass("hidden").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_to").addClass("hidden").addClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").show();
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").hide();
                    try{
                        APPSBDAPPJS.datetimepicker.SetDateGridPicker();
                    }catch(e){
                    }

                }else if(stype=="daterange"|| stype=="datetimerange" || stype=="timerange" ){
                    jQuery("#<?php echo esc_attr($srcDivId)?>_form").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").addClass("hidden").addClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_from .gs-date-picker-grid-options").attr("data-type",stype);
                    jQuery("#<?php echo esc_attr($srcDivId)?>_to .gs-date-picker-grid-options").attr("data-type",stype);
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").addClass("hidden");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_from").removeClass("hidden").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_to").removeClass("hidden").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").show();
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").hide();
                    try{
                        APPSBDAPPJS.datetimepicker.SetDateGridPicker();
                    }catch(e){

                    }

                }else{
                    jQuery("#<?php echo esc_attr($srcDivId)?>_from").addClass("hidden").removeAttr("data-type",stype);
                    jQuery("#<?php echo esc_attr($srcDivId)?>_to").addClass("hidden").addClass("d-none").removeAttr("data-type",stype);
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").removeClass("hidden");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_text").removeClass("hidden").removeClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?>_form").addClass("d-none");
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").show();

                    if(slectedSearchOption.val()!=""){
                        jQuery("#<?php echo esc_attr($srcDivId)?>_text .srcTextValue").attr("placeholder","Write "+slectedSearchOption.text()+" here ..").prop("disabled",false).show().focus();
                    }else{
                        jQuery("#<?php echo esc_attr($srcDivId)?>_text .srcTextValue").attr("placeholder","Select Search Property first").prop("disabled",true).show();
                    }
                    jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").hide();
                }
                if(jQuery("#autosearch_<?php echo esc_attr($tableId);?>").is(':checked')){
                    Grid_<?php echo esc_attr($tableId);?>_custom_reload();
                }

            }

            function <?php echo esc_attr($this->DownloadCSVMethod())?>(){
                var stype=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList option:selected").attr("stype");
                var data = jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("getGridParam", "postData");
                data.download_csv = true;
                data.searchOper = "eq";
                if(stype=="select"){
                    data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").val();
                }else if(stype=="date"||stype=="daterange"){
                    data.searchString = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_from .srcFrom").val();
                    data.toString = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_to .srcTo").val();
                    data.searchOper = "bt";
                }else{
                    data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").val();
                    data.searchOper = "eq";
                }
                data.searchField = jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val();
                data._search = true;
                data.searchString=(typeof (data.searchString)=="undefined")?"":data.searchString;
                data.searchField = jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val();
                data.searchField=(typeof (data.searchField)=="undefined")?"":data.searchField;
                data._search = true;

                if(jQuery("#difrm").length==0){
                    jQuery("body").append("<iframe id='difrm' class='jd-iframe-dl'></iframe>");
                }
                serialize = function(obj) {
                    var str = [];
                    for(var p in obj)
                        if (obj.hasOwnProperty(p)) {
                            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        }
                    return str.join("&");
                }
				<?php
				$cols=array();
				foreach ($this->colModel as $col){
					if(!empty($col->hidden))continue;
					$cols[$col->name]=$col->Title;
				}?>
                data.cols="<?php echo base64_encode(json_encode($cols));?>";
				<?php
				$filename=$this->DownloadFileName;
				$filename=preg_replace('#<(.*?)>(.*?)</(.*?)>#', '_',$filename);
				$filename=preg_replace('/<.*?>/', '_',$filename);
				$filename=preg_replace('#_.*?#', '',$filename);
				$p=@strpos($filename, "_");
				if($p>=0){

				}


				?>
                data.filename="<?php echo esc_url($filename);?>";
                var durl="<?php echo esc_url($this->url.(strpos($this->url,"?")?"&":"?"));?>"+serialize(data);
                jQuery("#difrm").attr("src",durl);
            }
            function Grid_<?php echo esc_attr($tableId);?>_custom_reload(){
                var IsMultiSearch=<?php echo esc_attr($this->multisearch?"true":"false");?>;
                var data = jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("getGridParam", "postData");
                data.first=true;data.download_csv = false;
                data.isMultiSearch=IsMultiSearch;
                if(IsMultiSearch){
                    data.ms=jQuery("#ms_<?php echo esc_attr($tableId);?>").serialize();
                    try{
                        if(window.Base64){
                            data.ms=Base64.encode(data.ms);
                        }
                    }catch(e){
                        app_grid_log(e.message);
                    }
                }
                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("setGridParam", { "postData": data });
                Grid_<?php echo esc_attr($tableId);?>_reload();
                sbtnclick_<?php echo esc_attr($tableId);?>=false;
            }
            function Grid_<?php echo esc_attr($tableId);?>_reset_custom_reload(){
                jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").val('');
                jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val('').trigger("change");
                Grid_<?php echo esc_attr($tableId);?>_custom_reload();
            }
            function Grid_<?php echo esc_attr($tableId);?>_advance_search(){
                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid('searchGrid');
                $("body > .ui-widget-overlay").prependTo(".gs-jq-grid ");
                $("#searchhdfbox_<?php echo esc_attr($this->GetGridId(true));?>").addClass("alert-info");
                $("#searchmodfbox_<?php echo esc_attr($this->GetGridId(true));?>").addClass("jqgrid-input");
                $("#searchmodfbox_<?php echo esc_attr($this->GetGridId(true));?> .ui-jqdialog-title").html('<i class="fa fa-search"></i> <?php $this->_e("Advance Search") ; ?>');
                $("#searchmodfbox_<?php echo esc_attr($this->GetGridId(true));?>").prependTo(".gs-jq-grid ").css("display","block");




            }
            function Grid_<?php echo esc_attr($tableId);?>_reload(){
                try {
	                <?php echo esc_attr($tableId);?>_ResizeGrid();
                }catch(e){}
                var stype=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList option:selected").attr("stype");
                var data = jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("getGridParam", "postData");
                if(stype=="select"){
                    data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").val();
                }else if(stype=="date"||stype=="daterange"){
                    data.searchString = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_from .srcFrom").val();
                    data.toString = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_to .srcTo").val();
                    data.searchOper = "bt";
                }else{
                    data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").val();
                    data.searchOper = "eq";
                }
                data.searchField = jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val();
                data._search = true;

                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("setGridParam", { "postData": data });
                jQuery("<?php echo esc_attr($this->GetGridId());?>").trigger("reloadGrid");
                data.first=false;
                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("setGridParam", { "postData": data });

            }
            var config_<?php echo esc_attr($tableId);?>=<?php $this->GetJson(true);?>;
            jQuery(function($){
                try{
                    jQuery.extend($.jgrid,{
                        defaults : {
                            recordtext: "<?php $this->_ee("View %s - %s of %s","{0}","{1}","{2}"); ?>",
                            emptyrecords: "",//No records to view deleteted by sarwar
                            loadtext: '<i class="fa fa-spinner fa-spin"></i> <?php $this->_e("Loading") ; ?>...',
                            pgtext : "<?php $this->_ee("Page %s of %s","{0}","{1}"); ?>"
                        }});
                }catch(e){
                    gcl(e);
                }
                //end language
                <?php
				if(!$this->isDisableAutoInit){
				?>
				<?php echo esc_attr($tableId);?>_init_grid();
				<?php }?>
            });
            function check_all_visible_<?php echo esc_attr($tableId);?>(){
				<?php
				if(!$this->isColumnChoseable){
				?>
                return ;
				<?php
				}else{
				?>
                try{
                    var cols_hidden=localStorage.getItem("<?php echo esc_attr($permanent_unique_id); ?>");

                    if(cols_hidden){
                        cols_hidden=JSON.parse(cols_hidden);
                        if(typeof cols_hidden=="object"){
                            config_<?php echo esc_attr($tableId);?>.custom_hidden_fields=cols_hidden;
                        }
                    }else{
                        localStorage.setItem("<?php echo esc_attr($permanent_unique_id); ?>",JSON.stringify(config_<?php echo esc_attr($tableId);?>.custom_hidden_fields));
                    }
                }catch(e){

                }
                var models=config_<?php echo esc_attr($tableId);?>.colModel;
                for(var i in models) {
                    var isChecked=check_visible_<?php echo esc_attr($tableId);?>(models[i].index);
                    if(models[i].Title) {
                        append_into_checklist_<?php echo esc_attr($tableId);?>(models[i].index, models[i].Title, isChecked);
                        if (isChecked) {
                            jQuery("#<?php echo esc_attr($tableId);?>").showCol(models[i].index);
                        }else{
                            jQuery("#<?php echo esc_attr($tableId);?>").hideCol(models[i].index);
                        }
                    }
                }
	            <?php echo esc_attr($tableId);?>_processResize();
	            <?php  } ?>
            }
            function check_visible_<?php echo esc_attr($tableId);?>(index){
                var custom_visible=config_<?php echo esc_attr($tableId);?>.custom_hidden_fields;
                if(custom_visible.indexOf(index) > -1){
                    return false;
                }
                return true;
            }
            function show_coll_<?php echo esc_attr($tableId);?>(index){
                if(config_<?php echo esc_attr($tableId);?>.custom_hidden_fields.indexOf(index) > -1){
                    jQuery("#<?php echo esc_attr($tableId);?>").hideCol(index);
                    return;
                }
                jQuery("#<?php echo esc_attr($tableId);?>").showCol(index);
            }
            function set_unset_key_<?php echo esc_attr($tableId);?>(index,isSet){

                if(isSet) {
                    if (config_<?php echo esc_attr($tableId);?>.custom_hidden_fields.indexOf(index) > -1) {
                        return;
                    }
                    config_<?php echo esc_attr($tableId);?>.custom_hidden_fields.push(index);
                }else{
                    var aind=config_<?php echo esc_attr($tableId);?>.custom_hidden_fields.indexOf(index)
                    if (aind !== -1) {
                        config_<?php echo esc_attr($tableId);?>.custom_hidden_fields.splice(aind, 1);
                    }
                }
				<?php
				if($this->isColumnChoseable){
				?>
                localStorage.setItem("<?php echo esc_attr($permanent_unique_id); ?>",JSON.stringify(config_<?php echo esc_attr($tableId);?>.custom_hidden_fields));
				<?php
				}
				?>


            }
            function append_into_checklist_<?php echo esc_attr($tableId);?>(index,title,isChecked){
                jQuery("#mc_<?php echo esc_attr($tableId);?> .ag_column-choose > .ag-column-container").append('<label class="cl-chose-input"> <input data-cl-key="'+index+'" type="checkbox" '+(isChecked?"checked":"")+'> '+title+'</label>');

            }
            function <?php echo esc_attr($tableId);?>_init_grid(){
                if(is_init_<?php echo esc_attr($tableId);?>){ return;}
                is_init_<?php echo esc_attr($tableId);?>=true;

                try{
                    SetDateGridPicker();
                }catch(e){}
	            <?php if( $this->width=="auto" && empty($this->container)){$this->autowidth=true;}?>

                config_<?php echo esc_attr($tableId);?>.afterInsertRow=eval(config_<?php echo esc_attr($tableId);?>.afterInsertRow);
                try{
                    for(var i in config_<?php echo esc_attr($tableId);?>.colModel){
                        try{
                            config_<?php echo esc_attr($tableId);?>.colModel[i].cellattr=eval(config_<?php echo esc_attr($tableId);?>.colModel[i].cellattr);
                            try{
                                if(!config_<?php echo esc_attr($tableId);?>.colModel[i].hidden){
                                    config_<?php echo esc_attr($tableId);?>.visible_fields[(config_<?php echo esc_attr($tableId);?>.colModel[i].index)] = config_<?php echo esc_attr($tableId);?>.colModel[i].Title;
                                }
                            }catch(e){}
                        }catch(e){
                            app_grid_log(e.message);
                        }

                    }
                }catch(e){
                    app_grid_log(e.message);
                }

                config_<?php echo esc_attr($tableId);?>.loadComplete=function(e){
                    try {APPSBDAPPJS.core.CallOnLoadGridData();}catch (e) {app_grid_log(e);}
                    try{APPSBDAPPJS.lightbox.SetLightbox();}catch(e){app_grid_log(e);}
		            <?php if(!empty($this->onLoadFunc)){ echo esc_attr($this->onLoadFunc);?>(e); <?php } ?>

		            <?php echo esc_attr($tableId);?>_resize_height(e);
		            <?php if($this->isShowNoRecordMsg){?>
                    jQuery("#gview_<?php echo esc_attr($tableId);?> .ui-jqgrid-bdiv .gridnorecord").hide().remove();
                    if (jQuery("#<?php echo esc_attr($tableId);?>").getGridParam("records")==0) {
                        jQuery("#gview_<?php echo esc_attr($tableId);?> .ui-jqgrid-bdiv").append('<div class="gridnorecord" id="gridnorecord">'+jQuery("#<?php echo esc_attr($tableId);?>").getGridParam("emptySetText")+'</div>');
                    }
		            <?php }?>
                    try{
                        app_handle_grid_unauthorize(e,jQuery("#gview_<?php echo esc_attr($tableId);?> .ui-jqgrid-bdiv > .gridnorecord"));
                    }catch(e){};
                };
                config_<?php echo esc_attr($tableId);?>.ajaxGridOptions=
                    {
                        dataFilter:
                            function(data,dataType){   // preprocess the data
					            <?php if(!empty($this->xs_combind_field)){?>
                                try{
                                    if(dataType=="json"){

                                        var data2=JSON.parse(data);
                                        jQuery.each(data2.rowdata,function(key,value){
                                            var optstr="<div class=\"row\" >"+("<div class='col-5 app-property-label'>"+config_<?php echo esc_attr($tableId);?>.visible_fields["<?php echo esc_attr($this->xs_combind_field);?>"]+"</div>")+"<div class=' app-property-value col text-left '>"+data2.rowdata[key]["<?php echo esc_attr($this->xs_combind_field);?>"]+"</div></div>";
                                            jQuery.each(data2.rowdata[key],function(key2,value2){
                                                if(key2=="<?php echo esc_attr($this->xs_combind_field);?>"){
                                                    return;
                                                }
                                                try{
                                                    if(typeof config_<?php echo esc_attr($tableId);?>.visible_fields[key2] !="undefined"){
                                                        optstr+="<div class=\"row\" >"+(key2=="action"?"":"<div class='col-5 app-property-label'>"+config_<?php echo esc_attr($tableId);?>.visible_fields[key2]+"</div>")+"<div class=' app-property-value col-"+(key2=="action"?"12 text-center":"7 text-left")+" '>"+data2.rowdata[key][key2]+"</div></div>";
                                                    }
                                                }catch(e){}
                                            });
                                            data2.rowdata[key]["<?php echo esc_attr($this->xs_combind_field);?>"]="<div class='d-none d-sm-block '>"+data2.rowdata[key]["<?php echo esc_attr($this->xs_combind_field);?>"]+"</div><div class='d-sm-none d-md-none d-lg-none app-grid-property-row text-left'>"+optstr+"</div>";
                                        });
                                    }
                                    data=JSON.stringify(data2);
                                }
                                catch(e){console.log(e);}
					            <?php }?>
                                return data;

                            }
                    };
                var isFirstLoaded_<?php echo esc_attr($tableId);?>=false;
	            <?php echo esc_attr($tableId);?>_serialize = function(obj) {
                    var str = [];
                    for (var p in obj)
                        if (obj.hasOwnProperty(p)) {
                            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        }
                    return str.join("&");
                }
                config_<?php echo esc_attr($tableId);?>.beforeRequest=function () {
                    var data = jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("getGridParam", "postData");
		            <?php if($this->hasDefaultvalue){?>
                    if(!isFirstLoaded_<?php echo esc_attr($tableId);?>){

                        isFirstLoaded_<?php echo esc_attr($tableId);?>=true;
                        var IsMultiSearch=<?php echo esc_attr($this->multisearch)?"true":"false";?>;

                        data.first=true;
                        data.download_csv = false;
                        data.isMultiSearch=IsMultiSearch;
                        if(IsMultiSearch){
                            data.ms=jQuery("#ms_<?php echo esc_attr($tableId);?>").serialize();
                            try{
                                if(window.Base64){
                                    data.ms=Base64.encode(data.ms);
                                }
                            }catch(e){

                            }
                            data._search = true;
                        }else{
                            var stype=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList option:selected").attr("stype");
                            if(stype=="select"){
                                data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcSelectValue").val();
                            }else if(stype=="date" || stype=="daterange"){
                                data.searchString = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_from .srcFrom").val();
                                data.to_date = ""+jQuery("#<?php echo esc_attr($srcDivId)?>_to .srcTo").val();
                                data.searchOper = "bt";
                            }else{
                                data.searchString = jQuery("#<?php echo esc_attr($srcDivId)?> .srcTextValue").val();
                                data.searchOper = "eq";
                            }
                            var srcField=jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val();
                            if(srcField !=""){
                                data.searchField = jQuery("#<?php echo esc_attr($srcDivId)?> .srcOptionList").val();
                                data._search = true;
                            }
                        }

                    }
		            <?php }?>
                    if(data.sidx!=""){
                        try{ var th_obj=$("#jqgh_<?php echo esc_attr($tableId);?>_"+data.sidx);
                            jQuery(".app-sorting").removeClass("app-sorting");
                            th_obj.addClass("app-sorting");
                            jQuery(".<?php echo esc_attr($tableId);?>-rm-srt").remove();
                            var rmicon=jQuery('<i class="<?php echo esc_attr($tableId);?>-rm-srt grid-reset-sort-btn fa fa-times-circle tooltip2" data-tooltip-position="top" title="Remove Sorting"></i>');
                            rmicon.click(function(e){ e.preventDefault();e.stopPropagation();
					            <?php echo esc_attr($tableId);?>_reset_sorting();
                                th_obj.find(">.s-ico > span").addClass("ui-state-disabled");
                                rmicon.fadeOut("fast",function(){$(this).remove();});
                                th_obj.removeClass("app-sorting");
                            });
                            th_obj.append(rmicon);
                        }catch(e){}


                    }
		            <?php if(!empty($this->attach_form)){?>
                    try{
                        var src_form_element=jQuery("<?php echo esc_attr($this->attach_form); ?>");
                        var obj = {};

                        if(src_form_element.length >0){
                            $.each( src_form_element.serializeArray(), function(i,o){
                                var n = "ms["+o.name+"]",
                                    v = o.value;
                                obj[n] = obj[n] === undefined ? v: $.isArray( obj[n] ) ? obj[n].concat( v ): [ obj[n], v ];
                            });
                        }
                        data.isMultiSearch=true;
                        data.ms=<?php echo esc_attr($tableId);?>_serialize(obj);
                        data.ms=Base64.encode(data.ms);

                    }catch(e){
                        app_grid_log("grid msg: "+e.message);
                    }
		            <?php } ?>
                    try{data=set_csrf_param(data);}catch(e){app_grid_log("grid msg: "+e.message);}
                    jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid("setGridParam", { "postData": data });
                    return true;
                };

                config_<?php echo esc_attr($tableId);?>.onSelectAll=function(aRowids,status){
		            <?php
			            if(!empty($this->OnSelectAll)){	 echo "return  ".$this->OnSelectAll?>(aRowids,status); <?php }?>

                }
                config_<?php echo esc_attr($tableId);?>.onSelectAll=function(aRowids,status){
		            <?php
			            if(!empty($this->OnSelectAll)){	 echo "return  ".$this->OnSelectAll?>(aRowids,status); <?php }?>

                }
                config_<?php echo esc_attr($tableId);?>.onInitGrid=function(){
                    //This event does not raised.
                    try{
			            <?php foreach ($this->colModel as $model){
			            if($model->sortable){
			            ?>
                        jQuery("#jqgh_<?php echo esc_attr($this->GetGridId(true)."_".$model->name);?> ").addClass("fld-sortable").find(" > .s-ico").show();
			            <?php
			            }else{
			            ?>
                        jQuery("#jqgh_<?php echo esc_attr($this->GetGridId(true)."_".$model->name);?>").addClass("no-hand-css");
			            <?php
			            }
			            }?>
                    }catch(e){}
                }

	            <?php if(!empty($this->container)){?>
                if(config_<?php echo esc_attr($tableId);?>.width=="auto"){
                    config_<?php echo esc_attr($tableId);?>.width=jQuery("<?php echo esc_attr($this->container); ?>").width();
                    if(config_<?php echo esc_attr($tableId);?>.width < minwidth_<?php echo esc_attr($tableId);?>){
                        config_<?php echo esc_attr($tableId);?>.width=minwidth_<?php echo esc_attr($tableId);?>;
                    }
                    config_<?php echo esc_attr($tableId);?>.width-=<?php echo esc_attr($this->rightPadding);?>;
                }
	            <?php }?>
	            <?php
	            foreach ($this->CustomPostArray['jsf'] as $key=>$value){
	            ?>
                config_<?php echo esc_attr($tableId);?>.<?php echo esc_attr($key);?>=<?php echo esc_attr( $value); ?>;
	            <?php
	            }
	            ?>
	            <?php
	            foreach ($this->custom_searchoption as $keyy=>$value){
	            $key=$this->tempIndex[$keyy];
	            ?>

                config_<?php echo esc_attr($tableId);?>.colModel[<?php echo esc_attr($key);?>]= $.extend({}, config_<?php echo esc_attr($tableId);?>.colModel[<?php echo esc_attr($key);?>], <?php echo esc_attr($value);?>);
	            <?php
	            }?>
                jQuery("#<?php echo esc_attr($tableId);?>").jqGrid(config_<?php echo esc_attr($tableId);?>);
                check_all_visible_<?php echo esc_attr($tableId);?>();
                jQuery("#<?php echo esc_attr($tableId);?>").jqGrid('navGrid','<?php echo esc_attr($this->pager);?>',{edit:false,add:false,del:false <?php if(!$this->ShowDefaultSearch){?>,search:false<?php }?>,reloadtext:"Reload",searchtext:" Search&nbsp;"},{},{},{},{sopt:['cn','bw','eq','ne','lt','gt','ew']} <?php if($this->multisearch){?> ,{multipleSearch:true}<?php }?>);
                $("#<?php echo "pager_".$tableId;?>").after(jQuery("#alertmod_<?php echo esc_attr($tableId);?>"));
				<?php if($this->toolbar[0] && !empty($this->toolbarControl)){?>
                jQuery("#t_<?php echo esc_attr($tableId);?>").append("<?php echo addslashes($this->toolbarControl);?>");
				<?php if($this->toolbarHeight){?>
                jQuery("#t_<?php echo esc_attr($tableId);?>").height(<?php echo (int)$this->toolbarHeight;?>)
				<?php }?>
                jQuery(".smtoolbar").hover(function(){$(this).addClass("ui-state-hover")},function(){$(this).removeClass("ui-state-hover")});
				<?php if(!empty($this->toolbarCSS)){ ?>
                var oldS=jQuery("#t_<?php echo esc_attr($tableId);?>").attr("style");
                jQuery("#t_<?php echo esc_attr($tableId);?>").attr("style",oldS+" <?php echo esc_attr($this->toolbarCSS);?>");
				<?php }?>
				<?php } if(false && !empty($this->multisearch)){?>
                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid('navGrid','#pager_<?php echo esc_attr($tableId);?>',{add:false,del:false,search:true,refresh:false},{},{},{},{multipleSearch:true});
				<?php
				}
				if($this->ShowDownloadButtonInBottom){
				?>
                var dlbutton='<td  class="ui-pg-button jd-w4px ui-state-disabled"><span class="ui-separator"></span></td><td class="ui-pg-button ui-corner-all" title="<?php $this->_e("Download CSV"); ?>" id=""><div class="ui-pg-div"><a onclick="<?php echo esc_attr($this->DownloadCSVMethod());?>();" class="grid-btn-footer  btn-success <?php echo esc_attr($this->getButtonClass()); ?>"><i class="fa fa-download "></i> <?php $this->_e("Download CSV"); ?></a></div></td>';
                jQuery("#refresh_<?php echo esc_attr($tableId);?>").after(dlbutton);
				<?php
				}
				if(!empty($this->beforeSelectRow)){?>
                jQuery("<?php echo esc_attr($this->GetGridId());?>").jqGrid('setGridParam',{beforeSelectRow:function(rowid, e) {
                        return <?php echo esc_attr($this->beforeSelectRow);?>(rowid, e);
                    }});
				<?php }?>
				<?php if(!empty($this->searchbtn)){?>
                jQuery("<?php echo esc_attr($this->searchbtn);?>").click(function(e){
                    e.preventDefault();
                    jQuery("#<?php echo esc_attr($tableId);?>").jqGrid('searchGrid', {sopt:['bw','cn']} );
                });
				<?php }?>

				<?php if(!empty($this->attach_form)){?>
                jQuery("<?php esc_attr($this->attach_form);?>").on("submit",function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var myGrid = jQuery("<?php echo esc_attr($this->GetGridId())?>");
                    myGrid.trigger('reloadGrid');
                });
				<?php } ?>

				<?php if($this->width=="auto" && !empty($this->container)){?>
                try{
                    APPSBDAPPJS.core.AddOnWindowResize(<?php echo esc_attr($tableId);?>_ResizeGrid);
                }catch(e){}
                try{
                    jQuery(window).on('resize', function() {
                        setTimeout(function(){
				            <?php echo esc_attr($tableId);?>_ResizeGrid();
                        },500);
                    });
                }catch(e){}

				<?php }?>
                <?php if(!$this->isDisabledOnCloseEventCall){ ?>
                try {
                    <?php if(!empty($this->_set_reload_event)){
                       ?>
                    APPSBDAPPJS.core.AddOnCloseLightboxWithEvent('<?php echo esc_attr($this->_set_reload_event); ?>',<?php echo esc_attr($this->ReloadMethod());?>);
                       <?php
                }else{?>
                    APPSBDAPPJS.core.AddOnCloseLightbox(<?php echo esc_attr($this->ReloadMethod());?>);
                    <?php } ?>
                }catch(e){}
                <?php } ?>


                var applyClassesToHeaders = function (grid) {
                    // Use the passed in grid as context,
                    // in case we have more than one table on the page.
                    var trHead = jQuery("thead:first tr", grid.hdiv);
                    var colModel = grid.getGridParam("colModel");
                    for (var iCol = 0; iCol < colModel.length; iCol++) {
                        var columnInfo = colModel[iCol];
                        if (columnInfo.thclasses) {
                            var headDiv = jQuery("th:eq(" + iCol + ")", trHead);
                            headDiv.addClass(columnInfo.thclasses);
                        }
                    }
                };
                try{
                    applyClassesToHeaders(jQuery("#<?php echo esc_attr($tableId);?>"));
                }catch(e){}

				<?php if($this->isColumnChoseable){
				?>
                try {
                    var firstThID = jQuery("#mc_<?php echo esc_attr($tableId);?> .ui-jqgrid-htable > thead .ui-jqgrid-labels #<?php echo esc_attr($tableId);?>_rn");
                    var settings_i = jQuery('<i class="apg-settings fa fa-cog"></i>');
                    settings_i.on("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if(jQuery(this).hasClass('ap-db-open')){
                            jQuery(this).removeClass('ap-db-open');
                            jQuery("#mc_<?php echo esc_attr($tableId);?> >.ag_column-choose").hide();
                        }else {
                            jQuery(this).addClass('ap-db-open');
                            jQuery("#mc_<?php echo esc_attr($tableId);?> >.ag_column-choose").show();
                        }
                    });
                    jQuery("#mc_<?php echo esc_attr($tableId);?> >.ag_column-choose .cl-chose-input").on("click", function (e) {
                        e.stopPropagation();

                    });
                    jQuery("#mc_<?php echo esc_attr($tableId);?> >.ag_column-choose .cl-chose-input input").on("change", function (e) {
                        var key = jQuery(this).data("cl-key");
                        var customHidden = config_<?php echo esc_attr($tableId);?>.custom_hidden_fields;
                        if (jQuery(this).is(":checked")) {
                            set_unset_key_<?php echo esc_attr($tableId);?>(key, false);
                        } else {
                            set_unset_key_<?php echo esc_attr($tableId);?>(key, true);
                        }
                        show_coll_<?php echo esc_attr($tableId);?>(key);
						<?php echo esc_attr($tableId);?>_ResizeGrid();
                    });
                    jQuery("body").on("click", function () {
                        jQuery("#mc_<?php echo esc_attr($tableId);?> >.ag_column-choose").hide();
                        settings_i.removeClass('ap-db-open');
                    });
                    firstThID.html(settings_i);
                }catch(e){}

				<?php
				}
				?>
	            <?php if($this->isDisableAutoInit){?>
                APPSBDAPPJS.Initialize();
	            <?php } ?>

				<?php $this->DisplayTooltip();?>
                $(window).trigger("resize");
            }
            function <?php echo esc_attr($tableId);?>_resize_height(e){
                if(jQuery("<?php echo esc_attr($this->container);?>").hasClass('grid-full-screen')){
                    return;
                }
                try{
                    if(config_<?php echo esc_attr($tableId);?>.height=="auto"){
                        return;
                    }
                    var data= config_<?php echo esc_attr($tableId);?>.auto_height_records;
                    if(e.records<data){
                        jQuery("#<?php echo esc_attr($tableId);?>").setGridHeight('auto');
                    }else{
                        jQuery("#<?php echo esc_attr($tableId);?>").setGridHeight(config_<?php echo esc_attr($tableId);?>.height);
                    }
                }catch(e){}
            }
            function <?php echo esc_attr($tableId);?>_reset_sorting(){

                var myGrid = jQuery("<?php echo esc_attr($this->GetGridId())?>");
                $("span.s-ico",myGrid[0].grid.hDiv).hide(); // hide sort icons
                myGrid.setGridParam({sortname: ''}).trigger('reloadGrid');

            }

            function <?php echo esc_attr($tableId);?>_ResizeGrid(){
                if(jQuery("<?php echo esc_attr($this->container);?>").hasClass('grid-full-screen')){
                    return;
                }
                try {
                    if (is_resized_<?php echo esc_attr($tableId);?>) {
                        jQuery("#<?php echo esc_attr($tableId);?>").setGridWidth('100');
                    }else{
                        is_resized_<?php echo esc_attr($tableId);?>=true;
	                    <?php echo esc_attr($tableId);?>_processResize(); //don't know why need this. however it fixed the problem
                    }
                }catch (e) {console.log(e);}
	            <?php echo esc_attr($tableId);?>_processResize();
            }
            function  <?php echo esc_attr($tableId);?>_processResize(){
                var c_minwidth_<?php echo esc_attr($tableId);?>=jQuery("<?php echo esc_attr($this->container);?>").width();
                if(c_minwidth_<?php echo esc_attr($tableId);?><=minwidth_<?php echo esc_attr($tableId);?>){
                    c_minwidth_<?php echo esc_attr($tableId);?>=minwidth_<?php echo esc_attr($tableId);?>;
                }
	            <?php if($this->rightPadding>0){?>
                c_minwidth_<?php echo esc_attr($tableId);?>-=<?php echo esc_attr($this->rightPadding);?>;
	            <?php }else{ ?>
                c_minwidth_<?php echo esc_attr($tableId);?>-=5;
	            <?php }?>
                jQuery("#<?php echo esc_attr($tableId);?>").setGridWidth(c_minwidth_<?php echo esc_attr($tableId);?>);


                var windowWidth=jQuery(window).width();
                if(windowWidth<768){
		            <?php if(!empty($this->xs_combind_field)){?>
                    for (var key in config_<?php echo esc_attr($tableId);?>.visible_fields){
                        if(key!="<?php echo esc_attr($this->xs_combind_field);?>"){
                            jQuery("#<?php echo esc_attr($tableId);?>").hideCol(key);
                        }
                    }
		            <?php }?>
		            <?php foreach ($this->hidecols['xs'] as $hcol){?>
                    jQuery("#<?php echo esc_attr($tableId);?>").hideCol("<?php echo esc_attr($hcol);?>");
		            <?php }?>
		            <?php foreach ($this->hidecols['sm'] as $hcol){?>
                    jQuery("#<?php echo esc_attr($tableId);?>").hideCol("<?php echo esc_attr($hcol);?>");
		            <?php }?>
                }else{
		            <?php if(!empty($this->xs_combind_field)){?>
                    for(var kindex in config_<?php echo esc_attr($tableId);?>.visible_fields){
                        show_coll_<?php echo esc_attr($tableId);?>(kindex);
                    }
		            <?php }?>
		            <?php foreach ($this->hidecols['xs'] as $hcol){?>
                    show_coll_<?php echo esc_attr($tableId);?>("<?php echo esc_attr($hcol);?>");
		            <?php }?>
		            <?php foreach ($this->hidecols['sm'] as $hcol){?>
                    show_coll_<?php echo esc_attr($tableId);?>("<?php echo esc_attr($hcol);?>");
		            <?php }
		            ?>
                }
                if(windowWidth<991){
		            <?php foreach ($this->hidecols['sm'] as $hcol){?>
                    jQuery("#<?php echo esc_attr($tableId);?>").hideCol("<?php echo esc_attr($hcol);?>");
		            <?php }?>

                }else{
		            <?php if(!empty($this->xs_combind_field)){?>
                    for(var kindex in config_<?php echo esc_attr($tableId);?>.visible_fields){
                        show_coll_<?php echo esc_attr($tableId);?>(kindex);
                    }
		            <?php }?>
		            <?php foreach ($this->hidecols['sm'] as $hcol){?>
                    show_coll_<?php echo esc_attr($tableId);?>("<?php echo esc_attr($hcol);?>");
		            <?php }?>

                }
            }
		</script>
		<div id="mc_<?php echo esc_attr($tableId);?>" class="gs-jq-grid " data-unique-id="<?php echo esc_attr($permanent_unique_id); ?>">
			<?php if($this->isColumnChoseable){ ?>
				<div class="ag_column-choose">
					<div class="ag-column-container">

					</div>
				</div>
			<?php } ?>
			<table id="<?php echo esc_attr($tableId);?>"></table>
			<div id="<?php echo "pager_".$tableId;?>"></div>
		</div>

		<?php
	}
	function RowIndex($id){
		$this->AddCustomModel(array("search"=>false,"sortable"=>false,"hidden"=>true,"key"=>true,"index"=>$id,"name"=>$id,"viewable"=>false));

	}
	function AddModelHidden($id){
		$this->AddCustomModel(array("search"=>false,"sortable"=>false,"hidden"=>true,"index"=>$id,"name"=>$id,"viewable"=>false));

	}


	function AddSearchProperty($title,$index_id,$search_type="",$searctionObtionValue=array()){
		$searchoptions=null;
		$extraparam="";
		if(is_array($searctionObtionValue) && $search_type=="select" && (count($searctionObtionValue)>0)){
			$searchoptions=new stdClass();
			$searchoptions->value=new stdClass();
			foreach ($searctionObtionValue as $key=>$val){
				$searchoptions->value->$key=$val;
			}
		}elseif (is_string($searctionObtionValue)){
			$extraparam=$searctionObtionValue;
		}

		$this->AddCustomModel(
			array(  "Title"=>$title,
			        "search"=>true,
			        "sortable"=>false,
			        "hidden"=>true,
			        "index"=>$index_id,
			        "name"=>$index_id,
			        "viewable"=>false,
			        "stype"=>"$search_type",
			        "searchoptions"=>$searchoptions,
			        "ExtraParam"=>$extraparam
			));


	}
	function AddHiddenProperty($index_id,$title=''){

		$this->AddCustomModel(
			array(  "Title"=>$title,
			        "search"=>false,
			        "sortable"=>false,
			        "hidden"=>true,
			        "index"=>$index_id,
			        "name"=>$index_id,
			        "viewable"=>false
			));

	}
	function AddModelCustomSearchable($title,$index_id,$width ,$align="left",$search_type="",$searctionObtionValue=array(),$isSortable=true){
		$this->AddModel($title, $index_id, $width,$align,"","","",true,$search_type,$searctionObtionValue,$isSortable);
	}
	function AddModelNonSearchable($title,$index_id,$width ,$align="left",$formatter="",$isSortable=false){
		$this->AddModel($title, $index_id, $width,$align,$formatter,"","",false,'','',$isSortable);
	}
	function AddCustomModel($properties=array()){
		if(empty($properties["index"])){
			return;
		}
		$Model=new GridModel();
		foreach ($properties as $key=>$property){
			$Model->$key=$property;
		}
		$keyy=$properties["index"];
		$this->tempIndex[$keyy]=count($this->colModel);
		array_push($this->colModel,$Model);
	}
	function AddSortableModel($title,$index_id,$width ,$align="left",$formatter="",$srcformat="",$newformat="",$isSearchable=true,$stype="",$serchoption="",$isSortable=true){
		$this->AddModel($title,$index_id,$width ,$align,$formatter,$srcformat,$newformat,$isSearchable,$stype,$serchoption,$isSortable);
	}
	function AddModel($title,$index_id,$width ,$align="left",$formatter="",$srcformat="",$newformat="",$isSearchable=true,$stype="",$serchoption="",$isSortable=false){
		$Model=new GridModel();
		$Model->Title=$this->__($title);
		$Model->name=$index_id;
		$Model->width=$width;
		$Model->index=$index_id;
		$Model->align=$align;
		$Model->sortable=$isSortable;
		if(!$isSearchable){
			$Model->search=false;
		}
		if(!empty($stype)){
			$Model->stype=$stype;
		}
		if(is_array($serchoption) && $stype=="select" && (count($serchoption)>0)){
			$Model->searchoptions=new stdClass();
			$Model->searchoptions->value=new stdClass();
			foreach ($serchoption as $key=>$val){
				$Model->searchoptions->value->$key=$val;
			}
		}elseif(is_string($serchoption)){
			$Model->ExtraParam=$serchoption;
		}
		if(!empty($formatter)){
			$Model->formater=$formatter;
		}else{
			$Model->formater="Grid_{$this->GridId}_formatter";
		}
		$Model->formatoptions=new stdClass();
		if(!empty($srcformat)){
			$Model->formatoptions->srcformat=$srcformat;
		}
		if(!empty($newformat)){
			$Model->formatoptions->newformat=$newformat;
		}
		$this->tempIndex[$index_id]=count($this->colModel);
		array_push($this->colModel,$Model);
		$this->TotalColumn++;
	}

	function SetToolBarPosition($position="top"){
		$this->toolbar=array(true,$position);
	}
	function AddToolbarContent($content){
		$this->toolbarControl.='<div class="ui-pg-div smtoolbar">'.$content.'</div>';
	}
	function AddToolbarButton($btnId,$btnText,$iconClass,$onClickMethod=""){
		$this->toolbarControl.='<div id="'.$btnId.'" onclick="'.$onClickMethod.'();" class="ui-pg-div ui-corner-all smtoolbar toolButton"><span class="'.$iconClass.'"></span>'.$btnText.'</div>';
	}

	/**
	 * Set Toolbar Height ...
	 * @param integer $heightInt
	 */
	function SetToolbarHeight($heightInt){
		$this->toolbarHeight=$heightInt;
	}
	/**
	 * Enter description here ...
	 * @param intger $top in px
	 * @param integer $bottom in px
	 */
	function SetToolbarPadding($top,$bottom=0){
		$this->toolbarCSS.="padding:".$top."px 0px ".$bottom."px 0px;";
	}

	function HasSearchProperty()
	{
		if (! $this->multisearch) {
			foreach ($this->colModel as $model) {
				if (! isset($model->search) || $model->search) {
					return true;
				}
			}
		} else {
			foreach ($this->colModel as $model) {
				if (! isset($model->search) || $model->search) {
					return true;
				}
			}
		}
		$this->CustomSearchOnTopGrid=false;
		return false;
	}
	function GetCustomSearch() {
		if(self::$botostrapVersion==4){
			$this->GetBootstrap4Customsearch();
		}else{
			$this->__("It is not supports bootstrap 3");
		}
	}
	function GetBootstrap4Customsearch(){
		$tableId=$this->GridId;
		$srcDivId="src_$tableId";
		$srcMFromId="ms_$tableId";
		ob_start();
		if(!$this->multisearch){
			?>
			<div class="grid-search-panel">
				<div class="gs-grid-serach row form-horizontal jd-p5px"
				     id="<?php echo esc_attr($srcDivId);?>" >

					<div class="col-md-4">
						<div class="form-group row form-group-sm">
							<label class="col-5 d-none d-sm-block" for="selectpropery"
							       class="control-label first-label col-md-6  hidden-xs"><span
									class=""><?php $this->_e("Select"); ?></span> <?php $this->_e("Property"); ?></label>
							<div class="col pl-0 pr-0  pl-sm-3  pr-sm-3">
							<select class="custom-select srcOptionList form-control-sm ">

								<?php
								$already=array();
								$options="";
								$count=0;
								foreach ($this->colModel as $model){
									if(in_array($model->name, $already)){continue;}
									if(!isset($model->search) ||$model->search){
										$datatest="";
										array_push($already, $model->name);
										if(!empty($model->searchoptions)){
											if(!empty($model->searchoptions->value) && (is_array($model->searchoptions->value) || is_object($model->searchoptions->value))){
												$datatest="data='".base64_encode(json_encode($model->searchoptions->value))."'";
											}
										}

										$options.="<option stype='".(!empty($model->stype)?$model->stype:"")."' $datatest  value='$model->name'>".esc_attr($model->Title)."</option>";
										$count++;
									}
									?>
								<?php }
								if($count>1){?>
									<option value=""><?php $this->_e("Select") ; ?></option>
								<?php }
								echo wp_kses_no_null($options);
								?>
							</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group ">
							<div id="<?php echo esc_attr($srcDivId)."_text"?>" class="row">
								<div   class="col col p-0">
									<input autocomplete="off"
									       class="srcTextValue form-control form-control-sm input-sm" type="text"
									       name="srcText" value="" />
                                    <select
										class="input-sm srcSelectValue custom-select form-control-sm jd-none"
										 name="srcText">

									</select>
								</div>
							</div>

							<div id="<?php echo esc_attr($srcDivId)."_form"?>" class="row">
								<div id="<?php echo esc_attr($srcDivId)."_from"?>" class="col d-none">
									<div class="form-group form-group-sm row">
											<div class="input-group date gs-date-picker-grid-options">
												<input autocomplete="off"
												       class="input-xs srcTextValue form-control form-control-sm srcFrom input-sm"
												       type="text" name="srcFrom" placeholder="<?php $this->_e("Form"); ?>" value="" />
												<div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">
                                                        <i class="fa fa-calendar jd-icon"></i>
                                                    </span>
												</div>
											</div>

									</div>
								</div>
								<div id="<?php echo esc_attr($srcDivId)."_to"?>" class="col d-none pr-0 pl-0 pl-sm-3">

                                    <div class="input-group date gs-date-picker-grid-options">
                                        <input autocomplete="off"
                                               class="srcTextValue form-control form-control-sm srcTo input-sm" placeholder="<?php $this->_e("To"); ?>" type="text"
                                               name="srcTo" value="" />
                                        <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">
                                                        <i class="fa fa-calendar jd-icon"></i>
                                                    </span>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>

					<div class="col col-md-2 p-l-0 p-r-0 text-center">
						<a class="<?php echo esc_attr($this->getButtonClass());?> btn-warning"
						   onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_custom_reload();"><i
								class="fa fa-search"></i> <?php $this->_e("Search") ; ?></a> <a
							class="<?php echo esc_attr($this->getButtonClass()); ?> btn-danger"
							onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_reset_custom_reload();"><i
								class="fa fa-times"> </i> <?php $this->_e("Reset") ; ?></a>
						<?php if($this->ShowAdvanceSearch){?>
							<a class="<?php echo esc_attr($this->getButtonClass()); ?> btn-warning"
							   onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_advance_search();"><i
									class="fa fa-search"></i> <?php $this->_e("Advance Search") ; ?></a>
						<?php }?>

						<?php if($this->IsCSVDownload){?>
							&nbsp;<a class="btn btn-sm btn-warning"
							         onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_reload();"><i
									class="fa fa-download"></i> <?php $this->_e("Download CSV") ; ?></a>
						<?php }?>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<?php
		}else{
			$already=array();
			?>
			<div class="grid-search-panel">
				<div class="gs-grid-serach row form-horizontal jd-p5px"
				     id="<?php echo esc_attr($srcDivId);?>" >
					<div class="col pl-2">
						<div class="grid-src-panel card card-default">
							<div class="src-heading card-header pt-1 pb-1"><?php $this->_e("Search"); ?>
								<a class="pull-right <?php echo esc_attr($this->getButtonClass()); ?> btn-warning"
								   onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_custom_reload();"><i
										class="fa fa-search"> </i> <?php $this->_e("Search") ; ?></a> <a
									class="pull-right <?php esc_attr($this->getButtonClass()); ?> btn-danger"
									onclick="javascript:Grid_<?php echo esc_attr($tableId);?>_reset_custom_reload();"><i
										class="fa fa-times"> </i> <?php $this->_e("Reset Search") ; ?></a>
							</div>

							<div class="card-body p-2">
								<form id="<?php echo esc_attr($srcMFromId);?>" onsubmit="return false;">
                                    <div class="row">
									<?php
									$leftCol="";
									$rightCol="";
									$mi=1;
									foreach ($this->colModel as $model){
										if(in_array($model->name, $already)){continue;}
										if(!isset($model->search) ||$model->search){
											array_push($already, $model->name);
											$datatest="";

											ob_start();
											?>

											<div class="form-group form-row">
												<label class="col-sm-2 col-md-4 control-label text-sm-right text-bold" for="name"><?php echo esc_html($model->Title); ?></label>
												<?php if(empty($model->stype) ||$model->stype=="text" ){?>
													<div class="col-sm-10 col-md-8">
														<?php if(in_array($model->name,$this->MultiSearchOperator)){?>
														<div class="input-group input-group-sm">
															<div class="input-group-addon operator">
																<select name="op[<?php echo esc_attr($model->name);?>]" class="">
																	<option value="eq">=</option>
																	<option value="gr">></option>
																	<option value="lg"><</option>
																</select>
															</div>
															<?php }?>
															<input type="text"
															       value="<?php echo esc_attr($model->dvalue);?>"
															       class="form-control form-control-sm input-sm" id="<?php echo esc_attr($model->name);?>"
															       name="ms[<?php echo esc_attr($model->name);?>]"
															       placeholder="<?php echo esc_attr($model->Title); ?>"></input>
															<?php if(in_array($model->name,$this->MultiSearchOperator)){?>
														</div>
													<?php }?>
													</div>
												<?php }elseif(strtolower($model->stype)=="date"||strtolower($model->stype)=="dateonly" || strtolower($model->stype)=="daterange"||strtolower($model->stype)=="timerange"  ||strtolower($model->stype)=="datetimerange"  ||strtolower($model->stype)=="time"||strtolower($model->stype)=="datetime"){
													$maintype=strtolower($model->stype);
													$placeholder=$this->__("Form");
													$custom="";
													if($maintype=='datetimerange'){

													}
													$isRange=$maintype=="daterange" || $maintype=="datetimerange" || $maintype=="timerange";
													if(!empty($model->ExtraParam) && is_string($model->ExtraParam)){
														$custom="-custom";
													}
													if( strtolower($model->stype)=="datetime" || strtolower($model->stype)=="datetimerange"){
														$model->dvalue=!empty($model->dvalue)?date('Y-m-d H:i',strtotime($model->dvalue)):"";
														$model->dvalue2=!empty($model->dvalue2)?date('Y-m-d H:i',strtotime($model->dvalue2)):"";
													}
													?>
													<div
														class="col-sm-<?php echo esc_attr($isRange)?5:10;?> col-md-<?php echo esc_attr($isRange)?4:8;?>">
														<div
															class="input-group <?php echo strtolower($model->stype)?> gs-date-picker-grid-options gs-<?php echo strtolower($model->stype).$custom;?>-picker-grid-options" data-type="<?php echo strtolower($model->stype);?>">
															<input autocomplete="off"
															       class="srcTextValue form-control form-control-smsrcMFrom" type="text"
															       name="ms[<?php echo esc_attr($model->name);?>]<?php if($isRange){?>[from]<?php }?>"
															       value="<?php echo esc_attr($model->dvalue);?>"
															       placeholder="<?php echo esc_attr($isRange)?$placeholder:$model->Title;?>"
																<?php echo !empty($custom) ? ' data-format="'.$model->ExtraParam.'"' :'';?> />
															<span class="input-group-addon input-group-addon-style">
                                                                <span class="fa <?php echo esc_attr($model->stype)=="date"?' fa-calendar ':' fa-clock-o '?>"></span></span>
														</div>
													</div>
													<?php if($isRange){?>
														<div class="col-sm-5 col-md-4">
															<div
																class="input-group <?php echo esc_attr($model->stype)?> gs-date-picker-grid-options  gs-<?php echo esc_attr($model->stype).$custom;?>-picker-grid-options" data-type="<?php echo esc_attr($model->stype);?>">
																<input autocomplete="off"
																       class="srcTextValue form-control form-control-smsrcMFrom" type="text"
																       name="ms[<?php echo esc_attr($model->name);?>][to]"
																       value="<?php echo esc_attr($model->dvalue2);?>" placeholder="<?php $this->_e("To"); ?>"
																	<?php echo !empty($custom) ? ' data-format="'.$model->ExtraParam.'"' :'';?> />
																<span class="input-group-addon input-group-addon-style">
                                                                    <span class="fa <?php echo esc_attr($model->stype)=="date"?' fa-calendar ':' fa-clock-o '?>"></span></span>
															</div>
														</div>
													<?php }?>
												<?php }elseif(strtolower($model->stype)=="select"){

													?>
													<div class="col-sm-10 col-md-8">
														<select class="form-control form-control-sm input-sm"
														        id="<?php echo esc_attr($model->name);?>"
														        name="ms[<?php echo esc_attr($model->name);?>]">
															<?php
															if(!empty($model->searchoptions->value) && count($model->searchoptions->value)>0){
																foreach ($model->searchoptions->value as  $value=>$title){
																	?>
																	<option
																		<?php echo esc_attr($model->dvalue==$value)?' selected="selected" ':'';?>
																		value="<?php echo esc_attr($value)?>"><?php echo esc_html($title);?></option>
																	<?php
																}
															}
															?>
														</select>
													</div>
												<?php }?>
											</div>
											<?php
											if($mi==1){
												$leftCol.=ob_get_clean();
												$mi=0;
											}else{
												$rightCol.=ob_get_clean();
												$mi=1;
											}


										}
									}
									?>
									<div class="col-md-6 form form-horizontal">
										<?php echo wp_kses_no_null($leftCol);?>
									</div>
									<div class="col-md-6">
										<?php echo wp_kses_no_null($rightCol);?>
									</div>
                                    </div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		$output = ob_get_contents();
		ob_end_clean();

		echo wp_kses_no_null($output);
	}

	/**
	 * @param string $onLoadFunc
	 */
	public function setOnLoadFunc( $onLoadFunc ) {
		$this->onLoadFunc = $onLoadFunc;
	}

	/**
	 * @param bool $isDisabledOnCloseEventCall
	 */
	public function setIsDisabledOnCloseEventCall( $isDisabledOnCloseEventCall ) {
		$this->isDisabledOnCloseEventCall = $isDisabledOnCloseEventCall;
	}

	/**
	 * @param string $set_reload_event
	 */
	public function setReloadEvent( $reload_event ) {
		$this->_set_reload_event = $reload_event;
	}

}
class GridModel{
	public $Title;
	public $objectName;
	public $width;
	public $name;
	public $index;
	public $formater="number";
	public $sopt;
	public $cellattr="col_cellattr";
	public $sortable;
	public $dvalue="";
	public $dvalue2="";
	public $ExtraParam;
	public $viewable=true;
}
