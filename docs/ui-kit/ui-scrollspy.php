<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="container-fluid">
                    <div class="grid grid-cols-1 gap-base">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">내비게이션 바 예제</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <div id="navbar-example2" class="max-h-73 overflow-y-auto">
                                    <header
                                        class="bg-default-100 sticky inset-x-0 top-0 z-20 mb-3 flex w-full flex-wrap px-6 py-3 text-sm lg:flex-nowrap lg:justify-start">
                                        <nav class="w-full md:flex md:items-center md:justify-between">
                                            <div class="flex items-center justify-between">
                                                <a class="me-4 flex-none py-1.25 text-lg font-semibold focus:opacity-80 focus:outline-hidden"
                                                    href="#" aria-label="Brand">내비 바</a>
                                            </div>

                                            <div id="hs-scrollspy-example-heading"
                                                class="basis-full overflow-hidden transition-all duration-300"
                                                aria-labelledby="hs-scrollspy-example">
                                                <div data-hs-scrollspy="#scrollspy-example"
                                                    data-hs-scrollspy-scrollable-parent="#navbar-example2"
                                                    class="flex flex-wrap items-center justify-center [--scrollspy-offset:220] md:justify-end md:[--scrollspy-offset:70]">
                                                    <a class="text-default-700 hover:text-default-500 focus:text-primary hs-scrollspy-active:text-white hs-scrollspy-active:bg-primary active rounded px-4 py-2 text-sm leading-6 focus:outline-hidden"
                                                        href="#fat">@fat</a>
                                                    <a class="text-default-700 hover:text-default-500 focus:text-primary hs-scrollspy-active:text-white hs-scrollspy-active:bg-primary rounded px-4 py-2 text-sm leading-6 focus:outline-hidden"
                                                        href="#mdo">@mdo</a>

                                                    <div
                                                        class="hs-dropdown [--adaptive:none] [--strategy:static] lg:[--adaptive:adaptive] lg:[--placement:bottom-right] lg:[--strategy:fixed]">
                                                        <button id="hs-dropdown-scrollspy" type="button"
                                                            class="group hs-scrollspy-active:text-white hs-scrollspy-active:bg-primary text-default-700 hover:text-default-500 focus:text-primary inline-flex items-center justify-center gap-x-1 rounded px-4 py-2 text-sm leading-6 focus:outline-hidden"
                                                            aria-haspopup="menu" aria-expanded="false"
                                                            aria-label="Dropdown">
                                                            드롭다운 <i data-icon="tabler:chevron-down" class="iconify tabler--chevron-down"></i>
                                                        </button>

                                                        <div class="hs-dropdown-menu" role="menu"
                                                            aria-orientation="vertical"
                                                            aria-labelledby="hs-dropdown-scrollspy">
                                                            <a class="dropdown-item" href="#one">하나</a>
                                                            <a class="dropdown-item" href="#two">둘</a>
                                                            <hr class="dropdown-divider" />
                                                            <a class="dropdown-item" href="#three">셋</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </nav>
                                    </header>

                                    <div id="scrollspy-example" class="space-y-4">
                                        <h4 id="fat" class="text-default-700 mb-2 text-lg font-bold">@fat</h4>
                                        <p>
                                            레깅스 키타, 브런치 아이디 아트 파티 돌로르 라보레. 피치포크 yr 에님 lo-fi 비포 데이 솔드 아웃 퀴. 텀블러 팜투테이블
                                            바이시클 라이츠 왓에버. 애니메 케피예 칼레스 가디건. 벨릿 세이탄 mcsweeney's 포토 부스 3 울프 문 이루레. 코스비 스웨터
                                            로모 진 쇼츠, 윌리엄스버그 후디 미님 퀴 유 프라버블리 해븐트 허드 오브 뎀 엣 가디건 트러스트 펀드 쿨파 바이오디젤 웨스 앤더슨
                                            에스테틱. 니힐 타투드 아쿠사무스, 크레드 아이러니 바이오디젤 케피예 아티잔 울람코 콘세쿠앗.
                                        </p>
                                        <h4 id="mdo" class="text-default-700 mb-2 text-lg font-bold">@mdo</h4>
                                        <p>
                                            베니암 마르파 머스타치 스케이트보드, 아디피시킹 푸기아트 벨릿 피치포크 비어드. 프리건 비어드 알리콰 큐피다타 mcsweeney's
                                            베로. 큐피다타 포 로코 니시, 에아 헬베티카 눌라 칼레스. 타투드 코스비 스웨터 푸드 트럭, mcsweeney's 퀴 논 프리건
                                            바이닐. Lo-fi 웨스 앤더슨 +1 사토리얼. 칼레스 논 에스테틱 엑서시테이션 퀴 젠트리파이. 브루클린 아디피시킹 크래프트 비어 바이스
                                            키타 데세런트.
                                        </p>
                                        <h4 id="one" class="text-default-700 mb-2 text-lg font-bold">하나 (one)</h4>
                                        <p>
                                            오카에캇 코모도 알리콰 델렉투스. 팹 크래프트 비어 데세런트 스케이트보드 에아. 로모 바이시클 라이츠 아디피시킹 반 미, 벨릿 에아 순트
                                            넥스트 레벨 로카보어 싱글 오리진 커피 인 마그나 베니암. 하이 라이프 아이디 바이닐, 에코 파크 콘세쿠앗 퀴 알리큅 반 미 피치포크.
                                            베로 VHS 에스트 아디피시킹. 콘섹테투르 니시 DIY 미님 메신저 백. 크레드 엑스 인, 서스테이너블 델렉투스 콘섹테투르 패니 백
                                            아이폰.
                                        </p>
                                        <h4 id="two" class="text-default-700 mb-2 text-lg font-bold">둘 (two)</h4>
                                        <p>
                                            인 인시디던트 에코 파크, 오피시아 데세런트 mcsweeney's 프로이던트 마스터 클렌즈 썬더캣츠 사피엔테 베니암. 엑셉튜어 VHS
                                            엘리트, 프로이던트 쇼어디치 +1 바이오디젤 라보룸 크래프트 비어. 싱글 오리진 커피 웨이페어러 이루레 포 로코, 큐피다타 테리 리처드슨
                                            마스터 클렌즈. 아수멘다 유 프라버블리 해븐트 허드 오브 뎀 아트 파티 패니 백, 타투드 눌라 가디건 템포르 애드. 프로이던트 울프
                                            네스시운트 사토리얼 케피예 에우 반 미 서스테이너블. 엘리트 울프 볼룹타테, lo-fi 에아 포틀랜드 비포 데이 솔드 아웃 포 로코.
                                            로카보어 에님 노스트루드 mlkshk 브루클린 네스시운트.
                                        </p>
                                        <h4 id="three" class="text-default-700 mb-2 text-lg font-bold">셋 (three)</h4>
                                        <p>
                                            레깅스 키타, 브런치 아이디 아트 파티 돌로르 라보레. 피치포크 yr 에님 lo-fi 비포 데이 솔드 아웃 퀴. 텀블러 팜투테이블
                                            바이시클 라이츠 왓에버. 애니메 케피예 칼레스 가디건. 벨릿 세이탄 mcsweeney's 포토 부스 3 울프 문 이루레. 코스비 스웨터
                                            로모 진 쇼츠, 윌리엄스버그 후디 미님 퀴 유 프라버블리 해븐트 허드 오브 뎀 엣 가디건 트러스트 펀드 쿨파 바이오디젤 웨스 앤더슨
                                            에스테틱. 니힐 타투드 아쿠사무스, 크레드 아이러니 바이오디젤 케피예 아티잔 울람코 콘세쿠앗.
                                        </p>
                                        <p>
                                            키타 트위 블로그, 쿨파 메신저 백 마르파 왓에버 델렉투스 푸드 트럭. 사피엔테 신스 아이디 아수멘다. 로카보어 세드 헬베티카 클리셰
                                            아이러니, 썬더캣츠 유 프라버블리 해븐트 허드 오브 뎀 콘세쿠앗 후디 글루텐 프리 lo-fi 팹 알리큅. 라보레 엘리트 플레이스엣 비포
                                            데이 솔드 아웃, 테리 리처드슨 프로이던트 브런치 네스시운트 퀴 코스비 스웨터 파리아투르 케피예 우트 헬베티카 아티잔. 가디건 크래프트
                                            비어 세이탄 레디메이드 벨릿. VHS 챔브레이 라보리스 템포르 베니암. 애니메 몰릿 미님 코모도 울람코 썬더캣츠.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
                        </div>
                        <!-- end card-->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">중첩된 내비게이션 예제</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <div id="scrollspy-scrollable-parent-2" class="max-h-75 overflow-y-auto">
                                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-base">
                                        <div class="border-default-300 md:border-e md:pe-2 lg:pe-7.5">
                                            <ul class="sticky top-0" data-hs-scrollspy="#scrollspy-2"
                                                data-hs-scrollspy-scrollable-parent="#scrollspy-scrollable-parent-2">
                                                <li data-hs-scrollspy-group="">
                                                    <a href="#item-1"
                                                        class="focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white active mb-1 block rounded px-4 py-2 font-medium focus:outline-hidden">항목
                                                        1</a>
                                                    <ul>
                                                        <li class="ms-6">
                                                            <a href="#item-1-1"
                                                                class="group focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 text-sm leading-6 focus:outline-hidden">항목
                                                                1-1</a>
                                                        </li>
                                                        <li class="ms-6">
                                                            <a href="#item-1-2"
                                                                class="group focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 text-sm leading-6 focus:outline-hidden">항목
                                                                1-2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="#item-2"
                                                        class="focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 font-medium focus:outline-hidden">항목
                                                        2</a>
                                                </li>
                                                <li data-hs-scrollspy-group="">
                                                    <a href="#item-3"
                                                        class="focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 font-medium focus:outline-hidden">항목
                                                        3</a>
                                                    <ul>
                                                        <li class="ms-6">
                                                            <a href="#item-3-1"
                                                                class="group focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 text-sm leading-6 focus:outline-hidden">항목
                                                                3-1</a>
                                                        </li>
                                                        <li class="ms-6">
                                                            <a href="#item-3-2"
                                                                class="group focus:text-primary hs-scrollspy-active:bg-primary hs-scrollspy-active:text-white mb-1 block rounded px-4 py-2 text-sm leading-6 focus:outline-hidden">항목
                                                                3-2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="md:col-span-3 lg:col-span-5">
                                            <div id="scrollspy-2" class="space-y-4">
                                                <h4 id="item-1" class="text-default-700 mb-2 text-lg font-bold">항목 1
                                                </h4>
                                                <p>
                                                    엑스 콘세쿠앗 코모도 아디피시킹 엑서시테이션 아우테 엑셉튜어 오카에캇 울람코 두이스 알리콰 아이디 마그나 울람코 에우. 도
                                                    아우테 입숨 입숨 울람코 실룸 콘섹테투르 우트 엣 아우테 콘섹테투르 라보레. 푸기아트 라보룸 인시디던트 템포르 에우
                                                    콘세쿠앗 에님 돌로르 프로이던트. 퀴 라보룸 도 논 엑셉튜어 눌라 마그나 에이우스모드 콘섹테투르 인. 알리콰 엣 알리콰
                                                    오피시아 퀴 엣 인시디던트 볼룹타테 논 애니메 레프레헨데리트 아디피시킹 돌로르 우트 콘세쿠앗 데세런트 몰릿 돌로르. 알리큅
                                                    눌라 에님 베니암 논 푸기아트 아이디 큐피다타 눌라 엘리트 큐피다타 코모도 벨릿 우트 에이우스모드 큐피다타 엘리트 돌로르.
                                                </p>
                                                <h5 id="item-1-1" class="text-default-700 mb-2 text-sm font-bold">항목 1-1
                                                </h5>
                                                <p>
                                                    아멧 템포르 몰릿 알리큅 파리아투르 엑셉튜어 코모도 도 에아 실룸 코모도 로렘 엣 오카에캇 엘리트 퀴 엣. 알리큅 라보레
                                                    엑스 엑스 에세 볼룹타테 오카에캇 로렘 울람코 데세런트. 알리콰 실룸 엑셉튜어 이루레 콘세쿠앗 아이디 퀴 에아. 싯
                                                    프로이던트 울람코 아우테 마그나 파리아투르 노스트루드 라보레. 레프레헨데리트 알리콰 코모도 에이우스모드 알리큅 에스트 도
                                                    두이스 아멧 프로이던트 마그나 콘섹테투르 콘세쿠앗 에우 코모도 푸기아트 논 퀴. 에님 알리큅 엑서시테이션 울람코 아디피시킹
                                                    볼룹타테 엑셉튜어 미님 엑서시테이션 미님 미님 코모도 아디피시킹 엑서시테이션 오피시아 니시 아디피시킹. 애니메 아이디
                                                    두이스 퀴 콘세쿠앗 라보레 아디피시킹 신트 돌로르 엘리트 실룸 애니메 엣 푸기아트.
                                                </p>
                                                <h5 id="item-1-2" class="text-default-700 mb-2 text-sm font-bold">항목 1-2
                                                </h5>
                                                <p>
                                                    실룸 니시 데세런트 마그나 에이우스모드 퀴 에이우스모드 벨릿 볼룹타테 파리아투르 라보룸 순트 에님. 이루레 라보리스 몰릿
                                                    콘세쿠앗 인시디던트 신트 엣 쿨파 쿨파 인시디던트 아디피시킹 마그나 마그나 오카에캇. 눌라 입숨 실룸 에이우스모드 신트
                                                    엘리트 엑셉튜어 에아 라보레 에님 콘섹테투르 인 라보레 애니메. 프로이던트 울람코 입숨 에세 엘리트 우트 로렘 에이우스모드
                                                    돌로르 엣 에이우스모드. 애니메 오카에캇 눌라 인 논 콘세쿠앗 에이우스모드 벨릿 인시디던트.
                                                </p>
                                                <h4 id="item-2" class="text-default-700 mb-2 text-lg font-bold">항목 2
                                                </h4>
                                                <p>
                                                    퀴스 마그나 로렘 애니메 아멧 입숨 도 몰릿 싯 실룸 볼룹타테 엑스 눌라 템포르. 라보룸 콘세쿠앗 논 엘리트 에님
                                                    엑서시테이션 실룸 알리콰 콘세쿠앗 아이디 알리콰. 에세 엑스 콘섹테투르 몰릿 볼룹타테 에스트 인 두이스 라보리스 애드 싯
                                                    입숨 애니메 로렘. 인시디던트 베니암 벨릿 엘리트 엘리트 베니암 로렘 알리콰 퀴스 울람코 데세런트 싯 에님 엘리트 알리콰
                                                    에세 이루레. 라보룸 니시 싯 에스트 템포르 라보룸 몰릿 라보레 오피시아 라보룸 엑셉튜어 코모도 논 코모도 돌로르 엑셉튜어
                                                    코모도. 입숨 푸기아트 엑스 에스트 콘섹테투르 입숨 코모도 템포르 순트 인 프로이던트.
                                                </p>
                                                <h4 id="item-3" class="text-default-700 mb-2 text-lg font-bold">항목 3
                                                </h4>
                                                <p>
                                                    퀴스 애니메 싯 도 아멧 푸기아트 돌로르 벨릿 싯 에아 에아 도 레프레헨데리트 쿨파 두이스. 노스트루드 알리콰 입숨
                                                    푸기아트 미님 프로이던트 엑셉튜어 알리큅 쿨파 아우테 템포르 레프레헨데리트. 데세런트 템포르 몰릿 엘리트 엑스 파리아투르
                                                    돌로르 벨릿 푸기아트 몰릿 쿨파 이루레 울람코 에스트 엑스 울람코 엑셉튜어.
                                                </p>
                                                <h5 id="item-3-1" class="text-default-700 mb-2 text-sm font-bold">항목 3-1
                                                </h5>
                                                <p>
                                                    데세런트 퀴스 엘리트 로렘 에이우스모드 아멧 에님 에님 아멧 미님 로렘 프로이던트 노스트루드. 에아 아이디 돌로르 애니메
                                                    엑서시테이션 아우테 푸기아트 라보레 볼룹타테 실룸 도 라보리스 라보레. 엑스 벨릿 엑서시테이션 니시 에님 라보레
                                                    레프레헨데리트 라보레 노스트루드 우트 우트. 에세 오피시아 순트 두이스 알리큅 울람코 템포르 에이우스모드 데세런트 이루레
                                                    노스트루드 이루레. 울람코 프로이던트 베니암 라보리스 에아 콘섹테투르 마그나 순트 엑스 엑서시테이션 알리큅 미님 에님 쿨파
                                                    오카에캇 엑서시테이션. 에스트 템포르 엑셉튜어 알리큅 라보룸 콘세쿠앗 도 데세런트 라보룸 에세 에이우스모드 이루레
                                                    프로이던트 입숨 에세 퀴.
                                                </p>
                                                <h5 id="item-3-2" class="text-default-700 mb-2 text-sm font-bold">항목 3-2
                                                </h5>
                                                <p>
                                                    라보레 싯 쿨파 코모도 엘리트 아디피시킹 싯 알리큅 엘리트 프로이던트 볼룹타테 미님 몰릿 노스트루드 아우테 레프레헨데리트
                                                    도. 몰릿 엑셉튜어 에우 로렘 입숨 애니메 코모도 신트 라보레 로렘 인 엑서시테이션 벨릿 인시디던트. 오카에캇 콘섹테투르
                                                    니시 인 오카에캇 프로이던트 미님 에님 순트 레프레헨데리트 엑서시테이션 큐피다타 엣 도 오피시아. 알리큅 콘세쿠앗 애드
                                                    라보레 라보레 몰릿 우트 아멧. 싯 파리아투르 템포르 프로이던트 인 베니암 쿨파 알리콰 엑셉튜어 엘리트 마그나 푸기아트
                                                    에이우스모드 아멧 오피시아.
                                                </p>
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
                                <h4 class="card-title">내비게이션 예제 (목록 그룹)</h4>
                                <button class="btn size-6 rounded-full bg-light text-dark hover:text-primary">
                                    <i data-icon="tabler:chevron-up" class="iconify tabler--chevron-up text-base"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                <div id="navbar-example4" class="max-h-50 overflow-y-auto">
                                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-base">
                                        <div>
                                            <ul class="border-default-300 sticky top-0 rounded border"
                                                data-hs-scrollspy="#scrollspy-3"
                                                data-hs-scrollspy-scrollable-parent="#navbar-example4">
                                                <li data-hs-scrollspy-group="">
                                                    <a href="#list-item-1"
                                                        class="hs-scrollspy-active:bg-default-100 border-default-300 active block rounded border-b px-4.75 py-3 font-medium">항목
                                                        1</a>
                                                </li>

                                                <li>
                                                    <a href="#list-item-2"
                                                        class="hs-scrollspy-active:bg-default-100 border-default-300 block rounded border-b px-4.75 py-3 font-medium">항목
                                                        2</a>
                                                </li>

                                                <li data-hs-scrollspy-group="">
                                                    <a href="#list-item-3"
                                                        class="hs-scrollspy-active:bg-default-100 border-default-300 block rounded border-b px-4.75 py-3 font-medium">항목
                                                        3</a>
                                                </li>

                                                <li data-hs-scrollspy-group="">
                                                    <a href="#list-item-4"
                                                        class="hs-scrollspy-active:bg-default-100 block rounded px-4.75 py-3 font-medium">항목
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="md:col-span-3 lg:col-span-5">
                                            <div id="scrollspy-3" class="space-y-4">
                                                <div id="list-item-1">
                                                    <h4 class="text-default-700 mb-2 text-lg font-bold">항목 1</h4>
                                                    <p class="mb-4">
                                                        엑스 콘세쿠앗 코모도 아디피시킹 엑서시테이션 아우테 엑셉튜어 오카에캇 울람코 두이스 알리콰 아이디 마그나 울람코
                                                        에우. 도 아우테 입숨 입숨 울람코 실룸 콘섹테투르 우트 엣 아우테 콘섹테투르 라보레. 푸기아트 라보룸 인시디던트
                                                        템포르 에우 콘세쿠앗 에님 돌로르 프로이던트. 퀴 라보룸 도 논 엑셉튜어 눌라 마그나 에이우스모드 콘섹테투르 인.
                                                        알리콰 엣 알리콰 오피시아 퀴 엣 인시디던트 볼룹타테 논 애니메 레프레헨데리트 아디피시킹 돌로르 우트 콘세쿠앗
                                                        데세런트 몰릿 돌로르. 알리큅 눌라 에님 베니암 논 푸기아트 아이디 큐피다타 눌라 엘리트 큐피다타 코모도 벨릿 우트
                                                        에이우스모드 큐피다타 엘리트 돌로르.
                                                    </p>
                                                </div>

                                                <div id="list-item-2">
                                                    <h4 class="text-default-700 mb-2 text-lg font-bold">항목 2</h4>
                                                    <p class="mb-4">
                                                        퀴스 마그나 로렘 애니메 아멧 입숨 도 몰릿 싯 실룸 볼룹타테 엑스 눌라 템포르. 라보룸 콘세쿠앗 논 엘리트 에님
                                                        엑서시테이션 실룸 알리콰 콘세쿠앗 아이디 알리콰. 에세 엑스 콘섹테투르 몰릿 볼룹타테 에스트 인 두이스 라보리스
                                                        애드 싯 입숨 애니메 로렘. 인시디던트 베니암 벨릿 엘리트 엘리트 베니암 로렘 알리콰 퀴스 울람코 데세런트 싯 에님
                                                        엘리트 알리콰 에세 이루레. 라보룸 니시 싯 에스트 템포르 라보룸 몰릿 라보레 오피시아 라보룸 엑셉튜어 코모도 논
                                                        코모도 돌로르 엑셉튜어 코모도. 입숨 푸기아트 엑스 에스트 콘섹테투르 입숨 코모도 템포르 순트 인 프로이던트.
                                                    </p>
                                                </div>

                                                <div id="list-item-3">
                                                    <h4 class="text-default-700 mb-2 text-lg font-bold">항목 3</h4>
                                                    <p class="mb-4">
                                                        퀴스 애니메 싯 도 아멧 푸기아트 돌로르 벨릿 싯 에아 에아 도 레프레헨데리트 쿨파 두이스. 노스트루드 알리콰 입숨
                                                        푸기아트 미님 프로이던트 엑셉튜어 알리큅 쿨파 아우테 템포르 레프레헨데리트. 데세런트 템포르 몰릿 엘리트 엑스
                                                        파리아투르 돌로르 벨릿 푸기아트 몰릿 쿨파 이루레 울람코 에스트 엑스 울람코 엑셉튜어.
                                                    </p>
                                                </div>

                                                <div id="list-item-4">
                                                    <h4 class="text-default-700 mb-2 text-lg font-bold">항목 4</h4>
                                                    <p class="mb-4">
                                                        퀴스 애니메 싯 도 아멧 푸기아트 돌로르 벨릿 싯 에아 에아 도 레프레헨데리트 쿨파 두이스. 노스트루드 알리콰 입숨
                                                        푸기아트 미님 프로이던트 엑셉튜어 알리큅 쿨파 아우테 템포르 레프레헨데리트. 데세런트 템포르 몰릿 엘리트 엑스
                                                        파리아투르 돌로르 벨릿 푸기아트 몰릿 쿨파 이루레 울람코 에스트 엑스 울람코 엑셉튜어.
                                                    </p>
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
                </div>

<?php include 'layout/footer.php'; ?>