<?php

function tep_validate_password($plain, $encrypted) {
  if (tep_not_null($plain) && tep_not_null($encrypted)) {
    // split apart the hash / salt
    $stack = explode(':', $encrypted);

    if (sizeof($stack) != 2)
      return false;

    if (hash_hmac("sha256", utf8_encode($plain), utf8_encode($stack[1]), false) == $stack[0]) {
      return true;
    }
  }

  return false;
}

function tep_encrypt_password($plain, $with_slat = true) {
    $password = '';

    for ($i = 0; $i < 10; $i++) {
        $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 4);

    $password = hash_hmac("sha256", utf8_encode($plain), utf8_encode($salt), false);

    if ($with_slat) {
        return $password . ":" . $salt;
    } else {
        return $password;
    }
}

function tep_generate_api_key() {
    global $wpdb;
    do {
        $api_key = tep_encrypt_password(tep_generator_password(), false);
    } while($wpdb->get_var("SELECT COUNT(*) FROM " . TABLE_USERS . " WHERE api_key='{$api_key}'") > 0);
    
    return $api_key;
}

function get_user_status_color($status) {
  switch ($status) {
    case 'checked_in' :
      return 'green';
    case 'looking' :
      return 'yellow';
    case 'busy' :
      return 'red';
    case 'designated_driver' :
      return 'orange';
  }

  return "white";
}

function get_address($address, $key = "") {
  $result = "";
  $result .= $address[$key . 'address_street_1'] . "";
  if ($address[$key . 'address_street_2'] != '') {
    $result .= " " . $address[$key . 'address_street_2'];
  }
  $result .= ", ";
  $result .= $address[$key . 'address_city'] . ", ";
  $result .= $address[$key . 'address_state'] . " ";
  $result .= $address[$key . 'address_zip'] . ", ";
  $result .= $address[$key . 'address_country'];

  return $result;
}

function get_url_title($title) {
  $title = urlencode($title);

  $avalibal_charactors = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.!@+-&";

  $result = "";

  for ($i = 0; $i < strlen($title); $i++) {
    $charac = substr($title, $i, 1);

    if (strpos($avalibal_charactors, $charac) === false) {
      continue;
    }
    $result .= $charac;
  }

  return $result;
}

function random_filename() {
	$seedstr = explode(" ", microtime(), 5);
	$seed    = $seedstr[0] * 10000;
	srand($seed);
	$random  = rand(1000,10000);

	return date("YmdHis", time()) . $random;
}

function upload_file($title, $file_name, $allow = "*", $require = true, $width = 0, $height = 0, $crop = false) {
  global $message_cls, $upload_img_path;
  if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
    if ($require) {
      $message_cls->set_error($file_name, "Empty file");
    }

    return false;
  }
  $file = $_FILES[$file_name];

  $info = pathinfo($file['name']);
  $ext = strtolower($info['extension']);

  if ($title == '') {
    $title = $info['filename'];
  }
  if ($allow != '*') {
    $allows = explode(",", $allow);
    if (in_array($ext, $allows)) {
      
    } else {
      $message_cls->set_error($file_name, "File type is not [" . $allow . "]");
    }
  }

  $year = date('Y');
  $month = date('m');
  $day = date('d');

  $upload_dir = DIR_WS_UPLOAD . "original/"; // . $year . "/" . $month . "/"; // . $day . "/";
  if (!is_dir($upload_dir)) {
    if (mkdir($upload_dir)) {
    } else {
        return false;
    }
  }

//  $new_image_file = get_url_title($title) . "." . $ext;

//  while (file_exists($upload_dir . $new_image_file)) {
//    $new_image_file = urlencode($title) . rand(1, 99) . "." . $ext;
	$new_image_file = random_filename() . "." . $ext;
//	if ($userid > 0){
 //       $new_image_file = $userid . "-" . $new_image_file;
 //   }
 // }
  
  if (move_uploaded_file($file["tmp_name"], $upload_dir . $new_image_file)) {
    $upload_img_path = $upload_dir . $new_image_file;
    if ($width != 0 && $height != 0) {
      $resized_image = image_resize($upload_dir . $new_image_file, $width, $height, $crop);
      @unlink($upload_dir . $upload_img_path);
      @rename($resized_image, DIR_WS_UPLOAD . $new_image_file);
    }
    return $new_image_file;
  } else {
    $message_cls->set_error($file_name, "Error upload file.");
  }

  return false;
}

