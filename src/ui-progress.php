<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">예제 (Examples)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">프로그레스 바를 사용하여 프로세스가 얼마나 진행되었는지 사용자에게 보여줄 수 있습니다.
                                    </p>
                                    <div class="flex flex-col gap-2.5">
                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 0%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 25%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 50%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 75%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">높이 (Height)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">다양한 크기의 프로그레스 바를 만들기 위해 서로 다른 높이 클래스가 사용됩니다. 외관을
                                        사용자 정의하려면 높이나 너비 값을 조정하세요.</p>

                                    <div class="flex flex-col gap-2.5">
                                        <div class="bg-default-100 flex h-px w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-danger flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 25%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-0.75 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 25%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-1.25 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-success flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 25%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-2 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-info flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 50%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-3 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-warning flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 75%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-3.75 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-success flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 38%"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">다중 바 (Multiple Bars)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">필요한 경우 하나의 프로그레스 구성 요소에 여러 프로그레스 바를 포함할 수 있습니다.</p>
                                    <!-- Multiple bars Progress -->
                                    <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded">
                                        <div class="bg-primary flex flex-col justify-center overflow-hidden whitespace-nowrap"
                                            style="width: 15%" role="progressbar" aria-valuenow="15" aria-valuemin="0"
                                            aria-valuemax="100"></div>

                                        <div class="bg-success flex flex-col justify-center overflow-hidden whitespace-nowrap"
                                            style="width: 30%" role="progressbar" aria-valuenow="30" aria-valuemin="0"
                                            aria-valuemax="100"></div>

                                        <div class="bg-info flex flex-col justify-center overflow-hidden whitespace-nowrap"
                                            style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <!-- End Multiple bars Progress -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">애니메이션 줄무늬 (Animated Stripes)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        줄무늬 그라데이션에 애니메이션을 적용할 수도 있습니다. <code>.progress-bar</code>에
                                        <code>.progress-bar-animated</code>를 추가하여 CSS3 애니메이션을 통해 줄무늬가 오른쪽에서 왼쪽으로 움직이게
                                        하세요.
                                    </p>

                                    <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                        <div class="bg-primary progress-striped h-4 animate-progress-stripes"
                                            style="width: 75%" role="progressbar" aria-valuenow="25" aria-valuemin="0"
                                            aria-valuemax="75"></div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>

                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">레이블 (Labels)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        <code>.progress-bar</code> 내부에 텍스트를 배치하여 프로그레스 바에 레이블을 추가하세요.
                                    </p>

                                    <div class="flex gap-base flex-col">
                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center text-2xs whitespace-nowrap text-white"
                                                style="width: 25%">25%</div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary relative flex h-4 w-[10%] items-center overflow-visible text-2xs font-medium whitespace-nowrap"
                                                style="width: 10%">프로그레스 바를 위한 긴 레이블 텍스트, 어두운 색상으로 설정됨</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">배경 (Backgrounds)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">배경 유틸리티 클래스를 사용하여 개별 프로그레스 바의 외관을 변경하세요.</p>
                                    <div class="flex flex-col gap-2.5">
                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-primary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 25%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="50">
                                            <div class="bg-info flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 50%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-warning flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 75%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <div class="bg-danger flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 100%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-dark flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 65%"></div>
                                        </div>

                                        <div class="bg-default-100 flex h-4 w-full overflow-hidden rounded"
                                            role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bg-secondary flex flex-col justify-center overflow-hidden text-center whitespace-nowrap transition duration-500"
                                                style="width: 50%"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">줄무늬 (Striped)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        <code>.progress-bar</code>에 <code>.progress-bar-striped</code>를 추가하여 프로그레스 바의
                                        배경색 위에 CSS 그라데이션을 통한 줄무늬를 적용할 수 있습니다.
                                    </p>

                                    <div class="flex flex-col gap-2.5">
                                        <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                            <div class="bg-primary progress h-4" style="width: 10%" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="10"></div>
                                        </div>

                                        <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                            <div class="bg-success progress h-4" style="width: 25%" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="25"></div>
                                        </div>

                                        <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                            <div class="bg-info progress h-4" style="width: 50%" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="50"></div>
                                        </div>

                                        <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                            <div class="bg-warning progress h-4" style="width: 75%" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="75"></div>
                                        </div>

                                        <div class="bg-default-100 h-4 w-full overflow-hidden rounded">
                                            <div class="bg-danger progress h-4" style="width: 100%" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <!-- End Multiple bars Progress -->
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">단계 (Steps)</h4>
                                </div>

                                <div class="card-body">
                                    <!-- Stepper -->
                                    <ul class="flex w-full items-center justify-between">
                                        <!-- Step 1 -->
                                        <li class="flex w-full items-center">
                                            <span
                                                class="bg-primary flex size-9 shrink-0 items-center justify-center rounded-full font-medium text-white">1</span>
                                            <div class="bg-primary h-0.5 flex-1"></div>
                                        </li>

                                        <!-- Step 2 -->
                                        <li class="flex w-full items-center">
                                            <span
                                                class="bg-primary flex size-9 shrink-0 items-center justify-center rounded-full font-medium text-white">2</span>
                                            <div class="bg-default-100 h-0.5 flex-1"></div>
                                        </li>

                                        <!-- Step 3 -->
                                        <li class="flex items-center">
                                            <span
                                                class="bg-default-100 text-default-800 flex size-9 shrink-0 items-center justify-center rounded-full font-medium">3</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>