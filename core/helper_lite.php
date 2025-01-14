<?php
	if ( !defined("APPSBD_IsPostBack")) {
		define( "APPSBD_IsPostBack", strtoupper( sanitize_text_field($_SERVER['REQUEST_METHOD'] )) == 'POST' );
	}


	if ( ! function_exists("APBD_GetHTMLRadioByArray")) {

		function  APBD_GetHTMLRadioByArray($title,$name, $id, $isRequired, $options, $checkedValue, $isDisabled=false, $isInline = true,$class="",$attr=array(),$group_class=''){
			foreach ($options as $key=>$value){
				$esc_attrbutes=" ";
				if(is_array($attr) && count($attr)>0){
					foreach ($attr as $skey=>$svalue){
						$esc_attrbutes.=$skey.'="'.$svalue.'" ';
					}
				}
				?>
                <div class=" <?php echo esc_attr($group_class); ?>  md-radio <?php echo esc_attr($isInline?' md-radio-inline ':''); ?>">
                    <input class="<?php echo esc_attr($class);?>" <?php echo esc_attr($esc_attrbutes);?>
                           id="<?php echo esc_attr($id)."-".esc_attr($key);?>" type="radio"
						<?php echo esc_attr($checkedValue==$key?' checked ':'');?>
						<?php if(!$isDisabled){?> name="<?php echo esc_attr($name);?>" <?php }else{?>
                            disabled="disabled" <?php }?> value="<?php echo esc_attr($key);?>"
						<?php if(!$isDisabled && $isRequired){?> data-bv-notempty="true"
                            data-bv-notempty-message="Choose <?php echo esc_attr($title);?>" <?php }?> />
                    <label class="" for="<?php echo esc_attr($id)."-".esc_attr($key);?>"><?php echo wp_kses_post($value);?></label>
                </div>


				<?php
			}
		}
	}

	if ( ! function_exists( "APBD_LoadFontAwesomeVector" ) ) {
		function APBD_LoadFontAwesomeVector($basePath){
			$path=realpath(dirname($basePath)."/../uilib/font-awesome/4.7.0/fonts/FontAwesome.svg");
			if(file_exists($path)){
				$data= strip_tags(file_get_contents($path));
				return $data;
			}
			return "";
		}
	}
	if ( ! function_exists("wp_kses_script")) {
		function wp_kses_script($html)
		{
			return wp_kses_no_null( $string, array( 'slash_zero' => 'keep' ) );;
		}
	}
	if ( ! function_exists( "APBD_DownloadFile" ) ) {
		function APBD_DownloadFile( $url, $downloadpath ) {
			$dir = dirname( $downloadpath );
			if ( ! is_dir( $dir ) ) {
				mkdir( $dir, 0755 );
			}
			if ( is_file( $downloadpath ) && file_exists( $downloadpath ) ) {
				$dir          = dirname( $downloadpath );
				$filename     = basename( $downloadpath );
				$downloadpath = $dir . "/" . time() . $filename;
			}
			$file = fopen( $url, "rb" );
			if ( $file ) {
				$newf = fopen( $downloadpath, "wb" );

				if ( $newf ) {
					while ( ! feof( $file ) ) {
						fwrite( $newf, fread( $file, 1024 * 8 ), 1024 * 8 );
					}
				}
			}

			if ( $file ) {
				fclose( $file );
			}

			return $downloadpath;
		}
	}

	if ( ! function_exists( "APBD_PostValue" ) ) {
		function APBD_PostValue( $index, $default = NULL) {
			$data = wp_parse_args( $_POST );

			if ( ! isset( $data[ $index ] ) ) {
				return $default;
			} else {
				return $data[ $index ];
			}
		}
	}

	if ( ! function_exists( "APBD_RequestValue" ) ) {
		function APBD_RequestValue( $index, $default = NULL ) {
			$data = wp_parse_args( $_REQUEST );

			if ( ! isset( $data[ $index ] ) ) {
				return $default;
			} else {
				return $data[ $index ];
			}
		}
	}
	if ( ! function_exists( "APBD_GetValue" ) ) {
		function APBD_GetValue( $index, $default = NULL) {
			$data = wp_parse_args( $_GET );

			if ( ! isset( $data[ $index ] ) ) {
				return $default;
			} else {
			    return $data[ $index ];
			}
		}
	}
	if ( ! function_exists( "SMPrint" ) ) {
		function SMPrint( $obj ) {


		}
	}
	if ( ! function_exists( "APBD_CleanDomainName" ) ) {
		function APBD_CleanDomainName($domain) {
			$domain=trim($domain);
			$domain=strtolower($domain);
			return str_replace(['https://','http://'],"",$domain);
		}
	}

	if ( ! function_exists( "APBD_GetUrlToHost" ) ) {
		function APBD_GetUrlToHost( $url ) {
			$result = parse_url( $url );
			$url    = ! empty( $result['host'] ) ? $result['host'] : $url;
			$url    = APBD_CleanDomainName( $url );

			return $url;
		}
	}

	if ( ! function_exists( "APBD_EndWith" ) ) {
		function APBD_EndWith( $haystack, $needle ) {
			$len  = strlen( $haystack );
			$nlen = strlen( $needle );
			$sub  = substr( $haystack, - $nlen );
			if ( $sub == $needle ) {
				return true;
			}

			return false;
		}
	}
	if ( ! function_exists( "APBD_StartWith" ) ) {
		function APBD_StartWith( $haystack, $needle ) {
			$len  = strlen( $haystack );
			$nlen = strlen( $needle );
			$sub  = substr( $haystack, 0, $nlen );
			if ( $sub == $needle ) {
				return true;
			}

			return false;
		}
	}

	if ( ! function_exists( "APBD_getTimeSpan" ) ) {
		function APBD_getTimeSpan( $fisettime ) {
			//return date('Y-m-d H:i:s',$fisettime);
			if ( version_compare( PHP_VERSION, '5.3' ) >= 0 ) {
				$d1 = new DateTime();
				$d1->setTimestamp( $fisettime );
				//return $d1->format("Y-m-d H:i:s");
				$d2 = new DateTime();
				if ( $d1->diff( $d2 )->days > 0 ) {
					if ( $d1->diff( $d2 )->i == 1 ) {
						return "Yesterday";
					}
					$isS = $d1->diff( $d2 )->days ? "s" : "";

					return $d1->diff( $d2 )->days . " day$isS ago";
				} elseif ( $d1->diff( $d2 )->h > 0 ) {
					$isS = $d1->diff( $d2 )->h ? "s" : "";

					return $d1->diff( $d2 )->h . " hour$isS ago";
				} elseif ( $d1->diff( $d2 )->i > 0 ) {
					$isS = $d1->diff( $d2 )->i ? "s" : "";

					return $d1->diff( $d2 )->i . " minute$isS ago";
				} elseif ( $d1->diff( $d2 )->s > 0 ) {
					return $d1->diff( $d2 )->i . " seconds ago";
				} else {
					return " a moment ago";
				}
			} else {
				return date( 'Y-m-d H:i:s', $fisettime );
			}
		}
	}


	if ( ! function_exists('APBD_AddLog'))
	{
		function APBD_AddLog($changed_type,$changed_value,$msg_code,$msg_param="",$member_id="",$agent_id="",$user="",$role="",$tag="")
		{
			//$changed_value=strlen($changed_value)>250?substr($changed_value, 0,247)."...":$changed_value;
			return true; //Mapp_log::AddLog($changed_type, $changed_value, $msg_code,$msg_param,$member_id,$agent_id,$tag,$user,$role);

		}
	}
	if ( ! function_exists('APBD_is_countable'))
	{
		function APBD_is_countable($vars)
		{
			if(function_exists("is_countable")){
			    return is_countable($vars);
            }else{
			    if(is_string($vars) || is_bool($vars)){
			        return false;
                }
			    return is_array($vars) || is_object($vars);
            }
		}
	}
    if ( ! function_exists('APBD_getWPDateWithFormat')) {
	    function APBD_getWPDateWithFormat( $timestr, $local = false ) {
			if ( $local && ( "0000-00-00 00:00:00" !== $timestr ) ) {
				$timestr = strtotime( $timestr );
				$timestr = wp_date( "Y-m-d H:i:s", $timestr );
			}
		    return APBD_getWPTimezoneTime($timestr,get_option( 'date_format' ));
	    }
    }
    if ( ! function_exists('APBD_getWPTimeWithFormat')) {
        function APBD_getWPTimeWithFormat( $timestr, $local = false ) {
			if ( $local && ( "0000-00-00 00:00:00" !== $timestr ) ) {
				$timestr = strtotime( $timestr );
				$timestr = wp_date( "Y-m-d H:i:s", $timestr );
			}
            return APBD_getWPTimezoneTime($timestr,get_option( 'time_format' ));
        }
    }

    if ( ! function_exists('APBD_getWPDateTimeWithFormat')) {
        function APBD_getWPDateTimeWithFormat( $timestr, $local = false ) {
			if ( $local && ( "0000-00-00 00:00:00" !== $timestr ) ) {
				$timestr = strtotime( $timestr );
				$timestr = wp_date( "Y-m-d H:i:s", $timestr );
			}
	        return APBD_getWPTimezoneTime($timestr, get_option( 'date_format' )." ".get_option( 'time_format' ));
        }
    }
	if ( ! function_exists('APBD_CastClass')) {
		function APBD_CastClass( $class,$object ) {
			$c=new $class();
			if(is_object($object)){
				foreach ($object as $key=>$value){
					if(property_exists($c,$key)){
						$c->{$key}=$value;
					}
				}
			}
			return $c;
		}
	}
    if ( ! function_exists('APBD_getWPTimezoneTime')) {
        function APBD_getWPTimezoneTime( $timestr = '', $format='' ) {
            $timezone = get_option( 'timezone_string' );
            try {
                $apptimezone = date_default_timezone_get();
                if ( ! empty ( $timestr ) ) {
                    $date = new DateTime ( $timestr, new DateTimeZone ( $apptimezone ) );
                } else {
                    $date = new DateTime ();
                }
                if ( ! empty( $timezone ) && strtoupper( $apptimezone ) != strtolower( $timezone ) ) {
                    $date->setTimezone( new DateTimeZone ( $timezone ) );
                }

                if (! empty ( $format )) {
                    return $date->format ( $format );
                } else {
                    return $date->getTimestamp ();
                }
            } catch ( Exception $e ) {
                return $e->getMessage();
            }
        }
    }

	if ( ! function_exists('APBD_getSystemFromWPTimezone')) {
		function APBD_getSystemFromWPTimezone( $timestr = '', $format='' ) {
			$timezone = get_option( 'timezone_string' );
			try {
				$apptimezone = date_default_timezone_get();
				if(empty($timezone)){
					$timezone=$apptimezone;
				}
				if ( !empty($timezone) && ! empty ( $timestr ) ) {
					$date = new DateTime ( $timestr, new DateTimeZone ( $timezone ) );
				} else {
					$date = new DateTime ();
				}
				if ( ! empty( $timezone ) && strtoupper( $apptimezone ) != strtolower( $timezone ) ) {
					$date->setTimezone( new DateTimeZone ( $apptimezone ) );
				}

				if (! empty ( $format )) {
					return $date->format ( $format );
				} else {
					return $date->getTimestamp ();
				}
			} catch ( Exception $e ) {
				return $e->getMessage();
			}
		}
	}
	if ( ! function_exists( "APBD_AppsbdGetMenuList" ) ) {
		function APBD_AppsbdGetMenuList() {
			$locations        = get_nav_menu_locations();
			$menusexitst      = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			$menuarray        = array();
			$locationid       = array();
			$menulocationlist = get_registered_nav_menus();
			foreach ( $locations as $l => $lok ) {
				$locationid[ $lok ] = $menulocationlist[ $l ];
			}

			foreach ( $menusexitst as $me ) {
				$menuarray[ $me->term_id ] = $me->name;
				if ( isset( $locationid[ $me->term_id ] ) ) {
					$menuarray[ $me->term_id ] .= " [" . $locationid[ $me->term_id ] . "]";
				}

			}

			return $menuarray;
		}
	}
	if ( ! function_exists( "APPSBDLoadModel" ) ) {
		function APPSBDLoadModel( $pluginFile, $modelName, $checkClass = "", $defaultext = ".php" ) {
			if ( ! empty( $checkClass ) && class_exists( $checkClass ) ) {
				return;
			}
			if ( ! EndWith( $modelName, $defaultext ) ) {
				$modelName .= ".php";
			}
			$modelPath = dirname( $pluginFile );
			require_once $modelPath . "/model/" . $modelName;
		}
	}
    if ( ! function_exists( "APBD_LoadLib" ) ) {
	function APBD_LoadLib( $pluginFile, $className = "", $defaultext = ".php" ) {
		if ( ! empty( $className ) && class_exists( $className ) ) {
			return;
		}
		if ( ! APBD_EndWith( $className, $defaultext ) ) {
			$className .= ".php";
		}
		$modelPath = plugin_dir_path( $pluginFile );
		require_once $modelPath . "/libs/" . $className;
	}
}
    if ( ! function_exists( "APBD_Load_Any" ) ) {
	    function APBD_Load_Any( $path, $className = "" ) {
		    if ( ! empty( $className ) && class_exists( $className ) ) {
			    return;
		    }
		    require_once $path;
	    }
    }
	if ( ! function_exists( "APBD_LoadCore" ) ) {
		function APBD_LoadCore( $modelName, $checkClass = "", $pathfile="",$defaultext = "") {
			if ( ! empty( $checkClass ) && class_exists( $checkClass ) ) {
				return;
			}
			if ( ! APBD_EndWith( $modelName, $defaultext ) ) {
				$modelName .= ".php";
			}
			if(empty($pathfile)){
				$pathfile= __FILE__ ;
			}
			$modelPath = dirname( $pathfile ) . "/../core";
			require_once $modelPath . "/" . $modelName;
		}
	}
	if ( ! function_exists( "APBD_LoadDatabaseModel" ) ) {
		function APBD_LoadDatabaseModel( $file, $modelName, $checkClass = "", $defaultext = ".php" ) {
			if ( empty( $checkClass ) ) {
				$checkClass = $modelName;
			}
			if ( class_exists( $checkClass ) ) {
				return;
			}
			if ( ! APBD_EndWith( $modelName, $defaultext ) ) {
				$modelName .= ".php";
			}
			$modelPath = dirname( $file ) . "/models/database";
			require_once $modelPath . "/" . $modelName;
		}
	}

	/* For message and hidden field*
	 */
	if ( ! function_exists( "APBD_add_model_errors_code" ) ) {
		function APBD_add_model_errors_code( $msg ) {
			return APBD_AddError( "Error Code:" . $msg );
		}
	}
    if ( ! function_exists( "APBD_Lan_e" ) ) {
        function APBD_Lan_e( $string, $parameter = null, $_ = null ) {
            $args = func_get_args();
            echo call_user_func_array( "APBD_Lan__", $args );
        }
    }
    if ( ! function_exists( "APBD_Lan_ee" ) ) {
        function APBD_Lan_ee( $string, $parameter = null, $_ = null ) {
            $args = func_get_args();
            foreach ( $args as &$arg ) {
                if ( is_string( $arg ) ) {
                    $arg = APBD_Lan__( $arg );
                }
            }
	        echo call_user_func_array( "APBD_Lan__", $args );
        }
    }
	if ( ! function_exists( "APBD_Lan__" ) ) {
		function APBD_Lan__( $string, $domain,$parameter = null, $_ = null ) {


			$args    = func_get_args();
			$args[0] = __( $args[0], $domain );
			if(isset($args[1])){
				unset($args[1]);
			}
			if ( count( $args ) > 1 ) {
				$msg = call_user_func_array( "sprintf", $args );
			} else {
				$msg = $args[0];
			}
			return $msg;
		}
	}
