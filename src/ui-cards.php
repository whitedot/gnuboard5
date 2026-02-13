<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-base mb-base">
                        <!-- Simple card -->
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-4">카드 제목을 기반으로 하고 카드의 주요 내용을 구성하는 간단한 예시 텍스트입니다.</p>

                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">버튼</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <!-- Simple card -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2.5">제목이 있는 기본 카드</h5>
                                <p class="mb-4">카드 제목을 기반으로 하고 카드의 주요 내용을 구성하는 간단한 예시 텍스트입니다.</p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">버튼</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <!-- Simple card -->
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5 class="card-title mb-2.5 text-white">배경색이 있는 카드</h5>
                                <p class="mb-4 text-white">Some quick example text to build on the card title and make
                                    up the bulk of the card's content. Some quick example text to build on the card
                                    title and make up.</p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-default-50 hover:text-primary">버튼</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <!-- Simple card -->
                        <div class="card bg-success">
                            <div class="card-body">
                                <h5 class="card-title mb-2.5 text-white">배경 그라데이션이 있는 카드</h5>
                                <p class="mb-4 text-white">Some quick example text to build on the card title and make
                                    up the bulk of the card's content. Some quick example text to build on the card
                                    title and make up.</p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-default-50 hover:text-primary">버튼</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-base mb-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">헤더가 있는 카드</h4>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title mb-2.5">특별한 제목 처리</h5>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로 이동</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header block border-dashed">
                                <h4 class="card-title mb-1.25">서브 헤더가 있는 카드</h4>
                                <h6 class="text-default-400 text-2xs">카드 부제목</h6>
                            </div>

                            <div class="card-body">
                                <p class="mb-4">로렘 입숨은 전통적인 라틴어 텍스트로, 디자인의 레이아웃을 보여주기 위해 사용됩니다.</p>
                                <p>출처 제목의 유명한 누군가</p>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">주요 카드 제목</div>

                            <div class="card-body">
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로 이동</a>
                            </div>
                            <!-- end card-body-->

                            <div class="card-footer text-default-400">2일 전</div>
                        </div>
                        <!-- end card-->
                    </div>

                    <h4 class="mb-base mt-4 text-base">고급 카드</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-base mb-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">작업 도구가 있는 카드</h4>
                                <div class="flex gap-1">

                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로 이동</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card bg-primary">
                            <div class="card-header border-dashed border-white">
                                <h4 class="card-title text-white">작업 도구 및 배경색이 있는 카드</h4>
                                <div class="flex gap-1">
                                    <button class="btn size-6 rounded-full bg-white/25 text-white">
                                        <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                    </button>

                                    <button class="btn size-6 rounded-full bg-white/25 text-white">
                                        <i data-icon="tabler:refresh" class="iconify tabler--refresh text-base"></i>
                                    </button>

                                    <button class="btn size-6 rounded-full bg-white/25 text-white">
                                        <i data-icon="tabler:x" class="iconify tabler--x text-base"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-4 text-white">With supporting text below as a natural lead-in to additional
                                    content.</p>
                                <a href="javascript: void(0);" class="btn btn-sm hover:text-primary bg-white">Go
                                    somewhere</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">작업 도구가 있는 카드</h4>
                                <div class="flex gap-1">

                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로 이동</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>

                    <h4 class="mb-base mt-4 text-base">테두리가 있는 카드</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-base mb-base">
                        <div class="card border-primary border">
                            <div class="card-body">
                                <h4 class="card-title mb-2.5">유색 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card border-primary border border-dashed">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-3">심플한 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card border-primary border-2">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-3">이중 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card border-dark border-s-3">
                            <div class="card-body">
                                <h4 class="card-title text-dark mb-3">이중 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-dark hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card border-primary border-s-3">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-3">유색 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-primary hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card border-info border-s-3">
                            <div class="card-body">
                                <h4 class="card-title mb-2.5">유색 테두리가 있는 카드</h4>
                                <p class="mb-4">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.
                                </p>
                                <a href="javascript: void(0);"
                                    class="btn btn-sm bg-info hover:bg-primary-hover text-white">Button</a>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->
                    </div>

                    <h4 class="mb-base mt-4 text-base">수평 카드</h4>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base mb-base">
                        <div class="card">
                            <div class="grid grid-cols-1 items-center md:grid-cols-3">
                                <div>
                                    <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-s-sm" alt="검 든 여우 이미지" style="width: 100%; height: 100%; min-height: 220px; object-fit: cover; object-position: center;" />
                                </div>

                                <div class="col-span-2">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2.5">수평 모드 카드</h5>
                                        <p class="mb-4">이것은 자연스럽게 추가 콘텐츠로 이어지는 보조 텍스트가 있는 더 넓은 카드입니다. 이 내용은 조금 더 깁니다.
                                        </p>
                                        <p>
                                            <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="grid grid-cols-1 items-center md:grid-cols-3">
                                <div class="col-span-2">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2.5">수평 모드 카드</h5>
                                        <p class="mb-4">이것은 자연스럽게 추가 콘텐츠로 이어지는 보조 텍스트가 있는 더 넓은 카드입니다. 이 내용은 조금 더 깁니다.
                                        </p>
                                        <p>
                                            <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-e-sm" alt="검 든 여우 이미지" style="width: 100%; height: 100%; min-height: 220px; object-fit: cover; object-position: center;" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-base mt-4 text-base">확장된 링크</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-base mb-base">
                        <div class="card group">
                            <a href="#">
                                <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-t-sm" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                                <div class="card-body">
                                    <h5 class="card-title mb-2.5">확장 링크가 있는 카드</h5>
                                    <a href="#"
                                        class="btn btn-sm bg-primary group-hover:bg-primary-hover mt-3 text-white">Button</a>
                                </div>
                            </a>
                        </div>

                        <div class="card">
                            <a href="#">
                                <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-t-sm" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-3">확장 링크가 있는 카드</h5>
                                    <p>카드 제목을 기반으로 내용을 구성하는 간단한 예시 텍스트입니다.
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="card group">
                            <a href="#">
                                <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-t-sm" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                                <div class="card-body">
                                    <h5 class="card-title mb-2.5">확장 링크가 있는 카드</h5>
                                    <a href="#"
                                        class="btn btn-sm bg-primary group-hover:bg-primary-hover mt-3 text-white">Button</a>
                                </div>
                            </a>
                        </div>

                        <div class="card">
                            <a href="#">
                                <img src="images/WChwGxuZl7SE6RiHti5x.jpg" class="rounded-t-sm" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-3">확장 링크가 있는 카드</h5>
                                    <p>카드 제목을 기반으로 내용을 구성하는 간단한 예시 텍스트입니다.
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <h4 class="mb-base mt-4 text-base">카드 그룹</h4>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3 md:gap-0 mb-base">
                        <div class="card h-full md:rounded-s-sm md:rounded-e-none">
                            <img class="card-img-top" src="images/WChwGxuZl7SE6RiHti5x.jpg" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                            <div class="card-body">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p class="mb-4">This is a wider card with supporting text below as a natural lead-in to
                                    additional content. This content is a little bit longer.</p>
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>

                        <div class="card h-full md:rounded-none">
                            <img class="card-img-top" src="images/WChwGxuZl7SE6RiHti5x.jpg" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                            <div class="card-body">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p class="mb-4">이 카드는 추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트를 포함하고 있습니다.</p>
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>

                        <div class="card h-full md:rounded-s-none md:rounded-e-sm">
                            <img class="card-img-top" src="images/WChwGxuZl7SE6RiHti5x.jpg" alt="검 든 여우 이미지" style="width: 100%; height: 220px; object-fit: cover; object-position: center;" />
                            <div class="card-body">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p class="mb-4">이것은 자연스럽게 추가 콘텐츠로 이어지는 보조 텍스트가 있는 더 넓은 카드입니다. 이 카드는 동일한 높이 동작을 보여주기 위해 첫
                                    번째 카드보다 더 긴 내용을 가지고 있습니다.</p>
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 rounded md:grid-cols-3 md:gap-0 mb-base">
                        <div class="card h-full md:rounded-s-sm md:rounded-e-none">
                            <div class="card-body flex-1">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p>This is a wider card with supporting text below as a natural lead-in to additional
                                    content. This content is a little bit longer.</p>
                            </div>

                            <div class="card-footer">
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>

                        <div class="card h-full md:rounded-none">
                            <div class="card-body flex-1">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p>This card has supporting text below as a natural lead-in to additional content.</p>
                            </div>

                            <div class="card-footer">
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>

                        <div class="card h-full md:rounded-s-none md:rounded-e-sm">
                            <div class="card-body flex-1">
                                <h5 class="card-title mb-2.5">카드 제목</h5>
                                <p>This is a wider card with supporting text below as a natural lead-in to additional
                                    content. This card has even longer content than the first to show that equal height
                                    action.</p>
                            </div>

                            <div class="card-footer">
                                <p>
                                    <small class="text-default-400 text-2xs">최근 업데이트: 3분 전</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-base mt-4 text-base">카드가 있는 내비게이션</h4>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="card">
                            <div class="card-header border-dashed pb-0">
                                <nav class="flex">
                                    <a href="#"
                                        class="text-primary border-default-300 rounded-t-lg border border-b-0 px-4 py-2 text-center text-sm font-medium">활성</a>

                                    <a href="#" class="text-default-600 px-4 py-2 font-medium">링크</a>

                                    <a href="#" class="px-4 py-2 font-medium" disabled>비활성화됨</a>
                                </nav>
                            </div>

                            <div class="card-body">
                                <h5 class="text-md mb-2 text-center">특별한 제목 처리</h5>
                                <p class="mb-4 text-center">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.</p>
                                <div class="text-center">
                                    <a href="#" class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로
                                        이동</a>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <nav class="flex">
                                    <a href="#" class="btn btn-sm bg-primary hover:bg-primary-hover text-white">활성</a>
                                    <a href="#" class="text-default-600 px-4 py-2 font-medium">링크</a>
                                    <a hrf="#" class="px-4 py-2 font-medium" disabled>비활성화됨</a>
                                </nav>
                            </div>

                            <div class="card-body">
                                <h5 class="text-md mb-2 text-center">특별한 제목 처리</h5>
                                <p class="mb-4 text-center">추가 콘텐츠로 자연스럽게 이어지는 아래의 보조 텍스트와 함께 제공됩니다.</p>

                                <div class="text-center">
                                    <a href="#" class="btn btn-sm bg-primary hover:bg-primary-hover text-white">어딘가로
                                        이동</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>
