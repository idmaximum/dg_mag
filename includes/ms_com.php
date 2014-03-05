<?php
/**
* @version $Id: mambo.php,v 1.45 2005/02/16 17:06:08 eddieajau Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

	/** ensure this file is being included by a parent file */
	defined( '_VALID_ACCESS' ) or die( 'Direct Access to this location is not allowed.  ms_com' );
	define( '_MOS_MAMBO_INCLUDED', 1 );

	if (phpversion() < '4.2.0') {
		require_once( $_Config_absolute_path . '/includes/compat.php41x.php' );
	}
	if (phpversion() < '4.3.0') {
		require_once( $_Config_absolute_path . '/includes/compat.php42x.php' );
	}
	if (in_array( 'globals', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
		die( 'Fatal error.  Global variable hack attempted.' );
	}

	@set_magic_quotes_runtime( 0 );

	if (@$_Config_error_reporting === 0) {
		error_reporting( 0 );
	} else if (@$_Config_error_reporting > 0) {
		error_reporting( $_Config_error_reporting );
	}

	#Connect to MySQL & Create Object
	require_once( $_Config_absolute_path . "/includes/adodb/adodb.inc.php" );



	if (sizeof($_POST) > 0)
	  $_FORM = $_POST;
	else if (sizeof($_GET) > 0)
	  $_FORM = $_GET;
	else
	  $_FORM = array("");
	
	/**
	* Connect to MySQL with ADODB
	* 
	*/
	function mosConnectADODB(){
		$DB = NewADOConnection('mysql');
		//$DB->Connect("localhost", "dgmaga", "Qvwa794^", "idmaxdev_dgmaga");
		$DB->Connect("localhost", "root", "12345", "dgmagazine");
		
		if (!$DB) die("Connection failed"); 
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		
		$DB->Execute("SET character_set_results=utf8");
		$DB->Execute("SET collation_connection=utf8_general_ci");
		$DB->Execute("SET NAMES 'utf8'");	
		
		return $DB;
	}
	  
	  
	  
	/**
	* Utility function to return a value from a named array or a specified default
	*/
	define( "_MOS_NOTRIM", 0x0001 );
	define( "_MOS_ALLOWHTML", 0x0002 );
	function trimArray($input)
	{
		  if(is_array($input))
		  {
				$key = array_keys($input);
				for($i=0;$i<sizeof($key);$i++)
				{
					$input[$key[$i]] =  stripslashes(trim($input[$key[$i]]));
				}
				return $input;
			}
			else
			{
				return $input;
			}
	}
	function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
		$return = null;
		if (isset( $arr[$name] )) {
			if (is_string( $arr[$name] )) {
				if (!($mask&_MOS_NOTRIM)) {
					$arr[$name] = trim( $arr[$name] );
				}
				if (!($mask&_MOS_ALLOWHTML)) {
					$arr[$name] = strip_tags( $arr[$name] );
				}
				if (!get_magic_quotes_gpc()) {
					$arr[$name] = addslashes( $arr[$name] );
				}
			}
			return $arr[$name];
		} else {
			return $def;
		}
	}

	/**
	* Strip slashes from strings or arrays of strings
	* @param value the input string or array
	*/
	function mosStripslashes(&$value)
	{
		$ret = '';
		if (is_string($value)) {
			$ret = stripslashes($value);
		} else {
			if (is_array($value)) {
				$ret = array();
				while (list($key,$val) = each($value)) {
					$ret[$key] = mosStripslashes($val);
				} // while
			} else {
				$ret = $value;
			} // if
		} // if
		return $ret;
	} // mosStripSlashes

	
	#Clear Text
	function clearText($data){
	
		return mosStripslashes(htmlspecialchars($data));
		
	}

	/**
	* Copy the named array content into the object as properties
	* only existing properties of object are filled. when undefined in hash, properties wont be deleted
	* @param array the input array
	* @param obj byref the object to fill of any class
	* @param string
	* @param boolean
	*/
	function mosBindArrayToObject( $array, &$obj, $ignore="", $prefix=NULL, $checkSlashes=true ) {
		if (!is_array( $array ) || !is_object( $obj )) {
			return (false);
		}

		if ($prefix) {
			foreach (get_object_vars($obj) as $k => $v) {
				if (strpos( $ignore, $k) === false) {
					if (isset($array[$prefix . $k ])) {
						$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes( $array[$k] ) : $array[$k];
					}
				}
			}
		} else {
			foreach (get_object_vars($obj) as $k => $v) {
				if (strpos( $ignore, $k) === false) {
					if (isset($array[$k])) {
						$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes( $array[$k] ) : $array[$k];
					}
				}
			}
		}

		return true;
	}

	/**
	* Utility function to read the files in a directory
	* @param string The file system path
	* @param string A filter for the names
	* @param boolean Recurse search into sub-directories
	* @param boolean True if to prepend the full path to the file name
	*/
	function mosReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
		$arr = array();
		if (!@is_dir( $path )) {
			return $arr;
		}
		$handle = opendir( $path );

		while ($file = readdir($handle)) {
			$dir = mosPathName( $path.'/'.$file, false );
			$isDir = is_dir( $dir );
			if (($file <> ".") && ($file <> "..")) {
				if (preg_match( "/$filter/", $file )) {
					if ($fullpath) {
						$arr[] = trim( mosPathName( $path.'/'.$file, false ) );
					} else {
						$arr[] = trim( $file );
					}
				}
				if ($recurse && $isDir) {
					$arr2 = mosReadDirectory( $dir, $filter, $recurse, $fullpath );
					$arr = array_merge( $arr, $arr2 );
				}
			}
		}
		closedir($handle);
		asort($arr);
		return $arr;
	}

	/**
	* Utility function redirect the browser location to another url
	*
	* Can optionally provide a message.
	* @param string The file system path
	* @param string A filter for the names
	*/
	function mosRedirect( $url, $msg='' ) {
		if (trim( $msg )) {
			if (strpos( $url, '?' )) {
				$url .= '&mosmsg=' . urlencode( $msg );
			} else {
				$url .= '?mosmsg=' . urlencode( $msg );
			}
		}

		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			header( "Location: $url" );
			//header ("Refresh: 0 url=$url");
		}
		exit();
	}

	function mosTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) {
		if (@$children[$id] && $level <= $maxlevel) {
			foreach ($children[$id] as $v) {
				$id = $v->id;

				if ( $type ) {
					$pre 	= '<sup>L</sup>&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else {
					$pre 	= '- ';
					$spacer = '&nbsp;&nbsp;';
				}

				if ( $v->parent == 0 ) {
					$txt 	= $v->name;
				} else {
					$txt 	= $pre . $v->name;
				}
				$pt = $v->parent;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count( @$children[$id] );
				$list = mosTreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
			}
		}
		return $list;
	}

	/**
	* Function to strip additional / or \ in a path name
	* @param string The path
	* @param boolean Add trailing slash
	*/
	function mosPathName($p_path,$p_addtrailingslash = true) {
		$retval = "";

		$isWin = (substr(PHP_OS, 0, 3) == 'WIN');

		if ($isWin)	{
			$retval = str_replace( '/', '\\', $p_path );
			if ($p_addtrailingslash) {
				if (substr( $retval, -1 ) != '\\') {
					$retval .= '\\';
				}
			}
			// Remove double \\
			$retval = str_replace( '\\\\', '\\', $retval );
		} else {
			$retval = str_replace( '\\', '/', $p_path );
			if ($p_addtrailingslash) {
				if (substr( $retval, -1 ) != '/') {
					$retval .= '/';
				}
			}
			// Remove double //
			$retval = str_replace('//','/',$retval);
		}

		return $retval;
	}

	function mosObjectToArray($p_obj)
	{
		$retarray = null;
		if(is_object($p_obj))
		{
			$retarray = array();
			foreach (get_object_vars($p_obj) as $k => $v)
			{
				if(is_object($v))
				$retarray[$k] = mosObjectToArray($v);
				else
				$retarray[$k] = $v;
			}
		}
		return $retarray;
	}
	/**
	* Checks the user agent string against known browsers
	*/
	function mosGetBrowser( $agent ) {
		require( "includes/agent_browser.php" );

		if (preg_match( "/msie[\/\sa-z]*([\d\.]*)/i", $agent, $m )
		&& !preg_match( "/webtv/i", $agent )
		&& !preg_match( "/omniweb/i", $agent )
		&& !preg_match( "/opera/i", $agent )) {
			// IE
			return "MS Internet Explorer $m[1]";
		} else if (preg_match( "/netscape.?\/([\d\.]*)/i", $agent, $m )) {
			// Netscape 6.x, 7.x ...
			return "Netscape $m[1]";
		} else if ( preg_match( "/mozilla[\/\sa-z]*([\d\.]*)/i", $agent, $m )
		&& !preg_match( "/gecko/i", $agent )
		&& !preg_match( "/compatible/i", $agent )
		&& !preg_match( "/opera/i", $agent )
		&& !preg_match( "/galeon/i", $agent )
		&& !preg_match( "/safari/i", $agent )) {
			// Netscape 3.x, 4.x ...
			return "Netscape $m[2]";
		} else {
			// Other
			$found = false;
			foreach ($browserSearchOrder as $key) {
				if (preg_match( "/$key.?\/([\d\.]*)/i", $agent, $m )) {
					$name = "$browsersAlias[$key] $m[1]";
					return $name;
					break;
				}
			}
		}

		return 'Unknown';
	}

	/**
	* Checks the user agent string against known operating systems
	*/
	function mosGetOS( $agent ) {
		require( "includes/agent_os.php" );

		foreach ($osSearchOrder as $key) {
			if (preg_match( "/$key/i", $agent )) {
				return $osAlias[$key];
				break;
			}
		}

		return 'Unknown';
	}

	/**
	* @param string SQL with ordering As value and 'name field' AS text
	* @param integer The length of the truncated headline
	*/
	function mosGetOrderingList( $sql, $chop='30' ) {
		global $database;

		$order = array();
		$database->setQuery( $sql );
		if (!($orders = $database->loadObjectList())) {
			if ($database->getErrorNum()) {
				echo $database->stderr();
				return false;
			} else {
				$order[] = mosHTML::makeOption( 1, 'first' );
				return $order;
			}
		}
		$order[] = mosHTML::makeOption( 0, '0 first' );
		for ($i=0, $n=count( $orders ); $i < $n; $i++) {

			if (strlen($orders[$i]->text) > $chop) {
				$text = substr($orders[$i]->text,0,$chop)."...";
			} else {
				$text = $orders[$i]->text;
			}

			$order[] = mosHTML::makeOption( $orders[$i]->value, $orders[$i]->value.' ('.$text.')' );
		}
		$order[] = mosHTML::makeOption( $orders[$i-1]->value+1, ($orders[$i-1]->value+1).' last' );

		return $order;
	}

	/**
	* Makes a variable safe to display in forms
	*
	* Object parameters that are non-string, array, object or start with underscore
	* will be converted
	* @param object An object to be parsed
	* @param int The optional quote style for the htmlspecialchars function
	* @param string|array An optional single field name or array of field names not
	*                     to be parsed (eg, for a textarea)
	*/
	function mosMakeHtmlSafe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
		if (is_object( $mixed )) {
			foreach (get_object_vars( $mixed ) as $k => $v) {
				if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' ) {
					continue;
				}
				if (is_string( $exclude_keys ) && $k == $exclude_keys) {
					continue;
				} else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys )) {
					continue;
				}
				$mixed->$k = htmlspecialchars( $v, $quote_style );
			}
		}
	}

	/**
	* Checks whether a menu option is within the users access level
	* @param int Item id number
	* @param string The menu option
	* @param int The users group ID number
	* @param database A database connector object
	* @return boolean True if the visitor's group at least equal to the menu access
	*/
	function mosMenuCheck( $Itemid, $menu_option, $task, $gid ) {
		global $database;
		$dblink="index.php?option=$menu_option";
		if ($Itemid!="" && $Itemid!=0) {
			$database->setQuery( "SELECT access FROM #__menu WHERE id='$Itemid'" );
		} else {
			if ($task!="") {
				$dblink.="&task=$task";
			}
			$database->setQuery( "SELECT access FROM #__menu WHERE link like '$dblink%'" );
		}
		$results = $database->loadObjectList();
		$access = 0;
		//echo "<pre>"; print_r($results); echo "</pre>";
		foreach ($results as $result) {
			$access = max( $access, $result->access );
		}
		return ($access <= $gid);
	}

	/**
	* Utility function to provide ToolTips
	* @param string ToolTip text
	* @param string Box title
	* @returns HTML code for ToolTip
	*/
	function mosToolTip( $tooltip, $title='', $width='', $image='tooltip.png', $text='', $href='#' ) {
		global $mosConfig_live_site;

		if ( $width ) {
			$width = ', WIDTH, \''.$width .'\'';
		}
		if ( $title ) {
			$title = ', CAPTION, \''.$title .'\'';
		}
		if ( !$text ) {
			$image 	= $mosConfig_live_site . '/includes/js/ThemeOffice/'. $image;
			$text 	= '<img src="'. $image .'" border="0" />';
		}
		$style = 'style="text-decoration: none; color: #333;"';
		if ( $href ) {
			$style = '';
		}
		$tip 	= "<a href=\"". $href ."\" onMouseOver=\"return overlib('" . $tooltip . "'". $title .", BELOW, RIGHT". $width .");\" onmouseout=\"return nd();\" ". $style .">". $text ."</a>";
		return $tip;
	}

	/**
	* Utility function to provide Warning Icons
	* @param string Warning text
	* @param string Box title
	* @returns HTML code for Warning
	*/
	function mosWarning($warning, $title='Mambo Warning') {
		global $mosConfig_live_site;
		$tip = "<a href=\"#\" onMouseOver=\"return overlib('" . $warning . "', CAPTION, '$title', BELOW, RIGHT);\" onmouseout=\"return nd();\"><img src=\"" . $mosConfig_live_site . "/includes/js/ThemeOffice/warning.png\" border=\"0\" /></a>";
		return $tip;
	}

	function mosCreateGUID(){
		srand((double)microtime()*1000000);
		$r = rand ;
		$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
		$m = md5 ($u);
		return($m);
	}

	function mosCompressID( $ID ){
		return(Base64_encode(pack("H*",$ID)));
	}

	function mosExpandID( $ID ) {
		return ( implode(unpack("H*",Base64_decode($ID)), '') );
	}

	/**
	* Initialise GZIP
	*/
	function initGzip() {
		global $mosConfig_gzip, $do_gzip_compress;
		$do_gzip_compress = FALSE;
		if ($mosConfig_gzip == 1) {
			$phpver = phpversion();
			$useragent = mosGetParam( $_SERVER, 'HTTP_USER_AGENT', '' );
			$canZip = mosGetParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

			if ( $phpver >= '4.0.4pl1' &&
					( strpos($useragent,'compatible') !== false ||
					  strpos($useragent,'Gecko')      !== false
					)
			   ) {
				if ( extension_loaded('zlib') ) {
					ob_start( 'ob_gzhandler' );
					return;
				}
			} else if ( $phpver > '4.0' ) {
				if ( strpos($canZip,'gzip') !== false ) {
					if (extension_loaded( 'zlib' )) {
						$do_gzip_compress = TRUE;
						ob_start();
						ob_implicit_flush(0);

						header( 'Content-Encoding: gzip' );
						return;
					}
				}
			}
		}
		ob_start();
	}

	/**
	* Perform GZIP
	*/
	function doGzip() {
		global $do_gzip_compress;
		if ( $do_gzip_compress ) {
			/**
			*Borrowed from php.net!
			*/
			$gzip_contents = ob_get_contents();
			ob_end_clean();

			$gzip_size = strlen($gzip_contents);
			$gzip_crc = crc32($gzip_contents);

			$gzip_contents = gzcompress($gzip_contents, 9);
			$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

			echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
			echo $gzip_contents;
			echo pack('V', $gzip_crc);
			echo pack('V', $gzip_size);
		} else {
			ob_end_flush();
		}
	}

	/**
	* Random password generator
	* @return password
	*/
	function mosMakePassword() {
		$salt = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789";
		$len = strlen($salt);
		$makepass="";
		mt_srand(10000000*(double)microtime());
		for ($i = 0; $i < 10; $i++)
		$makepass .= $salt[mt_rand(0,$len - 1)];
		return $makepass;
	}

	if (!function_exists('html_entity_decode')) {
		/**
		* html_entity_decode function for backward compatability in PHP
		* @param string
		* @param string
		*/
		function html_entity_decode ($string, $opt = ENT_COMPAT) {

			$trans_tbl = get_html_translation_table (HTML_ENTITIES);
			$trans_tbl = array_flip ($trans_tbl);

			if ($opt & 1) { // Translating single quotes
			// Add single quote to translation table;
			// doesn't appear to be there by default
			$trans_tbl["&apos;"] = "'";
			}

			if (!($opt & 2)) { // Not translating double quotes
			// Remove double quote from translation table
			unset($trans_tbl["&quot;"]);
			}

			return strtr ($string, $trans_tbl);
		}
	}

