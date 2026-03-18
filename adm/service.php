<?php
$sub_menu = '100400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '부가서비스';
include_once('./admin.head.php');
?>


    <p>아래의 서비스들은 영카트에서 이미 지원하는 기능으로 별도의 개발이 필요 없으며 서비스 신청후 바로 사용 할수 있습니다.</p>


<div>
    <div>
        <h3>신용카드 전자결제 서비스<br><span>(계좌이체, 가상계좌 결제 포함)</span></h3>

        <ul>
            <li><a href="http://sir.kr/main/service/p_pg.php" target="_blank"><img src="<?php echo G5_ADMIN_URL ?>/img/svc_btn_01.jpg" alt="KCP 신용카드 전자결제 신청하기"></a></li>
            <li><a href="http://sir.kr/main/service/lg_pg.php" target="_blank"><img src="<?php echo G5_ADMIN_URL ?>/img/svc_btn_02.jpg?v2" alt="토스페이먼츠 전자결제 신청하기"></a></li>
            <li><a href="http://sir.kr/main/service/inicis_pg.php" target="_blank"><img src="<?php echo G5_ADMIN_URL ?>/img/svc_btn_06.jpg" alt="KG 이니시스 전자결제 신청하기"></a></li>
        </ul>
    </div>

    <div>
        <h3>본인확인 서비스</h3>

        <ul>
            <li><a href="http://sir.kr/main/service/p_cert.php" target="_blank"><img src="<?php echo G5_ADMIN_URL ?>/img/svc_btn_01.jpg" alt="KCP 신청하기"></a></li>
            <li><a href="http://sir.kr/main/service/inicis_cert.php" target="_blank"><img src="<?php echo G5_ADMIN_URL ?>/img/svc_btn_06.jpg" alt="KG이니시스 신청하기"></a></li>
        </ul>
    </div>
</div>

<?php
include_once('./admin.tail.php');
