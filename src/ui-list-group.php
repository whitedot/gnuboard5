<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-base">
                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">기본 예제 (Basic Example)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">기본 리스트 그룹은 목록 항목이 포함된 단순한 순서 없는 목록으로 시작합니다. 아래 옵션을
                                        사용하여 확장하거나 필요에 맞게 추가로 사용자 정의할 수 있습니다.</p>

                                    <ul class="border-default-300 divide-default-300 divide-y rounded border">
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Dropbox 클라우드 스토리지</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Slack 팀 협업</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Microsoft Windows OS</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Zendesk 고객 지원</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Stripe 결제 통합
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">활성 항목 (Active Items)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">활성 상태를 사용하여 목록에서 현재 선택된 항목을 강조 표시합니다.</p>

                                    <ul class="border-default-300 divide-default-300 divide-y rounded border">
                                        <li class="bg-default-100 flex items-center gap-1.25 px-4 py-2.5">GitHub 저장소
                                        </li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Figma 디자인 도구</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Notion 워크스페이스</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Trello 작업 관리자</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">DigitalOcean 클라우드</li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">비활성 항목 (Disabled Items)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">비활성 상태를 사용하여 목록 항목을 사용할 수 없거나 비활성 상태임을 시각적으로 나타냅니다.
                                    </p>

                                    <ul class="border-default-300 divide-default-300 divide-y rounded border">
                                        <li
                                            class="bg-default-100 text-default-400 flex items-center gap-1.25 px-4 py-2.5">
                                            Dropbox 클라우드 스토리지</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Slack 팀 협업</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Microsoft Windows OS</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Zendesk 고객 지원</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Stripe 결제 통합
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">링크 및 버튼 (Links and Buttons)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">대화형 요소를 사용하여 호버, 활성 및 비활성 상태를 지원하는 실행 가능한 리스트 항목을
                                        만듭니다.</p>

                                    <div class="border-default-300 divide-default-300 divide-y rounded border">
                                        <a href="#" class="bg-default-100 flex items-center gap-1.25 px-4 py-2.5">Stripe
                                            결제 통합</a>
                                        <a href="#"
                                            class="hover:bg-default-100 flex items-center gap-1.25 px-4 py-2.5">Dropbox
                                            클라우드 서비스</a>
                                        <button type="button"
                                            class="hover:bg-default-100 focus:bg-default-100 flex w-full items-center gap-1.25 px-4 py-2.5">Slack
                                            커뮤니케이션</button>
                                        <button type="button"
                                            class="hover:bg-default-100 focus:bg-default-100 flex w-full items-center gap-1.25 px-4 py-2.5">Notion
                                            생산성 앱</button>
                                        <a href="#"
                                            class="bg-default-100 text-default-400 flex items-center gap-1.25 px-4 py-2.5">Zendesk
                                            지원 도구</a>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">플러시 (Flush)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">테두리와 둥근 모서리를 제거하여 카드 내부와 같이 상위 컨테이너의 가장자리에 목록 항목이
                                        평평하게 놓이도록 합니다.</p>

                                    <ul>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Slack 협업 도구</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Dropbox 클라우드 스토리지</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Notion 워크스페이스 정리</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Zendesk 고객 지원</li>
                                        <li class="flex items-center gap-1.25 px-4 py-2.5">Stripe 결제 처리기</li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">수평 레이아웃 (Horizontal)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">목록 항목을 수직 대신 수평으로 표시할 수 있습니다. 이 레이아웃은 모든 화면 크기에
                                        적용하거나 특정 중단점에서만 활성화할 수 있습니다.</p>

                                    <ul
                                        class="divide-default-300 border-default-300 mb-5 inline-flex divide-x rounded border">
                                        <li class="px-4 py-2.5">Slack</li>
                                        <li class="px-4 py-2.5">Notion</li>
                                        <li class="px-4 py-2.5">Dropbox</li>
                                    </ul>

                                    <ul
                                        class="divide-default-300 border-default-300 mb-5 inline-flex divide-x rounded border">
                                        <li class="px-4 py-2.5">Figma</li>
                                        <li class="px-4 py-2.5">Stripe</li>
                                        <li class="px-4 py-2.5">Zendesk</li>
                                    </ul>

                                    <ul
                                        class="divide-default-300 border-default-300 inline-flex divide-x rounded border">
                                        <li class="px-4 py-2.5">Trello</li>
                                        <li class="px-4 py-2.5">Asana</li>
                                        <li class="px-4 py-2.5">ClickUp</li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">컨텍스트 클래스 (Contextual Classes)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">컨텍스트 클래스를 사용하여 상태별 배경과 색상으로 목록 항목 스타일을 지정합니다.</p>

                                    <ul class="border-default-300 rounded border">
                                        <li class="border-default-300 border border-t-0 px-4 py-2.5">Dapibus ac
                                            facilisis in</li>
                                        <li
                                            class="text-primary bg-primary/15 border-primary border border-t-0 px-4 py-2.5">
                                            단순한 프라이머리 리스트 그룹 항목</li>
                                        <li
                                            class="text-secondary bg-secondary/15 border-secondary border border-t-0 px-4 py-2.5">
                                            단순한 세컨더리 리스트 그룹 항목</li>
                                        <li
                                            class="text-success bg-success/15 border-success border border-t-0 px-4 py-2.5">
                                            단순한 성공 리스트 그룹 항목</li>
                                        <li
                                            class="text-danger bg-danger/15 border-danger border border-t-0 px-4 py-2.5">
                                            단순한 위험 리스트 그룹 항목</li>
                                        <li
                                            class="text-warning bg-warning/15 border-warning border border-t-0 px-4 py-2.5">
                                            단순한 경고 리스트 그룹 항목</li>
                                        <li class="text-info bg-info/15 border-info border border-t-0 px-4 py-2.5">단순한
                                            정보 리스트 그룹 항목</li>
                                        <li
                                            class="bg-light/15 text-default-400 border-light border border-t-0 px-4 py-2.5">
                                            단순한 라이트 리스트 그룹 항목</li>
                                        <li class="text-dark bg-dark/15 border-dark border border-t-0 px-4 py-2.5">단순한
                                            다크 리스트 그룹 항목</li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">링크가 포함된 컨텍스트 클래스 (Contextual Classes with Link)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">컨텍스트 클래스를 사용하여 상태별 배경과 색상으로 목록 항목 스타일을 지정합니다.</p>

                                    <div class="border-default-300 rounded border">
                                        <a href="#"
                                            class="border-default-300 block border border-t-0 px-4 py-2.5">Dapibus ac
                                            facilisis in</a>
                                        <a href="#"
                                            class="hover:bg-primary/40 bg-primary/15 border-primary block border border-t-0 px-4 py-2.5">단순한
                                            프라이머리 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-secondary/40 bg-secondary/15 border-secondary block border border-t-0 px-4 py-2.5">단순한
                                            세컨더리 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-success/40 bg-success/15 border-success block border border-t-0 px-4 py-2.5">단순한
                                            성공 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-danger/40 bg-danger/15 border-danger block border border-t-0 px-4 py-2.5">단순한
                                            위험 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-warning/40 bg-warning/15 border-warning block border border-t-0 px-4 py-2.5">단순한
                                            경고 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-info/40 bg-info/15 border-info block border border-t-0 px-4 py-2.5">단순한
                                            정보 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-light/40 bg-light/15 border-light block border border-t-0 px-4 py-2.5">단순한
                                            라이트 리스트 그룹 항목</a>
                                        <a href="#"
                                            class="hover:bg-dark/40 bg-dark/15 border-dark block border border-t-0 px-4 py-2.5">단순한
                                            다크 리스트 그룹 항목</a>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">사용자 정의 콘텐츠 (Custom Content)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">Flexbox 유틸리티의 도움으로 아래 이미지와 같이 거의 모든 HTML을 추가할 수
                                        있습니다.</p>

                                    <div class="border-default-300 divide-default-300 divide-y rounded border">
                                        <a href="#" class="bg-default-100 block px-4 py-2.5">
                                            <div class="flex items-center justify-between">
                                                <h5 class="mb-2">리스트 그룹 항목 제목</h5>
                                                <small class="text-2xs">3일 전</small>
                                            </div>

                                            <p class="mb-1">여기에 목록 항목에 대한 상세 설명 텍스트가 들어갑니다. Flexbox를 사용하여 레이아웃을 유연하게 구성할
                                                수 있습니다.</p>
                                            <small class="text-2xs">추가 정보 텍스트입니다.</small>
                                        </a>

                                        <a href="#" class="hover:bg-default-100 focus:bg-default-100 block px-4 py-2.5">
                                            <div class="flex items-center justify-between">
                                                <h5 class="mb-2">리스트 그룹 항목 제목</h5>
                                                <small class="text-2xs">3일 전</small>
                                            </div>

                                            <p class="mb-1">여기에 목록 항목에 대한 상세 설명 텍스트가 들어갑니다. Flexbox를 사용하여 레이아웃을 유연하게 구성할
                                                수 있습니다.</p>
                                            <small class="text-2xs">추가 정보 텍스트입니다.</small>
                                        </a>

                                        <a href="#" class="hover:bg-default-100 focus:bg-default-100 block px-4 py-2.5">
                                            <div class="flex items-center justify-between">
                                                <h5 class="mb-2">리스트 그룹 항목 제목</h5>
                                                <small class="text-2xs">3일 전</small>
                                            </div>

                                            <p class="mb-1">여기에 목록 항목에 대한 상세 설명 텍스트가 들어갑니다. Flexbox를 사용하여 레이아웃을 유연하게 구성할
                                                수 있습니다.</p>
                                            <small class="text-2xs">추가 정보 텍스트입니다.</small>
                                        </a>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">배지 포함 (With Badges)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">유틸리티를 사용하여 읽지 않은 수, 활동 등을 표시하기 위해 리스트 그룹 항목에 배지를
                                        추가합니다.</p>

                                    <ul class="divide-default-300 border-default-300 divide-y rounded border">
                                        <li class="flex items-center justify-between gap-1.25 px-4 py-2.5">
                                            Gmail 알림
                                            <span class="badge bg-primary text-white rounded-full">14</span>
                                        </li>

                                        <li class="flex items-center justify-between gap-1.25 px-4 py-2.5">
                                            미처리 주문
                                            <span class="badge bg-success text-white rounded-full">2</span>
                                        </li>

                                        <li class="flex items-center justify-between gap-1.25 px-4 py-2.5">
                                            긴급 티켓
                                            <span class="badge bg-danger text-white rounded-full">99+</span>
                                        </li>

                                        <li class="flex items-center justify-between gap-1.25 px-4 py-2.5">
                                            완료된 거래
                                            <span class="badge bg-success text-white rounded-full">20+</span>
                                        </li>

                                        <li class="flex items-center justify-between gap-1.25 px-4 py-2.5">
                                            승인 대기 중인 송장
                                            <span class="badge bg-warning text-white rounded-full">12</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">체크박스 및 라디오 (Checkboxes and Radios)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">목록 항목 내에 체크박스와 라디오 버튼을 배치하고 필요에 따라 사용자 정의할 수 있습니다.
                                        접근성을 위해 보조 기술이 올바르게 해석할 수 있도록 적절한 레이블을 제공해야 합니다.</p>

                                    <div class="divide-default-300 border-default-300 divide-y rounded border">
                                        <div class="flex items-center gap-2 px-4 py-2.5">
                                            <input type="checkbox" class="form-checkbox size-4.25"
                                                id="newsletterCheckbox" />
                                            <label for="newsletterCheckbox">뉴스레터 구독</label>
                                        </div>

                                        <div class="flex items-center gap-2 px-4 py-2.5">
                                            <input type="checkbox" class="form-checkbox size-4.25" id="termsCheckbox" />
                                            <label for="termsCheckbox">이용 약관 동의</label>
                                        </div>
                                    </div>

                                    <div class="divide-default-300 border-default-300 mt-3 divide-y rounded border">
                                        <div class="flex items-center gap-2 px-4 py-2.5">
                                            <input type="radio" name="notificationOptions"
                                                class="form-checkbox size-4.25 rounded-full" id="emailRadio" checked />
                                            <label for="emailRadio">이메일로 알림 받기</label>
                                        </div>

                                        <div class="flex items-center gap-2 px-4 py-2.5">
                                            <input type="radio" name="notificationOptions"
                                                class="form-checkbox size-4.25 rounded-full" id="smsRadio" />
                                            <label for="smsRadio">SMS로 알림 받기</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-->
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end col-->

                        <div class="col-span-1">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">번호 매기기 (Numbered)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">목록 번호는 내장된 카운팅 시스템을 사용하여 자동으로 생성되고 스타일이 지정되어 각 항목에
                                        대해 일관된 번호 매기기 및 정렬을 보장합니다.</p>

                                    <ul class="divide-default-300 border-default-300 divide-y rounded border">
                                        <li class="flex justify-between gap-1.25 px-4 py-2.5">
                                            <div class="flex gap-3">
                                                <div>1.</div>
                                                <div>
                                                    <div class="font-bold">관리자 대시보드 프로</div>
                                                    현대적인 UI 구성 요소가 포함된 프리미엄 관리자 대시보드입니다.
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge bg-primary text-white rounded-full">865</span>
                                            </div>
                                        </li>

                                        <li class="flex justify-between gap-1.25 px-4 py-2.5">
                                            <div class="flex gap-3">
                                                <div>2.</div>
                                                <div>
                                                    <div class="font-bold">Vue 관리자 라이트</div>
                                                    Vue.js로 구축된 깔끔하고 최소한의 관리자 패널입니다.
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge bg-primary text-white rounded-full">140</span>
                                            </div>
                                        </li>

                                        <li class="flex justify-between gap-1.25 px-4 py-2.5">
                                            <div class="flex gap-3">
                                                <div>3.</div>
                                                <div>
                                                    <div class="font-bold">Angular 관리자 패널</div>
                                                    가볍고 강력한 Angular 기반 관리자 템플릿입니다.
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge bg-primary text-white rounded-full">85</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>