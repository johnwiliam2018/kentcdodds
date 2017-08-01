<?php

require ('library/admin_application_top.php');
require (DIR_WS_CLASSES . "ImageResize.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$image_configs = array(
    "watch" => 400,
    "phone" => 800,
    "tablet" => 1200,
    "pc" => 1600,
    "tv" => 2400
);

$result = array(
    "status" => "error",
    "message" => "",
    "file" => array()
);

if (!empty($_FILES['image'])) {
  $uploaded_image = "";
  if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
    $uploaded_image = upload_file("", "image", "jpg,jpeg,png,gif", true);
  } else {
    $result['mesage'] = "Empty uploaded image.";
    header('Content-Type: application/json');
    die(json_encode($result));
  }

  if (!$uploaded_image) {
    $result['mesage'] = $message_cls->get_error("image");
    header('Content-Type: application/json');
    die(json_encode($result));
  }

  $year = date('Y');
  $month = date('m');

  $original_dir = "original/";
  $image_info = @getimagesize(DIR_WS_UPLOAD . $original_dir . $uploaded_image);
  
  $result['status'] = "success";
  $result['image_name'] = $uploaded_image;
  $result['original'] = array(
    "original_image_size" => $image_info[0] . "*" . $image_info[1],
    "original_image_url" => HTTP_WS_UPLOAD . $original_dir . $uploaded_image
  );
  $original_width = $image_info[0];
  $original_height = $image_info[1];
  $wpdb->insert(TABLE_IMAGES, array(
      "image" => $uploaded_image,
      "width" => $image_info[0],
      "height" => $image_info[1],
      "size" => $_FILES['image']['size'],
      "uploaded" => date('Y-m-d H:i:s'),
      "isupload" => '1',
	  "uploaded_user_id" => '0'
  ));
  
  
    foreach ($image_configs as $type => $width) {
      $thumnail_dir = $type . "/"; // . $year . "/" . $month . "/"; // . $day . "/";
      if (!is_dir(DIR_WS_UPLOAD . $thumnail_dir)) {
        if (mkdir(DIR_WS_UPLOAD . $thumnail_dir)) {        }
      } 
	  if ($_FILES['image']['size'] < 4 * 1024 || $original_width <= $width) {
          copy (DIR_WS_UPLOAD . $original_dir . $uploaded_image, DIR_WS_UPLOAD . $thumnail_dir . $uploaded_image);
          $image_info[0] =  $original_width;
		  $image_info[1] = $original_height;
	  }
      else {
		  
		$image = new ImageResize(DIR_WS_UPLOAD . $original_dir . $uploaded_image);
		$image->resizeToWidth($width, true);

		$image->save(DIR_WS_UPLOAD . $thumnail_dir . $uploaded_image);

		$image_info = @getimagesize(DIR_WS_UPLOAD . $thumnail_dir . $uploaded_image);
          
      } 
      $result[$type] = array(
        $type . "_image_size" => $image_info[0] . "*" . $image_info[1],
        $type . "_image_url" => HTTP_WS_UPLOAD . $thumnail_dir . $uploaded_image
      );
    }
  

  header('Content-Type: application/json');
  echo json_encode($result);
}