function download_file($title, $file_url, $require = true, $width = 0, $height = 0, $crop = false) {
  global $message_cls, $upload_img_path;

  $year = date('Y');
  $month = date('m');
  $day = date('d');
  $upload_dir = $year . "/" . $month . "/" . $day . "/";
  chmod(DIR_WS_UPLOAD, 0777);
  if (!is_dir(DIR_WS_UPLOAD . $upload_dir)) {
    if (mkdir(DIR_WS_UPLOAD . $upload_dir, 0777, true)) {
      chmod(DIR_WS_UPLOAD . $upload_dir, 0777);
    }
  }
  if (strrpos($file_url, '?'))
    $ext = substr($file_url, strrpos($file_url, '.') + 1, strrpos($file_url, '?') - strrpos($file_url, '.') - 1);
  else
    $ext = substr($file_url, strrpos($file_url, '.') + 1);
  $new_image_file = $upload_dir . get_url_title($title) . "." . $ext;
  while (file_exists(DIR_WS_UPLOAD . $new_image_file)) {
    $new_image_file = $upload_dir . urlencode($title) . "_" . rand(1, 99) . "." . $ext;
  }

  $img_file = file_get_contents($file_url);
  if ($img_file == false) {
    if ($require) {
      $message_cls->set_error($file_name, "Invalid URL");
    }
    return false;
  }

  $file_loc = DIR_WS_UPLOAD . $new_image_file;

  $file_handler = fopen($file_loc, 'w');

  if (fwrite($file_handler, $img_file) == false) {
    return false;
  }

  fclose($file_handler);

  $upload_img_path = DIR_WS_UPLOAD . $new_image_file;

  if ($width != 0 && $height != 0) {
    $resized_image = image_resize(DIR_WS_UPLOAD . $new_image_file, $width, $height, $crop);
    @unlink(DIR_WS_UPLOAD . $upload_img_path);
    @rename($resized_image, DIR_WS_UPLOAD . $new_image_file);
  }

  return HTTP_WS_UPLOAD . $new_image_file;
}

function formated_image($original_img_url, $original_img_path, $width, $height, $crop = false) {
  $formatted_img = image_resize($original_img_path, $width, $height, $crop);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function formatted_mobile_image($original_img_url, $original_img_path) {
  $formatted_img = image_resize($original_img_path, MOBILE_IMAGE_WIDTH, MOBILE_IMAGE_HEIGHT, true);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function thumb_mobile_image($original_img_url, $original_img_path) {
  $formatted_img = image_resize($original_img_path, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);

  $url_info = pathinfo($original_img_url);
  $img_info = pathinfo($formatted_img);

  return $url_info['dirname'] . "/" . $img_info['basename'];
}

function upload_avatar($user_id, $user_name, $file_name) {
  global $message_cls;

  if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
    return "";
  }

  $file = $_FILES[$file_name];

  $avatar_dir = "avatar/";
  $avatar_dir .= ($user_id - ($user_id % 10000)) . "/";
  chmod(DIR_WS_UPLOAD, 0777);
  if (!is_dir(DIR_WS_UPLOAD . $avatar_dir)) {
    if (mkdir(DIR_WS_UPLOAD . $avatar_dir, 0777, true)) {
      chmod(DIR_WS_UPLOAD . $avatar_dir, 0777);
    }
  }

  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $new_image_file = $user_name . "." . $ext;
  @unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

  if (move_uploaded_file($file["tmp_name"], DIR_WS_UPLOAD . $avatar_dir . $new_image_file)) {
    $avartar_image = image_resize(DIR_WS_UPLOAD . $avatar_dir . $new_image_file, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);
    @unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);
    @rename($avartar_image, DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

    return HTTP_WS_UPLOAD . $avatar_dir . $new_image_file;
  } else {
    
  }

  return "";
}

function export_table_csv($table_name, $export_file_name) {
  export_query_csv("select * from " . $table_name);
}

function export_query_csv($query, $export_file_name) {
  $result = tep_db_query($query);

  if (!$result) {
    echo '<script lanuage="javascript">alert("No export data.")</script>';
  }

  $filed_count = mysql_num_fields($result);
  $headers = array();
  for ($i = 0; $i < $filed_count; $i++) {
    $headers[] = mysql_field_name($result, $i);
  }

  $fp = fopen("php://output", "w");
  if ($fp) {
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $export_file_name);
    header("Pragma: no-cache");
    fputcsv($fp, $headers);
    while ($row = tep_db_fetch_array($result)) {
      fputcsv($fp, array_values($row));
    }
  }
}

