<?
class FU{ //Function Utilities
 
		/* Resize Picture on the fly */
		#===================================================================
		function thumbnail_calcsize($w, $h, $square){    
			//$k = $square / max($w, $h);    
			//return array($w*$k, $h*$k);

			$xx = $square;
			$yy = $h * $xx / $w;
			return array($xx, $yy);
		}

		//don't forget to insert thumbnail_calcsize function (see above)
		function thumbnail_generator($srcfile, &$params){    
			// getting source image size    
			@list($w, $h) = getimagesize($srcfile);
			if ($w == false) return false;    
			
			// checking params array
			if (!(is_array($params)&&is_array($params[0]))) return false;
			
			$src = ImageCreateFromJpeg($srcfile);
			list($s1_w, $s1_h) = FU::thumbnail_calcsize($w, $h, $params[0]['size']);
			
			// Create first thumbnail    
			// Remember, first thumbnail should be largest thumbnail
			$img_s1 = imagecreatetruecolor($s1_w, $s1_h);
			imagecopyresampled($img_s1, $src, 0, 0, 0, 0, $s1_w, $s1_h, $w, $h);
			imagedestroy($src); // Destroy source image     

			// Other thumbnails are just downscaled copies of the first one    
			for($i=1; $i<sizeof($params); $i++)    {
				list($cur_w, $cur_h) = FU::thumbnail_calcsize($w, $h, $params[$i]['size']);
				$img_cur = imagecreatetruecolor($cur_w, $cur_h);
				imagecopyresampled($img_cur, $img_s1, 0, 0, 0, 0, $cur_w, $cur_h, $s1_w, $s1_h);
				imagejpeg($img_cur, $params[$i]['file'], 90);
				imagedestroy($img_cur);
			}
			
			// Saving first thumbnail
			imagejpeg($img_s1, $params[0]['file'], 90);
			imagedestroy($img_s1);
			
			return true;
		}

		function thumb_cropresize($source,$dest,$size) {

			$thumb_size = $size;

			$size = getimagesize($source);
			$width = $size[0];
			$height = $size[1];

			if($width> $height) {
			$x = ceil(($width - $height) / 2 );
			$width = $height;
			} elseif($height> $width) {
			$y = ceil(($height - $width) / 2);
			$height = $width;
			}

			$new_im = ImageCreatetruecolor($thumb_size,$thumb_size);
			$im = imagecreatefromjpeg($source);
			imagecopyresampled($new_im,$im,0,0,$x,$y,$thumb_size,$thumb_size,$width,$height);
			imagejpeg($new_im,$dest,100);

		}
		
		function cropImage($nw, $nh, $source, $stype, $dest) {
		 
		    $size = getimagesize($source);
		    $w = $size[0];
		    $h = $size[1];
		 
		    switch($stype) {
		        case 'gif':
		        $simg = imagecreatefromgif($source);
		        break;
		        case 'jpg':
		        case 'jpeg':
		        $simg = imagecreatefromjpeg($source);
		        break;
		        case 'png':
		        $simg = imagecreatefrompng($source);
		        break;
		    }
		 
		    $dimg = imagecreatetruecolor($nw, $nh);
		 
		    $wm = $w/$nw;
		    $hm = $h/$nh;
		 
		    $h_height = $nh/2;
		    $w_height = $nw/2;
		 
		    if($wm > $hm) {
		 
		        $adjusted_width = $w / $hm;
		        $half_width = $adjusted_width / 2;
		        $int_width = $half_width - $w_height;
		 
		        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
		 
		    } elseif(($wm <$hm) || ($wm == $hm)) {
		 
		        $adjusted_height = $h / $wm;
		        $half_height = $adjusted_height / 2;
		        $int_height = $half_height - $h_height;
		 
		        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
		 
		    } else {
		        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		    }
		 
		    imagejpeg($dimg,$dest,100);
		}		
		#=================================================================

 
		//########################  Network Util ################################
		function GetIP()
		{
		   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
				   $ip = getenv("HTTP_CLIENT_IP");
			   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
				   $ip = getenv("HTTP_X_FORWARDED_FOR");
			   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
				   $ip = getenv("REMOTE_ADDR");
			   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
				   $ip = $_SERVER['REMOTE_ADDR'];
			   else
				   $ip = "unknown";
		   return($ip);
		}/*-------GetIP()-------*/
  
		//########################  Network Util ################################
		

		///////////////////////////////////////// Java Script /////////////////////////////////////////////////
		##### ¿Ñ§¡ìªÑè¹¡ÃÍ§¤ÓËÂÒº áÅÐ¤Óâ¦É³Ò  #####
		function alert_mesg($message) {
			echo "<script language='javascript'>" ;
			echo "alert('$message')" ;
			echo "</script>" ;
		} // alert

		 