/**
* Utility class for all HTML drawing classes
* @package Mambo
*/
class mosHTML {

	function makeOption( $value, $text='' ) {
		$obj = new stdClass;
		$obj->value = $value;
		$obj->text = trim( $text ) ? $text : $value;
		return $obj;
	}

  function writableCell( $folder ) {

  	echo '<tr>';
  	echo '<td class="item">' . $folder . '/</td>';
  	echo '<td align="left">';
  	echo is_writable( "../$folder" ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
  	echo '</tr>';
  }

	/**
	* Generates an HTML select list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function selectList( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL ) {
		reset( $arr );
		$html = "\n<select name=\"$tag_name\" $tag_attribs>";
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " selected=\"selected\"" : '');
			}
			$html .= "\n\t<option value=\"".$k."\"$extra>" . $t . "</option>";
		}
		$html .= "\n</select>\n";
		return $html;
	}

	/**
	* Writes a select list of integers
	* @param int The start integer
	* @param int The end integer
	* @param int The increment
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The printf format to be applied to the number
	* @returns string HTML for the select list
	*/
	function integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format="" ) {
		$start = intval( $start );
		$end = intval( $end );
		$inc = intval( $inc );
		$arr = array();
		for ($i=$start; $i <= $end; $i+=$inc) {
			$fi = $format ? sprintf( "$format", $i ) : "$i";
			$arr[] = mosHTML::makeOption( $fi, $fi );
		}

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Writes a select list of month names based on Language settings
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function monthSelectListOriginal( $tag_name, $tag_attribs, $selected ) {
		$arr = array(
		mosHTML::makeOption( '01', _JAN ),
		mosHTML::makeOption( '02', _FEB ),
		mosHTML::makeOption( '03', _MAR ),
		mosHTML::makeOption( '04', _APR ),
		mosHTML::makeOption( '05', _MAY ),
		mosHTML::makeOption( '06', _JUN ),
		mosHTML::makeOption( '07', _JUL ),
		mosHTML::makeOption( '08', _AUG ),
		mosHTML::makeOption( '09', _SEP ),
		mosHTML::makeOption( '10', _OCT ),
		mosHTML::makeOption( '11', _NOV ),
		mosHTML::makeOption( '12', _DEC )
		);

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	function dateSelectList( $tag_name, $tag_attribs, $selected ) {						

		$arr = array(
		mosHTML::makeOption( '', 'วัน' ), mosHTML::makeOption( '01', '01' ), mosHTML::makeOption( '02', '02' ), mosHTML::makeOption( '03', '03' ), mosHTML::makeOption( '04', '04' ), mosHTML::makeOption( '05', '05' ),								
		mosHTML::makeOption( '06', '06' ), mosHTML::makeOption( '07', '07' ), mosHTML::makeOption( '08', '08' ), mosHTML::makeOption( '09', '09' ), mosHTML::makeOption( '10', '10' ),
		mosHTML::makeOption( '11', '11' ), mosHTML::makeOption( '12', '12' ), mosHTML::makeOption( '13', '13' ), mosHTML::makeOption( '14', '14' ), mosHTML::makeOption( '15', '15' ),	
		mosHTML::makeOption( '16', '16' ), mosHTML::makeOption( '17', '17' ), mosHTML::makeOption( '18', '18' ), mosHTML::makeOption( '19', '19' ), mosHTML::makeOption( '20', '20' ),							
		mosHTML::makeOption( '21', '21' ), mosHTML::makeOption( '22', '22' ), mosHTML::makeOption( '23', '23' ), mosHTML::makeOption( '24', '24' ), mosHTML::makeOption( '25', '25' ),	
		mosHTML::makeOption( '26', '26' ), mosHTML::makeOption( '27', '27' ), mosHTML::makeOption( '28', '28' ), mosHTML::makeOption( '29', '29' ), mosHTML::makeOption( '30', '30' ),	
		mosHTML::makeOption( '31', '31' ));					
				
		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	function monthSelectList( $tag_name, $tag_attribs, $selected ) {						

		$arr = array(
		mosHTML::makeOption( '', 'เดือน' ),
		mosHTML::makeOption( '01', 'มกราคม' ),
		mosHTML::makeOption( '02', 'กุมภาพันธ์' ),
		mosHTML::makeOption( '03', 'มีนาคม' ),
		mosHTML::makeOption( '04', 'เมษายน' ),
		mosHTML::makeOption( '05', 'พฤษภาคม' ),								
		mosHTML::makeOption( '06', 'มิถุนายน' ),
		mosHTML::makeOption( '07', 'กรกฎาคม' ),
		mosHTML::makeOption( '08', 'สิงหาคม' ),
		mosHTML::makeOption( '09', 'กันยายน' ),
		mosHTML::makeOption( '10', 'ตุลาคม' ),
		mosHTML::makeOption( '11', 'พฤศจิกายน' ),
		mosHTML::makeOption( '12', 'ธันวาคม' ));					
				
		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	
	/**
	* Generates an HTML select list from a tree based query list
	* @param array Source array with id and parent fields
	* @param array The id of the current list item
	* @param array Target array.  May be an empty array.
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function treeSelectList( &$src_list, $src_id, $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected ) {

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ($src_list as $v ) {
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$ilist = mosTreeRecurse( 0, '', array(), $children );

		// assemble menu items to the array
		$this_treename = '';
		foreach ($ilist as $item) {
			if ($this_treename) {
				if ($item->id != $src_id && strpos( $item->treename, $this_treename ) === false) {
					$tgt_list[] = mosHTML::makeOption( $item->id, $item->treename );
				}
			} else {
				if ($item->id != $src_id) {
					$tgt_list[] = mosHTML::makeOption( $item->id, $item->treename );
				} else {
					$this_treename = "$item->treename/";
				}
			}
		}
		// build the html select list
		return mosHTML::selectList( $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected );
	}

	/**
	* Writes a yes/no select list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function yesnoSelectList( $tag_name, $tag_attribs, $selected, $yes=_CMN_YES, $no=_CMN_NO ) {
		$arr = array(
		mosHTML::makeOption( '0', $no ),
		mosHTML::makeOption( '1', $yes ),
		);

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Generates an HTML radio list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radioList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );
		$html = "";
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" value=\"".$k."\"$extra $tag_attribs />" . $t;
		}
		$html .= "\n";
		return $html;
	}

	/**
	* Writes a yes/no radio list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes=_CMN_YES, $no=_CMN_NO ) {
		$arr = array(
		mosHTML::makeOption( '0', $no, true ),
		mosHTML::makeOption( '1', $yes, true )
		);
		return mosHTML::radioList( $arr, $tag_name, $tag_attribs, $selected );
	}

	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	* @return string
	*/
	function idBox( $rowNum, $recId, $checkedOut=false, $name='cid' ) {
		if ( $checkedOut ) {
			return '';
		} else {
			return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
		}
	}

	function sortIcon( $base_href, $field, $state='none' ) {
		global $mosConfig_live_site;

		$alts = array(
		'none' => _CMN_SORT_NONE,
		'asc' => _CMN_SORT_ASC,
		'desc' => _CMN_SORT_DESC,
		);
		$next_state = 'asc';
		if ($state == 'asc') {
			$next_state = 'desc';
		} else if ($state == 'desc') {
			$next_state = 'none';
		}

		$html = "<a href=\"$base_href&field=$field&order=$next_state\">"
		. "<img src=\"$mosConfig_live_site/images/M_images/sort_$state.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"{$alts[$next_state]}\" />"
		. "</a>";
		return $html;
	}

	/**
	* Writes Close Button
	*/
	function CloseButton ( &$params, $hide_js=NULL ) {
		// displays close button in Pop-up window
		if ( $params->get( 'popup' ) && !$hide_js ) {
			?>
			<div align="center" style="margin-top: 30px; margin-bottom: 30px;">
			<a href='javascript:window.close();'>
			<span class="small">
			<?php echo _PROMPT_CLOSE;?>
			</span>
			</a>
			</div>
			<?php
		}
	}

	/**
	* Writes Back Button
	*/
	function BackButton ( &$params, $hide_js=NULL ) {
		// Back Button
		if ( $params->get( 'back_button' ) && !$params->get( 'popup' ) && !$hide_js) {
			?>
			<div class="back_button">
			<a href='javascript:history.go(-1)'>
			<?php echo _BACK; ?>
			</a>
			</div>
			<?php
		}
	}

	/**
	* Cleans text of all formating and scripting code
	*/
	function cleanText ( &$text ) {
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
		$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
		$text = preg_replace( '/<!--.+?-->/', '', $text );
		$text = preg_replace( '/{.+?}/', '', $text );
		$text = preg_replace( '/&nbsp;/', ' ', $text );
		$text = preg_replace( '/&amp;/', ' ', $text );
		$text = preg_replace( '/&quot;/', ' ', $text );
		$text = strip_tags( $text );
		$text = htmlspecialchars( $text );
		return $text;
	}

	/**
	* Writes Print icon
	*/
	function PrintIcon( &$row, &$params, $hide_js, $link, $status=NULL ) {
		global $mosConfig_live_site, $mosConfig_absolute_path, $cur_template, $Itemid;
		if ( $params->get( 'print' )  && !$hide_js ) {
			// use default settings if none declared
			if ( !$status ) {
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			}

			// checks template image directory for image, if non found default are loaded
			if ( $params->get( 'icons' ) ) {
				$image = mosAdminMenus::ImageCheck( 'printButton.png', '/images/M_images/', NULL, NULL, _CMN_PRINT );
			} else {
				$image = _ICON_SEP .'&nbsp;'. _CMN_PRINT. '&nbsp;'. _ICON_SEP;
			}

			if ( $params->get( 'popup' ) && !$hide_js ) {
				// Print Preview button - used when viewing page
				?>
				<td align="right" width="100%" class="buttonheading">
				<a href="#" onclick="javascript:window.print(); return false" title="<?php echo _CMN_PRINT;?>">
				<?php echo $image;?>
				</a>
				</td>
				<?php
			} else {
				// Print Button - used in pop-up window
				?>
				<td align="right" width="100%" class="buttonheading">
				<a href="javascript:void window.open('<?php echo $link; ?>', 'win2', '<?php echo $status; ?>');" title="<?php echo _CMN_PRINT;?>">
				<?php echo $image;?>
				</a>
				</td>
				<?php
			}
		}
	}//End Class mosHTML

	/**
	* simple Javascript Cloaking
	* email cloacking
 	* by default replaces an email with a mailto link with email cloacked
	*/
	function emailCloaking( $mail, $mailto=1, $text='' ) {
		// convert text
		$mail 	= mosHTML::encoding_converter( $mail );
		// split email by @ symbol
		$mail	= explode( '@', $mail );
		$mail1	= explode( '.', $mail[1] );
		// random number
		$rand	= rand( 1, 100000 );

		$replacement 	= "<script language='JavaScript' type='text/javascript'>";
		$replacement 	.= "\n<!--\n";
		$replacement 	.= "var prefix = '&#109;a' + 'i&#108;' + '&#116;o';\n";
		$replacement 	.= "var path = 'hr' + 'ef' + '=';\n";
		$replacement 	.= "var addy". $rand ." = '". @$mail[0] ."' + '&#64;' + '". @$mail1[0] ."' + '&#46;' + '". @$mail1[1] ."';\n";
		if ( $mailto ) {
			// special handling when mail text is different from mail addy
			if ( $text ) {
				// convert text
				$text 	= mosHTML::encoding_converter( $text );
				// split email by @ symbol
				$text 	= explode( '@', $text );
				$text1	= explode( '.', $text[1] );
				$replacement 	.= "var addy_text". $rand ." = '". @$text[0] ."' + '&#64;' + '". @$text1[0] ."' + '&#46;' + '". @$text1[1] ."';\n";
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' + addy_text". $rand ." + '</a>' );";
			} else {
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' + addy". $rand ." + '</a>' );";
			}
		} else {
			$replacement 	.= "document.write( addy". $rand ." );";
		}
		$replacement 	.= "\n//-->\n";
		$replacement 	.= "</script>";
		$replacement 	.= "<noscript>";
		$replacement 	.= _CLOAKING;
		$replacement 	.= "</noscript>";

		return $replacement;
	}

	function encoding_converter( $text ) {
		// replace vowels with character encoding
		$text 	= str_replace( 'a', '&#97;', $text );
		$text 	= str_replace( 'e', '&#101;', $text );
		$text 	= str_replace( 'i', '&#105;', $text );
		$text 	= str_replace( 'o', '&#111;', $text );
		$text	= str_replace( 'u', '&#117;', $text );

		return $text;
	}
}

/*
// define
			class connMySQL

			// Utility
			mosGetParam
			mosStripslashes
			mosBindArrayToObject
			mosReadDirectory
			mosRedirect
			mosTreeRecurse
			mosPathName
			mosObjectToArray
			mosGetBrowser
			mosGetOS
			mosGetOrderingList
			mosMakeHtmlSafe
			mosMenuCheck
			mosToolTip
			mosWarning
			mosCreateGUID
			mosCompressID
			mosExpandID
			initGzip
			doGzip
			mosMakePassword
			html_entity_decode

709 : class mosHTML
*/




?>