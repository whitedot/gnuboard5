<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid xl:grid-cols-2 gap-base">
                        <div class="space-y-base">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">기본 팝오버 (Simple Popover)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                        <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                            <button type="button"
                                                class="btn bg-info hover:bg-info-hover text-white disabled:pointer-events-none disabled:opacity-50">지원
                                                정보 받기</button>

                                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                role="tooltip">
                                                <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                    <h3>도움이 필요하신가요?</h3>
                                                </div>

                                                <div class="p-5">우리 팀의 지원을 받으려면 여기를 클릭하세요. 연중무휴 24시간 대기하고 있습니다.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">마우스 오버 (Hover)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="hs-tooltip inline-block [--placement:right] [--trigger:hover]">
                                        <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                            <button type="button"
                                                class="btn bg-dark text-white disabled:pointer-events-none disabled:opacity-50">여기에
                                                마우스를 올리세요</button>

                                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                role="tooltip">
                                                <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                    <h3>흥미로운 기능들!</h3>
                                                </div>

                                                <div class="p-5">존재하는지 몰랐던 기능들을 발견해 보세요. 더 알아보려면 마우스를 올리세요!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">커스텀 팝오버 (Custom Popovers)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">프라이머리
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-primary rounded-t-md px-3.5 py-3">
                                                        <h3>프라이머리 팝오버</h3>
                                                    </div>

                                                    <div class="bg-primary rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여 스타일이
                                                        지정된 프라이머리 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-success hover:bg-success-hover text-white disabled:pointer-events-none disabled:opacity-50">성공
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-success rounded-t-md px-3.5 py-3">
                                                        <h3>성공 팝오버</h3>
                                                    </div>

                                                    <div class="bg-success rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여 스타일이
                                                        지정된 성공 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-danger hover:bg-danger-hover text-white disabled:pointer-events-none disabled:opacity-50">위험
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-danger rounded-t-md px-3.5 py-3">
                                                        <h3>위험 팝오버</h3>
                                                    </div>

                                                    <div class="bg-danger rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여 스타일이
                                                        지정된 위험 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-info hover:bg-info-hover text-white disabled:pointer-events-none disabled:opacity-50">정보
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-info rounded-t-md px-3.5 py-3">
                                                        <h3>정보 팝오버</h3>
                                                    </div>

                                                    <div class="bg-info rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여 스타일이
                                                        지정된 정보 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-dark text-white disabled:pointer-events-none disabled:opacity-50">다크
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-dark rounded-t-md px-3.5 py-3">
                                                        <h3>다크 팝오버</h3>
                                                    </div>

                                                    <div class="bg-dark rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여 스타일이
                                                        지정된 다크 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-secondary hover:bg-secondary-hover text-white disabled:pointer-events-none disabled:opacity-50">세컨더리
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible invisible absolute z-70 hidden w-55 text-start text-white opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-secondary rounded-t-md px-3.5 py-3">
                                                        <h3>세컨더리 팝오버</h3>
                                                    </div>

                                                    <div class="bg-secondary rounded-b-md px-4 py-2">이것은 CSS 변수를 사용하여
                                                        스타일이 지정된 세컨더리 테마 팝오버입니다.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-base">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">다음 클릭 시 닫기 (Dismiss on Next Click)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                        <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                            <button type="button"
                                                class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">팁
                                                보기</button>

                                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                role="tooltip">
                                                <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                    <h3>빠른 팁</h3>
                                                </div>

                                                <div class="p-5">작업 흐름을 즉시 개선할 수 있는 빠른 팁과 요령을 확인하세요.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">네 가지 방향 (Four Directions)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <div class="hs-tooltip inline-block [--placement:top] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">상단
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                        <h3>상단 팝오버</h3>
                                                    </div>

                                                    <div class="p-5">이 팝오버는 버튼 위에 나타납니다. 팁이나 정보 제공에 좋습니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:bottom] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">하단
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                        <h3>하단 팝오버</h3>
                                                    </div>

                                                    <div class="p-5">이 팝오버는 아래에 나타납니다. 추가 세부 정보 제공에 완벽합니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:right] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">우측
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                        <h3>우측 팝오버</h3>
                                                    </div>

                                                    <div class="p-5">우측에서 슬라이드 인하여 빠른 인사이트를 제공합니다.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hs-tooltip inline-block [--placement:left] [--trigger:click]">
                                            <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                                <button type="button"
                                                    class="btn bg-primary hover:bg-primary-hover text-white disabled:pointer-events-none disabled:opacity-50">좌측
                                                    팝오버</button>

                                                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white text-start opacity-0 transition-opacity"
                                                    role="tooltip">
                                                    <div class="bg-default-100 border-default-300 border-b px-3.5 py-3">
                                                        <h3>좌측 팝오버</h3>
                                                    </div>

                                                    <div class="p-5">좌측에 나타납니다. 툴팁이나 메모에 좋습니다.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">비활성화된 요소 (Disabled Elements)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="hs-tooltip inline-block [--placement:top] [--trigger:click]">
                                        <div class="hs-tooltip-toggle block text-center" tabindex="0">
                                            <button type="button"
                                                class="btn bg-primary text-white disabled:pointer-events-none disabled:opacity-50"
                                                disabled>비활성화된 버튼</button>

                                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible border-default-300 invisible absolute z-70 hidden w-70 rounded-md border bg-white p-5 text-start opacity-0 transition-opacity"
                                                role="tooltip">
                                                이 버튼은 비활성화되어 있지만, 팝오버는 여전히 작동합니다.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>