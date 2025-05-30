<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

if (defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH . '/head.php');
    return;
}

include_once(G5_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

/*********** Logo Data ************/
$logo = get_logo('pc');
$m_logo = get_logo('mo');

$logo_data = "";
if ($logo)
    $logo_data .= "<img src='" . $logo . "' ";
if ($m_logo)
    $logo_data .= "class='only-pc' /><img src='" . $m_logo . "' class='not-pc'";
if ($logo_data)
    $logo_data .= " />";

// 메인 페이지 체크
$is_main_page = (defined('_MAIN_') && _MAIN_ === true);
/*********************************/
?>

<script>
    // JavaScript 오류 방지를 위한 전역 스크립트
    (function () {
        window.set_equalizer = function () { return false; };
        window.flogin_submit = function (f) {
            if (!f.mb_id.value) {
                alert('아이디를 입력해 주세요.');
                f.mb_id.focus();
                return false;
            }
            if (!f.mb_password.value) {
                alert('비밀번호를 입력해 주세요.');
                f.mb_password.focus();
                return false;
            }
            return true;
        };

        if (typeof browser === 'undefined') {
            window.browser = {
                runtime: {
                    sendMessage: function () { return Promise.resolve(); },
                    onMessage: { addListener: function () { } }
                }
            };
        }

        window.addEventListener('error', function (e) {
            if (e.message && (
                e.message.includes('set_equalizer') ||
                e.message.includes('Unexpected token') ||
                e.message.includes('browser is not defined')
            )) {
                e.preventDefault();
                return false;
            }
        });

        window.addEventListener('unhandledrejection', function (e) {
            if (e.reason && e.reason.message && (
                e.reason.message.includes('message channel closed') ||
                e.reason.message.includes('browser is not defined')
            )) {
                e.preventDefault();
                return false;
            }
        });
    })();
</script>

<!-- 공통 스타일 -->
<style>
    @font-face {
        font-family: 'GowunDodum';
        src: url('../css/fonts/GowunDodum-Regular.ttf');
        font-weight: normal;
        font-style: normal;
    }

    body {
        background: linear-gradient(135deg, #1a4a4a 0%, #0d2d2d 30%, #1f3f3f 70%, #0a1f1f 100%) !important;
        color: #f0f5f3 !important;
        font-family: 'GowunDodum', 'Malgun Gothic', '맑은 고딕', 'Apple SD Gothic Neo', sans-serif !important;
        min-height: 100vh !important;
        position: relative !important;
        overflow-x: hidden !important;
    }

    /* 기존 헤더 숨기기 */
    #header,
    #gnb_wrapper,
    #gnb_control_box,
    .mini-menu,
    .mob_open {
        display: none !important;
    }

    /* 패럴랙스 배경 */
    .parallax-background {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100vh !important;
        z-index: -1 !important;
        overflow: hidden !important;
        pointer-events: none !important;
    }

    .parallax-layer {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 120vh !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        will-change: transform !important;
    }

    .layer-1 {
        background-image: url('<?= G5_URL ?>/img/layer1.png') !important;
        opacity: 0.8 !important;
    }

    .layer-2 {
        background-image: url('<?= G5_URL ?>/img/layer2.png') !important;
        opacity: 0.7 !important;
    }

    .layer-3 {
        background-image: url('<?= G5_URL ?>/img/layer3.png') !important;
        opacity: 0.6 !important;
    }

    .layer-4 {
        background-image: url('<?= G5_URL ?>/img/layer4.png') !important;
        opacity: 0.5 !important;
    }

    .layer-5 {
        background-image: url('<?= G5_URL ?>/img/layer5.png') !important;
        opacity: 0.4 !important;
    }

    .layer-6 {
        background-image: url('<?= G5_URL ?>/img/layer6.png') !important;
        opacity: 0.3 !important;
    }

    .layer-7 {
        background-image: url('<?= G5_URL ?>/img/layer7.png') !important;
        opacity: 0.2 !important;
    }

    .layer-8 {
        background-image: url('<?= G5_URL ?>/img/layer8.png') !important;
        opacity: 0.15 !important;
    }

    .layer-9 {
        background-image: url('<?= G5_URL ?>/img/layer9.png') !important;
        opacity: 0.1 !important;
    }

    /* 마법의 파티클들 */
    .magic-particles {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: -1;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 3px;
        height: 3px;
        background: #7ba05b;
        border-radius: 50%;
        opacity: 0;
        animation: magicFloat 8s infinite ease-in-out;
        box-shadow: 0 0 6px #7ba05b;
    }

    .particle:nth-child(2n) {
        background: #5d9cdb;
        box-shadow: 0 0 6px #5d9cdb;
        animation-duration: 10s;
    }

    .particle:nth-child(3n) {
        background: #d4af37;
        box-shadow: 0 0 6px #d4af37;
        animation-duration: 12s;
    }

    .particle:nth-child(4n) {
        background: #9370db;
        box-shadow: 0 0 6px #9370db;
        animation-duration: 6s;
    }

    @keyframes magicFloat {
        0% {
            transform: translateY(100vh) translateX(0) scale(0);
            opacity: 0;
        }

        20% {
            opacity: 1;
            transform: translateY(80vh) translateX(10px) scale(1);
        }

        50% {
            opacity: 0.8;
            transform: translateY(50vh) translateX(-20px) scale(1.2);
        }

        80% {
            opacity: 0.6;
            transform: translateY(20vh) translateX(15px) scale(0.8);
        }

        100% {
            transform: translateY(-10vh) translateX(-5px) scale(0);
            opacity: 0;
        }
    }

    /* 대형 로고 */
    .large-logo {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1;
        text-align: center;
        width: 100%;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .large-logo:hover {
        transform: translateX(-50%) scale(1.02);
    }

    .large-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .large-logo-text {
        font-size: 56px;
        color: #d4af37;
        letter-spacing: 14px;
        text-transform: uppercase;
        display: inline-block;
        text-shadow: 0 0 20px rgba(212, 175, 55, 0.6);
    }

    /* 상단 네비게이션 */
    .top-navigation {
        position: fixed;
        top: 290px;
        left: 0;
        width: 100%;
        height: 80px;
        z-index: 50;
        background: transparent;
    }

    .nav-container {
        height: 100%;
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .nav-left,
    .nav-right {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .nav-item {
        text-decoration: none;
        color: #9BA3B5;
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }

    .nav-item .nav-en {
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .nav-item .nav-ko {
        font-size: 10px;
        font-weight: normal;
        letter-spacing: 1px;
    }

    .nav-item:hover {
        color: #C7CEE9;
        text-shadow: 0 0 10px rgba(199, 206, 233, 0.8);
    }

    .nav-logo {
        width: 80px;
        height: 80px;
        background: transparent;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        overflow: hidden;
    }

    .nav-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .nav-logo-text {
        color: #d4af37;
        font-size: 12px;
        font-weight: bold;
    }

    <?php if ($is_main_page) { ?>
        /* 메인 페이지 전용 스타일 */
        #main_body {
            position: relative !important;
            z-index: 10 !important;
            min-height: 100vh !important;
            padding-top: 20px !important;
            background: transparent !important;
        }

        #no_design_main {
            max-width: none !important;
            margin: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: calc(100vh - 20px) !important;
            padding: 60px 40px !important;
        }

    <?php } else { ?>

        body {
            padding-top: 370px !important;
        }

        /* 서브페이지 컨테이너 - 일반 페이지용 */
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) {
            background: rgba(0, 0, 0, 0.75) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 12px !important;
            padding: 25px !important;
            margin: 15px auto !important;
            max-width: 800px !important;
            position: relative !important;
            z-index: 10 !important;
        }

        /* 텍스트 색상 개선 - 일반 페이지용 */
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout),
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) * {
            color: #f0f5f3 !important;
        }

        /* 입력 요소는 검은 배경 유지 - 일반 페이지용 */
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) input,
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) textarea,
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) select {
            background: rgba(255, 255, 255, 0.95) !important;
            color: #000 !important;
        }

        /* 링크 색상 - 일반 페이지용 */
        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) a {
            color: #95c070 !important;
        }

        #body>.fix-layout:first-child:not(.mypageWrap .fix-layout) a:hover {
            color: #7ba05b !important;
        }

    <?php } ?>

    /* 반응형 */
    @media (max-width: 768px) {
        .large-logo-text {
            font-size: 32px;
            letter-spacing: 8px;
        }

        .top-navigation {
            top: 200px;
            height: 40px;
        }

        <?php if ($is_main_page) { ?>
            .main-content {
                flex-direction: column;
                padding: 20px;
                margin-top: 280px;
                height: auto;
            }

            .center-grid {
                flex-direction: column;
                gap: 10px;
                height: auto;
            }

            .login-section,
            .menu-section,
            .mastodon-section {
                height: 200px;
                margin-bottom: 10px;
            }

        <?php } else { ?>
            body {
                padding-top: 280px !important;
            }

        <?php } ?>
    }

