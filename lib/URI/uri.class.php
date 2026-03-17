<?php
if (!defined('_GNUBOARD_')) exit;

class G5_URI {
	public $basename;
	public $parts;
	public $slashes;

    public static function getInstance()
    {   //싱글톤
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

	public function parseURL() {
		/* grab URL query string and script name */
		$uri = $_SERVER['REQUEST_URI'];
		$script = $_SERVER['SCRIPT_NAME'];
		/* get extension */
		$script_names = explode(".",$script);
		$ext = end($script_names);

		/* if extension is found in URL, eliminate it */
		if(strstr($uri,".")) {
			$arr_uri = explode('.', $uri);
			/* get last part */
			$last = end($arr_uri);

			if($last == $ext){
				array_pop($arr_uri);
				$uri = implode('.', $arr_uri);
			}
		}

		/* pick the name without extension */
		$basename = basename ($script, '.'.$ext);
		/* slicing query string */
		$temp  = explode('/',$uri);
		$key   = array_search($basename,$temp);
		$parts = array_slice ($temp, $key+1);
		$this->basename = $basename;
		$this->parts = $parts;

	}

	public function setRelative($relativevar) {
		/* count the number of slash
		   to define relative path */
		$numslash = count($this->parts);
		$slashes="";
		for($i=0;$i<$numslash;$i++){
			$slashes .= "../";
		}
		$this->slashes = $slashes;
		/* make relative path variable available for webpage */
		//eval("\$GLOBALS['$relativevar'] = '$slashes';");

        $links = array();
        $links[$relativevar] = $slashes;

        return $links;
	}

	public function getParts() {
		/* return array of sliced query string */
		return $this->parts;
	}

	public function setParts() {
		/* pair off query string variable and query string value */
		$numargs = func_num_args();
		$arg_list = func_get_args();
		$urlparts = $this->getParts();
        $links = array();
		for ($i = 0; $i < $numargs; $i++) {
			/* make them available for webpage */
			//eval ("\$GLOBALS['".$arg_list[$i] ."']= '$urlparts[$i]';");
            $links[$arg_list[$i]] = $urlparts[$i];
		}

        return $links;
   }
   /** 
   * convert normal URL query string to clean URL 
   */
   public function makeClean($string_url) {
	$url = parse_url($string_url);
        $strUrl = basename($url['path'],".php");
        parse_str($url['query'],$queryString);
        foreach($queryString as $value){
            $strUrl .= "/$value";
        }
        return $strUrl;
   }

   public function url_clean($string_url, $add_qry='') {
        global $config;

        $string_url = str_replace('&amp;', '&', $string_url);
		$url=parse_url($string_url);
		$page_name = isset($url['path']) ? basename($url['path'],".php") : '';

        if ($page_name !== 'content') {
            return $string_url;
        }

        $return_url = '';
	    parse_str($url['query'], $vars);

        $fragment = isset($url['fragment']) ? '#'.$url['fragment'] : '';
        $host = G5_URL;
        $normalized_string_url = preg_replace('/^https?:/i', '', $string_url);
        $normalized_content_url = preg_replace('/^https?:/i', '', G5_URL.'/content.php');

        if (strpos($normalized_string_url, $normalized_content_url) === false) {
            return $string_url;
        }

        if( isset($url['host']) ){
            $array_file_paths = run_replace('url_clean_page_paths', array('/content.php'));

            $str_path = isset($url['path']) ? $url['path'] : '';
            $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://';
            $host = $http.$url['host'].str_replace($array_file_paths, '', $str_path);
        }

        $add_param = '';
        $allow_param_keys = array('co_id'=>'');

        if( $result = array_diff_key($vars, $allow_param_keys ) ){
            $add_param = '?'.http_build_query($result,'','&amp;');
        }

        if( $add_qry ){
            $add_param .= $add_param ? '&amp;'.$add_qry : '?'.$add_qry;
        }

        if (!empty($vars['co_id'])) {
            if ($config['cf_bbs_rewrite'] > 1) {
                $content = get_content_db($vars['co_id'], true);
                $return_url = '/'.((isset($content['co_seo_title']) && $content['co_seo_title']) ? $content['co_seo_title'].'/' : $vars['co_id']);
            } else {
                $return_url = '/'.$vars['co_id'];
            }
        }

        return $host.'/content'.$return_url.$add_param.$fragment;
   }
}
