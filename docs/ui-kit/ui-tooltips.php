<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid xl:grid-cols-2 gap-base">
                        <div class="space-y-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">기본 (Basic)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">툴팁을 보려면 아래 링크 위에 마우스를 올리세요.</p>
                                    <p>
                                        강력한 관리자 기능인
                                        <span class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button" class="hs-tooltip-toggle text-primary">
                                                사용자 정의 대시보드
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">대시보드를 쉽게 관리하세요</span>
                                            </button>
                                        </span>
                                        와 UI 구성 요소는 확장 가능한 웹 애플리케이션을 효율적으로 구축하는 데 도움이 됩니다. 이 템플릿에는 개발 워크플로우를 가속화하기 위해 사전
                                        구축된 페이지, 깔끔한 레이아웃 및 재사용 가능한 코드 블록이 포함되어 있습니다. 사용자 관리부터 분석 및 설정에 이르기까지 모든 것이
                                        모듈식이며 개발자 친화적입니다.
                                        <span class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button" class="hs-tooltip-toggle text-primary">
                                                반응형 디자인
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">TailwindCSS 4로 구축됨</span>
                                            </button>
                                        </span>
                                        과 원활한 UX로 현대적인 관리자 패널을 만드세요. 전문가 수준의
                                        <span class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button" class="hs-tooltip-toggle text-primary">
                                                UI 툴킷
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">개발자를 위해 맞춤 제작됨</span>
                                            </button>
                                        </span>
                                        으로 빠르게 시작하고,
                                        <span class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button" class="hs-tooltip-toggle text-primary">
                                                강력한 구성 요소
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">차트, 테이블, 양식 등이 포함되어 있습니다</span>
                                            </button>
                                        </span>
                                        및 유연한 레이아웃으로 앱을 강화하세요.
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">비활성화된 요소 (Disabled Elements)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        <code>disabled</code> 속성이 있는 요소는 상호 작용할 수 없으므로 사용자가 마우스를 올리거나 클릭하여 툴팁(또는 팝오버)을
                                        트리거할 수 없습니다. 해결 방법으로, 툴팁을 트리거하려는 요소를 <code>&lt;div&gt;</code> 또는
                                        <code>&lt;span&gt;</code>과 같은 래퍼 요소로 감싸고, 가급적 <code>tabindex="0"</code>을 사용하여
                                        키보드 포커스가 가능하게 만든 다음 비활성화된 요소의 <code>pointer-events</code>를 재정의해야 합니다.
                                    </p>

                                    <div class="hs-tooltip inline-block [--placement:top]">
                                        <button type="button"
                                            class="hs-tooltip-toggle btn btn-solid-primary"
                                            disabled>
                                            비활성화된 버튼
                                            <span
                                                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                role="tooltip">비활성화된 툴팁</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">호버 요소 (Hover Elements)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        <code>disabled</code> 속성이 있는 요소는 상호 작용할 수 없으므로 사용자가 마우스를 올리거나 클릭하여 툴팁(또는 팝오버)을
                                        트리거할 수 없습니다. 해결 방법으로, 툴팁을 트리거하려는 요소를 <code>&lt;div&gt;</code> 또는
                                        <code>&lt;span&gt;</code>과 같은 래퍼 요소로 감싸고, 가급적 <code>tabindex="0"</code>을 사용하여
                                        키보드 포커스가 가능하게 만든 다음 비활성화된 요소의 <code>pointer-events</code>를 재정의해야 합니다.
                                    </p>

                                    <div class="hs-tooltip inline-block [--placement:top]">
                                        <button type="button"
                                            class="hs-tooltip-toggle btn btn-solid-primary">
                                            정보를 보려면 마우스를 올리세요
                                            <span
                                                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                role="tooltip">툴팁은 마우스를 올렸을 때만 나타납니다</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">네 가지 방향 (Four Directions)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        아래 버튼 위에 마우스를 올려 네 가지 툴팁 방향(상단, 우측, 하단, 좌측)을 확인하세요. 자동 위치 지정을 활성화하려면
                                        <code>[--placement:*]</code> 옵션을 제거하세요.
                                    </p>

                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-info">
                                                상단 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">상단에 표시되는 툴팁</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:bottom]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-info">
                                                하단 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">하단에 표시되는 툴팁</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:left]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-info">
                                                좌측 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">좌측에 표시되는 툴팁</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-info">
                                                우측 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">우측에 표시되는 툴팁</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">HTML 태그 (HTML Tags)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">사용자 정의 HTML이 추가된 경우입니다:</p>
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-secondary">
                                                HTML 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">
                                                    <em>새로운</em>
                                                    <u>툴팁</u>
                                                    <b>HTML</b>
                                                    <i>포함</i>
                                                    <br />
                                                    사용자 정의 메시지입니다!
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">색상 툴팁 (Color Tooltips)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">테마에 맞춰 요소를 강조하려면 사용자 정의 배경 색상이 있는 색상 툴팁을 사용하세요.</p>

                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-primary">
                                                기본(Primary) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-primary invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">사용자 정의 스타일이 적용된 기본 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-danger">
                                                위험(Danger) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-danger invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">사용자 정의 경고 메시지가 포함된 위험 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-info">
                                                정보(Info) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-info invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">사용자 정의 스타일이 적용된 정보 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-success-contrast">
                                                성공(Success) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-success invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">완료를 나타내는 성공 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-secondary">
                                                보조(Secondary) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-secondary invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">추가 정보를 제공하는 보조 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button"
                                                class="hs-tooltip-toggle btn btn-solid-warning">
                                                경고(Warning) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-warning invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">주의를 환기시키는 경고 툴팁입니다.</span>
                                            </button>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:top]">
                                            <button type="button" class="hs-tooltip-toggle btn btn-solid-dark">
                                                다크(Dark) 툴팁
                                                <span
                                                    class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible bg-dark invisible absolute z-10 inline-block w-46 rounded py-2 px-3 text-sm font-medium text-white opacity-0 shadow-2xs transition-opacity"
                                                    role="tooltip">중요한 정보가 포함된 다크 툴팁입니다.</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>