		function makeRandom() {
			$salt = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789";
			$len = strlen($salt);
			$makepass="";
			mt_srand(10000000*(double)microtime());
			for ($i = 0; $i < 4; $i++)
			$makepass .= $salt[mt_rand(0,$len - 1)];
			return $makepass;
		}
		///////////////////////////////////////// Java Script /////////////////////////////////////////////////



		//########################  About Picture #################################3
		function unlinkImage($abspath, $filename)
		{
			if( !empty($filename)){
				if (file_exists($abspath.$filename))
					@unlink( $abspath.$filename );						
			}
		}
				
		function RemoveExtension( $fileName )
		{
			return substr( $fileName, 0, strrpos( $fileName, '.' ) ) ;
		}

		function upload_WithRename_SmallOption( $FVARS, $sServerDir, $type="New"){		//äÁèàªç¤ÍÐäÃ·Ñé§¹Ñé¹ á¤è¶éÒª×èÍ«éÓ¡Ñ¹ ¡ç rename (number)
			$sFileName = "";

			$oFile = $FVARS ;
			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;
				//echo $sFileName;

				//File Allowed
				$sOriginalFileName = $sFileName ;
				$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
				$sExtension = strtolower( $sExtension ) ;

				//Upload Member Picture
				// Initializes the counter used to rename the file, if another one with the same name already exists.
				$iCounter = 0 ;

				while ( true )
				{
					// Compose the file path.
					$sFilePath = $sServerDir . $sFileName ;

					// If a file with that name already exists.
					if ( is_file( $sFilePath ) )
					{
						$iCounter++ ;
						$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
					}
					else
					{
						move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
						if ( is_file( $sFilePath ) )
						{
							$oldumask = umask(0) ;
							chmod( $sFilePath, 0777 ) ;
							umask( $oldumask ) ;
						}
									
						break ;
					}
				}

			}//if( !empty( $_FILES['name'] ) )		
			return $sFileName;
		}
		
		function unlinkImageTag($abspath, $text)
		{
			preg_match_all ( '#(?:<img )([^>]*?)(?:/?>)#is', $text, $imgtags, PREG_PATTERN_ORDER );

			$imgcontents = array();

			foreach ( (array) $imgtags[1] as $img )
			{
			preg_match_all ( '#([a-zA-Z]*?)=[\'"]([^"\']*)["\']#i', $img, $attributes, PREG_PATTERN_ORDER );
			$attrs = array();
			foreach ( (array) $attributes[1] as $key => $attr )
			{
			$attrs[$attributes[1][$key]] = $attributes[2][$key];
			}
			$imgcontents[] = $attrs;
			}

			//print_r($imgcontents);
			$num = count($imgcontents);					
			for( $i = 0; $i < $num; $i++){
			
				if( !empty($imgcontents[$i]["src"]) ){
					echo "<br>".$abspath.$imgcontents[$i]["src"];

					if (file_exists($abspath.$imgcontents[$i]["src"]))
						unlink( $abspath.$imgcontents[$i]["src"] );		
				}
			}
		}
		
		///////// Upload Picture /////////////////
		function upload_cropresize($FVARS, $sServerDir, $arAllowed, $arDenied, $crop2size){
			$sFileName = "";
			$resultReturn = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) )
			{
				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File Allowed
				$sOriginalFileName = $sFileName ;
				$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
				$sExtension = strtolower( $sExtension ) ;

				// Check if it is an allowed extension.
				if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){

					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "File type not permit.";
					//FU::alert_mesg($resultReturn["Msg"]);
					
				}else{

					$resultReturn["Flag"] = 0;
					$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;				
					
					$size = getimagesize($oFile['tmp_name']);	
					
					if( $size[0] > $crop2size ){
						#100x100
						//====================================================================================
						$thumbnailFile = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;
	
						$resultReturn["sCropImage"] = $thumbnailFile;		
						@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile, $crop2size);
						if ( is_file( $sServerDir.$thumbnailFile ) )
						{
							$oldumask = umask(0) ;
							chmod( $sServerDir.$thumbnailFile, 0777 ) ;
							umask( $oldumask ) ;
						}
						//====================================================================================						
					}else{
						$thumbnailFile = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
						$sFilePath = $sServerDir . $thumbnailFile ;
						move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
						if ( is_file( $sFilePath ) )
						{
							$oldumask = umask(0) ;
							chmod( $sFilePath, 0777 ) ;
							umask( $oldumask ) ;
						}
						$resultReturn["sCropImage"] = $thumbnailFile;
					}						
				}
			}
			else{
				$resultReturn["Flag"] = 1;
				$resultReturn["Msg"] = "No file uploaded.";			
			}
			return $resultReturn;			
		}		
				
		function uploadAvatar_cropresize($FVARS, $sServerDir, $arAllowed, $arDenied){
			$sFileName = "";
			$resultReturn = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) )
			{
				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File Allowed
				$sOriginalFileName = $sFileName ;
				$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
				$sExtension = strtolower( $sExtension ) ;

				// Check if it is an allowed extension.
				if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){

					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "File type not permit.";
					//FU::alert_mesg($resultReturn["Msg"]);
					
				}else{

					$resultReturn["Flag"] = 0;

					$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
							
					$size320 = 320;
					$size100 = 100;

					$size = getimagesize($oFile['tmp_name']);	
					
					if( $size[0] > $size320 ){

							// Setting params array for thumbnail_generator 
							//====================================================================================
							$thumbnailFile320 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$params320 = array( array( 'size' => '320', 'file' => $sServerDir.$thumbnailFile320) );    

							$resultReturn["sThumbnail320"] = $thumbnailFile320;												
							if (FU::thumbnail_generator($oFile['tmp_name'], $params320) == false) die("Error processing uploaded thumb file {$u_filename}");
							if ( is_file( $sServerDir.$thumbnailFile320 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile320, 0777 ) ;
								umask( $oldumask ) ;
							}
							//=====================================================================================
							
							#100x100
							//====================================================================================
							$thumbnailFile100 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;

							$resultReturn["sThumbnail100"] = $thumbnailFile100;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile100, '100');
							if ( is_file( $sServerDir.$thumbnailFile100 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile100, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================			
					}
					else if( $size[0] <= $size320 && $size[0] >= $size100 ){

							//echo "<br> < 320 --------------------   > 100";
							#100x100
							//====================================================================================
							$thumbnailFile100 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;
							$resultReturn["sThumbnail100"] = $thumbnailFile100;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile100, '100');
							if ( is_file( $sServerDir.$thumbnailFile100 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile100, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================

							$file320 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath320 = $sServerDir . $file320 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath320 ) ;
							if ( is_file( $sFilePath320 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath320, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail320"] = $file320;
					
					}
					else if( $size[0] < $size100 ){

							//echo "<br> <  100";

							$thumbnailFile320 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath = $sServerDir . $thumbnailFile320 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
							if ( is_file( $sFilePath ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail320"] = $thumbnailFile320;
							$resultReturn["sThumbnail100"] = $thumbnailFile320;				
					}
				}
			}
			else{
				$resultReturn["Flag"] = 1;
			
			}
			return $resultReturn;			
		}

		function uploadPhoto_cropresize($FVARS, $sServerDir, $arAllowed, $arDenied){
			$sFileName = "";
			$resultReturn = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) )
			{
				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File Allowed
				$sOriginalFileName = $sFileName ;
				$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
				$sExtension = strtolower( $sExtension ) ;

				// Check if it is an allowed extension.
				if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){

					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "File type not permit.";
					//FU::alert_mesg($resultReturn["Msg"]);
					
				}else{

					$resultReturn["Flag"] = 0;

					$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
							
					$size480 = 480;
					$size100 = 100;

					$size = getimagesize($oFile['tmp_name']);	
					
					if( $size[0] > $size480 ){

							// Setting params array for thumbnail_generator 
							//====================================================================================
							$thumbnailFile480 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$params480 = array( array( 'size' => '480', 'file' => $sServerDir.$thumbnailFile480) );    

							$resultReturn["sThumbnail480"] = $thumbnailFile480;												
							if (FU::thumbnail_generator($oFile['tmp_name'], $params480) == false) die("Error processing uploaded thumb file {$u_filename}");
							if ( is_file( $sServerDir.$thumbnailFile480 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile480, 0777 ) ;
								umask( $oldumask ) ;
							}
							//=====================================================================================
							
							#100x100
							//====================================================================================
							$thumbnailFile100 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;

							$resultReturn["sThumbnail100"] = $thumbnailFile100;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile100, '100');
							if ( is_file( $sServerDir.$thumbnailFile100 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile100, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================			
					}
					else if( $size[0] <= $size480 && $size[0] >= $size100 ){

							//echo "<br> < 480 --------------------   > 100";
							#100x100
							//====================================================================================
							$thumbnailFile100 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;
							$resultReturn["sThumbnail100"] = $thumbnailFile100;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile100, '100');
							if ( is_file( $sServerDir.$thumbnailFile100 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile100, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================

							$file480 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath480 = $sServerDir . $file480 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath480 ) ;
							if ( is_file( $sFilePath480 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath480, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail480"] = $file480; 
					
					}
					else if( $size[0] < $size100 ){

							//echo "<br> <  100";

							$thumbnailFile480 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath = $sServerDir . $thumbnailFile480 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
							if ( is_file( $sFilePath ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail480"] = $thumbnailFile480;
							$resultReturn["sThumbnail100"] = $thumbnailFile480;				
					}
				}
			}
			else{
				$resultReturn["Flag"] = 1;
			
			}
			return $resultReturn;		
		}

		function uploadPhoto_cropresize_gallery($FVARS, $sServerDir, $arAllowed, $arDenied){
			$sFileName = "";
			$resultReturn = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) )
			{
				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File Allowed
				$sOriginalFileName = $sFileName ;
				$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
				$sExtension = strtolower( $sExtension ) ;

				// Check if it is an allowed extension.
				if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){

					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "File type not permit.";
					//FU::alert_mesg($resultReturn["Msg"]);
					
				}else{

					$resultReturn["Flag"] = 0;

					$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
							
					$size450 = 450;
					$size89 = 89;

					$size = getimagesize($oFile['tmp_name']);	
					
					if( $size[0] > $size450 ){

							// Setting params array for thumbnail_generator 
							//====================================================================================
							$thumbnailFile450 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$params450 = array( array( 'size' => '450', 'file' => $sServerDir.$thumbnailFile450) );    

							$resultReturn["sThumbnail450"] = $thumbnailFile450;												
							if (FU::thumbnail_generator($oFile['tmp_name'], $params450) == false) die("Error processing uploaded thumb file {$u_filename}");
							if ( is_file( $sServerDir.$thumbnailFile450 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile450, 0777 ) ;
								umask( $oldumask ) ;
							}
							//=====================================================================================
							
							#100x100
							//====================================================================================
							$thumbnailFile89 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;

							$resultReturn["sThumbnail89"] = $thumbnailFile89;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile89, '89');
							if ( is_file( $sServerDir.$thumbnailFile89 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile89, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================			
					}
					else if( $size[0] <= $size450 && $size[0] >= $size89 ){

							//echo "<br> < 480 --------------------   > 100";
							#100x100
							//====================================================================================
							$thumbnailFile89 = FU::RemoveExtension( $sFileName ).'-2p.'.$sExtension;
							$resultReturn["sThumbnail89"] = $thumbnailFile89;		
							@FU::thumb_cropresize($oFile['tmp_name'], $sServerDir.$thumbnailFile89, '89');
							if ( is_file( $sServerDir.$thumbnailFile89 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sServerDir.$thumbnailFile89, 0777 ) ;
								umask( $oldumask ) ;
							}
							//====================================================================================

							$file450 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath450 = $sServerDir . $file450 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath450 ) ;
							if ( is_file( $sFilePath450 ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath450, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail450"] = $file450;


					
					}
					else if( $size[0] < $size89 ){

							//echo "<br> <  100";

							$thumbnailFile450 = FU::RemoveExtension( $sFileName ).'-1p.'.$sExtension;
							$sFilePath = $sServerDir . $thumbnailFile450 ;
							move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
							if ( is_file( $sFilePath ) )
							{
								$oldumask = umask(0) ;
								chmod( $sFilePath, 0777 ) ;
								umask( $oldumask ) ;
							}
							$resultReturn["sThumbnail450"] = $thumbnailFile450;
							$resultReturn["sThumbnail89"] = $thumbnailFile450;				
					}
				}
			}
			else{
				$resultReturn["Flag"] = 1;
			
			}
			return $resultReturn;		
		}

		
		#//Upload New Image
		function uploadNewImage( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $rename="1"){
			$sFileName = "";
			$resultReturn = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ($sFileName) มีขนาดใหญ่เกินที่กำหนดไว้";
					FU::alert_mesg($resultReturn["Msg"]);
				}else{
					$resultReturn["Flag"] = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"] = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ($sFileName)  ไม่ได้รับอนุญาตครับ";
						FU::alert_mesg($resultReturn["Msg"]);
					}else{
							$resultReturn["Flag"] = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	
							if($size[0] > $maxWidth) { 
								$resultReturn["Flag"] = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ($sFileName)  มีความกว้างเกินที่กำหนดไว้";
								FU::alert_mesg($resultReturn["Msg"]);
							}else{
								$resultReturn["Flag"] = 0;

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;

									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}								
										break ;
									}
								}
							}
						
					}
				}
				if( $resultReturn["Flag"]  == 1 )
					$resultReturn["sFileName"] = "";
				else
					$resultReturn["sFileName"] = $sFileName;

			}//if( !empty( $_FILES['file1'] ) )


			return $resultReturn;
		}		
		
		#Upload New Image & Crop
		function uploadNewImage_cropXY( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $reduceW, $reduceH, $rename=1){	
			$sFileName = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
				}else{
					$resultReturn["Flag"] = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"] = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
					}else{
							$resultReturn["Flag"] = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	
							if($size[0] > $maxWidth) { 
								$resultReturn["Flag"] = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีความกว้างเกินที่กำหนดไว้";
							}else{
								$resultReturn["Flag"] = 0;

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;

									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
			
										// Setting params array for thumbnail_generator
										$thumbnailFile = FU::RemoveExtension( $sFileName ).'-'.$reduceW.'p.'.$sExtension;
										$resultReturn["sThumbnail"] = $thumbnailFile;							
										FU::cropImage($reduceW, $reduceH, $sServerDir.$sFileName, $sExtension, $sServerDir.$thumbnailFile);
										break ;
									}
								}//while
							}
						
					}
				}
				if( $resultReturn["Flag"]  == 1 )
					$resultReturn["sFileName"] = "";
				else
					$resultReturn["sFileName"] = $sFileName;

			}//if( !empty( $_FILES['file1'] ) )


			return $resultReturn;				
		}
		
		#//Upload New Image and Reduce picture size on the fly
		function uploadNewImage_reducesize( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $reducesize, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
				}else{
					$resultReturn["Flag"] = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"] = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
					}else{
							$resultReturn["Flag"] = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	
							if($size[0] > $maxWidth) { 
								$resultReturn["Flag"] = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีความกว้างเกินที่กำหนดไว้";
							}else{
								$resultReturn["Flag"] = 0;

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;

									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
			
										// Setting params array for thumbnail_generator 
										$thumbnailFile = FU::RemoveExtension( $sFileName ).'-'.$reducesize.'p.'.$sExtension;
										$params = array(array('size' => $reducesize, 'file' => $sServerDir.$thumbnailFile));    

										$resultReturn["sThumbnail"] = $thumbnailFile;
										
										if (FU::thumbnail_generator($sServerDir.$sFileName, $params) == false) die("Error processing uploaded thumb file {$u_filename}");
										
										break ;
									}
								}//while
							}
						
					}
				}
				if( $resultReturn["Flag"]  == 1 )
					$resultReturn["sFileName"] = "";
				else
					$resultReturn["sFileName"] = $sFileName;

			}//if( !empty( $_FILES['file1'] ) )


			return $resultReturn;
		}		
		#======================================================================================================================

		#//Upload Edit Image
		function uploadEditImage( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $OldPicture, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			//Upload and delete old files, Get the posted file.
			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
					//echo "File1 ". $resultReturn["Msg"];		
				}else{
					$resultReturn["Flag"]  = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"]  = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
						//echo "File1 ". $resultReturn["Msg"];				
					}else{
							$resultReturn["Flag"]  = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	

							if($size[0] > $maxWidth) {  //ถ้าความกว้างมากกว่า 80 pixels (แก้ไขได้ที่ config)
								$resultReturn["Flag"]  = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีความกว้างเกินที่กำหนดไว้";
								//echo "File1 ". $resultReturn["Msg"];
							}else{
								$resultReturn["Flag"]  = 0;

								//echo "Here Step 3";

								//Delete Old Picture 
								if (file_exists($sServerDir.$OldPicture)){
									@unlink($sServerDir.$OldPicture );
								}

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
										
										break ;
									}
								}
							}
					}
				}
			
				if( $resultReturn["Flag"] == 1 )
					$resultReturn["sFileName"] = $OldPicture;
				else
					$resultReturn["sFileName"] = $sFileName;

			}//if( !empty( $_FILES['file1'] ) )
			else{
				//$resultReturn["Flag"] = 0;
				$resultReturn["sFileName"] = $OldPicture;
			}

			return $resultReturn;
		}

		#//Upload Edit Image and reduce size on the fly
		function uploadEditImage_reducesize( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $OldThumb, $OldPicture, $reducesize, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			//Upload and delete old files, Get the posted file.
			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
					//echo "File1 ". $resultReturn["Msg"];		
				}else{
					$resultReturn["Flag"]  = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"]  = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
						//echo "File1 ". $resultReturn["Msg"];				
					}else{
							$resultReturn["Flag"]  = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	

							if($size[0] > $maxWidth) {  //ถ้าความกว้างมากกว่า 80 pixels (แก้ไขได้ที่ config)
								$resultReturn["Flag"]  = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีความกว้างเกินที่กำหนดไว้";
								//echo "File1 ". $resultReturn["Msg"];
							}else{
								$resultReturn["Flag"]  = 0;

								//echo "Here Step 3";

								//Delete Old Picture 
								if (file_exists($sServerDir.$OldPicture)){
									@unlink($sServerDir.$OldPicture );
								}

								if (file_exists($sServerDir.$OldThumb)){
									@unlink($sServerDir.$OldThumb );
								}

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;

									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
			
										// Setting params array for thumbnail_generator 
										$thumbnailFile = FU::RemoveExtension( $sFileName ).'-'.$reducesize.'p.'.$sExtension;
										$params = array(array('size' => $reducesize, 'file' => $sServerDir.$thumbnailFile));    

										$resultReturn["sThumbnail"] = $thumbnailFile;
										
										if (FU::thumbnail_generator($sServerDir.$sFileName, $params) == false) die("Error processing uploaded thumb file {$u_filename}");
										
										break ;
									}
								}//while
							}
					}
				}

				$resultReturn["sFileName"] = $sFileName;

				if( $resultReturn["Flag"]  == 1 ){
					$resultReturn["sFileName"] = $OldPicture;
					$resultReturn["sThumbnail"] = $OldThumb;
				}

			}//if( !empty( $_FILES['file1'] ) )
			else{
				$resultReturn["sFileName"] = $OldPicture;
				$resultReturn["sThumbnail"] = $OldThumb;
			}

			return $resultReturn;
		}
		
		#//Upload Edit Image and reduce size on the fly
		function uploadEditImage_cropXY( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $maxWidth, $reduceW, $reduceH, $OldThumb, $OldPicture, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			//Upload and delete old files, Get the posted file.
			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
					//echo "File1 ". $resultReturn["Msg"];		
				}else{
					$resultReturn["Flag"]  = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"]  = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
						//echo "File1 ". $resultReturn["Msg"];				
					}else{
							$resultReturn["Flag"]  = 0;

							//Check width pixel
							$size = getimagesize($oFile['tmp_name']);	

							if($size[0] > $maxWidth) {  //ถ้าความกว้างมากกว่า 80 pixels (แก้ไขได้ที่ config)
								$resultReturn["Flag"]  = 1;
								$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีความกว้างเกินที่กำหนดไว้";
								//echo "File1 ". $resultReturn["Msg"];
							}else{
								$resultReturn["Flag"]  = 0;

								//echo "Here Step 3";

								//Delete Old Picture 
								if (file_exists($sServerDir.$OldPicture)){
									@unlink($sServerDir.$OldPicture );
								}

								if (file_exists($sServerDir.$OldThumb)){
									@unlink($sServerDir.$OldThumb );
								}

								// Initializes the counter used to rename the file, if another one with the same name already exists.
								$iCounter = 0 ;

								while ( true )
								{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;

									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
			
										// Setting params array for thumbnail_generator 
										$thumbnailFile = FU::RemoveExtension( $sFileName ).'-'.$reduceW.'p.'.$sExtension;
										$resultReturn["sThumbnail"] = $thumbnailFile;							
										FU::cropImage($reduceW, $reduceH, $sServerDir.$sFileName, $sExtension, $sServerDir.$thumbnailFile);
										break ;
									}
								}//while
							}
					}
				}

				$resultReturn["sFileName"] = $sFileName;

				if( $resultReturn["Flag"]  == 1 ){
					$resultReturn["sFileName"] = $OldPicture;
					$resultReturn["sThumbnail"] = $OldThumb;
				}

			}//if( !empty( $_FILES['file1'] ) )
			else{
				$resultReturn["sFileName"] = $OldPicture;
				$resultReturn["sThumbnail"] = $OldThumb;
			}

			return $resultReturn;
		}		
		#=======================================================================================================================
		


		#//Upload New File
		function uploadNewFile( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
					//echo $resultReturn["Msg"];
				}else{
					$resultReturn["Flag"] = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"] = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
						//echo $resultReturn["Msg"];
					}else{
							$resultReturn["Flag"] = 0;

							// Initializes the counter used to rename the file, if another one with the same name already exists.
							$iCounter = 0 ;

							while ( true )
							{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}								
										break ;
									}
							}													
					}
				}
				if( $resultReturn["Flag"]  == 1 )
					$resultReturn["sFileName"] = "";
			}//if( !empty( $_FILES['file1'] ) )
			$resultReturn["sFileName"] = $sFileName;

			return $resultReturn;
		}
		#======================================================================================================================

		#//Upload Edit Image
		function uploadEditFile( $FVARS, $sServerDir, $arAllowed, $arDenied, $maxSize, $OldFile, $rename=1){
			$sFileName = "";
			$oFile = $FVARS ;

			//Upload and delete old files, Get the posted file.
			if( !empty( $oFile['name'] ) ){

				// Get the uploaded file name and extension.
				$sFileName = $oFile['name'] ;

				//File size
				if( $oFile['size'] > $maxSize ){
					$resultReturn["Flag"] = 1;
					$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload มีขนาดใหญ่เกินที่กำหนดไว้";
					//echo "File1 ". $resultReturn["Msg"];		
				}else{
					$resultReturn["Flag"]  = 0;

					//File Allowed
					$sOriginalFileName = $sFileName ;
					$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
					$sExtension = strtolower( $sExtension ) ;

					// Check if it is an allowed extension.
					if ( ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) ) || ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) ) ){
						$resultReturn["Flag"]  = 1;
						$resultReturn["Msg"] = "ไฟล์รูปสมาชิกที่ท่าน Upload ไม่ได้รับอนุญาตครับ";
						//echo "File1 ". $resultReturn["Msg"];				
					}else{
							$resultReturn["Flag"]  = 0;

							//echo "Here Step 3";

							//Delete Old Picture 
							//echo $sServerDir.$OldFile;
							//echo "<br>";
							if (file_exists($sServerDir.$OldFile)){
								//echo "exists";
								//echo $sServerDir.$OldFile;
								@unlink($sServerDir.$OldFile );
							}else{
								//echo "Not exists";
							}
							//exit;

							// Initializes the counter used to rename the file, if another one with the same name already exists.
							$iCounter = 0 ;

							while ( true )
							{
									// Compose the file path.
									if( $rename == 1 )
										$sFileName = date('YmdHis').FU::makeRandom().".".$sExtension;
									$sFilePath = $sServerDir . $sFileName ;

									// If a file with that name already exists.
									if ( is_file( $sFilePath ) )
									{
										$iCounter++ ;
										$sFileName = FU::RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
									}
									else
									{
										move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;

										if ( is_file( $sFilePath ) )
										{
											$oldumask = umask(0) ;
											chmod( $sFilePath, 0777 ) ;
											umask( $oldumask ) ;
										}
										
										break ;
									}
							}
					}
				}

				$resultReturn["sFileName"] = $sFileName;

				if( $resultReturn["Flag"]  == 1 )
					$resultReturn["sFileName"] = $OldFile;

			}//if( !empty( $_FILES['file1'] ) )
			else{
				$resultReturn["sFileName"] = $OldFile;
			}

			return $resultReturn;
		}
		#=======================================================================================================================		


		//Move Directory ( Delete )
		function recursive_remove_directory($directory, $empty=FALSE)
		 {
			 // if the path has a slash at the end we remove it here
			 if(substr($directory,-1) == '/')
			 {
				 $directory = substr($directory,0,-1);
			 }
		  
			 // if the path is not valid or is not a directory ...
			 if(!file_exists($directory) || !is_dir($directory))
			 {
				 // ... we return false and exit the function
				 return FALSE;
		  
			 // ... if the path is not readable
			 }elseif(!is_readable($directory))
			 {
				 // ... we return false and exit the function
				 return FALSE;
		 
			 // ... else if the path is readable
			 }else{
		  
				// we open the directory
				 $handle = opendir($directory);
		  
				 // and scan through the items inside
				 while (FALSE !== ($item = readdir($handle)))
				 {
					// if the filepointer is not the current directory
					 // or the parent directory
					 if($item != '.' && $item != '..')
					 {
						 // we build the new path to delete
						 $path = $directory.'/'.$item;
		  
						 // if the new path is a directory
						 if(is_dir($path)) 
						 {
							 // we call this function with the new path
							 recursive_remove_directory($path);
		  
						 // if the new path is a file
						 }else{
							 // we remove the file
							 unlink($path);
						 }
					 }
				 }
				 // close the directory
				 closedir($handle);
		 
				 // if the option to empty is not set to true
				 if($empty == FALSE)
				 {
					 // try to delete the now empty directory
					 if(!rmdir($directory))
					 {
						 // return false if not possible
						 return FALSE;
					 }
				 }
				 // return success
				 return TRUE;
			 }
		 }

		function copydirr($fromDir,$toDir,$chmod=0757,$verbose=false)
		/*
		   copies everything from directory $fromDir to directory $toDir
		   and sets up files mode $chmod
		*/
		{
			//* Check for some errors
			$errors=array();
			$messages=array();
			if (!is_writable($toDir))
			   $errors[]='target '.$toDir.' is not writable';
			if (!is_dir($toDir))
			   $errors[]='target '.$toDir.' is not a directory';
			if (!is_dir($fromDir))
			   $errors[]='source '.$fromDir.' is not a directory';
			if (!empty($errors))
			{
			   if ($verbose)
				   foreach($errors as $err)
					   echo '<strong>Error</strong>: '.$err.'<br />';
			   return false;
			}
			//*/
			$exceptions=array('.','..');
			//* Processing
			$handle=opendir($fromDir);
			while (false!==($item=readdir($handle)))
			   if (!in_array($item,$exceptions))
				   {
				   //* cleanup for trailing slashes in directories destinations
				   $from=str_replace('//','/',$fromDir.'/'.$item);
				   $to=str_replace('//','/',$toDir.'/'.$item);
				   //*/
				   if (is_file($from))
					   {
					   if (@copy($from,$to))
						   {
						   chmod($to,$chmod);
						   touch($to,filemtime($from)); // to track last modified time
						   $messages[]='File copied from '.$from.' to '.$to;
						   }
					   else
						   $errors[]='cannot copy file from '.$from.' to '.$to;
					   }
						if (is_dir($from))
					   {
						   if (@mkdir($to))
							   {
							   chmod($to,$chmod);
							   $messages[]='Directory created: '.$to;
							   }
						   else
							   $errors[]='cannot create directory '.$to;
							   copydirr($from,$to,$chmod,$verbose);
					   }
				   }
			closedir($handle);
			//*/
			//* Output
			if ($verbose)
			   {
			   foreach($errors as $err)
				   echo '<strong>Error</strong>: '.$err.'<br />';
			   foreach($messages as $msg)
				   echo $msg.'<br />';
			   }
			//*/
			return true;
		}
		//########################  Upload Picture #################################
		#=========================================================================================================
		# Send Mail
		#=========================================================================================================
		function send_mail($subject,$msg,$to_name,$to_email,$from_name,$from_email, $isSMTP=0) {

			$mail = new PHPMailer();
	
			if( $isSMTP == "1" ){
				$mail->IsSMTP();
				$mail->SMTPDebug = 0;
				$mail->SMTPAuth = true;
				$mail->Host = "mail.idmaxdev.com";
				$mail->Port = 25;
				$mail->Username = "noreply@idmaxdev.com";
				$mail->Password = "X7pej39$";
			}

			$email = $to_email;
			$name = $to_name;
			$mail->Priority = 3;
			$mail->Encoding = "8bit";
			$mail->CharSet = "utf-8";
			$mail->From = $from_email;
			$mail->FromName = $from_name;
			$mail->WordWrap = 50;                              // set word wrap
			$mail->IsHTML(true);									// send as HTML
			$mail->Subject = $subject;
			$mail->Body =  $msg;
			
			$mail->AltBody  =  "This is the text-only body";

			$mail->AddAddress($email, $name);  

			if(!$mail->Send())
			{ 
				$sendFlag = 0;
			}else{		
				 
				$sendFlag = 1;
			}	

			// Clear all addresses and attachments for next loop
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			$mail->ClearCCs();

			unset($mail);
			$msg = "";
			unset($msg);
			
			return $sendFlag;

		} // end sendmail function 
		
		#geturl
		function curPageURL() {
			$pageURL = 'http';
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
				$pageURL .= "://";
				if ($_SERVER["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				}
		 	return $pageURL;
		}
		
		function curPageName() {
			 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		}

		
		//Cal
		//====================================================================
	 
	function CountDate($BeGin){
	  $now_final = date('Y-m-d', strtotime(DT::currentDateTime()));
	  $BeGin = date('Y-m-d', strtotime($BeGin));							
	  return ( strtotime($now_final) - strtotime($BeGin) ) / ( 60 * 60 * 24 );
	}
	 function strCrop($txt,$num) { #ข้อความ,จำนวน
		if(strlen($txt) >= $num ) {
			$txt = iconv_substr($txt, 0, $num,"UTF-8")."...";
		}
		return $txt;
	}			
	
	function alphanumeric_rand($num_require=8) {
	  $alphanumeric = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9);
	  if($num_require > sizeof($alphanumeric)){
		  echo "Error alphanumeric_rand(\$num_require) : \$num_require must less than " . sizeof($alphanumeric) . ", $num_require given";
		  return;
	  }
	  $rand_key = array_rand($alphanumeric , $num_require);
	  for($i=0;$i<sizeof($rand_key);$i++) $randomstring .= $alphanumeric[$rand_key[$i]];
	  return $randomstring;
  }
  
  	function getBrowser() 
		{ 
			$u_agent = $_SERVER['HTTP_USER_AGENT']; 
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version= "";
		
			//First get the platform?
			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}
			
			// Next get the name of the useragent yes seperately and for good reason
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Internet Explorer'; 
				$ub = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$bname = 'Mozilla Firefox'; 
				$ub = "Firefox"; 
			} 
			 elseif(preg_match('/blackberry/i',$u_agent)) 
			{ 
				$bname = 'BlackBerry'; 
				$ub = "BlackBerry"; 
			} 
			 elseif(preg_match('/iphone/i',$u_agent)) 
			{ 
				$bname = 'iPhone'; 
				$ub = "iPhone"; 
				
			 } else if (preg_match('/ipad/i', $u_agent)) { 
				$bname = 'iPad'; 
				$ub = "iPad"; 

             }
			 elseif(preg_match('/android/i',$u_agent)) 
			{ 
				$bname = 'Android'; 
				$ub = "Android"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$bname = 'Google Chrome'; 
				$ub = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$bname = 'Apple Safari'; 
				$ub = "Safari"; 
			} 
			elseif(preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Opera'; 
				$ub = "Opera"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$bname = 'Netscape'; 
				$ub = "Netscape"; 
			} 
			
			
			 
			
			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)) {
				// we have no matching number just continue
			}
			
			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1) {
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
					$version= $matches['version'][0];
				}
				else {
					$version= $matches['version'][1];
				}
			}
			else {
				$version= $matches['version'][0];
			}
			
			// check if we have a number
			if ($version==null || $version=="") {$version="?";}
			
			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
			);
		} 
		
		function ipToCountry($ip){
			$info = file_get_contents("http://who.is/whois-ip/ip-address/$ip");
			list($a, $b) = explode('country:        ', $info);
			return  substr($b,0,2);
		}
	  function chkBrowser(){
		preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT']);
	  }
		/////////////////////////////////////////////// End Function ///////////////////////////////////////////	
}
?>
