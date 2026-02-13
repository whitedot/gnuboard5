<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <!-- Single Button Dropdowns -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">단일 버튼 드롭다운</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">적절한 토글 클래스와 메뉴 구조를 사용하여 최소한의 마크업 변경으로 모든 버튼이나 링크를 드롭다운
                                    토글로 전환할 수 있습니다.</p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button" class="hs-dropdown-toggle btn bg-light text-dark"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            옵션 선택
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">프로필 설정</a>
                                            <a class="dropdown-item" href="#">알림</a>
                                            <a class="dropdown-item" href="#">로그아웃</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex">
                                        <a class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white"
                                            href="#" role="button" id="dropdownMenuLink" aria-haspopup="true"
                                            aria-expanded="false">
                                            빠른 작업
                                            <i
                                                class="iconify tabler--chevron-down hs-dropdown-open:rotate-180 transition-transform text-base"></i>
                                        </a>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">새로 만들기</a>
                                            <a class="dropdown-item" href="#">파일 업로드</a>
                                            <a class="dropdown-item" href="#">보고서 보기</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex [--trigger:hover]">
                                        <button type="button" class="hs-dropdown-toggle btn bg-light text-dark"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            마우스 올리기
                                            <i
                                                class="iconify tabler--chevron-down hs-dropdown-open:rotate-180 transition-transform text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="hover-dropdown">
                                            <a class="dropdown-item" href="#">프로필 설정</a>
                                            <a class="dropdown-item" href="#">알림</a>
                                            <a class="dropdown-item" href="#">로그아웃</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Alignment -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">메뉴 정렬</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>[--placement:bottom-right]</code>
                                    클래스를 사용하여 드롭다운 메뉴를 요소의 오른쪽에 배치하십시오.
                                </p>

                                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                                    <button type="button" class="hs-dropdown-toggle btn bg-light text-dark"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        오른쪽 정렬 메뉴
                                        <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                    </button>

                                    <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                        <a class="dropdown-item" href="#">작업</a>
                                        <a class="dropdown-item" href="#">다른 작업</a>
                                        <a class="dropdown-item" href="#">기타 작업</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Dropdown Arrow -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">커스텀 드롭다운 화살표</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 버튼은 아이콘을 포함하거나 제외하도록 커스텀할 수 있어, 깔끔한 텍스트 전용 토글이나
                                    커스텀 아이콘으로 시각적으로 향상된 토글을 만들 수 있습니다.</p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">화살표
                                            없음</button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">보고서 다운로드</a>
                                            <a class="dropdown-item" href="#">분석 보기</a>
                                            <a class="dropdown-item" href="#">데이터 내보내기</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn border-primary hover:bg-primary text-primary flex items-center border hover:text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            커스텀 아이콘
                                            <i data-icon="tabler:edit" class="iconify tabler--edit text-sm"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">프로필 수정</a>
                                            <a class="dropdown-item" href="#">계정 설정</a>
                                            <a class="dropdown-item" href="#">로그아웃</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Split Button Dropdowns -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">분할 버튼 드롭다운</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">일반 버튼과 별도의 토글을 결합하여 분할 버튼 드롭다운을 만드세요. 이 레이아웃은 메인 버튼에서
                                    빠른 작업을 제공하고 드롭다운에서 추가 옵션을 제공합니다.</p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn bg-primary hover:bg-primary-hover rounded-e-none text-white">기본(Primary)</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn bg-primary/90 hover:bg-primary-hover rounded-s-none px-2 text-white">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn bg-light hover:text-primary text-default-700 rounded-s-sm rounded-e-none">보조(Secondary)</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn bg-light hover:text-primary rounded-s-none px-2">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn bg-success/15 text-success hover:bg-success rounded-s-sm rounded-e-none hover:text-white">성공(Success)</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn bg-success/15 text-success hover:bg-success rounded-s-none px-2 hover:text-white">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn bg-info hover:bg-info-hover rounded-s-sm rounded-e-none text-white">정보(Info)</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn bg-info/85 hover:bg-info-hover rounded-s-none px-2 text-white">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variant -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">변형</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 메뉴는 모든 버튼 변형과 함께 사용할 수 있으므로 기본, 보조 또는 성공 버튼과 같은
                                    다양한 색상이나 스타일에 맞출 수 있습니다.</p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            기본(Primary)
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">새로 만들기</a>
                                            <a class="dropdown-item" href="#">변경 사항 저장</a>
                                            <a class="dropdown-item" href="#">지금 게시</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">초안 보기</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button" class="hs-dropdown-toggle btn bg-light text-default-700"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            보조(Secondary)
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">설정</a>
                                            <a class="dropdown-item" href="#">환경 설정</a>
                                            <a class="dropdown-item" href="#">계정 정보</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">로그아웃</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-success hover:bg-info-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            성공(Success)
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">완료로 표시</a>
                                            <a class="dropdown-item" href="#">보고서 다운로드</a>
                                            <a class="dropdown-item" href="#">리뷰 제출</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">작업 아카이브</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sizing -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">크기 조절</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 메뉴는 유연한 디자인 옵션을 위해 크고 작은 버튼 또는 분할 버튼 변형을 포함한 모든
                                    크기의 버튼과 결합할 수 있습니다.</p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-light text-default-700 px-6 py-2 text-lg"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            큰 버튼
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">다른 작업</a>
                                            <a class="dropdown-item" href="#">기타 작업</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">분리된 링크</a>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="bg-light hover:text-primary text-default-700 relative rounded-s-sm px-6 py-2 text-lg">큰
                                            버튼</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle bg-light hover:text-primary relative flex items-center justify-center rounded-e-sm px-4 py-2">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn btn-sm bg-light text-default-700"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            작은 버튼
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">다른 작업</a>
                                            <a class="dropdown-item" href="#">기타 작업</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">분리된 링크</a>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn btn-sm bg-light text-default-700 hover:text-primary rounded-e-none">작은
                                            버튼</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:bottom-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn btn-sm bg-light text-default-700 hover:text-primary rounded-s-none px-2">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 드롭업 Variation -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">드롭업 변형</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    부모 요소에
                                    <code>[--placement:top]</code>
                                    또는
                                    <code>[--placement:top-left]</code>
                                    클래스를 사용하여 토글 버튼 위에 드롭다운 메뉴를 표시하십시오.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:top]">
                                        <button type="button" class="hs-dropdown-toggle btn bg-light text-default-700"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            드롭업
                                            <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">다른 작업</a>
                                            <a class="dropdown-item" href="#">기타 작업</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">분리된 링크</a>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="btn bg-light text-default-700 hover:text-primary rounded-e-none">분할
                                            드롭업</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:top-left]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn bg-light/90 text-default-700 hover:text-primary rounded-s-none px-2">
                                                <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">새 작업</a>
                                                <a class="dropdown-item" href="#">사용자 배정</a>
                                                <a class="dropdown-item" href="#">마감일 설정</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">Project 설정</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 드롭스타트 Variation -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">드롭스타트 변형</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    부모 요소에
                                    <code>[--placement:left-start]</code>
                                    클래스를 사용하여 토글 버튼의 왼쪽에 드롭다운 메뉴를 배치하십시오.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:left-start]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            <i data-icon="tabler:chevron-left" class="iconify tabler--chevron-left text-base"></i>
                                            드롭스타트
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">다른 작업</a>
                                            <a class="dropdown-item" href="#">기타 작업</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">분리된 링크</a>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <div class="hs-dropdown relative inline-flex [--placement:left-start]">
                                            <button type="button"
                                                class="hs-dropdown-toggle btn sky-700 bg-secondary/90 hover:bg-secondary-hover rounded-e-none px-2 text-white">
                                                <i data-icon="tabler:chevron-left" class="iconify tabler--chevron-left text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>

                                        <button type="button"
                                            class="btn bg-secondary hover:bg-secondary-hover rounded-s-none text-white">분할
                                            드롭스타트</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropend Variation -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">드롭엔드 변형</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    부모 요소에
                                    <code>[--placement:right-end]</code>
                                    클래스를 사용하여 토글 버튼의 오른쪽에 드롭다운 메뉴를 표시하십시오.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:right-end]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            Dropend
                                            <i data-icon="tabler:chevron-right" class="iconify tabler--chevron-right text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">프로필 보기</a>
                                            <a class="dropdown-item" href="#">메시지 보내기</a>
                                            <a class="dropdown-item" href="#">문제 보고</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">사용자 차단</a>
                                        </div>
                                    </div>

                                    <div class="inline-flex">
                                        <button type="button"
                                            class="bg-primary hover:bg-primary-hover relative rounded-s-sm px-4 py-2 font-medium text-white">분할
                                            드롭스타트</button>

                                        <div class="hs-dropdown relative inline-flex [--placement:right-end]">
                                            <button type="button"
                                                class="hs-dropdown-toggle bg-primary/90 hover:bg-primary-hover relative flex items-center justify-center rounded-e-sm px-2 py-2 text-white">
                                                <i data-icon="tabler:chevron-right" class="iconify tabler--chevron-right text-base"></i>
                                            </button>

                                            <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">다른 작업</a>
                                                <a class="dropdown-item" href="#">기타 작업</a>
                                                <hr class="dropdown-divider" />
                                                <a class="dropdown-item" href="#">분리된 링크</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Item -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">활성 항목</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    드롭다운 항목에
                                    <code>.active</code>
                                    클래스를 사용하여 현재 선택되었거나 활성화된 옵션으로 강조하십시오.
                                </p>

                                <div class="hs-dropdown relative inline-flex [--placement:bottom-end]">
                                    <button type="button"
                                        class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        활성화된 항목
                                        <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                    </button>

                                    <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                        <a class="dropdown-item" href="#">일반 링크</a>
                                        <a class="dropdown-item active" href="#">활성 링크</a>
                                        <a class="dropdown-item" href="#">다른 링크</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 비활성화됨 Item -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">비활성 항목</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    드롭다운 항목에
                                    <code>.disabled</code>
                                    클래스를 사용하여 메뉴 내에서 사용할 수 없거나 비활성화된 옵션을 나타내십시오.
                                </p>

                                <div class="hs-dropdown relative inline-flex [--placement:bottom-end]">
                                    <button type="button"
                                        class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        비활성화됨
                                        <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                    </button>

                                    <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                        <a class="dropdown-item" href="#">일반 링크</a>
                                        <a class="dropdown-item active" href="#" disabled>비활성 링크</a>
                                        <a class="dropdown-item" href="#">다른 링크</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 헤더s -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">헤더</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 메뉴 내부에 헤더를 추가하여 관련 메뉴 항목을 명확하게 그룹화하거나 레이블을 지정하십시오.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:bottom-end]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-primary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            헤더
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <h6 class="px-2.75 py-2 font-semibold">드롭다운 헤더</h6>
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">다른 작업</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 다크 드롭다운s -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">다크 드롭다운</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    메뉴 요소에
                                    <code>data-theme="dark"</code>
                                    를 추가하여 드롭다운 메뉴에 다크 테마를 적용하십시오. 이를 통해 개별 항목을 변경하지 않고도 어두운 내비게이션 바나 레이아웃에 맞출 수 있습니다.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:bottom-end]">
                                        <button type="button" class="hs-dropdown-toggle btn bg-dark text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            다크 드롭다운
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div data-theme="dark" class="hs-dropdown-menu" role="menu"
                                            aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">대시보드</a>
                                            <a class="dropdown-item" href="#">내 주문</a>
                                            <a class="dropdown-item" href="#">Billing 설정</a>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">로그아웃</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Centered Dropdowns -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">중앙 정렬 드롭다운</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>[--placement:bottom]</code>
                                    또는
                                    <code>[--placement:top]</code>
                                    클래스를 사용하여 드롭다운 메뉴를 토글 버튼의 아래나 위 중앙에 배치하십시오.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex [--placement:bottom]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            중앙 드롭다운
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <div class="space-y-0.5">
                                                <a class="dropdown-item" href="#">작업</a>
                                                <a class="dropdown-item" href="#">작업 2</a>
                                                <a class="dropdown-item" href="#">작업 3</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex [--placement:top]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            중앙 드롭업
                                            <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">작업</a>
                                            <a class="dropdown-item" href="#">작업 2</a>
                                            <a class="dropdown-item" href="#">작업 3</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Auto Close Behavior -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">자동 닫기 동작</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    기본적으로 드롭다운 메뉴는 내부 또는 외부를 클릭할 때 닫힙니다. autoClose 옵션을 사용하여 드롭다운의 이 동작을 변경할 수 있습니다.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            기본 드롭다운
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">메뉴 항목</a>
                                            <a class="dropdown-item" href="#">메뉴 항목</a>
                                            <a class="dropdown-item" href="#">메뉴 항목</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex [--auto-close:inside]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            내부 클릭 가능
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex [--auto-close:outside]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            외부 클릭 가능
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                        </div>
                                    </div>

                                    <div class="hs-dropdown relative inline-flex [--auto-close:false]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            수동 닫기
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu" aria-orientation="vertical">
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                            <a class="dropdown-item" href="#">Menu item</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Text -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">텍스트</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 메뉴 내에 자유로운 형식의 텍스트를 배치하고 간격 유틸리티를 사용하세요. 메뉴 너비를
                                    제한하기 위해 추가적인 크기 조정 스타일이 필요할 수 있습니다.</p>
                                <div class="hs-dropdown relative inline-flex [--placement:bottom-end]">
                                    <button type="button"
                                        class="hs-dropdown-toggle btn bg-primary hover:bg-primary-hover text-white"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        텍스트 드롭다운
                                        <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                    </button>

                                    <div class="hs-dropdown-menu w-44 p-4" role="menu" aria-orientation="vertical">
                                        <span class="text-default-400 mb-4">드롭다운 메뉴 내에서 자유롭게 흐르는 예시 텍스트입니다.</span>
                                        <p class="text-default-400">그리고 이것은 추가 예시 텍스트입니다.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Forms -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">폼</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">드롭다운 메뉴 내에 폼을 넣거나 드롭다운 메뉴로 만들고, 마진 또는 패딩 유틸리티를 사용하여 필요한
                                    여백을 주십시오.</p>
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div
                                        class="hs-dropdown relative inline-flex [--auto-close:inside] [--placement:bottom-end]">
                                        <button type="button"
                                            class="hs-dropdown-toggle btn bg-secondary hover:bg-secondary-hover text-white"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            폼
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down text-base"></i>
                                        </button>

                                        <div class="hs-dropdown-menu min-w-44" role="menu"
                                            aria-orientation="forms-dropdown-menu">
                                            <form class="px-7.5 py-5">
                                                <div class="mb-5">
                                                    <label class="form-label">이메일 주소</label>
                                                    <input class="form-input text-sm" type="email"
                                                        placeholder="email@example.com" />
                                                </div>

                                                <div class="mb-5">
                                                    <label class="form-label">비밀번호</label>
                                                    <input class="form-input text-sm" type="password"
                                                        placeholder="비밀번호" />
                                                </div>

                                                <div class="mb-4 flex items-center gap-2.5">
                                                    <input type="checkbox" class="form-checkbox shrink-0"
                                                        id="dropdownCheck" />
                                                    <label for="dropdownCheck" class="text-default-500 ms-1 text-sm">로그인
                                                        상태 유지</label>
                                                </div>

                                                <button type="submit"
                                                    class="btn bg-primary text-white hover:bg-primary-hover">로그인</button>
                                            </form>
                                            <hr class="dropdown-divider" />
                                            <a class="dropdown-item" href="#">처음이신가요? 회원가입</a>
                                            <a class="dropdown-item" href="#">비밀번호를 잊으셨나요?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>