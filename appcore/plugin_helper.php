<?php

if ( ! function_exists( "sanitize_elite_post_slug" ) ) {
    function sanitize_elite_post_slug($name)
    {
        return sanitize_title_with_dashes('apbd-el-' . $name);
    }
}

if ( ! function_exists( "APBD_LoadPluginAPI" ) ) {
	function APBD_LoadPluginAPI(  $className = "", $sub_path='',$defaultext = ".php" ) {
		if ( ! empty( $className ) && class_exists( $className ) ) {
			return;
		}
		if ( ! APBD_EndWith( $className, $defaultext ) ) {
			$className .= ".php";
		}
		if(!empty($sub_path)){
			$sub_path='/'.$sub_path;
		}
		$apifile = dirname(__FILE__)."/../api/".$sub_path."/".$className;
		if(file_exists($apifile)) {
			require_once $apifile;
		}
	}
}
if ( ! function_exists( "APBD_getMimeType" ) ) {
    function APBD_getMimeType($file)
    {
        if (function_exists("mime_content_type")) {
            return mime_content_type($file);
        }
        if (class_exists("finfo")) {
            $finfo = new finfo(FILEINFO_MIME);
            return $finfo->file($file, FILEINFO_MIME_TYPE);
        }
        return '';
    }
}
if ( ! function_exists("APBD_GetHTMLRadioBoxByArrayWithCols")) {

	function APBD_GetHTMLRadioBoxByArrayWithCols($option_group_class='',$option_item_class='',$title='',$name='', $id='', $isRequired=false, $options=[], $checkedValue='Y', $isDisabled=false, $bgcolor = '#ffffff',$class="",$attr=array(),$disabled_options=[]){
		?>
		<div class="apbd-app-box-radio <?php echo esc_attr($option_group_class); ?>">
			<?php
			foreach ($options as $key=>$value){
				$attrStr=" ";
				if(is_array($attr) && count($attr)>0){
					foreach ($attr as $key=>$value){
						$attrStr.=$key.'="'.$value.'" ';
					}
				}
				?>

                <label class="apbd-app-box-option <?php echo is_string($option_item_class) ? $option_item_class : (!empty($option_item_class[$key]) ? $option_item_class[$key] : ''); ?> ">
                    <input class="apbd-app-box-option-input <?php echo esc_attr($class); ?>" <?php echo esc_attr($attrStr); ?>
                           id="<?php echo esc_attr($id); ?>" type="radio"
                        <?php echo esc_attr($checkedValue == $key ? 'checked="checked"' : ""); ?>
                        <?php if (!$isDisabled && !in_array($key, $disabled_options)) { ?> name="<?php echo esc_attr($name); ?>" <?php } else { ?>
                            disabled="disabled" <?php } ?> value="<?php echo esc_attr($key); ?>"
                        <?php if (!$isDisabled && $isRequired) { ?> data-bv-notempty="true"
                            data-bv-notempty-message="<?php esc_attr_e("Choose","support-genix-lite"); ?> <?php echo esc_attr($title); ?>" <?php } ?> />
                    <span class="apbd-app-box-html"
                          <?php if (!empty($bgcolor)){ ?>style="background-color: <?php echo esc_attr($bgcolor); ?>;" <?php } ?>>
                         <?php echo wp_kses_no_null($value); ?>
                    </span>
                </label>

				<?php
			}
			?>
		</div>
		<?php
	}
}


if( !function_exists('apbd_get_user_role_name') ){
    /**
     * @param WP_User $userObject
     * @return string
     */
    function apbd_get_user_role_name($userObject){
        global $wp_roles;
        if(!empty($userObject->roles[0])) {
            $user_role_slug =$userObject->roles[0];
            return translate_user_role($wp_roles->roles[$user_role_slug]['name']);
        }
        return "";
    }
}
if( !function_exists('apbd_editor_text_filter') ) {
    function apbd_editor_text_filter($string)
    {
        $string = str_replace("'", "’", $string);
        return wp_kses_html($string);
    }
}
if( !function_exists('apbd_get_user_title_by_id') ) {
    function apbd_get_user_title_by_id($id)
    {
        if(empty($id)){
            return '';
        }
        $user=get_user_by("id",$id);
        $title=$user->first_name . ' ' . $user->last_name;//Name of ticket user
        if (empty(trim($title))) {
            $title = $user->display_name;
        }
        return $title;
    }
}
if( !function_exists('apbd_get_user_title_by_user') ) {
    /**
     * @param WP_User $user
     * @return string
     */
    function apbd_get_user_title_by_user($user)
    {
        $title="";
        if($user instanceof WP_User) {
            if (!empty($user->first_name) && property_exists($user, 'last_name')) {
                $title = $user->first_name . ' ' . $user->last_name;//Name of ticket user
                if (empty(trim($title))) {
                    $title = $user->display_name;
                }
            } elseif (!empty($user->display_name)) {
                $title = $user->display_name;
            }
        }
        return $title;
    }
}

if(!function_exists('apbd_wps_get_role_users')) {
    /**
     * @param $role
     * @param $orderby
     * @param $order
     * @return WP_User []
     */
    function apbd_wps_get_role_users($role, $orderby, $order)
    {
        $args = array(
            'role' => $role,
            'orderby' => $orderby,
            'order' => $order
        );
        $users = get_users($args);
        return $users;
    }
}