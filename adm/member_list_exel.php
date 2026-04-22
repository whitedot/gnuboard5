<?php
$sub_menu = "200400";
require_once './_common.php';
require_once './member_list_exel.lib.php'; // 회원관리파일 공통 라이브러리

auth_check_menu($auth, $sub_menu, 'r');

$member_export_view = admin_build_member_export_page_view($_GET, $config);
$params = $member_export_view['params'];
extract($member_export_view, EXTR_SKIP);
extract($params, EXTR_SKIP);

$g5['title'] = $title;
require_once './admin.head.php';
?>

<section class="card mb-5">
    <div class="card-header">
        <h2 class="card-title">회원 엑셀 생성</h2>
    </div>
    <div class="card-body space-y-3 text-sm leading-6 text-default-700">
        <p><strong>회원수 <?php echo number_format(MEMBER_EXPORT_PAGE_SIZE);?>건 초과 시</strong> <?php echo number_format(MEMBER_EXPORT_PAGE_SIZE);?>건 단위로 분리 저장되며, <strong>엑셀 생성 최대 건수는 <?php echo number_format(MEMBER_EXPORT_MAX_SIZE);?>건</strong>입니다. 초과 시 조건 추가 설정 후 재시도하시기 바랍니다.</p>
        <p><strong>수신동의 확인 대상은 만료일까지 1달 미만인 회원</strong>을 기준으로 필터링됩니다.</p>
        <p>
            파일 생성 시 서버에 임시 생성된 파일 중 <strong>오늘 날짜를 제외 한 파일은 자동 삭제</strong>되며, 수동 삭제가 필요하면
            <a href="<?php echo G5_ADMIN_URL; ?>/member_list_file_delete.php" class="font-semibold text-primary hover:underline">회원관리파일 일괄삭제</a>
            를 이용해 주세요.
        </p>
        <p>회원 정보 수정은 <a href="<?php echo G5_ADMIN_URL;?>/member_list.php" class="font-semibold text-primary hover:underline">회원 관리</a>에서 진행하실 수 있습니다.</p>
    </div>
</section>

<div class="mb-5 inline-flex items-center gap-2 rounded-lg border border-default-300 bg-card px-4 py-2 text-sm font-medium text-default-700 shadow-sm">
    <span>총건수</span>
    <?php if ($total_error != "") { ?>
    <span class="text-danger"><?php echo $total_error ?></span>
    <?php } else { ?>
    <span><?php echo number_format($total_count) ?>건</span>
    <?php } ?>
</div>