#load_log_board {
    max-width: 1000px; /* 적당한 넓이 */
    width: 90%; /* 작은 화면에서 유연성 */
    margin: 15px auto; /* 중앙 정렬 */
    padding: 20px;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    border-radius: 12px;
}

/* shop_page 영역 넓이 제한 */
#shop_page {
    max-width: 1000px; /* 원하는 넓이로 조정 */
    width: 90%; /* 작은 화면에서 유연성 */
    margin: 0 auto; /* 중앙 정렬 */
    padding: 20px;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    border-radius: 12px;
    position: relative;
    z-index: 10;
}

/* shop_page 내부 요소들 조정 */
#shop_npc {
    text-align: center;
    margin-bottom: 20px;
}

#shop_npc img {
    max-width: 200px; /* NPC 이미지 크기 조정 */
    height: auto;
}

#item_info {
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

#item_list_box {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 15px;
}

/* 카테고리 버튼들 */
#shop_cate ul {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}

#shop_cate li {
    list-style: none;
}

/* 아이템 리스트 그리드 조정 */
#shop_item_list ul {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    padding: 0;
    margin: 0;
}

#shop_item_list li {
    list-style: none;
    text-align: center;
}

#shop_item_list li a {
    display: block;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    text-decoration: none;
    color: #f0f5f3;
    transition: all 0.3s ease;
}

