<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-base mb-base">
                        <div class="card">
                            <div class="card-body">
                                <div class="bg-primary rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>프라이머리 (Primary)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-secondary rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>세컨더리 (Secondary)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-success rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>성공 (Success)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-info rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>정보 (Info)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-warning rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>경고 (Warning)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-danger rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>위험 (Danger)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-dark rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>다크 (Dark)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="bg-light rounded" style="height: 100px"></div>
                                <div class="mt-base text-center">
                                    <h6>라이트 (Light)</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="my-7.5 text-lg font-bold">테두리 색상 (Border Colors)</h4>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-base">
                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 추가 (Additive Border)</h5>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        테두리 유틸리티를 사용하여 요소에 테두리를 <b>추가</b>합니다. 모든 테두리를 한 번에 선택하거나 하나씩 선택할 수 있습니다.
                                    </p>

                                    <div class="flex flex-wrap gap-7.5">
                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-t"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-e"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-b"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-s"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 색상 (Border Color)</h5>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">테마 색상을 기반으로 구축된 유틸리티를 사용하여 테두리 색상을 변경합니다.</p>

                                    <div class="flex flex-wrap gap-2.5">
                                        <div class="text-center">
                                            <div class="border-primary bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-primary bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-secondary bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-success bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-danger bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-warning bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-info bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-light bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-dark bg-light/50 size-9 border"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 두께 (Border Width Size)</h5>
                                </div>

                                <div class="card-body">
                                    <div class="flex flex-wrap gap-2.5">
                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-2"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-3"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-4"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-5"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 색상 투명도 (Border Color opacity)</h5>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">테마 색상을 기반으로 구축된 유틸리티를 사용하여 테두리의 투명도를 변경합니다.</p>

                                    <div class="flex flex-wrap gap-2.5">
                                        <div class="text-center">
                                            <div class="border-primary/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-primary/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-secondary/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-success/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-danger/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-warning/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-info/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-light/15 bg-light/50 size-9 border"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-dark/15 bg-light/50 size-9 border"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-base flex-col">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 제거 (Subtractive Border)</h5>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">테두리 유틸리티를 사용하여 요소의 테두리를 제거합니다. 모든 테두리를 한 번에 선택하거나
                                        하나씩 선택할 수 있습니다.</p>

                                    <div class="flex flex-wrap gap-7.5">
                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border-none"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border border-t-0"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border border-e-0"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border border-b-0"></div>
                                        </div>

                                        <div class="text-center">
                                            <div class="border-default-300 bg-light/50 size-9 border border-s-0"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">테두리 투명도 (Border Opacity)</h5>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        다양한 <code>.border-opacity</code> 유틸리티 중에서 선택하세요:
                                    </p>

                                    <div class="border-primary mb-2.5 border p-2.5">이것은 기본 강조 테두리입니다.</div>
                                    <div class="border-primary/75 mb-2.5 border p-2.5">이것은 75% 투명도의 강조 테두리입니다.</div>
                                    <div class="border-primary/50 mb-2.5 border p-2.5">이것은 50% 투명도의 강조 테두리입니다.</div>
                                    <div class="border-primary/25 mb-2.5 border p-2.5">이것은 25% 투명도의 강조 테두리입니다.</div>
                                    <div class="border-primary/10 border p-2.5">이것은 10% 투명도의 강조 테두리입니다.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>