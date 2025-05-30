<?
    include_once('./_common.php');
    if($is_member & !$config['cf_open']) {
       goto_url(G5_URL.'/main.php');
    }
    if(defined('G5_THEME_PATH')) {
       require_once(G5_THEME_PATH.'/enter.php');
       return;
    }

    /*********** Logo Data ************/
    $logo = get_logo('pc');
    $logo_data = "";
    if($logo)     $logo_data .= "<img src='".$logo."' ";
    if($m_logo)       $logo_data .= "class='only-pc' /><img src='".$m_logo."' class='not-pc'";
    if($logo_data) $logo_data.= " />";
    /*********************************/
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="title" content="<?=$g5['title']?>">
    <meta name="keywords" content="<?=$config['cf_site_descript']?>">
    <meta name="description" content="<?=$config['cf_site_descript']?>">

    <meta property="og:title" content="<?=$g5['title']?>">
    <meta property="og:description" content="<?=$config['cf_site_descript']?>">
    <meta property="og:url" content="<?=G5_URL?>">

    <title><?=$g5['title']?></title>

    <link rel="shortcut icon" href="<?=$config['cf_favicon']?>">
    <link rel="icon" href="<?=$config['cf_favicon']?>">

    <style>
        * {
            box-sizing: content-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            margin: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            position: relative;
            cursor: none;
        }

        /* 마법 지팡이 커서 */
        .custom-cursor {
            position: fixed;
            width: 3px;
            height: 24px;
            background: linear-gradient(to bottom, #8b4513 0%, #654321 70%, #d4af37 70%, #ffd700 100%);
            border-radius: 2px;
            pointer-events: none;
            z-index: 9999;
            transform-origin: bottom center;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
        }

        .custom-cursor::before {
            content: '✦';
            position: absolute;
            top: -8px;
            left: -6px;
            font-size: 14px;
            color: #d4af37;
            text-shadow: 0 0 8px rgba(212, 175, 55, 0.8);
            animation: sparkle 2s ease-in-out infinite;
        }

        .custom-cursor.hover {
            height: 30px;
            background: linear-gradient(to bottom, #8b4513 0%, #654321 60%, #d4af37 60%, #ffd700 80%, #ff6b6b 100%);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.8);
        }

        .custom-cursor.hover::before {
            content: '★';
            font-size: 16px;
            color: #ffd700;
            animation: sparkle 0.5s ease-in-out infinite;
        }

        @keyframes sparkle {
            0%, 100% {
                opacity: 0.7;
                transform: scale(1) rotate(0deg);
            }
            50% {
                opacity: 1;
                transform: scale(1.2) rotate(180deg);
            }
        }

        /* 별빛 밤하늘 배경 */
        .noite {
            background: -webkit-linear-gradient(top, rgb(0, 0, 0) 50%, rgb(25, 19, 39) 80%, rgb(43, 32, 72));
            width: 100%;
            height: 100%;
            position: absolute;
            overflow: hidden;
        }

        .constelacao {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            animation: rotate 600s infinite linear;
        }

        .estrela {
            background-color: white;
            border-radius: 50%;
            position: absolute;
            animation-name: estrela;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        .estrela.style1 { animation: estrela 0.5s linear infinite, fadeOut 15s ease-in-out infinite; }
        .estrela.style2 { animation: estrela 1s linear infinite, fadeOut 20s ease-in-out infinite; }
        .estrela.style3 { animation: estrela 1.5s linear infinite, fadeOut 25s ease-in-out infinite; }
        .estrela.style4 { animation: estrelaDestacada 2s linear infinite, fadeOut 30s ease-in-out infinite; }

        .estrela.tam1 { width: 1px; height: 1px; }
        .estrela.tam2 { width: 2px; height: 2px; }
        .estrela.tam3 { width: 3px; height: 3px; }

        .estrela.opacity1 { opacity: 1; }
        .estrela.opacity2 { opacity: .5; }
        .estrela.opacity3 { opacity: .1; }

        .meteoro {
            position: absolute;
            background-color: #fff;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            transform: rotate(-35deg);
            animation-timing-function: linear;
            animation-iteration-count: infinite;
            animation-duration: 1s;
        }

        .meteoro:before {
            content: "";
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            width: 0;
            height: 0;
            border-top: 1px solid transparent;
            border-bottom: 1px solid transparent;
            border-left: 85px solid white;
            position: absolute;
            left: 2px;
            top: 0;
        }

        .meteoro.style1 { animation-name: meteoroStyle1; }
        .meteoro.style2 { animation-name: meteoroStyle2; }
        .meteoro.style3 { animation-name: meteoroStyle3; }
        .meteoro.style4 { animation-name: meteoroStyle4; }

        .lua {
            position: absolute;
            right: 200px;
            top: 150px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            box-shadow: 0 0 160px 0px #fff, 0 0 30px -4px #fff, 0 0 8px 2px rgba(255, 255, 255, 0.26);
            background-color: #fff;
            animation-name: lua;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
            animation-duration: 10s;
        }

        .lua .textura {
            background-image: url(https://raw.githubusercontent.com/interaminense/starry-sky/master/src/img/bgMoon.png);
            background-position: center;
            background-size: 100%;
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 50%;
            overflow: hidden;
            opacity: 0.4;
        }

        .floresta {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }

        .floresta img {
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        /* 로고 스타일 */
        .title {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 200;
            text-align: center;
            cursor: pointer;
        }

        .title a {
            display: inline-block;
            text-decoration: none;
        }

        .title img {
            display: block;
            margin: 0 auto;
            max-width: 300px;
            cursor: pointer;
            transition: all 0.4s ease;
            border-radius: 8px;
            opacity: 0.9;
        }

        .title img:hover {
            transform: scale(1.05);
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.4);
            filter: brightness(1.2);
            opacity: 1;
        }

        /* 별빛 애니메이션 */
        @keyframes estrela {
            0% {
                box-shadow: 0 0 10px 0px rgba(255, 255, 255, 0.05);
            }
            50% {
                box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.4);
            }
            100% {
                box-shadow: 0 0 10px 0px rgba(255, 255, 255, 0.05);
            }
        }

        @keyframes estrelaDestacada {
            0% {
                background-color: #FFFFFF;
                box-shadow: 0 0 10px 0px rgba(255, 255, 255, 1);
            }
            20% {
                background-color: #FFC4C4;
                box-shadow: 0 0 10px 0px rgb(255, 196, 196, 1);
            }
            80% {
                background-color: #C4CFFF;
                box-shadow: 0 0 10px 0px rgb(196, 207, 255, 1);
            }
            100% {
                background-color: #FFFFFF;
                box-shadow: 0 0 10px 0px rgba(255, 255, 255, 0.2);
            }
        }

        /* 별들이 점점 사라지는 애니메이션 */
        @keyframes fadeOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            70% {
                opacity: 1;
                transform: scale(1);
            }
            85% {
                opacity: 0.5;
                transform: scale(0.8);
            }
            95% {
                opacity: 0.1;
                transform: scale(0.3);
            }
            100% {
                opacity: 0;
                transform: scale(0);
            }
        }

        @keyframes meteoroStyle1 {
            0% { opacity: 0; right: 300px; top: 100px; }
            30% { opacity: .3; }
            60% { opacity: .3; }
            100% { opacity: 0; right: 1000px; top: 600px; }
        }

        @keyframes meteoroStyle2 {
            0% { opacity: 0; right: 700px; top: 100px; }
            30% { opacity: 1; }
            60% { opacity: 1; }
            100% { opacity: 0; right: 1400px; top: 600px; }
        }

        @keyframes meteoroStyle3 {
            0% { opacity: 0; right: 300px; top: 300px; }
            30% { opacity: 1; }
            60% { opacity: 1; }
            100% { opacity: 0; right: 1000px; top: 800px; }
        }

        @keyframes meteoroStyle4 {
            0% { opacity: 0; right: 700px; top: 300px; }
            30% { opacity: 1; }
            60% { opacity: 1; }
            100% { opacity: 0; right: 1400px; top: 800px; }
        }

        @keyframes lua {
            0% {
                box-shadow: 0 0 160px 0px #fff, 0 0 30px -4px #fff, 0 0 8px 2px rgba(255, 255, 255, 0.26);
            }
            50% {
                box-shadow: 0 0 80px 0px #fff, 0 0 30px -4px #fff, 0 0 8px 2px rgba(255, 255, 255, 0.26);
            }
            100% {
                box-shadow: 0 0 160px 0px #fff, 0 0 30px -4px #fff, 0 0 8px 2px rgba(255, 255, 255, 0.26);
            }
        }

        @keyframes rotate {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        /* 반응형 */
        @media (max-width: 768px) {
            .title img {
                max-width: 250px;
            }

            .lua {
                right: 50px;
                top: 100px;
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>

<!-- 커스텀 커서 -->
<div class="custom-cursor"></div>

<!-- 별빛 밤하늘 배경 -->
<div class="noite"></div>
<div class="constelacao"></div>
<div class="lua">
    <div class="textura"></div>
</div>
<div class="chuvaMeteoro"></div>
<div class="floresta">
    <img src="https://raw.githubusercontent.com/interaminense/starry-sky/master/src/img/bgTree.png" alt="" />
</div>

<!-- 로고 -->
<div class="title">
    <a href="./main.php">
        <img src="./img/logo.png" alt="Logo">
    </a>
</div>

<script>
// 지팡이 커서
const cursor = document.querySelector('.custom-cursor');
let mouseX = 0, mouseY = 0;

document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    cursor.style.left = mouseX - 1.5 + 'px';
    cursor.style.top = mouseY - 20 + 'px';
});

// 호버 효과
document.querySelectorAll('a, .title img').forEach(element => {
    element.addEventListener('mouseenter', () => {
        cursor.classList.add('hover');
    });
    element.addEventListener('mouseleave', () => {
        cursor.classList.remove('hover');
    });
});

// 별빛 밤하늘 초기화
function init() {
    // 별들
    var style = ["style1", "style2", "style3", "style4"];
    var tam = ["tam1", "tam1", "tam1", "tam2", "tam3"];
    var opacity = ["opacity1", "opacity1", "opacity1", "opacity2", "opacity2", "opacity3"];

    function getRandomArbitrary(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    var estrela = "";
    var qtdeEstrelas = 250;
    var noite = document.querySelector(".constelacao");
    var widthWindow = window.innerWidth;
    var heightWindow = window.innerHeight;

    for (var i = 0; i < qtdeEstrelas; i++) {
        estrela += "<span class='estrela " + style[getRandomArbitrary(0, 4)] + " " + opacity[getRandomArbitrary(0, 6)] + " "
        + tam[getRandomArbitrary(0, 5)] + "' style='animation-delay: ." + getRandomArbitrary(0, 9) + "s; left: "
        + getRandomArbitrary(0, widthWindow) + "px; top: " + getRandomArbitrary(0, heightWindow) + "px;'></span>";
    }

    noite.innerHTML = estrela;

    // 유성
    var numeroAleatorio = 5000;

    setTimeout(function() {
        carregarMeteoro();
    }, numeroAleatorio);

    function carregarMeteoro() {
        setTimeout(carregarMeteoro, numeroAleatorio);
        numeroAleatorio = getRandomArbitrary(5000, 10000);
        var meteoro = "<div class='meteoro " + style[getRandomArbitrary(0, 4)] + "'></div>";
        document.getElementsByClassName('chuvaMeteoro')[0].innerHTML = meteoro;
        setTimeout(function() {
            document.getElementsByClassName('chuvaMeteoro')[0].innerHTML = "";
        }, 1000);
    }
}

window.onload = init;
</script>

</body>
</html>