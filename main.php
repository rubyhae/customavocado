<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>아보카도 에디션</title>
    <?php
    include_once('./_common.php');
    define('_MAIN_', true);

    if (defined('G5_THEME_PATH')) {
        require_once(G5_THEME_PATH . '/main.php');
        return;
    }
    include_once(G5_PATH . '/head.php');
    include_once(G5_PATH . "/intro.php");
    ?>

    <!-- 메인 페이지 전용 스타일 -->
    <style>
        /* 메인 페이지 전용 레이아웃 */
        #main_body {
            position: relative !important;
            z-index: 10 !important;
            min-height: 100vh !important;
            padding-top: 20px !important;
            background: transparent !important;
            backdrop-filter: none !important;
            margin-top: 0 !important;
        }

        /* 기존 요소들 완전 숨기기 */
        #main_side_box,
        #main_login_box,
        #main_banner_box,
        #guides-container,
        .guide-box,
        #main_copyright_box {
            display: none !important;
            visibility: hidden !important;
        }

        /* main.css 스타일 덮어쓰기 */
        #no_design_main {
            max-width: none !important;
            margin: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: calc(100vh - 20px) !important;
            padding: 60px 40px !important;
            grid-template-areas: none !important;
            grid-template-columns: none !important;
            grid-template-rows: none !important;
            gap: 0 !important;
        }

        /* 메인 컨텐츠 영역 - 모든 섹션 높이 강제 통일 */
        .main-content {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: row;
            gap: 0;
            align-items: stretch;
            justify-content: stretch;
            margin-top: 370px;
            height: 300px;
        }

        /* 로그인 섹션 */
        .login-section {
            flex: 0.7;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 0;
            padding: 15px 10px;
            height: 300px !important;
            min-height: 300px !important;
            max-height: 300px !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 8px;
            overflow: visible;
            position: relative;
            margin-right: 20px;
            box-sizing: border-box;
        }

        .login-section:before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            z-index: 1 !important;
            pointer-events: none !important;
        }

        .login-section>* {
            position: relative !important;
            z-index: 2 !important;
        }

        /* 중앙 그리드를 컨테이너로 복원하여 3개를 그룹화 */
        .center-grid {
            flex: 2.1;
            display: flex;
            flex-direction: row;
            gap: 0;
            height: 300px !important;
            min-height: 300px !important;
            max-height: 300px !important;
        }

        /* 마스토돈 섹션 */
        .mastodon-section {
            flex: 0.7;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 0;
            height: 300px !important;
            min-height: 300px !important;
            max-height: 300px !important;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #EDF0F9;
            font-size: 14px;
            letter-spacing: 1px;
            position: relative;
            margin-left: 20px;
            box-sizing: border-box;
        }

        .mastodon-section:before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            z-index: 1 !important;
            pointer-events: none !important;
        }

        .mastodon-section>* {
            position: relative !important;
            z-index: 2 !important;
        }

        /* 로그인 섹션 개선된 스타일 */
        .login-section {
            flex: 0.7;
            background: rgba(0, 0, 0, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
            padding: 20px 15px;
            height: 300px !important;
            min-height: 300px !important;
            max-height: 300px !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
            overflow: visible;
            position: relative;
            margin-right: 20px;
            box-sizing: border-box;
            backdrop-filter: blur(5px);
        }

        /* 로그인 폼 */
        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .login-input {
            width: 100%;
            height: 40px !important;
            font-size: 14px !important;
            padding: 8px 12px;
            border: 1px solid rgba(155, 163, 181, 0.3);
            background: rgba(155, 163, 181, 0.9) !important;
            color: #000 !important;
            border-radius: 6px;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .login-input::placeholder {
            color: rgba(0, 0, 0, 0.6);
        }

        .login-input:focus {
            outline: none;
            border-color: rgba(155, 163, 181, 0.6);
            background: rgba(155, 163, 181, 1);
        }

        .login-button {
            width: 100%;
            height: 42px !important;
            font-size: 16px !important;
            font-weight: bold;
            background: rgba(61, 66, 92, 0.9) !important;
            border: 1px solid rgba(61, 66, 92, 0.8);
            color: #fff !important;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .login-button:hover {
            background: rgba(61, 66, 92, 1) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .register-link {
            width: 100%;
            height: 38px !important;
            font-size: 14px !important;
            font-weight: bold;
            color: #fff !important;
            text-decoration: none;
            padding: 0;
            background: rgba(61, 66, 92, 0.7) !important;
            border: 1px solid rgba(61, 66, 92, 0.6);
            border-radius: 6px;
            margin-top: 0;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .register-link:hover {
            background: rgba(61, 66, 92, 0.9) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            color: #fff !important;
            text-decoration: none;
        }

        /* 로그인된 사용자 정보 표시 */
        .user-info {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            margin-bottom: 5px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .user-welcome {
            color: #EDF0F9;
            font-size: 14px;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(237, 240, 249, 0.5);
            text-align: center;
            margin-bottom: 5px;
        }

        .user-actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: center;
            width: 100%;
        }

        /* 메뉴 섹션들 - 중간 3개는 갭 없이, 높이 강제 통일 */
        .menu-section {
            flex: 1;
            background: rgba(0, 0, 0, 0.7) !important;
            border: none !important;
            border-radius: 0 !important;
            height: 300px !important;
            min-height: 300px !important;
            max-height: 300px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            text-decoration: none !important;
            color: inherit !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            padding: 20px !important;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-sizing: border-box !important;
        }

        .menu-section:last-child {
            border-right: none;
        }

        .menu-section:before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-image: url('<?= G5_URL ?>/img/frame1.png') !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            z-index: 1 !important;
            pointer-events: none !important;
        }

        .menu-section>* {
            position: relative !important;
            z-index: 2 !important;
        }

        .menu-section:hover {
            background: rgba(0, 0, 0, 0.8) !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4) !important;
        }

        .menu-frame1,
        .menu-frame2 {
            background-image: none !important;
        }

        .menu-title-en {
            font-size: 16px;
            color: #9BA3B5;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .menu-title-ko {
            font-size: 14px;
            color: #9BA3B5;
            letter-spacing: 1px;
        }

        /* 마스토돈 섹션 */
        .mastodon-section {
            grid-column: 3;
            grid-row: 1;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 0;
            height: 300px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #EDF0F9;
            font-size: 14px;
            letter-spacing: 1px;
        }

        /* 반응형 */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr 1fr 1fr;
                grid-template-rows: auto auto;
                gap: 15px;
                margin-top: 320px;
            }

            .center-grid {
                grid-column: 1 / 4;
                grid-row: 1;
                margin-bottom: 15px;
            }

            .login-section {
                grid-column: 1;
                grid-row: 2;
            }

            .mastodon-section {
                grid-column: 3;
                grid-row: 2;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                padding: 20px;
                margin-top: 280px;
            }

            .center-grid {
                grid-column: 1;
                grid-template-columns: 1fr;
                gap: 10px;
                height: auto;
            }

            .login-section,
            .menu-section,
            .mastodon-section {
                grid-column: 1;
                height: 200px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div id="main_body">
        <?
        $main_content = get_site_content('site_main');
        if ($main_content) {
            echo $main_content;
        } else {
            ?>
            <div id="no_design_main">
                <div class="main-content">
                    <!-- 로그인 정보창 -->
                    <!-- 로그인 정보창 -->
                    <div class="login-section">
                        <?php if ($is_member) { ?>
                            <div class="user-info">
                                <?php
                                // avo_character 테이블에서 섬네일 가져오기
                                $char_sql = "SELECT ch_thumb FROM avo_character WHERE mb_id = '{$member['mb_id']}' ORDER BY ch_id DESC LIMIT 1";
                                $char_result = sql_fetch($char_sql);
                                $thumb_url = $char_result['ch_thumb'];

                                if ($thumb_url && $thumb_url != '') { ?>
                                    <a href="<?= G5_URL ?>/mypage">
                                        <img src="<?= $thumb_url ?>" alt="프로필" class="user-avatar">
                                    </a>
                                <?php } else { ?>
                                    <a href="<?= G5_URL ?>/mypage">
                                        <div class="user-avatar"
                                            style="background: rgba(61, 66, 92, 0.6); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; border: none;">
                                            👤</div>
                                    </a>
                                <?php } ?>
                                <div class="user-welcome"><?= $member['mb_nick'] ?>님</div>
                                <div class="user-actions">
                                    <!-- 강제로 모든 버튼 표시 -->
                                    <div
                                        style="background: #ABB5C9; color: #fff; padding: 4px 8px; margin: 2px 0; border-radius: 4px; text-align: center; width: 40%; font-size: 12px;">
                                        <a href="<?= G5_BBS_URL ?>/memo.php" style="color: #fff; text-decoration: none;">✉ 쪽지</a>
                                    </div>
                                    <?php if ($is_admin) { ?>
                                        <div
                                            style="background: #ABB5C9; color: #fff; padding: 4px 8px; margin: 2px 0; border-radius: 4px; text-align: center; width: 40%; font-size: 12px;">
                                            <a href="<?= G5_ADMIN_URL ?>" target="_blank"
                                                style="color: #fff; text-decoration: none;">⚙ 관리자</a>
                                        </div>
                                    <?php } ?>
                                    <div
                                        style="background: #B43C3C; color: #fff; padding: 4px 8px; margin: 2px 0; border-radius: 4px; text-align: center; width: 40%; font-size: 12px;">
                                        <a href="<?= G5_BBS_URL ?>/logout.php" style="color: #fff; text-decoration: none;">⏻
                                            로그아웃</a>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <form name="flogin" action="<?= G5_BBS_URL ?>/login_check.php" onsubmit="return flogin_submit(this);"
                                method="post" class="login-form">
                                <input type="text" name="mb_id" class="login-input" placeholder="아이디" required>
                                <input type="password" name="mb_password" class="login-input" placeholder="비밀번호" required>
                                <input type="submit" value="로그인" class="login-button">
                                <a href="<?= G5_BBS_URL ?>/register.php" class="register-link">회원가입</a>
                            </form>
                        <?php } ?>
                    </div>

                    <!-- 중앙 그리드 (신청서양식, 마이룸, 주식) -->
                    <div class="center-grid">
                        <!-- 신청서 양식 -->
                        <a href="#" class="menu-section menu-frame1">
                            <div class="menu-title-en">Application</div>
                            <div class="menu-title-ko">신 청 서 양 식</div>
                        </a>

                        <!-- 마이룸 -->
                        <a href="<?= G5_URL ?>/bbs/content.php?co_id=room" class="menu-section menu-frame2">
                            <div class="menu-title-en">My Room</div>
                            <div class="menu-title-ko">마 이 룸</div>
                        </a>

                        <!-- 주식 -->
                        <a href="<?= G5_URL ?>/item_mix" class="menu-section menu-frame1">
                            <div class="menu-title-en">Item Mix</div>
                            <div class="menu-title-ko">조     합</div>
                        </a>
                    </div>

                    <!-- 마스토돈 -->
                    <div class="mastodon-section">마스토돈</div>
                </div>
            </div>
        <?php } ?>
    </div>

    <script>
        // 로그인 폼 검증
        function flogin_submit(f) {
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
        }

        // set_equalizer 오류 방지
        window.set_equalizer = function () { };

        window.addEventListener('error', function (e) {
            if (e.message && e.message.includes('set_equalizer')) {
                e.preventDefault();
                return false;
            }
        });
    </script>

    <?
    include_once(G5_PATH . '/tail.php');
    ?>
</body>

</html>