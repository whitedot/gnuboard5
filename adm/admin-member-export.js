window.AdminMemberExport = {
    init() {
        const root = document.querySelector('[data-admin-member-export]');
        if (!root) {
            return;
        }

        const form = root.querySelector('[data-admin-member-export-form]');
        const downloadButton = root.querySelector('#btnExcelDownload');
        const adRangeToggle = form ? form.querySelector('input[name="ad_range_only"]') : null;
        const adRangeType = form ? form.querySelector('#ad_range_type') : null;
        const customPeriod = root.querySelector('[data-admin-member-export-custom-period]');
        const channelRow = root.querySelector('[data-admin-member-export-channel-row]');
        let eventSource = null;

        const config = {
            streamUrl: root.dataset.streamUrl || '',
            popupProgressTitle: root.dataset.popupProgressTitle || '엑셀 다운로드 진행 중',
            popupDoneTitle: root.dataset.popupDoneTitle || '엑셀 파일 다운로드 완료',
            closeConfirmMessage: root.dataset.closeConfirmMessage || '엑셀 다운로드가 진행 중입니다.\n정말 중지하시겠습니까?',
            downloadStoppedMessage: root.dataset.downloadStoppedMessage || '엑셀 다운로드가 중단되었습니다.',
            downloadFailedSummary: root.dataset.downloadFailedSummary || '엑셀 파일 다운로드 실패',
            downloadFailedMessage: root.dataset.downloadFailedMessage || '엑셀 파일 다운로드에 실패하였습니다',
            estimatedSecondsMultiplier: Number(root.dataset.estimatedSecondsMultiplier || 0.00144),
            estimatedSecondsMin: Number(root.dataset.estimatedSecondsMin || 5),
            environmentReady: root.dataset.environmentReady === '1',
            environmentError: root.dataset.environmentError || '',
        };

        const closePreviousEventSource = () => {
            if (eventSource) {
                eventSource.close();
                eventSource = null;
            }
        };

        const toggleAdRangeSections = () => {
            const adRangeEnabled = !!(adRangeToggle && adRangeToggle.checked);
            root.querySelectorAll('.ad_range_wrap').forEach(element => {
                element.classList.toggle('is-hidden', !adRangeEnabled);
            });

            if (customPeriod) {
                const showCustomPeriod = adRangeEnabled && adRangeType && adRangeType.value === 'custom_period';
                customPeriod.classList.toggle('is-hidden', !showCustomPeriod);
            }

            if (channelRow) {
                const showChannelRow = adRangeEnabled && adRangeType && ['month_confirm', 'custom_period'].includes(adRangeType.value);
                channelRow.classList.toggle('is-hidden', !showChannelRow);
            }
        };

        const autoSubmitFilterForm = event => {
            if (!form) {
                return;
            }

            const target = event.target;
            if (!target || !form.contains(target)) {
                return;
            }

            if (event.type === 'keydown') {
                if (event.key !== 'Enter') {
                    return;
                }

                event.preventDefault();
                form.submit();
                return;
            }

            if (target.type === 'date' && event.type !== 'blur') {
                return;
            }

            if (target.type !== 'date' && event.type !== 'change') {
                return;
            }

            form.submit();
        };

        const buildDownloadParams = () => {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.append('mode', 'start');
            return params.toString();
        };

        const triggerAutoDownload = (url, filename) => {
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.download = filename;
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
        };

        const handlePopupCloseWithConfirm = event => {
            if (eventSource) {
                if (!confirm(config.closeConfirmMessage)) {
                    event.preventDefault();
                    return;
                }

                closePreviousEventSource();
                alert(config.downloadStoppedMessage);
            }

            PopupManager.close('popupOverlay');
        };

        const showDownloadPopup = () => {
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

            PopupManager.render(config.popupProgressTitle, bodyHTML, '', { disableOutsideClose: true });

            const closeButton = document.querySelector('.popup-close-btn');
            if (closeButton) {
                closeButton.removeAttribute('onclick');
                closeButton.addEventListener('click', handlePopupCloseWithConfirm);
            }
        };

        const handleProgressUpdate = event => {
            const data = JSON.parse(event.data);
            const { status, downloadType, message, total, totalChunks, currentChunk, zipFile, files, filePath } = data;
            const titleEl = document.getElementById('popupTitle');
            const summaryEl = document.querySelector('.progress-summary');
            const messageEl = document.querySelector('.progress-message');
            const spinnerEl = document.querySelector('.progress-spinner');
            const resultEl = document.querySelector('.loading-message');
            const downloadBoxEl = document.querySelector('.progress-download-box');
            const errorEl = document.querySelector('.progress-error');

            if (!summaryEl || !messageEl || !downloadBoxEl || !errorEl) {
                return;
            }

            if (status === 'progress') {
                summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일로 ${downloadType === 2 ? '분할 생성됩니다' : '다운로드됩니다'} (총 ${total.toLocaleString('ko-KR')}건)`;
                messageEl.innerHTML = downloadType === 2 ? `<strong>(${currentChunk} / ${totalChunks})</strong> 파일 생성 중` : '엑셀 파일 생성 중';

                const sec = Math.max(config.estimatedSecondsMin, Math.ceil(total * config.estimatedSecondsMultiplier));
                const estimatedText = `예상 처리 시간은 약 ${sec >= 60 ? `${Math.floor(sec / 60)}분 ${sec % 60}초` : `${sec}초`}`;
                const estimatedTimeText = document.getElementById('estimatedTimeText');
                if (estimatedTimeText) {
                    estimatedTimeText.innerText = estimatedText;
                }
                return;
            }

            if (status === 'zipping') {
                summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일이 압축파일로 생성됩니다`;
                messageEl.innerHTML = `<strong>${totalChunks}</strong> 파일 압축하는 중`;
                return;
            }

            if (status === 'zippingError') {
                errorEl.innerHTML = message;
                return;
            }

            if (status === 'error') {
                const parts = message.split(/<br\s*\/?>/i);
                summaryEl.innerHTML = config.downloadFailedSummary;
                messageEl.innerHTML = parts[0] || '';
                errorEl.innerHTML = parts.slice(1).join('<br>') || '';
                if (resultEl) {
                    resultEl.innerHTML = '';
                }
                spinnerEl?.classList.add('is-hidden');
                closePreviousEventSource();
                return;
            }

            if (status === 'done') {
                closePreviousEventSource();
                if (titleEl) {
                    titleEl.textContent = config.popupDoneTitle;
                }
                messageEl.innerHTML = `<strong>총 ${total.toLocaleString('ko-KR')}건의 데이터 다운로드가 완료되었습니다!</strong>`;
                spinnerEl?.classList.add('is-hidden');

                let html = '<p>* 자동으로 다운로드가 되지 않았다면 아래 버튼을 클릭해주세요.</p>';

                if (zipFile) {
                    const url = `${filePath}/${zipFile}`;
                    html += `<a href="${url}" class="btn btn-tertiary" download>압축파일 다운로드</a>`;
                    downloadBoxEl.innerHTML = html;
                    triggerAutoDownload(url, zipFile);
                    return;
                }

                if (files?.length) {
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
        };

        const handleDownloadError = event => {
            const errorMessage = event?.message || event?.data || '알 수 없는 오류가 발생했습니다.';
            const summaryEl = document.querySelector('.progress-summary');
            const messageEl = document.querySelector('.progress-message');
            const errorEl = document.querySelector('.progress-error');
            const loadingMessage = document.querySelector('.loading-message');
            const spinnerEl = document.querySelector('.progress-spinner');

            if (summaryEl) {
                summaryEl.innerHTML = config.downloadFailedSummary;
            }
            if (messageEl) {
                messageEl.innerHTML = config.downloadFailedMessage;
            }
            if (errorEl) {
                errorEl.innerHTML = errorMessage;
            }
            if (loadingMessage) {
                loadingMessage.innerHTML = '';
            }
            spinnerEl?.classList.add('is-hidden');
            closePreviousEventSource();
        };

        const startExcelDownload = () => {
            if (!config.environmentReady) {
                alert(config.environmentError || config.downloadFailedMessage);
                return;
            }

            closePreviousEventSource();
            showDownloadPopup();

            const query = buildDownloadParams();
            eventSource = new EventSource(`${config.streamUrl}?${query}`);
            eventSource.onmessage = handleProgressUpdate;
            eventSource.onerror = handleDownloadError;
        };

        toggleAdRangeSections();
        if (adRangeToggle) {
            adRangeToggle.addEventListener('change', toggleAdRangeSections);
        }
        if (adRangeType) {
            adRangeType.addEventListener('change', toggleAdRangeSections);
        }
        if (form) {
            form.addEventListener('change', autoSubmitFilterForm);
            form.addEventListener('blur', autoSubmitFilterForm, true);
            form.addEventListener('keydown', autoSubmitFilterForm);
        }
        if (downloadButton) {
            downloadButton.addEventListener('click', startExcelDownload);
        }
    }
};
