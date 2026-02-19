<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨



$max_width = $max_height = 0;
$bn_first_class = '';
$bn_slide_btn = '';
$bn_sl = '';
$main_banners = array();

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $main_banners[] = $row;

    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? '' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
    $item_html = '';

    if (file_exists($bimg))
    {
        $banner = '';
        $size = getimagesize($bimg);

        if($size[2] < 1 || $size[2] > 16)
            continue;

        if($max_width < $size[0])
            $max_width = $size[0];

        if($max_height < $size[1])
            $max_height = $size[1];

        $item_html .= '<div>';
        if ($row['bn_url'][0] == '#')
            $banner .= '<a href="'.$row['bn_url'].'">';
        else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
            $banner .= '<a href="'.G5_SHOP_URL.'/bannerhit.php?bn_id='.$row['bn_id'].'"'.$bn_new_win.'>';
        }
        $item_html .= $banner.'<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'?'.preg_replace('/[^0-9]/i', '', $row['bn_time']).'" width="'.$size[0].'" alt="'.get_text($row['bn_alt']).'"'.$bn_border.'>';
        if($banner)
            $item_html .= '</a>';
        $item_html .= '</div>';
    }
    
    $banner_style = $max_height ? '' : '';
    if ($i==0) echo '<div id="main_bn"><div><div>'.PHP_EOL;
    
    echo $item_html;
}

if ($i > 0) {
    echo '</div>'.PHP_EOL;
	
	echo '<div><a href="#"><i></i></a><div id="slide-counter"></div><a href="#"><i></i></a> </div>'.PHP_EOL;

    echo '</div>'.PHP_EOL;
    
    echo '<div>'.PHP_EOL;
        echo '<div>
    <ul>';
		$k = 0;
		foreach( $main_banners as $row ){
            $alt_title = $row['bn_alt'] ? cut_str(get_text($row['bn_alt']), 12, '') : '&nbsp;';
			echo '<li><a data-slide-index="'.$k.'" href="#">'.$alt_title.'</a></li>'.PHP_EOL;
			$k++;
			}
		
    echo '</ul>
    </div>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
?>


<?php
}