<!-- 회원 검색 필터링 폼 -->
<form id="fsearch" name="fsearch" method="get" class="card ui-form-theme ui-form-showcase">
    <input type="hidden" name="token" value="<?php echo $form_token; ?>">
    <div class="card-header">
        <h2 class="card-title">회원 검색 필터링</h2>
    </div>
    <fieldset class="card-body">
        <legend class="caption-sr-only">회원 검색 필터링</legend>
        <div class="af-grid">

            <!-- 검색어 적용 -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="use_stx" value="1" <?php echo !empty($use_stx) ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">검색어 적용</span>
                    </label>
                </div>
                <div class="af-field">
                    <div class="flex flex-col gap-2 lg:flex-row lg:flex-nowrap lg:items-center">
                    <select name="sfl" class="form-select lg:w-auto">
                        <?php
                            foreach ($sfl_options as $val => $label) {
                                $selected = $sfl === $val ? 'selected' : '';
                                echo "<option value=\"$val\" $selected>$label</option>";
                            }
                        ?>
                    </select>
                    <input type="text" name="stx" value="<?php echo htmlspecialchars($stx); ?>" placeholder="검색어 입력" class="form-input lg:w-72 xl:w-80">
                    <div class="af-inline shrink-0 whitespace-nowrap">
                        <label class="af-check form-label">
                            <input type="radio" name="stx_cond" value="like" <?php echo $stx_cond === 'like' ? 'checked' : ''; ?> class="form-radio">
                            <span class="form-label">포함</span>
                        </label>
                        <label class="af-check form-label">
                            <input type="radio" name="stx_cond" value="equal" <?php echo $stx_cond === 'equal' ? 'checked' : ''; ?> class="form-radio">
                            <span class="form-label">일치</span>
                        </label>
                    </div>
                    </div>
                </div>
            </div>

            <!-- 레벨 적용 -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="use_level" value="1" <?php echo !empty($use_level) ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">레벨 적용</span>
                    </label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                    <select name="level_start" class="form-select">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $level_start == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <span class="ui-form-inline-note">~</span>
                    <select name="level_end" class="form-select">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $level_end == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    </div>
                </div>
            </div>

            <!-- 가입기간 적용 -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="use_date" value="1" <?php echo !empty($use_date) ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">가입기간 적용</span>
                    </label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                    <input type="date" name="date_start" max="9999-12-31" value="<?php echo htmlspecialchars($date_start); ?>" class="form-input">
                    <span class="ui-form-inline-note">~</span>
                    <input type="date" name="date_end" max="9999-12-31" value="<?php echo htmlspecialchars($date_end); ?>" class="form-input">
                    </div>
                </div>
            </div>

            <!-- 차단회원 조건 -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="use_intercept" value="1" <?php echo !empty($use_intercept) ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">차단회원</span>
                    </label>
                </div>
                <div class="af-field">
                    <select name="intercept" id="intercept" class="form-select lg:w-auto">
                        <?php
                            foreach ($intercept_options as $val => $label) {
                                $selected = $intercept === $val ? 'selected' : '';
                                echo "<option value=\"$val\" $selected>$label</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <!-- 휴대폰 번호 조건 - 초기세팅(설정에 휴대폰번호가 보이기/필수입력이면 기본값 checked로 설정) -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="use_hp_exist" value="1" <?php echo $use_hp_checked; ?> class="form-checkbox">
                        <span class="form-label">휴대폰 번호 있는 경우만</span>
                    </label>
                </div>
                <div class="af-field">
                    <p class="hint-text">휴대폰 번호가 입력된 회원만 내보냅니다.</p>
                </div>
            </div>

            <!-- 정보수신동의 조건 -->
            <div class="af-row">
                <div class="af-label">
                    <label class="af-check form-label">
                        <input type="checkbox" name="ad_range_only" value="1" <?php echo !empty($ad_range_only) ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">정보수신동의에 동의한 경우만</span>
                    </label>
                </div>
                <div class="af-field">
                    <p class="hint-text">「정보통신망이용촉진및정보보호등에관한법률」에 따라 <strong>광고성 정보 수신동의 여부</strong>를 <strong>매2년</strong>마다 확인해야 합니다.</p>
                </div>
            </div>

            <div class="af-row ad_range_wrap <?php echo !empty($ad_range_only) ? '' : 'is-hidden'; ?>">
                <div class="af-label">
                    <label for="ad_range_type" class="form-label">회원범위</label>
                </div>
                <div class="af-field">
                    <div class="space-y-3">
                            <select name="ad_range_type" id="ad_range_type" class="form-select lg:w-auto">
                                <?php 
                                    foreach ($ad_range_options as $val => $label) {
                                        $selected = $ad_range_type === $val ? 'selected' : '';
                                        echo "<option value=\"$val\" $selected>$label</option>";
                                    }
                                ?>
                            </select>

                            <div class="ad_range_wrap space-y-3">
                                <!-- 기간 직접 입력 -->
                                <div class="<?php echo !empty($ad_range_only) && $ad_range_type == 'custom_period' ? '' : 'is-hidden'; ?> space-y-2">
                                    <div class="af-inline">
                                        <input type="date" name="agree_date_start" max="9999-12-31" value="<?php echo htmlspecialchars($agree_date_start_value); ?>" class="form-input">
                                        <span class="ui-form-inline-note">~</span>
                                        <input type="date" name="agree_date_end" max="9999-12-31" value="<?php echo htmlspecialchars($agree_date_end_value); ?>" class="form-input">
                                    </div>
                                    <p class="hint-text">광고성 정보 수신(<strong>이메일</strong>) 동의일자 기준</p>
                                </div>

                                <!-- 설명 문구 -->
                                <?php if ($active_ad_range_text !== '') { ?>
                                    <div><p class="hint-text mt-0"><?php echo $active_ad_range_text; ?></p></div>
                                <?php } ?>
                            </div>
                    </div>
                </div>
            </div>

            <!-- 채널 체크박스 -->
            <div class="af-row ad_range_wrap <?php echo !empty($ad_range_only) && in_array($ad_range_type, ['month_confirm', 'custom_period'], true) ? '' : 'is-hidden'; ?>">
                <div class="af-label">
                    <label class="form-label">확인 채널</label>
                </div>
                <div class="af-field">
                        <div class="af-inline">
                            <label class="af-check form-label"><input type="checkbox" name="ad_mailling" value="1" <?php echo $ad_mailling_checked; ?> class="form-checkbox"><span class="form-label">광고성 이메일 수신</span></label>
                        </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 px-0 pt-2">
                <button type="button" id="btnExcelDownload" class="btn btn-solid-primary">엑셀파일 다운로드</button>
                <button type="button" onclick="location.href='?'" class="btn btn-surface-default-soft">초기화</button>
            </div>
        </div>
    </fieldset>
</form>

<script>
document.querySelector('input[name="ad_range_only"]').addEventListener('change', function () {
  document.querySelectorAll('.ad_range_wrap').forEach(el => {
    el.classList.toggle('is-hidden', !this.checked);
  });
});

document.querySelectorAll('#fsearch input, #fsearch select').forEach(el => {
    const submit = () => document.getElementById('fsearch').submit();
    el.addEventListener(el.type === 'date' ? 'blur' : 'change', submit);

    el.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
        e.preventDefault();
        submit();
        }
    });
});
</script>

