<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid xl:grid-cols-2 gap-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">기본 토스트 (Basic)</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">토스트는 필요한 만큼 유연하며 마크업이 거의 필요하지 않습니다. 최소한 토스트 내용을 포함하는 단일
                                    요소가 필요하며 닫기 버튼을 강력하게 권장합니다.</p>
                                <div id="dismiss-toast"
                                    class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 max-w-xs rounded-md border shadow transition duration-300"
                                    role="alert" tabindex="-1" aria-labelledby="dismiss-button-label">
                                    <div class="border-default-300 flex items-center border-b px-3 py-2">
                                        <p id="dismiss-button-label"
                                            class="text-default-600 flex items-center gap-1.5 text-sm">
                                            <img src="/images/logo-sm.png" alt="brand-logo" class="size-4" />
                                            <strong class="me-auto font-semibold">BRAND</strong>
                                        </p>

                                        <div class="ms-auto flex items-center gap-2">
                                            <span class="text-default-400 text-xs">11분 전</span>
                                            <button type="button"
                                                class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                aria-label="Close" data-hs-remove-element="#dismiss-toast">
                                                <i data-icon="tabler:x" class="iconify tabler--x text-default-800 size-6"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-3 text-sm">안녕하세요! 이것은 토스트 메시지입니다.</div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">배치 (Placement)</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">
                                    필요에 따라 사용자 정의 CSS로 토스트를 배치하십시오. 우측 상단은 알림용으로 자주 사용되며 중앙 상단도 많이 사용됩니다. 한 번에 하나의 토스트만
                                    표시하려는 경우 토스트에 직접 위치 스타일을 부여하십시오.
                                </p>

                                <div class="p-20">
                                    <div class="absolute start-1/2 top-45 -translate-x-1/2 transform">
                                        <div id="placement-toast"
                                            class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 w-60 rounded-md border shadow transition duration-300 md:w-80"
                                            role="alert" tabindex="-1" aria-labelledby="placement-label">
                                            <div class="border-default-300 flex items-center border-b px-3 py-2">
                                                <p id="placement-label"
                                                    class="text-default-600 flex items-center gap-1.5 text-sm">
                                                    <img src="/images/logo-sm.png" alt="brand-logo" class="size-4" />
                                                    <strong class="me-auto font-semibold">BRAND</strong>
                                                </p>

                                                <div class="ms-auto flex items-center gap-2">
                                                    <span class="text-default-400 text-xs">11분 전</span>
                                                    <button type="button"
                                                        class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                        aria-label="Close" data-hs-remove-element="#placement-toast">
                                                        <i data-icon="tabler:x" class="iconify tabler--x text-default-800 size-6"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-3 text-sm">안녕하세요! 이것은 토스트 메시지입니다.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">사용자 정의 콘텐츠 (Custom content)</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="space-y-3">
                                    <div id="Custom-toast"
                                        class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 max-w-xs rounded-md border shadow transition duration-300"
                                        role="alert" tabindex="-1" aria-labelledby="Custom-label">
                                        <div class="border-default-300 flex items-center border-b p-3">
                                            <p id="Custom-label"
                                                class="text-default-600 flex items-center gap-1.5 text-sm">안녕하세요! 이것은
                                                토스트 메시지입니다.</p>

                                            <div class="ms-auto flex items-center gap-2">
                                                <button type="button"
                                                    class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                    aria-label="Close" data-hs-remove-element="#Custom-toast">
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-default-800 text-2xl"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Custom-toast2"
                                        class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-primary border-default-300 max-w-xs rounded-md border transition duration-300"
                                        role="alert" tabindex="-1" aria-labelledby="Custom-label2">
                                        <div class="border-default-300 flex items-center border-b p-3 text-white">
                                            <p id="Custom-label2" class="flex items-center gap-1.5 text-sm">안녕하세요! 이것은
                                                토스트 메시지입니다.</p>

                                            <div class="ms-auto flex items-center gap-2">
                                                <button type="button"
                                                    class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                    aria-label="Close" data-hs-remove-element="#Custom-toast2">
                                                    <i data-icon="tabler:x" class="iconify tabler--x text-2xl"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Custom-toast3"
                                        class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 max-w-xs rounded-md border p-3 transition duration-300"
                                        role="alert" tabindex="-1" aria-labelledby="Custom-label3">
                                        <div
                                            class="border-default-300 text-default-600 flex items-center border-b pb-3">
                                            <p id="Custom-label3" class="flex items-center gap-1.5 text-sm">안녕하세요! 이것은
                                                토스트 메시지입니다.</p>
                                        </div>

                                        <div class="mt-3 flex items-center gap-1">
                                            <button class="btn btn-sm bg-primary hover:bg-primary-hover text-white">작업
                                                수행</button>
                                            <button class="btn btn-sm bg-secondary hover:bg-secondary-hover text-white"
                                                aria-label="Close" data-hs-remove-element="#Custom-toast3">닫기</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">스태킹 (Stacking)</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                    data-action="card-toggle">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <p class="text-default-400 mb-4">여러 개의 토스트가 있을 때 기본적으로 읽기 쉬운 방식으로 수직으로 쌓입니다.</p>
                                <div class="flex justify-end">
                                    <div class="space-y-base p-5">
                                        <div>
                                            <div id="Stacking-1"
                                                class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 max-w-xs rounded-md border shadow transition duration-300"
                                                role="alert" tabindex="-1" aria-labelledby="Stacking-label">
                                                <div class="border-default-300 flex items-center border-b px-3 py-2">
                                                    <p id="Stacking-label"
                                                        class="text-default-600 flex items-center gap-1.5 text-sm">
                                                        <img src="/images/logo-sm.png" alt="brand-logo"
                                                            class="size-4" />
                                                        <strong class="me-auto font-semibold">BRAND</strong>
                                                    </p>

                                                    <div class="ms-auto flex items-center gap-2">
                                                        <span class="text-default-400 text-xs">방금 전</span>
                                                        <button type="button"
                                                            class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                            aria-label="Close" data-hs-remove-element="#Stacking-1">
                                                            <i data-icon="tabler:x" class="iconify tabler--x text-default-800 text-xl"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="p-3 text-sm">보이시나요? 바로 이렇게요.</div>
                                            </div>
                                        </div>

                                        <div>
                                            <div id="Stacking-2"
                                                class="hs-removing:translate-x-5 hs-removing:opacity-0 bg-default-100 border-default-300 max-w-xs rounded-md border shadow transition duration-300"
                                                role="alert" tabindex="-1" aria-labelledby="Stacking-label-2">
                                                <div class="border-default-300 flex items-center border-b px-3 py-2">
                                                    <p id="Stacking-label-2"
                                                        class="text-default-600 flex items-center gap-1.5 text-sm">
                                                        <img src="/images/logo-sm.png" alt="brand-logo"
                                                            class="size-4" />
                                                        <strong class="me-auto font-semibold">BRAND</strong>
                                                    </p>

                                                    <div class="ms-auto flex items-center gap-2">
                                                        <span class="text-default-400 text-xs">2초 전</span>
                                                        <button type="button"
                                                            class="flex items-center justify-center opacity-50 hover:opacity-100 focus:opacity-100 focus:outline-hidden"
                                                            aria-label="Close" data-hs-remove-element="#Stacking-2">
                                                            <i data-icon="tabler:x" class="iconify tabler--x text-default-800 text-xl"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="p-3 text-sm">알림입니다. 토스트는 자동으로 쌓입니다.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>