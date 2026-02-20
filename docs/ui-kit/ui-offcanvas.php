<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">오프캔버스 (Offcanvas)</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">오프캔버스(드로어) 구성 요소는 메뉴, 카트 등을 위한 숨겨진 사이드바를 제공하여 UI를 개선하고
                                    공간을 절약합니다.</p>

                                <button data-hs-overlay="#offcanvasExample"
                                    class="btn btn-solid-primary" aria-haspopup="dialog"
                                    aria-expanded="false" aria-controls="offcanvasExample">오프캔버스 열기 (왼쪽)</button>

                                <div id="offcanvasExample"
                                    class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-e transition-all duration-300"
                                    role="dialog" tabindex="-1" aria-labelledby="offcanvasExampleLabel">
                                    <div class="flex items-center justify-between p-5">
                                        <h3 id="offcanvasExampleLabel">오프캔버스</h3>

                                        <button type="button" aria-label="Close" data-hs-overlay="#offcanvasExample">
                                            <span class="sr-only">닫기</span>
                                            <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                        </button>
                                    </div>

                                    <div class="p-5">
                                        <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>

                                        <h5 class="mt-5 mb-2">목록</h5>
                                        <ul class="mb-4 list-disc ps-5">
                                            <li>임시 텍스트 예시 1</li>
                                            <li>임시 텍스트 예시 2</li>
                                            <li>임시 텍스트 예시 3</li>
                                        </ul>

                                        <ul class="mb-4 list-disc ps-5">
                                            <li>임시 텍스트 예시 4</li>
                                            <li>임시 텍스트 예시 5</li>
                                            <li>임시 텍스트 예시 6</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">오프캔버스 백드롭 (Offcanvas Backdrop)</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    오프캔버스와 백드롭이 보일 때 <code>&lt;body&gt;</code> 요소의 스크롤은 비활성화됩니다.
                                    <code>[--body-scroll:true]</code> 속성을 사용하여 <code>&lt;body&gt;</code> 스크롤을 토글하고,
                                    <code>[--overlay-backdrop:false]</code>를 사용하여 백드롭을 토글합니다.
                                </p>

                                <div class="flex flex-wrap gap-2.5">
                                    <div>
                                        <button type="button" class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="offcanvasScrolling" data-hs-overlay="#offcanvasScrolling">본문
                                            스크롤 활성화</button>

                                        <div id="offcanvasScrolling"
                                            class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-e transition-all duration-300 [--body-scroll:true] [--overlay-backdrop:false]"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasScrollingLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasScrollingLabel">스크롤이 활성화된 오프캔버스</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasScrolling">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>

                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 4</li>
                                                    <li>임시 텍스트 예시 5</li>
                                                    <li>임시 텍스트 예시 6</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="offcanvasWithBackdrop"
                                            data-hs-overlay="#offcanvasWithBackdrop">백드롭 활성화 (기본값)</button>

                                        <div id="offcanvasWithBackdrop"
                                            class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-e transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasWithBackdropLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasWithBackdropLabel">백드롭이 있는 오프캔버스</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasWithBackdrop">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>

                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 4</li>
                                                    <li>임시 텍스트 예시 5</li>
                                                    <li>임시 텍스트 예시 6</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="offcanvasWithBothOptions"
                                            data-hs-overlay="#offcanvasWithBothOptions">
                                            스크롤 및 백드롭 모두 활성화
                                        </button>

                                        <div id="offcanvasWithBothOptions"
                                            class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-e transition-all duration-300 [--body-scroll:true]"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasWithBothOptionsLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasWithBothOptionsLabel">스크롤과 백드롭이 적용된 오프캔버스</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasWithBothOptions">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>

                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 4</li>
                                                    <li>임시 텍스트 예시 5</li>
                                                    <li>임시 텍스트 예시 6</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-->
                            </div>
                            <!-- end col-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">오프캔버스 위치 (Offcanvas Placement)</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">상단, 우측, 하단 예시를 아래에서 확인해 보세요.</p>

                                <div class="flex flex-wrap gap-2.5">
                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="offcanvasTop"
                                            data-hs-overlay="#offcanvasTop">상단 오프캔버스 토글</button>

                                        <div id="offcanvasTop"
                                            class="hs-overlay hs-overlay-open:translate-y-0 bg-card border-default-300 fixed inset-x-0 top-0 z-80 hidden size-full max-h-60 -translate-y-full transform border-b transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasTopLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasTopLabel">오프캔버스 상단</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasTop">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="offcanvasRight"
                                            data-hs-overlay="#offcanvasRight">우측 오프캔버스 토글</button>

                                        <div id="offcanvasRight"
                                            class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed end-0 top-0 z-80 hidden h-full w-full max-w-sm translate-x-full transform border-s transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasRightLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasRightLabel">오프캔버스 우측</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasRight">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="offcanvasBottom"
                                            data-hs-overlay="#offcanvasBottom">하단 오프캔버스 토글</button>

                                        <div id="offcanvasBottom"
                                            class="hs-overlay hs-overlay-open:translate-y-0 bg-card border-default-300 fixed inset-x-0 bottom-0 z-80 hidden size-full max-h-60 translate-y-full transform border-t transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasBottomLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasBottomLabel">오프캔버스 하단</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasBottom">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="offcanvasLeft"
                                            data-hs-overlay="#offcanvasLeft">왼쪽 오프캔버스 토글</button>

                                        <div id="offcanvasLeft"
                                            class="hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-s transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
                                            <div class="flex items-center justify-between p-5">
                                                <h3 id="offcanvasLeftLabel">오프캔버스 왼쪽</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasLeft">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">다크 오프캔버스 (Dark Offcanvas)</h4>

                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    다크 네비바와 같은 다양한 테마에 맞게 유틸리티 클래스를 사용하여 오프캔버스의 모양을 사용자 정의하십시오.
                                    다크 스타일링을 위해 오프캔버스 구성 요소에 <code>.dark</code> 또는 <code>data-theme="dark"</code>를
                                    추가하십시오.
                                </p>

                                <div class="flex flex-wrap gap-2.5">
                                    <div>
                                        <button class="btn btn-solid-primary"
                                            aria-haspopup="dialog" aria-expanded="false" aria-controls="offcanvasDark"
                                            data-hs-overlay="#offcanvasDark">다크 오프캔버스</button>

                                        <div id="offcanvasDark"
                                            class="dark hs-overlay hs-overlay-open:translate-x-0 bg-card border-default-300 fixed start-0 top-0 z-80 hidden h-full w-full max-w-sm -translate-x-full transform border-s transition-all duration-300"
                                            role="dialog" tabindex="-1" aria-labelledby="offcanvasDarkLabel">
                                            <div class="text-default-700 flex items-center justify-between p-5">
                                                <h3 id="offcanvasDarkLabel">다크 오프캔버스</h3>

                                                <button type="button" aria-label="Close"
                                                    data-hs-overlay="#offcanvasDark">
                                                    <span class="sr-only">닫기</span>
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-xl"></i>
                                                </button>
                                            </div>

                                            <div class="text-default-900 p-5">
                                                <div>플레이스홀더 텍스트입니다. 실제로는 텍스트, 이미지, 목록 등 선택한 요소를 넣을 수 있습니다.</div>
                                                <h5 class="mt-5 mb-2">목록</h5>
                                                <ul class="mb-4 list-disc ps-5">
                                                    <li>임시 텍스트 예시 1</li>
                                                    <li>임시 텍스트 예시 2</li>
                                                    <li>임시 텍스트 예시 3</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>