<script>
let eventSource = null;

// 일반 엑셀 다운로드 버튼 클릭
document.getElementById('btnExcelDownload').addEventListener('click', () => {
    startExcelDownload();
});

// 엑셀 다운로드 실행
// 1. 기존 SSE 종료
function closePreviousEventSource() {
    if (eventSource) {
        eventSource.close();
        eventSource = null;
    }
}

// 2. FormData QueryString 변환
function buildDownloadParams(selectedFields = []) {
    const formData = new FormData(document.getElementById('fsearch'));
    const params = new URLSearchParams(formData);

    params.append('mode', 'start');

    return params.toString();
}

// 3. 메인 함수
function startExcelDownload(selectedFields = []) {
    closePreviousEventSource();

    const query = buildDownloadParams(selectedFields);
    showDownloadPopup();

    eventSource = new EventSource(`member_list_exel_export.php?${query}`);
    eventSource.onmessage = handleProgressUpdate();
    eventSource.onerror = handleDownloadError();
}

// 다운로드 팝업 표시
function showDownloadPopup() {
    const bodyHTML = `
        <div class="excel-download-progress">
            <div class="progress-desc">
                <p class="progress-summary">총 <strong>0</strong>개 파일로 분할됩니다</p>
                <p class="progress-message"><strong>(0 / 0)</strong> 파일 다운로드 중</p>
                <p class="progress-error"></p>
            </div>
            <div class="progress-spinner">
                <div class="spinner"></div>
                <p class="loading-message">
                    엑셀 파일을 생성 중입니다. 잠시만 기다려주세요.<br>
                    현재 데이터 기준으로 <strong id="estimatedTimeText"></strong> 정도 소요될 수 있습니다.<br>
                    <strong>페이지를 벗어나거나 닫으면 다운로드가 중단</strong>되니, 작업 완료까지 기다려 주세요.
                </p>
            </div>
            <div class="progress-box">
                <div class="progress-download-box"></div>
            </div>
        </div>
    `;

    PopupManager.render('엑셀 다운로드 진행 중', bodyHTML, '', { disableOutsideClose: true });

    // 닫기 버튼 이벤트 핸들링
    const closeBtn = document.querySelector('.popup-close-btn');
    if (closeBtn) {
        closeBtn.removeAttribute('onclick');
        closeBtn.addEventListener('click', handlePopupCloseWithConfirm);
    }
}

// 닫기 버튼 클릭 시 다운로드 중단 여부 확인
function handlePopupCloseWithConfirm(e) {
    if (eventSource) {
        const confirmClose = confirm("엑셀 다운로드가 진행 중입니다.\n정말 중지하시겠습니까?");
        if (!confirmClose) {
            e.preventDefault();
            return;
        }
        eventSource.close();
        eventSource = null;
        alert("엑셀 다운로드가 중단되었습니다.");
    }
    PopupManager.close('popupOverlay');
}

// 체크박스 선택 시 최대 3개 제한 및 선택된 항목 미리보기 표시
function bindFieldSelectEvents() {
    const fieldSelectForm = document.getElementById('fieldSelectForm');
    if (!fieldSelectForm) return;

    fieldSelectForm.addEventListener('change', function (e) {
        if (e.target.name === 'fields') {
            const selected = fieldSelectForm.querySelectorAll('input[name="fields"]:checked');
            if (selected.length > 3) {
                alert("최대 3개까지 선택 가능합니다.");
                e.target.checked = false;
                return;
            }

            // 선택된 항목 표시
            const previewContainer = document.getElementById('selectedFieldsPreview');
            let spans = '<strong>선택된 항목:</strong>';
            selected.forEach(field => {
                const label = field.parentElement.textContent.trim();
                spans += `<span class="field-tag">${label}</span>`;
            });
            previewContainer.innerHTML = spans;
        }
    });
}