if ( ! function_exists( "apbd_getKarnelObj" ) ) {
    function apbd_getKarnelObj()
    {
        $obj = null;
        if (class_exists("AppsBDKarnelLite") ) {
            $obj = "AppsBDKarnelLite";
        } elseif (class_exists("AppsBDKarnelPro")) {
            $obj = "AppsBDKarnelPro";
        } elseif (class_exists("AppsBDKarnel")) {
            $obj = "AppsBDKarnel";
        }
        return $obj;
    }
}


	if ( ! function_exists( "APBD_AddQueryError" ) ) {
		function APBD_AddQueryError( $msg ) {
			if ( defined( "WP_DEBUG" ) && WP_DEBUG ) {



			}
		}
	}
	if ( ! function_exists( "APBD_AddError" ) ) {
		function APBD_AddError( $msg ) {
			return apbd_getKarnelObj()::AddError( $msg );
		}
	}
	if ( ! function_exists( "APBD_AddWarning" ) ) {
		function APBD_AddWarning( $msg ) {
			return apbd_getKarnelObj()::AddWarning( $msg );
		}
	}
	if ( ! function_exists( "APBD_AddInfo" ) ) {
		function APBD_AddInfo( $msg ) {
			return apbd_getKarnelObj()::AddInfo( $msg );
		}
	}
	if ( ! function_exists( "APBD_GetError" ) ) {
		function APBD_GetError( $prefix = '', $postfix = '' ) {
			return apbd_getKarnelObj()::GetError( $prefix, $postfix );
		}
	}
	if ( ! function_exists( "APBD_GetError" ) ) {
		function APBD_GetInfo( $prefix = '', $postfix = '' ) {
			return apbd_getKarnelObj()::GetInfo( $prefix, $postfix );
		}
	}
	if ( ! function_exists( "APBD_GetMsg" ) ) {
		function APBD_GetMsg( $prefix1 = '<div class="msg alert alert-success show alert-dismissible fade in" role="alert"><i class="fa fa-check"> </i> ',  $prefix2 = '<div class="msg alert alert-error alert-danger" role="alert" ><i class="fa fa-times"> </i> ',$prefix3 = '<div class="msg alert alert-error alert-warning" role="alert" ><i class="fa fa-times"> </i> ', $postfix = '</div>' ) {
			return apbd_getKarnelObj()::GetMsg( $prefix1, $prefix2,$prefix3, $postfix );
		}
	}

	if ( ! function_exists( "APBD_GetMsg_API" ) ) {
		function APBD_GetMsg_API( $prefix1 = '', $prefix2 = '',$prefix3 = '', $postfix = ', ' ) {
			$string= apbd_getKarnelObj()::GetMsg( $prefix1, $prefix2,$prefix3, $postfix );
			return rtrim(strip_tags($string),', ');
		}
	}
	if ( ! function_exists( "APBD_GetHiddenFieldsHTML" ) ) {
		function APBD_GetHiddenFieldsHTML() {
			echo apbd_getKarnelObj()::GetHiddenFieldsHTML();
		}
	}
	if ( ! function_exists( "APBD_HasUIMsg" ) ) {
		function APBD_HasUIMsg() {
			return apbd_getKarnelObj()::HasUIMsg();
		}
	}

	if ( ! function_exists( "APBD_AddHiddenFields" ) ) {
		function APBD_AddHiddenFields( $key, $value ) {
			return apbd_getKarnelObj()::AddHiddenFields( $key, $value );
		}
	}
	if ( ! function_exists( "APBD_GetLastFirstSubString" ) ) {
		function APBD_GetLastFirstSubString( $str, $lastFirstStrLength=4,$middleChar='*',$middleLength=-1 ) {
			$strl=strlen($str);
			if($middleLength <0){
				$middleLength=$strl-($lastFirstStrLength*2);
				$middleLength=$middleLength<1?0:$middleLength;
			}
			return substr($str,0,$lastFirstStrLength).str_repeat($middleChar,$middleLength).substr($str,(-1)*$lastFirstStrLength);
		}
	}
	if ( ! function_exists("APBD_app_add_into_language_msg")) {
		function APBD_app_add_into_language_msg( $title,$path,$str,$pofileName ) {
			if(is_writable($path)){
				if(!is_dir($path)){
					mkdir($path,0740,true);
				}
				$path_po_file=$path.$pofileName;
				$path2=$path."lag_po.ini";
				$path.="lag_po.php";
				$str=strip_tags($str);
				$str=trim($str);
				if(empty($str)){
					return;
				}
				//if (is_writable($filename)) {
				$newstr='_("'.$str.'");';
				$newstr2='lang[]="'.$str.'";';
				$po_string="\nmsgid \"{$str}\"\nmsgstr \"\"\n";
				if(file_exists($path)){
					if( strpos(file_get_contents($path),$newstr) !== false) {
						// do stuff
						return;
					}
				}else{
					$newstr="<?php\n".$newstr;
				}
				$fh = fopen($path, 'a');
				if($fh){
					fwrite($fh, $newstr."\n");
					fclose($fh);
				}
				if(file_exists($path2)){
					if( strpos(file_get_contents($path2),$newstr2) !== false) {
						// do stuff
						return;
					}
				}else{
					//$newstr="<?php\n".$newstr;
				}
				$fh = fopen($path2, 'a');
				if($fh){
					fwrite($fh, $newstr2."\n");
					fclose($fh);
				}
				//po file generate
				$isNew=false;
				$header_str="";
				if(!file_exists($path_po_file)){
					$isNew=true;
					$header_str='
msgid ""
msgstr ""
"Project-Id-Version: '.$title.'\n"
"POT-Creation-Date: '.date("Y-m-d H:i:sO").'\n"
"PO-Revision-Date: '.date("Y-m-d H:i:sO").'\n"
"Last-Translator: \n"
"Language-Team: appsbd\n"
"Language: en_US\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"X-Generator: '.$title.'\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"

';
					for($i=0;$i<=9;$i++){
						$header_str.="\nmsgid \"{$i}\"\nmsgstr \"{$i}\"\n";
					}

				}
				$fh = fopen($path_po_file, 'a');if($fh){if($isNew){fwrite($fh, $header_str."\n");}fwrite($fh, $po_string."\n");fclose($fh);}}
		    }

	    }
	if ( ! function_exists( "APBD_OldFields" ) ) {
		function APBD_OldFields( $obj, $fields ) {
			if ( is_string( $fields ) ) {
				$fields = explode( ",", $fields );
			}
			foreach ( $fields as $fld ) {
				if ( property_exists( $obj, $fld ) ) {
					if ( method_exists( $obj, "IsHTMLProperty" ) ) {
						if ( $obj->IsHTMLProperty( $fld ) ) {
							continue;
						};
					}
					APBD_AddOldFields( $fld, $obj->$fld );
				}
			}
		}
	}

	if ( ! function_exists( "APBD_AddOldFields" ) ) {
		function APBD_AddOldFields( $key, $value ) {
			return apbd_getKarnelObj()::AddOldFields( $key, $value );
		}
	}
	if ( ! function_exists( "APBD_GetHiddenFieldsArray" ) ) {
		function APBD_GetHiddenFieldsArray() {
			return apbd_getKarnelObj()::GetHiddenFieldsArray();
		}
	}
    if ( ! function_exists("AppsbdLoader")) {
	function AppsbdLoader($session_id)
	{
        apbd_getKarnelObj()::AppsbdLoader($session_id);
	}
}
    if ( ! function_exists("APBD_CurrentUrl")) {
	    function APBD_CurrentUrl( $isWithParam = true ) {
	        $apprequest=SupportGNInput::get_server_array();
		    if ( isset( $apprequest['HTTPS'] ) &&
		         ( $apprequest['HTTPS'] == 'on' || $apprequest['HTTPS'] == 1 ) ||
		         isset( $apprequest['HTTP_X_FORWARDED_PROTO'] ) &&
		         $apprequest['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
			    $protocol = 'https://';
		    } else {
			    $protocol = 'http://';
		    }
		    if ( $isWithParam ) {
			    return $protocol . $apprequest['HTTP_HOST'] . $apprequest['REQUEST_URI'];
		    } else {
			    $url_parts = parse_url( $protocol . $apprequest['HTTP_HOST'] . $apprequest['REQUEST_URI'] );

			    return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
		    }
	    }
    }
    if ( ! function_exists('APBD_AddFileLog')) {
	    function APBD_AddFileLog( $log_string, $fileName = "log.txt" ) {


	    }
    }
	if ( ! function_exists('getLinkCustomButton')) {
		function getLinkCustomButton($mainUrl,$buttonUrl,$buttonName) {
			if(strpos($mainUrl,"?")!==false){
				return $mainUrl."&cbtn=".urlencode($buttonUrl)."&cbtnn=".urlencode($buttonName);
			}else{
				return $mainUrl."?cbtn=".$buttonUrl."&cbtnn=".$buttonName;
			}
		}
	}
	if ( ! function_exists('getCustomBackButtion')) {
		function getCustomBackButtion($className="btn btn-sm btn-outline-secondary  mb-2 mt-2 mt-sm-0 mb-sm-0 ") {
			$bkbtn = APBD_GetValue( "cbtn", "" );
			$bkbtname = APBD_GetValue( "cbtnn", "" );
			if ( ! empty( $bkbtn ) ) {
				?>
				<a href="<?php echo esc_url($bkbtn); ?>" data-effect="mfp-move-from-top"
				   class="popupformWR <?php echo esc_attr($className); ?>"> <i
						class="fa fa-angle-double-left"></i> <?php echo !empty($bkbtname)?esc_attr($bkbtname):"Back" ?></a>
			<?php }
		}
	}
	if ( ! function_exists('APBD_zipFile')) {
		/**
		 * function APBD_zipFile.  Creates a zip file from source to destination
		 *
		 * @param  string $source Source path for zip
		 * @param  string $destination Destination path for zip
		 * @param  string|boolean $flag OPTIONAL If true includes the folder also
		 * @return boolean
		 */
		function APBD_zipFile($source, $destination, $flag = '')
		{
			if ( !extension_loaded('zip') ) {
				return false;
			}

			$zip = new ZipArchive();
			$tmp_file = tempnam(WP_CONTENT_DIR,'');
			if (!$zip->open($tmp_file, ZIPARCHIVE::CREATE)) {
				return false;
			}

			$source = str_replace('\\', '/', realpath($source));
			if($flag)
			{
				$flag = basename($source) . '/';
				//$zip->addEmptyDir(basename($source) . '/');
			}

			if (is_dir($source) === true)
			{
				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
				foreach ($files as $file)
				{
					$file = str_replace('\\', '/', realpath($file));

					if (is_dir($file) === true)
					{
						$src = str_replace($source . '/', '', $flag.$file . '/');
						if( WP_PLUGIN_DIR.'/' !== $src ) # Workaround, as it was creating a strange empty folder like /www_dev/dev.plugins/wp-content/plugins/
							$zip->addEmptyDir( $src );
					}
					else if (is_file($file) === true)
					{
						$src = str_replace($source . '/', '', $flag.$file);
						$zip->addFromString( $src, file_get_contents($file));
					}
				}
			}
			else if (is_file($source) === true)
			{
				$zip->addFromString($flag.basename($source), file_get_contents($source));
			}

			$tt = $zip->close();
			if(file_exists($tmp_file))
			{
				// push to download the zip
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="'.$destination.'"');
				readfile($tmp_file);
				// remove zip file is exists in temp path
				exit();
			}
			else {
				echo wp_kses_html($tt);
				die();
			}
		}

	}

	if ( ! function_exists("APBD_GetHTMLRadioBoxByArray")) {

		function APBD_GetHTMLRadioBoxByArray($title,$name, $id, $isRequired, $options, $checkedValue, $isDisabled=false, $bgcolor = '#ffffff',$class="",$attr=array(),$disabled_options=[]){
			?>
            <div class="apbd-app-box-radio">
				<?php
					foreach ($options as $key=>$value){
						$attrStr=" ";
						if(is_array($attr) && count($attr)>0){
							foreach ($attr as $key=>$value){
								$attrStr.=$key.'="'.$value.'" ';
							}
						}
						?>

                        <label class="apbd-app-box-option"> <input class="apbd-app-box-option-input <?php echo esc_attr($class);?>" <?php echo esc_attr($attrStr);?>
                                                              id="<?php echo esc_attr($id);?>" type="radio"
								<?php echo esc_attr($checkedValue==$key?'checked="checked"':"");?>
								<?php if(!$isDisabled && !in_array($key,$disabled_options)){?> name="<?php echo esc_attr($name);?>" <?php }else{?>
                                    disabled="disabled" <?php }?> value="<?php echo esc_attr($key);?>"
								<?php if(!$isDisabled && $isRequired){?> data-bv-notempty="true"
                                    data-bv-notempty-message="Choose <?php echo esc_attr($title);?>" <?php }?> />
                            <span class="apbd-app-box-html" <?php if(!empty($bgcolor)){ ?>style="background-color: <?php echo esc_attr($bgcolor); ?>;" <?php } ?>>
                         <?php echo wp_kses_post($value);?>
                    </span>

                        </label>

						<?php
					}
				?>
            </div>
			<?php
		}
	}
	if ( ! function_exists("APBD_GPrint")) {
		function APBD_GPrint( $obj ) {


		}
	}
	if ( ! function_exists("APBD_GPrintDie")) {
		function APBD_GPrintDie( $obj ) {


		}
	}
	if ( ! function_exists( "APBD_IsValidEmail" ) ) {
		function APBD_IsValidEmail( $email ) {
			return filter_var( $email, FILTER_VALIDATE_EMAIL );
		}
	}
	if ( ! function_exists('APBD_getTextByKey'))
	{
		function APBD_getTextByKey($key,$data=array())
		{
			return !empty($data[$key])?$data[$key]:$key;
		}
	}
	if ( ! function_exists( "APBD_GetHTMLOption" ) ) {
		function APBD_GetHTMLOption( $value, $text, $selected = "" ,$attr=array()){
			$attrStr="";
			if(is_array($attr) && count($attr)>0){
				foreach ($attr as $key=>$kvalue){
					$attrStr.=" ".$key.'="'.$kvalue.'"';
				}
			}
			?>
            <option <?php echo esc_attr($attrStr);?> <?php echo esc_attr($selected==$value?" selected ":"");?>
                    value="<?php echo esc_attr($value);?>"><?php echo wp_kses_post($text);?></option>
			<?php
		}
	}
	if ( ! function_exists("APBD_GetHTMLOptionByArray")){
		function APBD_GetHTMLOptionByArray($options,$selected="",$attr=[]){
			if(is_array($options)){
				foreach ($options as $key=>$value){
					if(is_array($selected)){
						APBD_GetHTMLOption($key,$value,(in_array($key,$selected)?$key:""),$attr);
					}else{
						APBD_GetHTMLOption($key,$value,$selected,$attr);
					}

				}
			}

		}
	}
if(!function_exists("APBD_GetHTMLSwitchButton")){
	function APBD_GetHTMLSwitchButton($id,$name,$default_value="",$boolvalue='',$checkedValue='Y',$isDisabled=false,$input_class='',$label_class='bg-mat',$group_class='material-switch-sm'){
		?><div class="material-switch <?php echo esc_attr($group_class); ?> ">
        <input  name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($default_value); ?>" type="hidden">
        <input  class="<?php echo esc_attr($input_class); ?>" id="<?php echo esc_attr($id); ?>" <?php echo wp_kses_post($isDisabled?' disabled="disabled"' :'name="'.$name.'"');?>  type="checkbox" <?php echo esc_attr($checkedValue ==$boolvalue? "checked" : "")?>  value="<?php echo esc_attr($boolvalue);?>" >
        <label for="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($label_class); ?>"></label>
        </div><?php

	}
}
	if ( ! function_exists("APBD_GetHTMLSwitchButtonInline")){
		function APBD_GetHTMLSwitchButtonInline($id,$name,$default_value,$boolvalue,$checkedValue,$isDisabled=false,$input_class='',$label_class='bg-mat',$group_class='material-switch-sm inline'){
			APBD_GetHTMLSwitchButton($id,$name,$default_value,$boolvalue,$checkedValue,$isDisabled,$input_class,$label_class,$group_class);
		}
	}
    if ( ! function_exists( "APBD_get_remote_ip" ) ) {
        function APBD_get_remote_ip(  ) {
	        $apprequest=SupportGNInput::get_server_array();
            if ( ! empty( $apprequest['HTTP_X_REAL_IP'] ) ) {
                return $apprequest['HTTP_X_REAL_IP'];
            }elseif ( ! empty( $apprequest['HTTP_CLIENT_IP'] ) ) {
                return $apprequest['HTTP_CLIENT_IP'];
            }elseif(!empty($apprequest['HTTP_X_FORWARDED_FOR'])){
                return $apprequest['HTTP_X_FORWARDED_FOR'];
            }elseif(!empty($apprequest['HTTP_CF_CONNECTING_IP'])){
                return $apprequest['HTTP_CF_CONNECTING_IP'];
            }else {
                return ! empty( $apprequest['REMOTE_ADDR'] ) ? $apprequest['REMOTE_ADDR'] : "-";
            }
        }
    }
if ( ! function_exists("wp_kses_html")) {
    function wp_kses_html($html)
    {
        $allowedposttags = wp_kses_allowed_html('post');
        $allowed_atts = array('align' => true, 'class' => true, 'type' => true, 'id' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true, 'src' => true, 'alt' => true, 'href' => true, 'rel' => true, 'rev' => true, 'target' => true, 'novalidate' => true, 'value' => true, 'name' => true, 'tabindex' => true, 'action' => true, 'method' => true, 'for' => true, 'width' => true, 'height' => true, 'data-*' => true,'selected'=>true,"checked"=>true, 'title' => true,);
        $allowedTags=['address','a','abbr','acronym','area','article','aside','audio','b','bdo','big','blockquote','br','button','caption','cite','code','col','colgroup','del','dd','dfn','details','div','dl','dt','em','fieldset','section','figure','figcaption','font','footer','h1','h2','h3','h4','h5','h6','header','hgroup','hr','i','img','ins','kbd','label','legend','li','main','map','mark','menu','nav','p','pre','q','s','samp','span','section','small','strike','strong','sub','summary','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','track','tt','u','ul','ol','var','video','form','input','iframe','script','style','option','select'];
        foreach ($allowedTags as $tag){
            $allowedposttags[$tag] = $allowed_atts;
        }
        return wp_kses($html, $allowedposttags);
    }
}
if ( ! function_exists('app_sanitize_object')) {

    function app_sanitize_object($var)
    {
        if (is_array($var)) {
            return array_map('app_sanitize_object', $var);
        } else {
            return is_scalar($var) ? sanitize_text_field($var) : $var;
        }
    }
}

if(!function_exists('APBD_get_file_system')) {
	/**
	 * @return WP_Filesystem_Direct
	 */
	function &APBD_get_file_system() {
		global $wp_filesystem;
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem();
		return $wp_filesystem;
	}
}
if ( ! function_exists( "APBD_file_get_contents" ) ) {
	function APBD_file_get_contents( $filename){
		$wp_filesystem=APBD_get_file_system();
		return $wp_filesystem->get_contents($filename);
	}
}
if ( ! function_exists( "APBD_read_php_input_stream" ) ) {
	function APBD_read_php_input_stream(){
		$wp_filesystem=APBD_get_file_system();
		return $wp_filesystem->get_contents('php://input');
	}
}
if ( ! function_exists( "APBD_file_put_contents" ) ) {
	function APBD_file_put_contents( $filename, $data, $flags = 0, $context = NULL ) {
		if ( file_put_contents( $filename, $data, $flags, $context ) ) {
			return true;
		} else {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			global $wp_filesystem;

			return $wp_filesystem->put_contents(
				$filename,
				$data,
				FS_CHMOD_FILE // predefined mode settings for WP files
			);
		}
	}
}
if ( ! function_exists( "APBD_AddLogFile" ) ) {
	function APBD_AddLogFile( $data,$isAppend=true,$filename="appsbd.log"){
		$filenamePath=WP_CONTENT_DIR."/".$filename;
		if(!is_string($data)){
			$data=print_r($data,true);
		}
		if($isAppend) {
			return apbd_file_put_contents( $filenamePath, $data, FILE_APPEND );
		}else{
			return apbd_file_put_contents( $filenamePath, $data);
		}
		// in production mode
		return false;
	}
}
if ( ! function_exists( 'APBD_EncryptionKey' ) ) {
	function APBD_EncryptionKey() {
		return md5( rand( 10, 99 ) . rand( 10, 99 ) . time() . rand( 10, 99 ) );
	}
}

/* end hidden field*/