#shop_item_list li a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

#shop_item_list img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 8px;
}

#shop_item_list span {
    display: block;
    font-size: 12px;
    word-break: keep-all;
}

/* 페이징 */
#shop_paging {
    text-align: center;
    margin-top: 20px;
}

/* 반응형 처리 */
@media (max-width: 768px) {
    #shop_page {
        max-width: none;
        width: 95%;
        margin: 10px auto;
        padding: 15px;
    }

    #shop_item_list ul {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 10px;
    }

    #shop_cate ul {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<!-- 패럴랙스 배경 -->
<div class="parallax-background">
    <div class="parallax-layer layer-1" data-speed="0.1"></div>
    <div class="parallax-layer layer-2" data-speed="0.2"></div>
    <div class="parallax-layer layer-3" data-speed="0.3"></div>
    <div class="parallax-layer layer-4" data-speed="0.4"></div>
    <div class="parallax-layer layer-5" data-speed="0.5"></div>
    <div class="parallax-layer layer-6" data-speed="0.6"></div>
    <div class="parallax-layer layer-7" data-speed="0.7"></div>
    <div class="parallax-layer layer-8" data-speed="0.8"></div>
    <div class="parallax-layer layer-9" data-speed="0.9"></div>
</div>

<!-- 마법의 파티클들 -->
<div class="magic-particles">
    <div class="particle" style="left: 5%; animation-delay: 0s;"></div>
    <div class="particle" style="left: 15%; animation-delay: 1s;"></div>
    <div class="particle" style="left: 25%; animation-delay: 2s;"></div>
    <div class="particle" style="left: 35%; animation-delay: 0.5s;"></div>
    <div class="particle" style="left: 45%; animation-delay: 1.5s;"></div>
    <div class="particle" style="left: 55%; animation-delay: 2.5s;"></div>
    <div class="particle" style="left: 65%; animation-delay: 3s;"></div>
    <div class="particle" style="left: 75%; animation-delay: 0.3s;"></div>
    <div class="particle" style="left: 85%; animation-delay: 1.8s;"></div>
    <div class="particle" style="left: 95%; animation-delay: 2.8s;"></div>
    <div class="particle" style="left: 10%; animation-delay: 3.5s;"></div>
    <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
    <div class="particle" style="left: 50%; animation-delay: 4.5s;"></div>
    <div class="particle" style="left: 70%; animation-delay: 5s;"></div>
    <div class="particle" style="left: 90%; animation-delay: 5.5s;"></div>
</div>

