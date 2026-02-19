<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="space-y-base">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">테두리 스피너 (Border Spinner)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">가벼운 로딩 표시기로 테두리 스피너를 사용하세요.</p>
                                    <div class="border-dark inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                        role="status" aria-label="loading">
                                        <span class="sr-only">로딩 중...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">색상 (Colors)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        이 스피너들은 <code>.border-primary</code>, <code>.border-success</code>, 또는
                                        <code>.border-danger</code>와 같은 테두리 색상 유틸리티를 사용하여 다양한 색상 변형을 표시합니다. 각 스피너의 상단
                                        테두리는 부드러운 회전 효과를 위해 투명하게 처리되어 있습니다.
                                    </p>

                                    <div class="flex gap-base flex-wrap items-center">
                                        <div class="border-primary inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-secondary inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-success inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-danger inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-warning inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-info inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-light inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>

                                        <div class="border-dark inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading">
                                            <span class="sr-only">로딩 중...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">정렬 (Alignment)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        이 스피너들은 <code>animate-spin</code>, <code>border-dark</code>,
                                        <code>border-t-transparent</code>와 같은 간단한 유틸리티 클래스를 사용하여 회전하는 로딩 표시기를 만듭니다.
                                        <code>rounded-full</code> 클래스는 스피너를 완벽한 원형으로 유지하고 깔끔하게 정렬합니다.
                                    </p>

                                    <div class="flex gap-base flex-wrap items-center">
                                        <strong>로딩 중...</strong>
                                        <div class="border-dark ms-auto inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading"></div>
                                    </div>

                                    <div class="mt-5 flex items-center justify-center gap-base">
                                        <div class="border-dark inline-block size-8 animate-spin rounded-full border-3 border-t-transparent"
                                            role="status" aria-label="loading"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">버튼 내 스피너 (Buttons Spinner)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="grid md:grid-cols-2 gap-base">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button class="btn btn-icon bg-primary">
                                                <div class="inline-block size-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </button>

                                            <button class="btn btn-icon bg-primary rounded-full">
                                                <div class="inline-block size-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </button>

                                            <button class="btn bg-primary">
                                                <div class="inline-block size-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </button>

                                            <button class="btn bg-primary">
                                                <div class="flex items-center gap-3">
                                                    <div class="inline-block size-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                                                        role="status" aria-label="loading">
                                                        <span class="sr-only">로딩 중...</span>
                                                    </div>
                                                    <span class="text-white">로딩 중..</span>
                                                </div>
                                            </button>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <button class="btn btn-icon bg-primary">
                                                <span class="size-4 animate-grow rounded-full bg-white"></span>
                                            </button>

                                            <button class="btn btn-icon bg-primary rounded-full">
                                                <span class="size-4 animate-grow rounded-full bg-white"></span>
                                            </button>

                                            <button class="btn bg-primary">
                                                <span class="size-4 animate-grow rounded-full bg-white"></span>
                                            </button>

                                            <button class="btn bg-primary">
                                                <div class="flex items-center gap-3">
                                                    <span class="size-4 animate-grow rounded-full bg-white"></span>
                                                    <strong class="text-white">로딩 중...</strong>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-base">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">확장형 스피너 (Growing Spinner)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">Tailwind CSS로 제작된 간단한 확장형 스피너입니다. 유틸리티 클래스를 사용하여
                                        크기, 색상 및 정렬을 쉽게 변경할 수 있습니다.</p>

                                    <div class="flex items-center justify-start">
                                        <span class="bg-dark size-8 animate-grow rounded-full"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">유색 확장형 스피너 (Color Growing Spinner)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        이 확장형 스피너들은 서로 다른 배경 색상 클래스를 사용하여 다양한 색상 테마를 표시합니다. <code>.bg-primary</code>,
                                        <code>.bg-success</code>, 또는 <code>.bg-warning</code>과 같은 색상 클래스를 적용하여 스피너의 외관을
                                        사용자 정의할 수 있습니다.
                                    </p>

                                    <div class="flex gap-base flex-wrap items-center">
                                        <div class="flex items-center justify-center">
                                            <span class="bg-primary size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-secondary size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-success size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-danger size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-warning size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-info size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-default-200 size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-dark size-8 animate-grow rounded-full"></span>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <span class="bg-purple size-8 animate-grow rounded-full"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">크기 (Size)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="grid md:grid-cols-2 gap-base">
                                        <div class="flex items-center gap-base">
                                            <div class="flex items-center justify-center">
                                                <div class="border-primary inline-block size-11 animate-spin rounded-full border-3 border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-center">
                                                <span class="bg-secondary size-11 animate-grow rounded-full"></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-base">
                                            <div class="flex items-center justify-center">
                                                <div class="border-primary inline-block size-9 animate-spin rounded-full border-3 border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-center">
                                                <span class="bg-secondary size-7 animate-grow rounded-full"></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-base">
                                            <div class="flex items-center justify-center">
                                                <div class="border-primary inline-block size-7 animate-spin rounded-full border-3 border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-center">
                                                <span class="bg-secondary size-6 animate-grow rounded-full"></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-base">
                                            <div class="flex items-center justify-center">
                                                <div class="border-dark inline-block size-4 animate-spin rounded-full border-2 border-t-transparent"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">로딩 중...</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-center">
                                                <span class="bg-dark size-4 animate-grow rounded-full"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>