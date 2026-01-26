<?php
if (!defined('_GNUBOARD_')) exit;

// 이미지를 얻는다
function get_image($img, $width=0, $height=0, $img_id='')
{
    global $g5, $default;

    $full_img = G5_DATA_PATH.'/item/'.$img;

    if (file_exists($full_img) && $img)
    {
        if (!$width)
        {
            $size = getimagesize($full_img);
            $width = $size[0];
            $height = $size[1];
        }
        $str = '<img src="'.G5_DATA_URL.'/item/'.$img.'" alt="" width="'.$width.'" height="'.$height.'"';

        if($img_id)
            $str .= ' id="'.$img_id.'"';

        $str .= '>';
    }
    else
    {
        $str = '<img src="'.G5_SHOP_URL.'/img/no_image.gif" alt="" ';
        if ($width)
            $str .= 'width="'.$width.'" height="'.$height.'"';
        else
            $str .= 'width="'.$default['de_mimg_width'].'" height="'.$default['de_mimg_height'].'"';

        if($img_id)
            $str .= ' id="'.$img_id.'"';
        $str .= '>';
    }

    return $str;
}


// 상품 이미지를 얻는다
function get_it_image($it_id, $width, $height=0, $anchor=false, $img_id='', $img_alt='', $is_crop=false)
{
    global $g5;

    if(!$it_id || !$width)
        return '';

    $row = get_shop_item($it_id, true);

    if(!$row['it_id'])
        return '';

    $filename = $thumb = $img = '';
    
    $img_width = 0;
    for($i=1;$i<=10; $i++) {
        $file = G5_DATA_PATH.'/item/'.$row['it_img'.$i];
        if(is_file($file) && $row['it_img'.$i]) {
            $size = @getimagesize($file);
            if(! isset($size[2]) || $size[2] < 1 || $size[2] > 3)
                continue;

            $filename = basename($file);
            $filepath = dirname($file);
            $img_width = $size[0];
            $img_height = $size[1];

            break;
        }
    }

    if($img_width && !$height) {
        $height = round(($width * $img_height) / $img_width);
    }

    if($filename) {
        //thumbnail($filename, $source_path, $target_path, $thumb_width, $thumb_height, $is_create, $is_crop=false, $crop_mode='center', $is_sharpen=true, $um_value='80/0.5/3')
        $thumb = thumbnail($filename, $filepath, $filepath, $width, $height, false, $is_crop, 'center', false, $um_value='80/0.5/3');
    }

    if($thumb) {
        $file_url = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
        $img = '<img src="'.$file_url.'" width="'.$width.'" height="'.$height.'" alt="'.$img_alt.'"';
    } else {
        $img = '<img src="'.G5_SHOP_URL.'/img/no_image.gif" width="'.$width.'"';
        if($height)
            $img .= ' height="'.$height.'"';
        $img .= ' alt="'.$img_alt.'"';
    }

    if($img_id)
        $img .= ' id="'.$img_id.'"';
    $img .= '>';

    if($anchor)
        $img = $img = '<a href="'.shop_item_url($it_id).'">'.$img.'</a>';

    return run_replace('get_it_image_tag', $img, $thumb, $it_id, $width, $height, $anchor, $img_id, $img_alt, $is_crop);
}

// 상품이미지 썸네일 생성
function get_it_thumbnail($img, $width, $height=0, $id='', $is_crop=false)
{
    $str = '';

    if ( $replace_tag = run_replace('get_it_thumbnail_tag', $str, $img, $width, $height, $id, $is_crop) ){
        return $replace_tag;
    }

    $file = G5_DATA_PATH.'/item/'.$img;
    if(is_file($file))
        $size = @getimagesize($file);

    if (! (isset($size) && is_array($size))) 
        return '';

    if($size[2] < 1 || $size[2] > 3)
        return '';

    $img_width = $size[0];
    $img_height = $size[1];
    $filename = basename($file);
    $filepath = dirname($file);

    if($img_width && !$height) {
        $height = round(($width * $img_height) / $img_width);
    }

    $thumb = thumbnail($filename, $filepath, $filepath, $width, $height, false, $is_crop, 'center', false, $um_value='80/0.5/3');

    if($thumb) {
        $file_url = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
        $str = '<img src="'.$file_url.'" width="'.$width.'" height="'.$height.'"';
        if($id)
            $str .= ' id="'.$id.'"';
        $str .= ' alt="">';
    }

    return $str;
}


