<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 게시물 읽기 시작 { -->

<article id="bo_v">
    <header>
        <h2 id="bo_v_title">
            <?php
            echo '<span>'.$view['category'].'</span> '; // 분류 출력 끝
            ?>
            <?php
            echo $view['subject']; // 글제목 출력
            ?>
        </h2>
    </header>

    <section id="bo_v_info">
        <h2>페이지 정보</h2>
        <span>작성자</span><strong><?php echo $view['name'] ?></strong>
        <span>작성일</span><strong><i aria-hidden="true"></i> <?php echo $view['datetime']; ?></strong>
        <?php if($view['email'] || $view['hp']) { ?>
            <?php if($view['email']) { ?>
            <span>이메일</span>
            <strong><i aria-hidden="true"></i> <?php echo $view['email']; ?></strong>
            <?php } ?>
            <?php if($view['hp']) { ?>
            <span>휴대폰</span>
            <strong><i aria-hidden="true"></i> <?php echo $view['hp']; ?></strong>
            <?php } ?>
        <?php } ?>
        
        <!-- 게시물 상단 버튼 시작 { -->
	    <div id="bo_v_top">
	        <?php
	        ob_start();
			?>

	        <ul>
				<li><a href="<?php echo $list_href ?>" title="목록"><i aria-hidden="true"></i><span>목록</span></a></li>
	            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" title="글쓰기"><i aria-hidden="true"></i><span>글쓰기</span></a></li><?php } ?>
                <?php if ($update_href || $delete_href) { ?>
	        	<li>
	        		<button type="button" class="btn_more_opt" title="게시판 읽기 옵션"><i aria-hidden="true"></i><span>게시판 읽기 옵션</span></button>
	        		<ul class="more_opt">
	        			<?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>" title="수정">수정<i aria-hidden="true"></i></a></li><?php } ?>
	            		<?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;" title="삭제">삭제<i aria-hidden="true"></i></a></li><?php } ?>
	        		</ul>
	        	</li>
                <?php } ?>
	        </ul>
	        <script>
				// 게시판 리스트 옵션
				$(".btn_more_opt").on("click", function() {
				    $(".more_opt").toggle();
				})
			</script>
	        <?php
	        $link_buttons = ob_get_contents();
	        ob_end_flush();
			?>
	    </div>
	    <!-- } 게시물 상단 버튼 끝 -->
	</section>

    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>

        <?php
        // 파일 출력
        if($view['img_count']) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<$view['img_count']; $i++) {
                //echo $view['img_file'][$i];
                echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
            }

            echo "</div>\n";
        }
         ?>

        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?></div>
        <!-- } 본문 내용 끝 -->

        <?php if($view['qa_type']) { ?>
        <div id="bo_v_addq"><a href="<?php echo $rewrite_href; ?>">추가질문</a></div>
        <?php } ?>

        <?php if($view['download_count']) { ?>

        <!-- 첨부파일 시작 { -->
        <section id="bo_v_file">
            <h2>첨부파일</h2>
            <ul>
            <?php
            // 가변 파일
            for ($i=0; $i<$view['download_count']; $i++) {
             ?>
                <li>
                    <i aria-hidden="true"></i>
                    <a href="<?php echo $view['download_href'][$i];  ?>" class="view_file_download" download>
                        <strong><?php echo $view['download_source'][$i] ?></strong>
                    </a>
                </li>
            <?php
            }
             ?>
            </ul>
        </section>
        <!-- } 첨부파일 끝 -->
        <?php } ?>
    </section>
    
    <?php if ($prev_href || $next_href) { ?>
    <ul>
        <?php if ($prev_href) { ?><li><a href="<?php echo $prev_href ?>"><i aria-hidden="true"></i> 이전글</a></li><?php } ?>
        <?php if ($next_href) { ?><li><a href="<?php echo $next_href ?>">다음글 <i aria-hidden="true"></i></a></li><?php } ?>
    </ul>
    <?php } ?>

    <?php
    // 질문글에서 답변이 있으면 답변 출력, 답변이 없고 관리자이면 답변등록폼 출력
    if(!$view['qa_type']) {
        if($view['qa_status'] && $answer['qa_id'])
            include_once($qa_skin_path.'/view.answer.skin.php');
        else
            include_once($qa_skin_path.'/view.answerform.skin.php');
    }
    ?>

    <?php if($view['rel_count']) { ?>
    <section id="bo_v_rel">
        <h2>연관질문</h2>

        
            <table>
            <thead>
            <tr>
                <th scope="col">제목</th>
                <th scope="col">등록일</th>
                <th scope="col">상태</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for($i=0; $i<$view['rel_count']; $i++) {
            ?>
            <tr>
                <td>
                    <span><?php echo get_text($rel_list[$i]['category']); ?></span>

                    <a href="<?php echo $rel_list[$i]['view_href']; ?>">
                        <?php echo $rel_list[$i]['subject']; ?>
                    </a>
                </td>
                <td><?php echo $rel_list[$i]['date']; ?></td>
                <td><span class="<?php echo ($rel_list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($rel_list[$i]['qa_status'] ? '<i aria-hidden="true"></i> 답변완료' : '<i aria-hidden="true"></i> 답변대기'); ?></span></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
        
    </section>
    <?php } ?>



</article>
<!-- } 게시판 읽기 끝 -->

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});
</script>