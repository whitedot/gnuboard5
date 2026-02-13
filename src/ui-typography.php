<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 xl:grid-cols-6 gap-base">
                        <div class="lg:col-span-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">디스플레이 헤딩 (Display Headings)</h4>
                                </div>

                                <div class="card-body">
                                    <div class="space-y-3 grid grid-cols-2">
                                        <h1 class="text-xs">text-xs (가장 작음)</h1>
                                        <h1 class="text-sm">text-sm (작음)</h1>
                                        <h1 class="text-base">text-base (기본)</h1>
                                        <h1 class="text-lg">text-lg (큼)</h1>
                                        <h1 class="text-xl">text-xl</h1>
                                        <h1 class="text-2xl">text-2xl</h1>
                                        <h1 class="text-3xl">text-3xl</h1>
                                        <h1 class="text-4xl">text-4xl</h1>
                                        <h1 class="text-5xl">text-5xl</h1>
                                        <h1 class="text-6xl">text-6xl</h1>
                                        <h1 class="text-7xl">text-7xl</h1>
                                        <h1 class="text-8xl">text-8xl</h1>
                                        <h1 class="text-9xl">text-9xl (가장 큼)</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">인라인 텍스트 요소 (Inline Text Elements)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="space-y-4">
                                        <p class="text-default-400">일반적인 인라인 HTML5 요소의 스타일입니다.</p>
                                        <p class="text-lg font-light">제목이 여기에 표시됩니다</p>
                                        <p class="font-light">
                                            mark 태그를 사용하여 텍스트를
                                            <mark class="bg-warning/15 p-1">강조(하이라이트)</mark>
                                            할 수 있습니다.
                                        </p>
                                        <p class="font-light">
                                            <del>이 텍스트 줄은 삭제된 텍스트로 처리됩니다.</del>
                                        </p>
                                        <p class="font-light">
                                            <s>이 텍스트 줄은 더 이상 정확하지 않은 것으로 처리됩니다.</s>
                                        </p>
                                        <p class="font-light">
                                            <ins>이 텍스트 줄은 문서의 추가 사항으로 처리됩니다.</ins>
                                        </p>
                                        <p class="font-light">
                                            <u>이 텍스트 줄은 밑줄이 그어진 상태로 렌더링됩니다.</u>
                                        </p>
                                        <p class="font-light">
                                            <small>이 텍스트 줄은 세부 항목(fine print)으로 처리됩니다.</small>
                                        </p>
                                        <p class="font-light">
                                            <strong>이 줄은 굵은 텍스트로 렌더링됩니다.</strong>
                                        </p>
                                        <p class="font-light">
                                            <em>이 줄은 기울임꼴 텍스트로 렌더링됩니다.</em>
                                        </p>
                                        <p class="mb-0 font-light">
                                            Nulla
                                            <abbr title="attribute">속성(attr)</abbr>
                                            vitae elit libero, a pharetra augue.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">기호 없는 리스트 (Unordered)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">순서가 명시적으로 중요하지 않은 항목 목록입니다.</p>
                                    <ul class="list-inside list-disc space-y-1">
                                        <li>완벽한 반응형 디자인</li>
                                        <li>TailwindCSS 4 프레임워크로 구축</li>
                                        <li>깔끔하고 현대적인 UI 구성 요소</li>
                                        <li>교차 브라우저 호환성</li>
                                        <li>
                                            다양한 양식 요소 및 유효성 검사
                                            <ul class="ms-10 list-inside list-disc">
                                                <li>풍부한 입력 컨트롤</li>
                                                <li>단계별 양식 마법사</li>
                                                <li>실시간 유효성 검사</li>
                                                <li>사용자 정의 가능한 스타일</li>
                                            </ul>
                                        </li>
                                        <li>고급 차트 및 그래프 라이브러리</li>
                                        <li>통합된 데이터 테이블 및 정렬</li>
                                        <li>개발자 친화적인 코드 구조</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">번호 매기기 리스트 (Ordered)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">순서가 명시적으로 중요한 항목 목록입니다.</p>
                                    <ol class="list-inside list-decimal space-y-1">
                                        <li>모든 종속성 설치</li>
                                        <li>프로젝트 환경 설정 구성</li>
                                        <li>폴더 구조 및 라우팅 설정</li>
                                        <li>UI 구성 요소 및 레이아웃 통합</li>
                                        <li>
                                            핵심 모듈 구현
                                            <ol class="ms-10 list-inside list-decimal space-y-1">
                                                <li>인증 및 권한 부여</li>
                                                <li>대시보드 위젯 및 메트릭</li>
                                                <li>사용자 프로필 관리</li>
                                                <li>알림 및 메시징 시스템</li>
                                            </ol>
                                        </li>
                                        <li>백엔드 API 연결 및 데이터 흐름 테스트</li>
                                        <li>성능 및 반응형 디자인 최적화</li>
                                        <li>최종 테스트 및 배포</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">스타일 없는 리스트 (Unstyled)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">
                                        <strong>이것은 직계 자식 리스트 항목에만 적용됩니다.</strong>
                                        즉, 중첩된 리스트에 대해서도 클래스를 추가해야 합니다.
                                    </p>

                                    <div class="space-y-4">
                                        <ul class="list-none">
                                            <li>프로젝트 종속성 설치</li>
                                            <li>
                                                빌드 설정 구성
                                                <ul class="list-inside list-disc">
                                                    <li>환경 변수 업데이트</li>
                                                </ul>
                                            </li>
                                            <li>프로젝트 구조 및 경로 설정</li>
                                            <li>로컬 개발 서버 실행</li>
                                        </ul>

                                        <h5>인라인 리스트 (Inline List)</h5>
                                        <p class="text-default-400">
                                            <code>display: inline-block;</code>과 적절한 간격을 사용하여 리스트 항목을 가로로 표시합니다.
                                        </p>

                                        <ul class="flex items-center gap-4">
                                            <li class="list-inline-item">HTML</li>
                                            <li class="list-inline-item">CSS</li>
                                            <li class="list-inline-item">JavaScript</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">정렬 (Alignment)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">인용구(blockquote)의 정렬을 변경하려면 필요에 따라 텍스트 유틸리티를 사용하세요.
                                    </p>
                                    <figure class="mb-5 text-center">
                                        <blockquote class="blockquote">
                                            <p class="mb-1 text-lg">"디자인은 단순히 어떻게 보이고 느껴지는가가 아닙니다. 디자인은 어떻게 작동하는가에 대한
                                                것입니다."</p>
                                        </blockquote>

                                        <figcaption class="text-default-400">
                                            - 스티브 잡스,
                                            <cite title="Steve Jobs Biography">스티브 잡스 전기</cite> 중
                                        </figcaption>
                                    </figure>

                                    <figure class="text-end">
                                        <blockquote class="blockquote">
                                            <p class="mb-1 text-lg">"단순함은 궁극의 정교함이다."</p>
                                        </blockquote>

                                        <figcaption class="text-default-400">
                                            - 레오나르도 다 빈치,
                                            <cite title="Design Philosophy">디자인 철학</cite> 중
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">인라인 (Inline)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">리스트의 글머리 기호를 제거하고 .list-inline 및 .list-inline-item
                                        클래스를 조합하여 약간의 여백을 적용합니다.</p>
                                    <ul class="list-inside">
                                        <li>이것은 리스트 항목입니다.</li>
                                        <li>또 다른 항목입니다.</li>
                                        <li>하지만 인라인으로 표시됩니다.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">출처 표기 (Naming a Source)</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-default-400 mb-4">인용구(blockquote) 요소에 담긴 잘 알려진 인용문입니다.</p>
                                    <figure>
                                        <blockquote>
                                            <p class="mb-1 text-lg">"디자인은 단순히 어떻게 보이고 느껴지는가가 아닙니다. 디자인은 어떻게 작동하는가에 대한
                                                것입니다."</p>
                                        </blockquote>

                                        <figcaption class="text-default-400">
                                            - 스티브 잡스,
                                            <cite title="Design Philosophy">디자인 철학</cite> 중
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">블록 인용구 (Blockquotes)</h4>
                                </div>

                                <div class="card-body">
                                    <!-- 기본 -->
                                    <blockquote>
                                        <p class="text-lg">"좋은 디자인은 명확합니다. 위대한 디자인은 투명합니다."</p>
                                    </blockquote>
                                    <figcaption class="mt-1 mb-base text-default-400">
                                        ?? 조 스파라노,
                                        <cite class="italic">디자인 원칙</cite> 중
                                    </figcaption>

                                    <p class="text-default-400 mb-4">인용구의 정렬을 변경하려면 필요에 따라 텍스트 유틸리티를 사용하세요.</p>

                                    <!-- 중앙 -->
                                    <blockquote class="text-center">
                                        <p class="text-lg">"먼저 문제를 해결하세요. 그런 다음 코드를 작성하세요."</p>
                                    </blockquote>
                                    <figcaption class="mt-1 mb-base text-default-400 text-center">
                                        ?? 존 존슨,
                                        <cite class="italic">개발자의 지혜</cite> 중
                                    </figcaption>

                                    <!-- 우측 -->
                                    <blockquote class="text-end">
                                        <p class="text-lg">"단순함은 효율성의 영혼입니다."</p>
                                    </blockquote>
                                    <figcaption class="mt-1 mb-base text-default-400 text-end">
                                        ?? 오스틴 프리먼,
                                        <cite class="italic">디자인의 효율성</cite> 중
                                    </figcaption>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include 'layout/footer.php'; ?>