// 이미지 URL 을 얻는다.
function get_it_imageurl($it_id)
{
    global $g5;

    $row = get_shop_item($it_id, true);
    $filepath = '';

    for($i=1; $i<=10; $i++) {
        $img = $row['it_img'.$i];
        $file = G5_DATA_PATH.'/item/'.$img;
        if(!is_file($file))
            continue;

        $size = @getimagesize($file);
        if($size[2] < 1 || $size[2] > 3)
            continue;

        $filepath = $file;
        break;
    }

    if($filepath)
        $str = str_replace(G5_PATH, G5_URL, $filepath);
    else
        $str = G5_SHOP_URL.'/img/no_image.gif';

    return $str;
}

// 큰 이미지
function get_large_image($img, $it_id, $btn_image=true)
{
    global $g5;

    if (file_exists(G5_DATA_PATH.'/item/'.$img) && $img != '')
    {
        $size   = getimagesize(G5_DATA_PATH.'/item/'.$img);
        $width  = $size[0];
        $height = $size[1];
        $str = '<a href="javascript:popup_large_image(\''.$it_id.'\', \''.$img.'\', '.$width.', '.$height.', \''.G5_SHOP_URL.'\')">';
        if ($btn_image)
            $str .= '큰이미지</a>';
    }
    else
        $str = '';
    return $str;
}

// 상품이미지 업로드
function it_img_upload($srcfile, $filename, $dir)
{
    if($filename == '')
        return '';

    $size = @getimagesize($srcfile);
    if($size[2] < 1 || $size[2] > 3)
        return '';

    //php파일도 getimagesize 에서 Image Type Flag 를 속일수 있다
    if (!preg_match('/\.(gif|jpe?g|png)$/i', $filename))
        return '';

    if(!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION);
        @chmod($dir, G5_DIR_PERMISSION);
    }

    $pattern = "/[#\&\+\-%@=\/\\:;,'\"\^`~\|\!\?\*\$#<>\(\)\[\]\{\}]/";

    $filename = preg_replace("/\s+/", "", $filename);
    $filename = preg_replace( $pattern, "", $filename);

    $filename = preg_replace_callback("/[가-힣]+/", '_callback_it_img_upload', $filename);

    $filename = preg_replace( $pattern, "", $filename);
    $prepend = '';

    // 동일한 이름의 파일이 있으면 파일명 변경
    if(is_file($dir.'/'.$filename)) {
        for($i=0; $i<20; $i++) {
            $prepend = str_replace('.', '_', microtime(true)).'_';

            if(is_file($dir.'/'.$prepend.$filename)) {
                usleep(mt_rand(100, 10000));
                continue;
            } else {
                break;
            }
        }
    }

    $filename = $prepend.$filename;

    upload_file($srcfile, $filename, $dir);

    $file = str_replace(G5_DATA_PATH.'/item/', '', $dir.'/'.$filename);

    return $file;
}

function _callback_it_img_upload($matches){
    return isset($matches[0]) ? base64_encode($matches[0]) : '';
}

// 파일을 업로드 함
function upload_file($srcfile, $destfile, $dir)
{
    if ($destfile == "") return false;
    // 업로드 한후 , 퍼미션을 변경함
    @move_uploaded_file($srcfile, $dir.'/'.$destfile);
    @chmod($dir.'/'.$destfile, G5_FILE_PERMISSION);
    return true;
}

