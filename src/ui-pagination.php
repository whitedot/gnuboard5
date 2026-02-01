<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">기본 페이지네이션 (Default Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="flex items-center -space-x-px" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous">
                                            <span>이전</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span>다음</span>
                                        </button>
                                    </nav>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">정렬 (Alignment)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="mb-4 flex items-center justify-center -space-x-px"
                                        aria-label="Pagination">
                                        <button type="button"
                                            class="text-default-400 border-default-300 inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous" disabled>
                                            <span>이전</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span>다음</span>
                                        </button>
                                    </nav>

                                    <nav class="flex items-center justify-end -space-x-px" aria-label="Pagination">
                                        <button type="button"
                                            class="text-default-400 border-default-300 inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous" disabled>
                                            <span>이전</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span>다음</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">사용자 정의 색상 페이지네이션 (Custom Color Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="mb-4 flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <i data-icon="tabler:chevron-left" class="iconify tabler--chevron-left"></i>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="bg-info flex size-8.75 items-center justify-center rounded text-white">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <i data-icon="tabler:chevron-right" class="iconify tabler--chevron-right"></i>
                                        </button>
                                    </nav>

                                    <nav class="flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <i data-icon="tabler:arrow-left" class="iconify tabler--arrow-left"></i>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-secondary flex size-8.75 items-center justify-center rounded text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <i data-icon="tabler:arrow-right" class="iconify tabler--arrow-right"></i>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">비활성 및 활성 상태 (Disabled and active states)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="flex items-center -space-x-px" aria-label="Pagination">
                                        <button type="button"
                                            class="text-default-400 border-default-300 inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous" disabled>
                                            <span>이전</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 bg-primary hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 text-white transition-all duration-300">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-3 py-1.5 transition-all duration-300">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-3 py-1.5 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span>다음</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">사용자 정의 아이콘 페이지네이션 (Custom Icon Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="mb-4 flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <i data-icon="tabler:chevron-left" class="iconify tabler--chevron-left"></i>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="bg-primary flex size-8.75 items-center justify-center rounded text-white">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <i data-icon="tabler:chevron-right" class="iconify tabler--chevron-right"></i>
                                        </button>
                                    </nav>

                                    <nav class="flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <i data-icon="tabler:arrow-left" class="iconify tabler--arrow-left"></i>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-primary flex size-8.75 items-center justify-center rounded text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <i data-icon="tabler:arrow-right" class="iconify tabler--arrow-right"></i>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>

                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">크기 조절 (Sizing)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="mb-4 flex items-center -space-x-px" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-6 py-3 text-base transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous">
                                            <span class="text-base">«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-6 py-3 text-base transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-current="page">
                                            1
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-6 py-3 text-base transition-all duration-300 first:rounded-s-md last:rounded-e-md">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-6 py-3 text-base transition-all duration-300 first:rounded-s-md last:rounded-e-md">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-6 py-3 text-base transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span class="text-base">»</span>
                                        </button>
                                    </nav>

                                    <nav class="flex items-center -space-x-px" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-2 py-1 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-2 py-1 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-2 py-1 transition-all duration-300 first:rounded-s-md last:rounded-e-md">2</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex items-center justify-center border px-2 py-1 transition-all duration-300 first:rounded-s-md last:rounded-e-md">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary inline-flex items-center justify-center gap-x-1.5 border px-2 py-1 transition-all duration-300 first:rounded-s-md last:rounded-e-md"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">박스형 페이지네이션 (Boxed Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="mb-4 flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-primary flex size-8.75 items-center justify-center rounded text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>

                                    <nav class="mb-4 flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center gap-x-1.5 rounded border text-base transition-all duration-300"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center rounded border text-base transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center rounded border text-base transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-primary flex size-12.5 items-center justify-center rounded text-base text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center rounded border text-base transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center rounded border text-base transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-12.5 items-center justify-center gap-x-1.5 rounded border text-base transition-all duration-300"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>

                                    <nav class="flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center rounded border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-primary flex size-7.25 items-center justify-center rounded text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-7.25 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">둥근 형태 페이지네이션 (Rounded Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded-full border transition-all duration-300"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded-full border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded-full border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="bg-primary flex size-8.75 items-center justify-center rounded-full text-white">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded-full border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center rounded-full border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-primary flex size-8.75 items-center justify-center gap-x-1.5 rounded-full border transition-all duration-300"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">부드러운 스타일 페이지네이션 (Soft Pagination)</h4>
                                    <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary"
                                        data-action="card-toggle">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <!-- Pagination -->
                                    <nav class="flex items-center gap-1.5" aria-label="Pagination">
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Previous">
                                            <span>«</span>
                                        </button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center rounded border transition-all duration-300"
                                            aria-current="page">1</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center rounded border transition-all duration-300">2</button>
                                        <button type="button"
                                            class="text-danger bg-danger/10 flex size-8.75 items-center justify-center rounded">3</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center rounded border transition-all duration-300">4</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center rounded border transition-all duration-300">5</button>
                                        <button type="button"
                                            class="border-default-300 hover:bg-default-100 hover:text-danger flex size-8.75 items-center justify-center gap-x-1.5 rounded border transition-all duration-300"
                                            aria-label="Next">
                                            <span>»</span>
                                        </button>
                                    </nav>
                                    <!-- End Pagination -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>