// 엑셀 생성 및 다운로드 실행
function handleProgressUpdate() {
    return function(e) {
        const data = JSON.parse(e.data);
        const { status, downloadType, message, total, current, totalChunks, currentChunk, zipFile, files, filePath } = data;

        // DOM 요소 캐싱
        const titleEl = document.getElementById('popupTitle');
        const summaryEl = document.querySelector('.progress-summary');
        const messageEl = document.querySelector('.progress-message');
        const spinnerEl = document.querySelector('.progress-spinner');
        const resultEl = document.querySelector('.loading-message');
        const downloadBoxEl = document.querySelector('.progress-download-box');
        const errorEl = document.querySelector('.progress-error');

        if (status === "progress") 
        {
            summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일로 ` + (downloadType === 2 ? `분할 생성됩니다` : `다운로드됩니다`) + ` (총 ${total.toLocaleString('ko-KR')}건)`;
            messageEl.innerHTML = downloadType === 2 ? `<strong>(${currentChunk} / ${totalChunks})</strong> 파일 생성 중` : `엑셀 파일 생성 중`;

            /* 작업 소요 시간 : 예상 시간 (1만건당 10초) */
            const sec = Math.max(5, Math.ceil(total * 0.0012 * 1.2)); // 최소 5초 보장
            const text = `예상 처리 시간은 약 ${sec >= 60 ? `${Math.floor(sec / 60)}분 ${sec % 60}초` : `${sec}초`}`;
            document.getElementById('estimatedTimeText').innerText = text;
        }
        else if (status === "zipping") 
        {
            summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일이 압축파일로 생성됩니다`;
            messageEl.innerHTML = `<strong>${totalChunks}</strong> 파일 압축하는 중`;
        }
        else if (status === "zippingError") 
        {
            errorEl.innerHTML = message;
        } 
        else if (status === "error") 
        {
            summaryEl.innerHTML = `엑셀 파일 다운로드 실패`;
            resultEl.innerHTML = '';
            spinnerEl?.classList.add('is-hidden');

            const parts = message.split(/<br\s*\/?>/i);
            messageEl.innerHTML = parts[0] || '';
            errorEl.innerHTML = parts.slice(1).join('<br>') || '';

            // SSE 작업 닫기
            eventSource?.close();
            eventSource = null;
        } 
        else if (status === "done") 
        {
            // SSE 작업 닫기
            eventSource?.close();
            eventSource = null;

            titleEl.textContent = '엑셀 파일 다운로드 완료';
            messageEl.innerHTML = `<strong>총 ${total.toLocaleString('ko-KR')}건의 데이터 다운로드가 완료되었습니다!</strong>`;
            spinnerEl?.classList.add('is-hidden');

            let html = '<p>* 자동으로 다운로드가 되지 않았다면 아래 버튼을 클릭해주세요.</p>';
            const baseUrl = `<?php echo G5_DATA_URL; ?>/member_list/<?php echo date('Ymdhis'); ?>/`; // 공통 URL 분리

            if (zipFile) {
                const url = `${filePath}/${zipFile}`;
                html += `<a href="${url}" class="btn btn-tertiary" download>압축파일 다운로드</a>`;
                downloadBoxEl.innerHTML = html;
                triggerAutoDownload(url, zipFile);
            } else if (files?.length) {
                files.forEach((file, index) => {
                    const url = `${filePath}/${file}`;
                    html += `<a class="btn btn-tertiary" href="${url}" download>엑셀파일 다운로드 ${index + 1}</a>`;
                });
                downloadBoxEl.innerHTML = html;

                if (files.length === 1) {
                    const url = `${filePath}/${files[0]}`;
                    triggerAutoDownload(url, files[0]);
                } else {
                    summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일이 생성되었습니다. 아래 버튼을 눌러 다운로드 받아주세요.`;
                }
            }
        }
    }
}

// SSE 오류 처리
function handleDownloadError() {
    return function(e){
        const errorMessage = e?.message || e?.data || '알 수 없는 오류가 발생했습니다.';

        document.querySelector('.progress-summary').innerHTML = `엑셀 파일 다운로드 실패`;
        document.querySelector('.progress-message').innerHTML = `엑셀 파일 다운로드에 실패하였습니다`;
        document.querySelector('.progress-error').innerHTML = errorMessage;
        document.querySelector('.loading-message').innerHTML = '';
        document.querySelector('.progress-spinner').classList.add('is-hidden');

        if (eventSource) {
            eventSource.close();
            eventSource = null;
        }
    }
}

// 자동 다운로드 실행
function triggerAutoDownload(url, filename) {
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>

<?php
require_once './admin.tail.php';
