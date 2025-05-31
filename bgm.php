<?php
include_once('./_common.php');

if($action == "play") {
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGM Player</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

body {
            background: transparent;
            font-family: 'GowunDodum', 'Malgun Gothic', '맑은 고딕', sans-serif;
            overflow: hidden;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

.bgm-player {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: auto;
    height: auto;
}

.mini-item {
    list-style: none;
    position: relative;
    display: inline-block;
}

        /* 회전 텍스트 컨테이너 - 버튼보다 살짝 크게 */
.rotating-text-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100px;
    height: 100px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

        .mini-item:hover .rotating-text-container {
            opacity: 1;
        }

.rotating-text {
    position: absolute;
    width: 100%;
    height: 100%;
    animation: rotate 10s linear infinite;
}

.rotating-text span {
    position: absolute;
    left: 50%;
    top: 0;
    font-size: 8px;
    font-weight: bold;
    color: #33446b;
    transform-origin: 0 50px;
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
}

        .music_btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(145deg, #e0e5ec, #c7cdd8);
            box-shadow:
                4px 4px 8px rgba(163, 177, 198, 0.6),
                -4px -4px 8px rgba(255, 255, 255, 0.8);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
            z-index: 2;
        }

        .music_btn:hover {
            transform: translateY(-2px);
            box-shadow:
                8px 8px 16px rgba(163, 177, 198, 0.7),
                -8px -8px 16px rgba(255, 255, 255, 0.9);
        }

        .music_btn:active {
            box-shadow:
                inset 4px 4px 8px rgba(163, 177, 198, 0.6),
                inset -4px -4px 8px rgba(255, 255, 255, 0.8);
        }

        .music_btn::before {
            content: '';
            font-size: 20px;
            color: #33446b;
            transition: all 0.3s ease;
        }

        /* 재생 상태 아이콘 */
        .play::before {
            content: '▶';
            margin-left: 3px;
        }

        .play.playy::before {
            content: '⏸';
            margin-left: 0;
        }

        /* 메뉴 텍스트 숨김 */
        .menu-txt {
            display: none;
        }

        /* 재생 중 애니메이션 효과 */
        .playy {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow:
                    6px 6px 12px rgba(163, 177, 198, 0.6),
                    -6px -6px 12px rgba(255, 255, 255, 0.8),
                    0 0 0 0 rgba(51, 68, 107, 0.4);
            }
            50% {
                box-shadow:
                    6px 6px 12px rgba(163, 177, 198, 0.6),
                    -6px -6px 12px rgba(255, 255, 255, 0.8),
                    0 0 0 8px rgba(51, 68, 107, 0.1);
            }
            100% {
                box-shadow:
                    6px 6px 12px rgba(163, 177, 198, 0.6),
                    -6px -6px 12px rgba(255, 255, 255, 0.8),
                    0 0 0 0 rgba(51, 68, 107, 0);
            }
        }

        /* 숨겨진 YouTube iframe */
        #ytplayer {
            position: absolute;
            left: -9999px;
            top: -9999px;
            opacity: 0;
            pointer-events: none;
        }

        /* 반응형 */
        @media (max-width: 768px) {
            .music_btn {
                width: 50px;
                height: 50px;
            }

            .music_btn::before {
                font-size: 16px;
            }

            .menu-txt {
                font-size: 10px;
                bottom: -20px;
            }

            .rotating-text-container {
                width: 100px;
                height: 100px;
            }

            .rotating-text span {
                font-size: 10px;
                transform-origin: 0 50px;
            }
        }
    </style>
</head>
<body>
    <!-- 숨겨진 YouTube iframe -->
    <iframe id="ytplayer" type="text/html" width="1" height="1"
            src="https://www.youtube.com/embed?listType=playlist&list=<?=$config['cf_bgm']?>&autoplay=0&disablekb=1&loop=1&playsinline=1&rel=0&origin=<?=G5_URL?>&enablejsapi=1"
            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

    <!-- BGM 플레이어 -->
    <div class="bgm-player">
        <div class="mini-item">
            <!-- 회전 텍스트 -->
            <div class="rotating-text-container">
                <div class="rotating-text" id="rotatingText">
                    <!-- 텍스트는 JavaScript로 생성 -->
                </div>
            </div>

            <a href="#" class="play music_btn" id="bgmToggle" onclick="return fn_control_bgm()"></a>
            <div class="menu-txt onoff stop2"></div>
        </div>
    </div>

    <script>
        let player;
        let isPlaying = false;
        let isPlayerReady = false;

// 회전 텍스트 생성
function createRotatingText() {
    const text = "Iss guh day thoo avorneen slawn ";
    const rotatingTextElement = document.getElementById('rotatingText');

    // 기존 내용 초기화
    rotatingTextElement.innerHTML = '';

    for (let i = 0; i < text.length; i++) {
        const span = document.createElement('span');
        span.textContent = text[i];
        // 각 글자를 원형으로 균등하게 배치
        const angle = (i * 360) / text.length;
        span.style.transform = `rotate(${angle}deg)`;
        rotatingTextElement.appendChild(span);
    }
}

        // YouTube API 로드
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('ytplayer', {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
        }

        function onPlayerReady(event) {
            isPlayerReady = true;
            console.log('BGM Player Ready');
        }

        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                isPlaying = true;
                updateButtonState(true);
            } else if (event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED) {
                isPlaying = false;
                updateButtonState(false);
            }
        }

        function onPlayerError(event) {
            console.log('BGM Player Error:', event.data);
            isPlaying = false;
            updateButtonState(false);
        }

        function fn_control_bgm() {
            if (!player || !isPlayerReady) {
                console.log('Player not ready yet');
                return false;
            }

            if (isPlaying) {
                player.pauseVideo();
                updateButtonState(false);
            } else {
                player.playVideo();
                updateButtonState(true);
            }

            return false;
        }

        function updateButtonState(playing) {
            const playBtn = document.querySelector('.play');
            const onoffText = document.querySelector('.onoff');

            if (playing) {
                playBtn.classList.add('playy');
                onoffText.classList.add('play2');
                onoffText.classList.remove('stop2');
                isPlaying = true;
            } else {
                playBtn.classList.remove('playy');
                onoffText.classList.add('stop2');
                onoffText.classList.remove('play2');
                isPlaying = false;
            }
        }

        // 페이지 로드 시 회전 텍스트 생성
        document.addEventListener('DOMContentLoaded', function() {
            createRotatingText();
        });

        // YouTube API 스크립트 로드
        if (!window.YT) {
            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        } else {
            onYouTubeIframeAPIReady();
        }

        // 이퀄라이저 효과 (원본 코드와 호환)
        function set_equalizer() {
            // 필요시 이퀄라이저 효과 추가
        }

        // 드래그 방지
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
        });

        // 우클릭 방지
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>

<?php } ?>