<!-- 대형 로고 -->
<a href="<?= G5_URL ?>/" class="large-logo">
    <img src="<?= G5_URL ?>/img/large_logo.png" alt="Large Logo"
        onerror="this.style.display='none'; this.parentNode.querySelector('.large-logo-text').style.display='block';" />
    <div class="large-logo-text" style="display: none;">LARGE LOGO</div>
</a>

<!-- 상단 네비게이션 -->
<nav class="top-navigation">
    <div class="nav-container">
        <div class="nav-left">
            <a href="<?= G5_URL ?>/bbs/content.php?co_id=notice" class="nav-item">
                <span class="nav-en">NOTICE</span>
                <span class="nav-ko">공 지 사 항</span>
            </a>
            <a href="<?= G5_URL ?>/bbs/board.php?bo_table=story" class="nav-item">
                <span class="nav-en">STORY</span>
                <span class="nav-ko">스 토 리</span>
            </a>
            <a href="<?= G5_URL ?>/bbs/content.php?co_id=system" class="nav-item">
                <span class="nav-en">SYSTEM</span>
                <span class="nav-ko">시 스 템</span>
            </a>
        </div>

        <a href="<?= G5_URL ?>/" class="nav-logo">
            <?php
            $logo_sql = "SELECT cs_value FROM avo_css_config WHERE cs_name = 'logo'";
            $logo_result = sql_fetch($logo_sql);
            $logo_url = $logo_result['cs_value'];

            if ($logo_url && $logo_url != '') { ?>
                <img src="<?= $logo_url ?>" alt="로고" />
            <?php } else { ?>
                <span class="nav-logo-text">LOGO</span>
            <?php } ?>
        </a>

        <div class="nav-right">
            <a href="<?= G5_URL ?>/member" class="nav-item">
                <span class="nav-en">MEMBER</span>
                <span class="nav-ko">멤 버</span>
            </a>
            <a href="<?= G5_URL ?>/bbs/board.php?bo_table=search" class="nav-item">
                <span class="nav-en">SEARCH</span>
                <span class="nav-ko">조 사 게 시 판</span>
            </a>
            <a href="<?= G5_URL ?>/shop" class="nav-item">
                <span class="nav-en">SHOP</span>
                <span class="nav-ko">상 점</span>
            </a>
            <!-- 테스트용 임시링크 -->
            <a href="<?= G5_URL ?>/item_mix" class="nav-item">
                <span class="nav-en">TEST</span>
                <span class="nav-ko">테스트중</span>
            </a>
        </div>
    </div>
</nav>

<script>
    // 공통 JavaScript
    document.addEventListener('DOMContentLoaded', function () {
        // 스크롤 강제 활성화
        function enableScroll() {
            document.body.style.overflow = 'auto';
            document.body.style.overflowX = 'hidden';
            document.body.style.overflowY = 'auto';
            document.body.style.minHeight = '150vh';
        }

        // head.php의 JavaScript 부분에서 패럴랙스 함수를 이것으로 교체하세요

        // 패럴랙스 효과 (수정된 버전)
        function updateParallax() {
            const scrollTop = window.pageYOffset;
            const parallaxLayers = document.querySelectorAll('.parallax-layer');

            // 메인 컨텐츠가 있는지 확인
            const mainContent = document.querySelector('.main-content');

            parallaxLayers.forEach(function (layer) {
                const speed = parseFloat(layer.dataset.speed);
                let yPos = -(scrollTop * speed);

                // 메인 컨텐츠 영역에서는 패럴랙스 효과 제한
                if (mainContent && scrollTop > 200) {
                    yPos = Math.max(yPos, -100);
                }

                layer.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
        }

        // 마법 파티클 생성
        function createMagicParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 3 + 's';
            particle.style.animationDuration = (6 + Math.random() * 6) + 's';

            const colors = ['#7ba05b', '#5d9cdb', '#d4af37', '#9370db'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            particle.style.background = color;
            particle.style.boxShadow = `0 0 6px ${color}`;

            const particlesContainer = document.querySelector('.magic-particles');
            if (particlesContainer) {
                particlesContainer.appendChild(particle);
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                }, 15000);
            }
        }

        // 초기화
        enableScroll();
        updateParallax();

        // 마법 파티클 생성 시작
        setInterval(createMagicParticle, 2000);
    });
</script>