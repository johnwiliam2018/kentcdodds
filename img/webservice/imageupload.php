<?php

class ClaimApi extends Webservice {
	function validate() {
		global $message_cls, $wpdb;
		
		$this -> device = tep_get_value_post("device");		
		$this -> api_key = tep_get_value_post("api_key", "Security Key", "require;");
		$this -> image = tep_get_value_post("image");		
		$image = upload_file("", "image", "jpg,png,jpeg,gif",true);

		$this -> image = $image;
		if ($message_cls->is_empty_error()) {
            if ($this->validate_apikey()) {
                $this->errorcode = SUCCESS_CODE;
            }
        }           
	}
	
	function run() {
		global $message_cls, $wpdb;
		$this -> validate();
		
		$image_info = @getimagesize(DIR_WS_UPLOAD . "original/" . $this -> image);
                                
                $original_imageinfo = array(
                    "original_image_size" => $image_info[0] . "*" . $image_info[1],
                    "original_image_url" => HTTP_WS_UPLOAD . "original/" . $this -> image
                );
                $original_width = $image_info[0];
				$original_height = $image_info[1];
                $image_configs = array(
                    "watch" => 400,
                    "phone" => 800,
                    "tablet" => 1200,
                    "pc" => 1600,
                    "tv" => 2400
                );

                if ($this -> errorcode == SUCCESS_CODE) {
					$imageinfo = array(
						"image" => $this -> image,
						"width" => $image_info[0],
						"height" => $image_info[1],
						"size" => $_FILES['image']['size'],
						"uploaded" => date('Y-m-d H:i:s'),
						"isupload" => 1,
						"uploaded_user_id" => $this->logined_user->id
					);
			
				 
					foreach ($image_configs as $type => $width) {
					  $thumnail_dir = $type . "/"; 
					  if (!is_dir(DIR_WS_UPLOAD . $thumnail_dir)) {
						if (mkdir(DIR_WS_UPLOAD . $thumnail_dir)) {        }
					  }
					  if ($_FILES['image']['size'] < 4 * 1024 || $original_width <= $width) {
						  copy (DIR_WS_UPLOAD . "original/" . $this -> image, DIR_WS_UPLOAD . $thumnail_dir . $this -> image);
						  $image_info[0] =  $original_width;
						  $image_info[1] = $original_height;
					  }
					  else {
						  
						$image = new ImageResize(DIR_WS_UPLOAD . "original/" . $this -> image);
						$image->resizeToWidth($width, true);
						$image->save(DIR_WS_UPLOAD . $thumnail_dir . $this -> image); 					  
						$image_info = @getimagesize(DIR_WS_UPLOAD . $thumnail_dir . $this -> image);
						  
						  
					  }  
					  ${$type . '_imageinfo'} = array(
						$type . "_image_size" => $image_info[0] . "*" . $image_info[1],
						$type . "_image_url" => HTTP_WS_UPLOAD . $thumnail_dir . $this -> image
					  );                              
                            
                    }
                        
                        $image_data = array(
                            "image_name" => $this -> image,
                            "orignial" => $original_imageinfo,
                            "watch" => $watch_imageinfo,
                            "phone" => $phone_imageinfo,
                            "tablet" => $tablet_imageinfo,
                            "pc" => $pc_imageinfo,
                            "tv" => $tv_imageinfo
                        );
			if ($wpdb -> insert(TABLE_IMAGES, $imageinfo) !== false) {
				$this -> result = $image_data;
			} else {
				$this->set_database_error();
			}
		}
		$this -> json_result();
	}
}
