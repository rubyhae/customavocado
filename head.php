<section id="body">
    <div class="fix-layout"><?php
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
        color: #f0f5f3 !important;
        font-family: 'GowunDodum', 'Malgun Gothic', '맑은 고딕', 'Apple SD Gothic Neo', sans-serif !important;
        min-height: 100vh !important;
        position: relative !important;
        overflow-x: hidden !important;
    }

    /* 마법의 파티클들 */
    .magic-particles {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: -9;
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
        pointer-events: none;
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

    /* 메인 페이지 대형 로고 - 메인 페이지에서만 표시 */
    <?php if ($is_main_page) { ?>
    .large-logo {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1105;
        text-align: center;
        width: 100%;
        height: 340px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        pointer-events: auto;
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

    /* 메인 페이지 상단 네비게이션 */
    .top-navigation {
        position: fixed;
        top: 380px;
        left: 0;
        width: 100%;
        height: 80px;
        z-index: 200;
        background: transparent;
        pointer-events: none;
    }

    <?php } else { ?>

    /* 서브 페이지 - 대형 로고 숨김 */
    .large-logo {
        display: none !important;
    }

    /* 서브 페이지 상단 네비게이션 - 아이콘들 위에 위치 */
    .top-navigation {
        position: fixed;
        top: 20px;
        left: 0;
        width: 100%;
        height: 80px;
        z-index: 200;
        background: transparent;
        pointer-events: none;
    }

    <?php } ?>

    .nav-container {
        height: 100%;
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        pointer-events: auto;
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
            z-index: 1000 !important;
            min-height: 100vh !important;
            padding-top: 120px !important; /* 네비게이션 공간만 확보 */
            background: transparent !important;
            pointer-events: auto !important;
        }

        #no_design_main {
            max-width: none !important;
            margin: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: calc(100vh - 120px) !important;
            padding: 60px 40px !important;
            position: relative !important;
            z-index: 1001 !important;
            pointer-events: auto !important;
        }

        /* 메인 페이지의 모든 요소들이 클릭 가능하도록 */
        #main_body *,
        #no_design_main *,
        .main-content *,
        .login-section *,
        .center-grid *,
        .menu-section *,
        .mastodon-section * {
            pointer-events: auto !important;
            position: relative !important;
            z-index: inherit !important;
        }

        /* 메인 컨텐츠 영역 우선순위 강화 */
        .main-content {
            position: relative !important;
            z-index: 1002 !important;
            pointer-events: auto !important;
        }

    <?php } else { ?>

        body {
            padding-top: 120px !important; /* 서브페이지도 네비게이션 공간만 확보 */
        }

    <?php } ?>

    /* 반응형 */
    @media (max-width: 768px) {
        <?php if ($is_main_page) { ?>
        .top-navigation {
            top: 10px;
            height: 60px;
        }
        <?php } else { ?>
        .top-navigation {
            top: 10px;
            height: 60px;
        }
        <?php } ?>

        .nav-container {
            padding: 0 10px;
        }

        .nav-left,
        .nav-right {
            gap: 15px;
        }

        .nav-item .nav-en {
            font-size: 14px;
        }

        .nav-item .nav-ko {
            font-size: 9px;
        }

        .nav-logo {
            width: 60px;
            height: 60px;
        }

        <?php if ($is_main_page) { ?>
            #main_body {
                padding-top: 20px !important;
            }

            #no_design_main {
                min-height: calc(100vh - 20px) !important;
                padding: 60px 40px !important;
            }

        <?php } else { ?>
            body {
                padding-top: 80px !important;
            }

        <?php } ?>
    }

/* 필요한 부분만 간단히 */
    #load_log_board,
    #shop_page {
        max-width: 1000px;
        width: 90%;
        margin: 15px auto;
        padding: 20px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        position: relative;
        z-index: 10;
    }

    /* fix-layout 컨테이너 - 모든 페이지에 마진 적용 */
    .fix-layout {
        max-width: 1000px;
        width: 90%;
        margin: 0 auto;
        padding: 20px;
        position: relative;
        z-index: 10;
    }

    /* 캐릭터 프로필 페이지 전용 스타일 */
    #character_profile {
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 30px;
        margin: 20px 0;
    }

    /* 전신 이미지 크기 조정 */
    #character_body img {
        max-width: 300px;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* 캐릭터 이미지 영역 가운데 정렬 */
    .visual-area {
        text-align: center;
        margin: 20px 0;
    }

    #character_body,
    #character_head {
        text-align: center;
        margin: 10px 0;
    }

    /* 반응형 디자인 */
    @media (max-width: 768px) {
        .fix-layout {
            width: 95%;
            padding: 15px;
        }

        #character_profile {
            padding: 20px;
            margin: 10px 0;
        }

        #character_body img {
            max-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .fix-layout {
            width: 98%;
            padding: 10px;
        }

        #character_profile {
            padding: 15px;
        }

        #character_body img {
            max-width: 200px;
        }
    }

/* BGM 플레이어 컨테이너 - 회전 텍스트까지 딱 맞게 */
#bgm-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 120px;
    height: 120px;
    z-index: 999;
    border-radius: 50%;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
}

#bgm-frame {
    width: 100%;
    height: 100%;
    border: none;
    background: transparent;
}

/* 모바일에서 BGM 플레이어 크기 조정 */
@media (max-width: 768px) {
    #bgm-container {
        width: 85px;
        height: 85px;
        bottom: 15px;
        right: 15px;
    }
}

@media (max-width: 480px) {
    #bgm-container {
        width: 75px;
        height: 75px;
        bottom: 10px;
        right: 10px;
    }
}
</style>

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

<!-- 메인 페이지에서만 대형 로고 표시 -->
<?php if ($is_main_page) { ?>
<!-- 대형 로고 -->
<a href="<?= G5_URL ?>/" class="large-logo">
    <img src="<?= G5_URL ?>/img/large_logo.png" alt="아보카도 에디션 로고" />
</a>
<?php } ?>

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
        </div>
    </div>
</nav>

<!-- BGM 플레이어 -->
<?php if ($config['cf_bgm'] && $config['cf_bgm'] != '') { ?>
<div id="bgm-container">
    <iframe id="bgm-frame" src="<?= G5_URL ?>/bgm.php?action=play" allowfullscreen></iframe>
</div>
<?php } ?>

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

        // 마법 파티클 생성 시작
        setInterval(createMagicParticle, 2000);
    });
</script>