// 상품이미지 썸네일 삭제
function delete_item_thumbnail($dir, $file)
{
    if(!$dir || !$file)
        return;

    $filename = preg_replace("/\.[^\.]+$/i", "", $file); // 확장자제거

    $files = glob($dir.'/thumb-'.$filename.'*');

    if(is_array($files)) {
        foreach($files as $thumb_file) {
            @unlink($thumb_file);
        }
    }
}

// 사용후기 썸네일 생성
function get_itemuse_thumb($contents, $thumb_width, $thumb_height, $is_create=false, $is_crop=true, $crop_mode='center', $is_sharpen=true, $um_value='80/0.5/3'){
    
    global $config;

    $img = $filename = $alt = "";

    $matches = get_editor_image($contents, false);

    for($i=0; $i<count($matches[1]); $i++)
    {
        // 이미지 path 구함
        $p = parse_url($matches[1][$i]);
        if(strpos($p['path'], '/'.G5_DATA_DIR.'/') != 0)
            $data_path = preg_replace('/^\/.*\/'.G5_DATA_DIR.'/', '/'.G5_DATA_DIR, $p['path']);
        else
            $data_path = $p['path'];

        $srcfile = G5_PATH.$data_path;

        if(preg_match("/\.({$config['cf_image_extension']})$/i", $srcfile) && is_file($srcfile)) {
            $size = @getimagesize($srcfile);
            if(empty($size))
                continue;

            $filename = basename($srcfile);
            $filepath = dirname($srcfile);

            preg_match("/alt=[\"\']?([^\"\']*)[\"\']?/", $matches[0][$i], $malt);
            $alt = isset($malt[1]) ? get_text($malt[1]) : '';

            break;
        }
    }

    if($filename) {
        $thumb = thumbnail($filename, $filepath, $filepath, $thumb_width, $thumb_height, $is_create, $is_crop, $crop_mode, $is_sharpen, $um_value);

        if($thumb) {
            $src = G5_URL.str_replace($filename, $thumb, $data_path);
            $img = '<img src="'.$src.'" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="'.$alt.'">';
        }
    }

    return $img;
}

// 사용후기에서 후기에 이미지가 있으면 썸네일을 리턴하며 후기에 이미지가 없으면 상품이미지를 리턴합니다.
function get_itemuselist_thumbnail($it_id, $contents, $thumb_width, $thumb_height, $is_create=false, $is_crop=true, $crop_mode='center', $is_sharpen=true, $um_value='80/0.5/3')
{
    global $g5, $config;
    $img = $filename = $alt = "";

    if($contents) {
        $img = get_itemuse_thumb($contents, $thumb_width, $thumb_height);
    }

    if(!$img)
        $img = get_it_image($it_id, $thumb_width, $thumb_height);

    return $img;
}

function get_item_images_info($it, $size=array(), $image_width=0, $image_height=0){
    
    if( !(is_array($it) && $it) ) return array();
    $images = array();

    for($i=1; $i<=10; $i++) {
        if(!$it['it_img'.$i]) continue;
        $file = G5_DATA_PATH.'/item/'.$it['it_img'.$i];
        if( $is_exists = run_replace('is_exists_item_file', is_file($file), $it, $i) ){
            $thumb = get_it_thumbnail($it['it_img'.$i], $image_width, $image_height);
            $attr = (isset($size[0]) && isset($size[1]) && $size[0] && $size[1]) ? 'width="'.$size[0].'" height="'.$size[1].'" ' : '';
            $imageurl = G5_DATA_URL.'/item/'.$it['it_img'.$i];
            $infos = array(
                'thumb'=>$thumb,
                'imageurl'=>$imageurl,
                'imagehtml'=>'<img src="'.$imageurl.'" '.$attr.' alt="'.get_text($it['it_name']).'" id="largeimage_'.$i.'">',
                );
            $images[$i] = run_replace('get_image_by_item', $infos, $it, $i, $size);
        }
    }
    return $images; 
}
