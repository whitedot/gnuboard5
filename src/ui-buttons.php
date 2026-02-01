<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">기본 버튼</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>.btn</code> 클래스를 <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code>, 또는
                                    <code>&lt;input&gt;</code> 요소에 사용하여 스타일이 적용된 버튼을 빠르게 생성할 수 있습니다.
                                </p>

                                <div class="flex flex-wrap gap-2.5">
                                    <button type="button" class="btn border-default-300">기본</button>
                                    <button type="button"
                                        class="btn bg-primary hover:bg-primary-hover text-white">프라이머리</button>
                                    <button type="button"
                                        class="btn bg-secondary hover:bg-secondary-hover text-white">세컨더리</button>
                                    <button type="button"
                                        class="btn bg-success hover:bg-success-hover text-white">성공</button>
                                    <button type="button"
                                        class="btn bg-danger hover:bg-danger-hover text-white">위험</button>
                                    <button type="button"
                                        class="btn bg-warning hover:bg-warning-hover text-white">경고</button>
                                    <button type="button" class="btn bg-info hover:bg-info-hover text-white">정보</button>
                                    <button type="button"
                                        class="btn bg-light text-dark hover:bg-light-hover">라이트</button>
                                    <button type="button" class="btn bg-dark hover:bg-dark-hover text-white">다크</button>
                                </div>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">둥근 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                버튼에 <code>.rounded-full</code>을 추가하여 매끄러운 알약 모양의 모서리를 제공합니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button" class="btn border-default-300 rounded-full">기본</button>
                                <button type="button"
                                    class="btn bg-primary hover:bg-primary-hover rounded-full text-white">프라이머리</button>
                                <button type="button"
                                    class="btn bg-secondary hover:bg-secondary-hover rounded-full text-white">세컨더리</button>
                                <button type="button"
                                    class="btn bg-success hover:bg-success-hover rounded-full text-white">성공</button>
                                <button type="button"
                                    class="btn bg-danger hover:bg-danger-hover rounded-full text-white">위험</button>
                                <button type="button"
                                    class="btn bg-warning hover:bg-warning-hover rounded-full text-white">경고</button>
                                <button type="button"
                                    class="btn bg-info hover:bg-info-hover rounded-full text-white">정보</button>
                                <button type="button"
                                    class="btn bg-light text-dark hover:bg-light-hover rounded-full">라이트</button>
                                <button type="button"
                                    class="btn bg-dark hover:bg-dark-hover rounded-full text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">아웃라인 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                <code>.border-*</code> 클래스를 사용하여 유색 테두리가 있는 버튼을 만듭니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn border-secondary text-secondary hover:bg-secondary hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn border-success text-success hover:bg-success hover:text-white">성공</button>
                                <button type="button"
                                    class="btn border-danger text-danger hover:bg-danger hover:text-white">위험</button>
                                <button type="button"
                                    class="btn border-warning text-warning hover:bg-warning hover:text-white">경고</button>
                                <button type="button"
                                    class="btn border-info text-info hover:bg-info hover:text-white">정보</button>
                                <button type="button"
                                    class="btn border-light text-dark hover:bg-light hover:text-dark">라이트</button>
                                <button type="button"
                                    class="btn border-dark text-dark hover:bg-dark hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">둥근 아웃라인 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                아웃라인 버튼에 <code>.rounded-full</code>을 적용하여 매끄러운 알약 모양의 모서리를 제공합니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn border-primary text-primary hover:bg-primary rounded-full hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn border-secondary text-secondary hover:bg-secondary rounded-full hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn border-success text-success hover:bg-success rounded-full hover:text-white">성공</button>
                                <button type="button"
                                    class="btn border-danger text-danger hover:bg-danger rounded-full hover:text-white">위험</button>
                                <button type="button"
                                    class="btn border-warning text-warning hover:bg-warning rounded-full hover:text-white">경고</button>
                                <button type="button"
                                    class="btn border-info text-info hover:bg-info rounded-full hover:text-white">정보</button>
                                <button type="button"
                                    class="btn border-light text-dark hover:bg-light hover:text-dark rounded-full">라이트</button>
                                <button type="button"
                                    class="btn border-dark text-dark hover:bg-dark rounded-full hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">소프트 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                <code>.bg-*/15</code> 클래스를 사용하여 부드럽고 색조가 있는 배경색의 버튼을 만듭니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn text-primary bg-primary/15 hover:bg-primary hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn text-secondary bg-secondary/15 hover:bg-secondary hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn text-success bg-success/15 hover:bg-success hover:text-white">성공</button>
                                <button type="button"
                                    class="btn text-danger bg-danger/15 hover:bg-danger hover:text-white">위험</button>
                                <button type="button"
                                    class="btn text-warning bg-warning/15 hover:bg-warning hover:text-white">경고</button>
                                <button type="button"
                                    class="btn text-info bg-info/15 hover:bg-info hover:text-white">정보</button>
                                <button type="button"
                                    class="btn text-dark bg-dark/15 hover:bg-dark hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">둥근 소프트 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                <code>.bg-*/15</code>와 <code>.rounded-full</code>을 결합하여 부드러운 알약 모양의 버튼을 만듭니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn text-primary bg-primary/15 hover:bg-primary rounded-full hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn text-secondary bg-secondary/15 hover:bg-secondary rounded-full hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn text-success bg-success/15 hover:bg-success rounded-full hover:text-white">성공</button>
                                <button type="button"
                                    class="btn text-danger bg-danger/15 hover:bg-danger rounded-full hover:text-white">위험</button>
                                <button type="button"
                                    class="btn text-warning bg-warning/15 hover:bg-warning rounded-full hover:text-white">경고</button>
                                <button type="button"
                                    class="btn text-info bg-info/15 hover:bg-info rounded-full hover:text-white">정보</button>
                                <button type="button"
                                    class="btn text-dark bg-dark/15 hover:bg-dark rounded-full hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">고스트 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">배경을 투명하게 유지하고 호버 시 색상을 강조하려면 고스트 스타일 버튼을 사용하세요.</p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn text-primary hover:bg-primary rounded hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn text-secondary hover:bg-secondary rounded hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn text-success hover:bg-success rounded hover:text-white">성공</button>
                                <button type="button"
                                    class="btn text-danger hover:bg-danger rounded hover:text-white">위험</button>
                                <button type="button"
                                    class="btn text-warning hover:bg-warning rounded hover:text-white">경고</button>
                                <button type="button"
                                    class="btn text-info hover:bg-info rounded hover:text-white">정보</button>
                                <button type="button"
                                    class="btn text-dark hover:bg-dark rounded hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">둥근 고스트 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                고스트 스타일 버튼과 <code>.rounded-full</code>을 결합하여 호버 시 강조되는 둥근 투명 버튼을 만듭니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn text-primary hover:bg-primary rounded-full hover:text-white">프라이머리</button>
                                <button type="button"
                                    class="btn text-secondary hover:bg-secondary rounded-full hover:text-white">세컨더리</button>
                                <button type="button"
                                    class="btn text-success hover:bg-success rounded-full hover:text-white">성공</button>
                                <button type="button"
                                    class="btn text-danger hover:bg-danger rounded-full hover:text-white">위험</button>
                                <button type="button"
                                    class="btn text-warning hover:bg-warning rounded-full hover:text-white">경고</button>
                                <button type="button"
                                    class="btn text-info hover:bg-info rounded-full hover:text-white">정보</button>
                                <button type="button"
                                    class="btn text-dark hover:bg-dark rounded-full hover:text-white">다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">그라데이션 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">그라데이션 유틸리티 클래스를 사용하여 매끄러운 색상 전환을 적용하고 스타일리시한 그라데이션 버튼을
                                만듭니다.</p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn border-0 bg-linear-to-br from-purple-600 to-blue-500 text-white hover:bg-linear-to-bl">보라색에서
                                    파란색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-r from-cyan-500 to-blue-500 text-white hover:bg-linear-to-bl">청록색에서
                                    파란색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-br from-green-400 to-blue-600 text-white hover:bg-linear-to-bl">녹색에서
                                    파란색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-r from-purple-500 to-pink-500 text-white hover:bg-linear-to-l">보라색에서
                                    분홍색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-br from-pink-500 to-orange-400 text-white hover:bg-linear-to-bl">분홍색에서
                                    주황색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-r from-teal-200 to-lime-200 text-gray-900 hover:bg-linear-to-l">청록색에서
                                    라임색으로</button>
                                <button type="button"
                                    class="btn border-0 bg-linear-to-r from-red-200 via-red-300 to-yellow-200 text-gray-900 hover:bg-linear-to-bl">빨간색에서
                                    노란색으로</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="col-span-1">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">둥근 그라데이션 버튼</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    그라데이션 유틸리티와 <code>.rounded-full</code>을 결합하여 혼합된 색상 전환이 있는 매끄러운 알약 모양의 버튼을 만듭니다.
                                </p>

                                <div class="flex flex-wrap gap-2.5">
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-br from-purple-600 to-blue-500 text-white hover:bg-linear-to-bl">보라색에서
                                        파란색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-r from-cyan-500 to-blue-500 text-white hover:bg-linear-to-bl">청록색에서
                                        파란색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-br from-green-400 to-blue-600 text-white hover:bg-linear-to-bl">녹색에서
                                        파란색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-r from-purple-500 to-pink-500 text-white hover:bg-linear-to-l">보라색에서
                                        분홍색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-br from-pink-500 to-orange-400 text-white hover:bg-linear-to-bl">분홍색에서
                                        주황색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-r from-teal-200 to-lime-200 text-gray-900 hover:bg-linear-to-l">청록색에서
                                        라임색으로</button>
                                    <button type="button"
                                        class="btn rounded-full border-0 bg-linear-to-r from-red-200 via-red-300 to-yellow-200 text-gray-900 hover:bg-linear-to-bl">빨간색에서
                                        노란색으로</button>
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
                                <h4 class="card-title">버튼 크기</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>.btn-lg</code> 또는 <code>.btn-sm</code>을 사용하여 큰 버튼이나 작은 버튼을 빠르게 만들 수 있습니다.
                                </p>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    <button type="button"
                                        class="btn btn-lg bg-primary hover:bg-primary-hover text-white">대형</button>
                                    <button type="button" class="btn bg-info hover:bg-info-hover text-white">보통</button>
                                    <button type="button"
                                        class="btn btn-sm bg-success hover:bg-success-hover text-white">소형</button>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>
                    <!-- end col-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">비활성화된 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                <code>&lt;button&gt;</code>에 <code>disabled</code> 속성을 추가하여 사용자 상호 작용을 방지하고 비활성 상태를
                                시각적으로 나타냅니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button" class="btn bg-info text-white hover:bg-info-hover"
                                    disabled>정보</button>
                                <button type="button" class="btn bg-danger text-white hover:bg-danger-hover"
                                    disabled>위험</button>
                                <button type="button" class="btn bg-dark text-white hover:bg-dark-hover"
                                    disabled>다크</button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="col-span-1">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">블록 버튼</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>.w-full</code> 클래스를 추가하여 버튼이 컨테이너의 전체 너비를 차지하게 합니다.
                                </p>

                                <div class="space-y-3">
                                    <button type="button"
                                        class="btn bg-primary hover:bg-primary-hover w-full text-white">Block
                                        버튼</button>
                                    <button type="button"
                                        class="btn btn-lg bg-success hover:bg-success-hover w-full text-white">Block
                                        버튼</button>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>
                    <!-- end col-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">아이콘 버튼</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">아이콘 버튼을 사용하면 아이콘만 사용하여 컴팩트한 컨트롤을 만들거나, 툴바 및 작업 영역에서 더 명확하게
                                하기 위해 아이콘을 텍스트와 쌍으로 구성할 수 있습니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <button type="button" class="btn btn-icon bg-primary hover:bg-primary-hover text-white">
                                    <i data-icon="tabler:star" class="iconify tabler--star text-lg"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-icon bg-secondary hover:bg-secondary-hover text-white">
                                    <i data-icon="tabler:leaf" class="iconify tabler--leaf text-lg"></i>
                                </button>

                                <button type="button" class="btn btn-icon bg-warning hover:bg-warning-hover text-white">
                                    <i data-icon="tabler:settings" class="iconify tabler--settings text-lg"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-icon bg-info/15 text-info hover:bg-info hover:text-white">
                                    <i data-icon="tabler:bell" class="iconify tabler--bell text-lg"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-icon bg-secondary hover:bg-secondary-hover text-white">
                                    <i data-icon="tabler:rocket" class="iconify tabler--rocket text-lg"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-icon border-dark text-dark hover:bg-dark rounded-full border hover:text-white">
                                    <i data-icon="tabler:plane" class="iconify tabler--plane text-lg"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-icon bg-secondary/15 text-secondary hover:bg-secondary hover:text-white">
                                    <i data-icon="tabler:microphone" class="iconify tabler--microphone text-lg"></i>
                                </button>

                                <button type="button" class="btn bg-light/60 hover:text-primary">
                                    <i data-icon="tabler:hand-stop" class="iconify tabler--hand-stop text-lg"></i>
                                    중지
                                </button>

                                <button type="button" class="btn bg-dark text-white">
                                    <i data-icon="tabler:bolt" class="iconify tabler--bolt text-lg"></i>
                                    부스트
                                </button>

                                <button type="button"
                                    class="btn border-info text-info hover:bg-info border hover:text-white">
                                    <i data-icon="tabler:credit-card" class="iconify tabler--credit-card text-base"></i>
                                    결제
                                </button>

                                <button type="button" class="btn bg-danger hover:bg-danger-hover text-white">
                                    <i data-icon="tabler:tools" class="iconify tabler--tools text-base"></i>
                                    도구
                                </button>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2.5">
                                <button type="button"
                                    class="btn border-secondary text-secondary hover:bg-secondary size-7.5 border hover:text-white">
                                    <i data-icon="tabler:star" class="iconify tabler--star text-xs"></i>
                                </button>

                                <button type="button" class="btn bg-primary hover:bg-primary-hover size-7.5 text-white">
                                    <i data-icon="tabler:leaf" class="iconify tabler--leaf text-xs"></i>
                                </button>

                                <button type="button"
                                    class="btn bg-success hover:bg-success-hover size-7.5 rounded-full text-white">
                                    <i data-icon="tabler:settings" class="iconify tabler--settings text-xs"></i>
                                </button>

                                <button type="button"
                                    class="btn border-secondary text-secondary hover:bg-secondary size-11.25 border hover:text-white">
                                    <i data-icon="tabler:bell" class="iconify tabler--bell text-xl"></i>
                                </button>

                                <button type="button"
                                    class="btn bg-primary hover:bg-primary-hover size-11.25 rounded-full text-white">
                                    <i data-icon="tabler:rocket" class="iconify tabler--rocket text-xl"></i>
                                </button>

                                <button type="button"
                                    class="btn bg-success hover:bg-success-hover size-11.25 rounded-full text-white">
                                    <i data-icon="tabler:share" class="iconify tabler--share text-xl"></i>
                                </button>

                                <button type="button" class="btn bg-info hover:bg-info-hover size-11.25 text-white">
                                    <i data-icon="tabler:star" class="iconify tabler--star text-xl"></i>
                                </button>

                                <button type="button"
                                    class="btn bg-warning hover:bg-warning-hover size-11.25 text-white">
                                    <i data-icon="tabler:alert-octagon" class="iconify tabler--alert-octagon text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">버튼 태그</h4>
                            <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                data-action="card-toggle">
                                <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-default-400 mb-4">
                                <code>.btn</code> 클래스는 <code>&lt;button&gt;</code>, <code>&lt;a&gt;</code>,
                                <code>&lt;input&gt;</code> 요소에서 작동하지만 브라우저에 따라 모양이 약간 다를 수 있습니다.
                            </p>

                            <div class="flex flex-wrap gap-2.5">
                                <a href="#" class="btn bg-primary hover:bg-primary-hover rounded text-white">링크</a>
                                <button type="submit"
                                    class="btn bg-primary hover:bg-primary-hover rounded text-white">버튼</button>
                                <input type="button" class="btn bg-primary hover:bg-primary-hover rounded text-white"
                                    value="입력" />
                                <input type="submit" class="btn bg-primary hover:bg-primary-hover rounded text-white"
                                    value="제출" />
                                <input type="reset" class="btn bg-primary hover:bg-primary-hover rounded text-white"
                                    value="초기화" />
                            </div>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="col-span-1">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">버튼 그룹</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    <code>inline-flex</code>를 사용하여 버튼을 단일 컨테이너 내로 그룹화하여 나란히 정렬하거나 일관된 간격으로 수직으로 쌓을 수
                                    있습니다.
                                </p>

                                <div class="mb-3 inline-flex">
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-e-none">왼쪽</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-none">중간</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-s-none">오른쪽</button>
                                </div>

                                <br />

                                <div class="mb-3 inline-flex">
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-e-none">1</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-none">2</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-none">3</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-s-none">4</button>
                                </div>

                                <div class="mb-3 inline-flex">
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-e-none">5</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-none">6</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-s-none">7</button>
                                </div>

                                <div class="mb-3 inline-flex">
                                    <button type="button" class="btn bg-light hover:text-primary">8</button>
                                </div>

                                <br />

                                <div class="mb-3 inline-flex">
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-e-none">1</button>
                                    <button type="button"
                                        class="btn bg-primary hover:bg-primary-hover rounded-none text-white">2</button>
                                    <button type="button"
                                        class="btn bg-light hover:text-primary rounded-none">3</button>

                                    <div class="hs-dropdown relative inline-flex">
                                        <button type="button" class="btn bg-light hover:text-primary rounded-s-none"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            드롭다운
                                            <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down"></i>
                                        </button>

                                        <div class="hs-dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="#">드롭다운 링크</a>
                                            <a class="dropdown-item" href="#">드롭다운 링크</a>
                                        </div>
                                    </div>
                                </div>

                                <br />

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-base">
                                    <div>
                                        <div class="inline-flex flex-col">
                                            <button type="button"
                                                class="btn bg-light hover:text-primary rounded-b-none">상단</button>
                                            <button type="button"
                                                class="btn bg-light hover:text-primary rounded-none">중간</button>
                                            <button type="button"
                                                class="btn bg-light hover:text-primary rounded-t-none">하단</button>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="inline-flex flex-col">
                                            <button type="button"
                                                class="btn bg-light hover:text-primary rounded-b-none">버튼 1</button>
                                            <button type="button"
                                                class="btn bg-light hover:text-primary rounded-none">버튼 2</button>
                                            <div class="hs-dropdown relative inline-flex">
                                                <button type="button"
                                                    class="btn bg-light hover:text-primary rounded-t-none"
                                                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                                    버튼 3
                                                    <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down"></i>
                                                </button>

                                                <div class="hs-dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="#">드롭다운 링크</a>
                                                    <a class="dropdown-item" href="#">드롭다운 링크</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>
                    <!-- end col-->
                </div>
        </div>

<?php include 'layout/footer.php'; ?>