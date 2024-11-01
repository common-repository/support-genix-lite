<?php
class APBDUpdateResponse
{
	public $IsStoppedUpdate; 	public $version; 	public $slug; 	public $plugin_name; 	public $name; 	public $new_version; 	public $requires; 	public $tested; 	public $downloaded; 	public $last_updated; 	public $url; 	public $download_link; 	public $sections=[]; 	public $icons=[];
	public $banners=[];
	public $banners_rtl=[];
	public $update_denied_type="";
	public $is_downloadable=true;
	function __construct() {
		$this->version=$this->new_version;
		$this->plugin_name=&$this->name;
	}

	function AddSection($sectionName,$text){
		$this->sections[$sectionName]=$text;
	}
}