function get_longitude_length($lat, $long, $lat_length = 111000) {
  return abs($lat_length * cos($lat));
}

function get_mile_from_meter($meter) {
  return round(($meter / 1000 * 0.625), 2);
}

function get_user_all_count() {
  global $wpdb;

  return $wpdb->get_var("SELECT count(*) FROM " . TABLE_USERS);
}

function get_schools($format = 0) {
  global $wpdb;

  $schools = $wpdb->get_results("SELECT * FROM " . TABLE_SCHOOLS . " ORDER BY `name`");
  if ($format == 0) {
    return $schools;
  }

  $temp = array();
  foreach ($schools as $school) {
    $temp[$school->ID] = $school->name;
  }

  return $temp;
}

function striptags($string) {
  return trim(str_replace("\t", "", str_replace("\n", "", str_replace("\r", "", strip_tags($string)))));
}

function get_ebay_search_result($ebay_link) {
  $html = file_get_html($ebay_link);
  if ($html === false) {
    return array();
  }

  $result = array();
  foreach ($html->find("#Results #ListViewInner li.lvresult") as $search_item) {
    $ebay_info = array();
    foreach ($search_item->find(".lvpic a") as $a_item) {
      $ebay_info['ebay_link'] = $a_item->href;
      $ebay_info['ebay_img'] = $a_item->firstChild()->getAttribute('src');
    }

    if ($ebay_info['ebay_link']) {
      
    } else {
      continue;
    }

    foreach ($search_item->find(".lvtitle a") as $title_item) {
      $ebay_info['ebay_title'] = substr($title_item->getAttribute('title'), 26);
    }

    foreach ($search_item->find(".lvprices .lvprice span") as $price_item) {
      $ebay_info['ebay_price'] = striptags($price_item->innertext);
    }

    $result[] = $ebay_info;

    if (count($result) == 20) {
      break;
    }
  }

  return $result;
}

function get_youtube_search_result($youtube_link) {
  $result = array();

  $temp = parse_url($youtube_link);
  if (!isset($temp['query'])) {
    return $result;
  }
  parse_str($temp['query'], $temp);
  if (isset($temp['v']) && $temp['v']) {
    try {
      $doc = new DOMDocument();
      $doc->loadHTMLFile($youtube_link);
      $doc->preserveWhiteSpace = false;
      $title_div = $doc->getElementById('eow-title');
      $title = $title_div->nodeValue;

      $youtube_id = $temp['v'];
      $youtube_info['youtube_id'] = $youtube_id;
      $youtube_info['youtube_link'] = $youtube_link;
      $youtube_info['youtube_title'] = $title;
      $youtube_info['youtube_img'] = "https://img.youtube.com/vi/" . $youtube_id . "/0.jpg";

      $result[] = $youtube_info;
    } catch (Exception $e) {
      return $result;
    }
  } else {
    $html = file_get_html($youtube_link);
    if ($html === false) {
      return $result;
    }

    foreach ($html->find("#results .section-list .item-section li .yt-lockup .yt-lockup-content h3.yt-lockup-title a") as $a_item) {
      $youtube_info = array();

      $temp = parse_url($a_item->href);
      if (!isset($temp['query']))
        continue;
      parse_str($temp['query'], $temp);
      $youtube_id = isset($temp['v']) ? $temp['v'] : "";

      if ($youtube_id) {
        
      } else {
        continue;
      }

      $youtube_info['youtube_id'] = $youtube_id;
      $youtube_info['youtube_link'] = "https://youtube.com" . $a_item->href;
      $youtube_info['youtube_title'] = striptags($a_item->innertext);
      $youtube_info['youtube_img'] = "https://img.youtube.com/vi/" . $youtube_id . "/0.jpg";

      $result[] = $youtube_info;

      if (count($result) == 20) {
        break;
      }
    }
  }

  return $result;
}
