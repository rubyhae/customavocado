<?php
if (!defined('_GNUBOARD_')) exit; // 그누보드 외 접근 차단

$is_admin_page = defined('G5_IS_ADMIN') && G5_IS_ADMIN;
if ($is_admin_page) {
    return;
}

$menu_widget_uid = get_uniqid();

$menu_data = call_user_func(function() use ($g5, $member, $config, $is_admin) {
    $is_admin_user = $is_admin ? true : false;

    $css_sql = sql_query("select * from {$g5['css_table']}");
    $css = array();
    for($i=0; $cs = sql_fetch_array($css_sql); $i++) {
        $css[$cs['cs_name']][0] = $cs['cs_value'];
        $css[$cs['cs_name']][1] = $cs['cs_etc_1'];
        $css[$cs['cs_name']][2] = $cs['cs_etc_2'];
    }

    $menu_left = $css['menu_style'][0]; 
    $menu_background = $css['menu_background'][1];
	$m_menu_background = $css['m_menu_background'][1];
    $menu_text_color = $css['menu_text'][0];
    $menu_hover_color = $css['menu_text'][2];
    $menu_height = $css['menu_height'][0];
    $menu_color_default = $css['color_default'][0];
    $menu_color_point = $css['color_point'][0];

    $sql = " select * from {$g5['menu_table']} where me_use = '1' order by me_order*1, me_id";
    $result = sql_query($sql);
    $menu_datas = array();
    
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $menu_datas[$i] = $row;
    }
    
    return array(
        'menu_datas' => $menu_datas,
        'menu_text_color' => $menu_text_color,
        'menu_hover_color' => $menu_hover_color,
        'menu_height' => $menu_height,
        'menu_background' => $menu_background,
        'menu_color_default' => $menu_color_default,
        'menu_left' => $menu_left,
		'm_menu_background' => $m_menu_background,
        'menu_color_point' => $menu_color_point
    );
});

extract($menu_data);
?>

<link href="<?=G5_URL?>/widget/menuup/menu_widget.css" type="text/css" rel="stylesheet">

<style>
:root {
    --mh-color: <?php echo $menu_hover_color; ?>;
    --mt-color: <?php echo $menu_text_color; ?>;
    --df-color: <?php echo $menu_color_default; ?>;
    --mbg-color: <?php echo $menu_background; ?>;
    --mbd-color: #000;
    --mln-color: <?php echo $m_menu_background; ?>;
    --mp-color: <?php echo $menu_color_point; ?>;
    --me-top: <?php echo $menu_height; ?>;
    --me-left: <?php echo $menu_left; ?>;
}
</style>

<div id="mobile-menu-toggle">
    <i class="fa-solid fa-bars"></i>
</div>

<div class="os-style-menu-widget">
    <div class="menu-header">
        <a href="#" class="user-info-btn"><i class="fa-solid fa-bars"></i></a>
        <?php if ($is_member) { ?> 
        <span class="user-nickname"><?php echo $member['mb_nick']; ?></span>
        <?php } else { ?>
        <span class="user-nickname">Guest</span>
        <?php } ?>
        <button class="menu-minimize-btn">
            <i class="fa-solid fa-minus"></i>
        </button>
    </div>
    
    <!-- 사용자 정보 서브메뉴 -->
    <div class="user-info-submenu">
        <?php if ($is_member) { ?>
            <?php if ($is_admin) { ?>
                <!-- 관리자일 경우 -->
                <div class="user-info-item">
                    <a href="#" class="header-icon admin-icon" onClick="window.open('<?=G5_ADMIN_URL?>')">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <span>관리자 페이지</span>
                    </a>
                </div>
                <div class="user-info-item">
                    <a href="<?php echo G5_BBS_URL ?>/logout.php" class="header-icon logout-icon">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span>로그아웃</span>
                    </a>
                </div>
            <?php } else { ?>
                <!-- 일반 회원일 경우 -->
                <div class="user-info-item">
                    <a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=register_form.php" class="header-icon member-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>회원 정보</span>
                    </a>
                </div>
                <div class="user-info-item">
                    <a href="<?php echo G5_BBS_URL ?>/logout.php" class="header-icon logout-icon">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span>로그아웃</span>
                    </a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <!-- 게스트일 경우 -->
            <?php if($config['cf_1']){ ?>
                <!-- 회원가입을 열어뒀을 시 -->
                <div class="user-info-item">
                    <a href="#" class="header-icon register-icon" onClick="location.href='<?php echo G5_BBS_URL ?>/register.php'">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        <span>회원가입</span>
                    </a>
                </div>
            <?php } ?>
            <div class="user-info-item">
                <a href="<?=G5_BBS_URL?>/login.php" class="header-icon login-icon">
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    <span>로그인</span>
                </a>
            </div>
        <?php } ?>
    </div>
    
    <ul class="main-menu">
        <?php
        $current_main = null;
        $item_count = 0;
        $visible_items = array();

        foreach($menu_datas as $i => $row) {
            if(strpos($row['me_name'], 'ㄴ') !== 0) {
                if($row['me_name'] != '구분선') {
                    $visible_items[] = $i;
                } else {
                    if(count($visible_items) % 2 != 0) {
                        $last_idx = end($visible_items);
                        $menu_datas[$last_idx]['center_align'] = true;
                    }
                    $visible_items = array();
                }
            }
        }

        if(count($visible_items) % 2 != 0) {
            $last_idx = end($visible_items);
            $menu_datas[$last_idx]['center_align'] = true;
        }

        $current_main = null;
        $item_count = 0;

        foreach($menu_datas as $i => $row) {
            if(isset($row['me_level']) && $row['me_level'] == 10 && !$is_admin) {
                continue;
            }
            
            if(strpos($row['me_name'], 'ㄴ') !== 0) {
                if($row['me_name'] == '구분선') {
                    echo '<li class="menu-divider full-width-item"></li>';
                    continue;
                }
                
                $has_submenu = false;
                if(isset($menu_datas[$i+1]) && strpos($menu_datas[$i+1]['me_name'], 'ㄴ') === 0) {
                    $has_submenu = true;
                }
                
                $is_folder = empty($row['me_link']) || $row['me_link'] == '#' || $has_submenu;
                
                $current_main = $row;
                $item_count++;
                
                $center_class = isset($row['center_align']) && $row['center_align'] ? 'center-align' : '';
                
                $restricted_class = '';
                if (isset($row['me_level']) && $row['me_level'] > $member['mb_level']) {
                    $restricted_class = 'menu-restricted';
                }
                ?>
                <li class="menu-item <?php echo $has_submenu ? 'has-submenu' : ''; ?> <?php echo $center_class; ?> <?php echo $restricted_class; ?>">
                    <?php if($is_folder) { ?>
                        <div class="folder-item">
                            <span class="menu-icon">
                                <?php if(!empty($row['me_img'])) {
                                    if(strpos($row['me_img'], '.') !== false) {
                                        echo '<img src="'.$row['me_img'].'" alt="">';
                                    } else {
                                        echo '<i class="'.$row['me_img'].'"></i>';
                                    }
                                } else {
                                    echo '<i class="fa-solid fa-folder"></i>';
                                } ?>
                            </span>
                            <span class="menu-text"><?php echo $row['me_name']; ?></span>
                            <?php if($has_submenu) { ?>
                                <span class="submenu-indicator">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>">
                            <span class="menu-icon">
                                <?php if(!empty($row['me_img'])) {
                                    if(strpos($row['me_img'], '.') !== false) {
                                        echo '<img src="'.$row['me_img'].'" alt="">';
                                    } else {
                                        echo '<i class="'.$row['me_img'].'"></i>';
                                    }
                                } else {
                                    echo '<i class="fa-solid fa-file"></i>';
                                } ?>
                            </span>
                            <span class="menu-text"><?php echo $row['me_name']; ?></span>
                        </a>
                    <?php } ?>
                    
                    <?php
                    if($has_submenu) {
                        echo '<ul class="sub-menu">';
                        
                        $j = $i + 1;
                        while(isset($menu_datas[$j]) && strpos($menu_datas[$j]['me_name'], 'ㄴ') === 0) {
                            $sub_item = $menu_datas[$j];
                            
                            if(isset($sub_item['me_level']) && $sub_item['me_level'] == 10 && !$is_admin) {
                                $j++;
                                continue;
                            }
                            
                            $sub_name = substr($sub_item['me_name'], 3);
                            $is_subfolder = empty($sub_item['me_link']) || $sub_item['me_link'] == '#';
                            
                            $sub_restricted_class = '';
                            if (isset($sub_item['me_level']) && $sub_item['me_level'] > $member['mb_level']) {
                                $sub_restricted_class = 'menu-restricted';
                            }
                            
                            echo '<li class="menu-item '.$sub_restricted_class.'">';
                            if($is_subfolder) {
                                echo '<div class="folder-item">';
                                echo '<span class="menu-icon">';
                                if(!empty($sub_item['me_img'])) {
                                    if(strpos($sub_item['me_img'], '.') !== false) {
                                        echo '<img src="'.$sub_item['me_img'].'" alt="">';
                                    } else {
                                        echo '<i class="'.$sub_item['me_img'].'"></i>';
                                    }
                                } else {
                                    echo '<i class="fa-solid fa-folder"></i>';
                                }
                                echo '</span>';
                                echo '<span class="menu-text">'.$sub_name.'</span>';
                                echo '</div>';
                            } else {
                                echo '<a href="'.$sub_item['me_link'].'" target="_'.$sub_item['me_target'].'">';
                                echo '<span class="menu-icon">';
                                if(!empty($sub_item['me_img'])) {
                                    if(strpos($sub_item['me_img'], '.') !== false) {
                                        echo '<img src="'.$sub_item['me_img'].'" alt="">';
                                    } else {
                                        echo '<i class="'.$sub_item['me_img'].'"></i>';
                                    }
                                } else {
                                    echo '<i class="fa-solid fa-file"></i>';
                                }
                                echo '</span>';
                                echo '<span class="menu-text">'.$sub_name.'</span>';
                                echo '</a>';
                            }
                            echo '</li>';
                            
                            $j++;
                        }
                        
                        echo '</ul>';
                    }
                    ?>
                </li>
            <?php
            }
        }
        ?>

    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 순수 JavaScript 요소 선택
    const menuWidget = document.querySelector('.os-style-menu-widget');
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    
    // 모바일 여부 확인
    const isMobile = window.innerWidth <= 768;
    
    // 페이지 새로고침 감지 및 위치 초기화 (중복 제거)
    if (performance.navigation.type === 1) {
        localStorage.removeItem('menu_widget_x');
        localStorage.removeItem('menu_widget_y');
    }
    
    // 서브메뉴 방향 설정 함수
    function updateSubmenuDirection() {
        const menuItems = menuWidget.querySelectorAll('.menu-item.has-submenu');
        const windowWidth = window.innerWidth;
        const menuRect = menuWidget.getBoundingClientRect();
        const menuCenterX = menuRect.left + menuRect.width / 2;
        
        // 메뉴가 화면 왼쪽에 있는지 오른쪽에 있는지 확인
        const isMenuOnRightSide = menuCenterX > windowWidth / 2;
        
        menuItems.forEach(item => {
            const subMenu = item.querySelector('.sub-menu');
            if (subMenu) {
                if (isMenuOnRightSide) {
                    // 메뉴가 오른쪽에 있으면 서브메뉴는 왼쪽으로 열림
                    subMenu.style.left = 'auto';
                    subMenu.style.right = '100%';
                } else {
                    // 메뉴가 왼쪽에 있으면 서브메뉴는 오른쪽으로 열림
                    subMenu.style.left = '100%';
                    subMenu.style.right = 'auto';
                }
            }
        });
    }
    
    // 초기 서브메뉴 방향 설정
    updateSubmenuDirection();
    
    // 윈도우 리사이즈 시 서브메뉴 방향 업데이트
    window.addEventListener('resize', updateSubmenuDirection);
    
    // folderItems 중복 선언 제거
    const folderItems = document.querySelectorAll('.os-style-menu-widget .folder-item');
    
    folderItems.forEach(item => {
        const parent = item.closest('.menu-item');
        const subMenu = parent.querySelector('.sub-menu');
        
        if(subMenu) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // 서브메뉴를 열기 전에 방향 업데이트
                updateSubmenuDirection();
                
                const otherOpenMenus = document.querySelectorAll('.os-style-menu-widget .menu-item.open');
                otherOpenMenus.forEach(menu => {
                    if(menu !== parent) {
                        menu.classList.remove('open');
                        const menuText = menu.querySelector('.menu-text').textContent;
                        localStorage.setItem('menu_' + menuText, false);
                    }
                });
                
                parent.classList.toggle('open');
                
                const isOpen = parent.classList.contains('open');
                localStorage.setItem('menu_' + parent.querySelector('.menu-text').textContent, isOpen);
            });
            
            const savedState = localStorage.getItem('menu_' + parent.querySelector('.menu-text').textContent);
            if(savedState === 'true') {
                parent.classList.add('open');
            }
        }
    });

    const mainMenuLinks = document.querySelectorAll('.os-style-menu-widget .menu-item:not(.has-submenu) > a');
    mainMenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            const openMenus = document.querySelectorAll('.os-style-menu-widget .menu-item.open');
            openMenus.forEach(menu => {
                menu.classList.remove('open');
                const menuText = menu.querySelector('.menu-text').textContent;
                localStorage.setItem('menu_' + menuText, false);
            });
        });
    });

    const minimizeButtons = document.querySelectorAll('.menu-minimize-btn');
    
    minimizeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const menuWidget = this.closest('.os-style-menu-widget');
            
            if (menuWidget) {
                menuWidget.classList.toggle('minimized');
                
                const icon = this.querySelector('i');
                if (menuWidget.classList.contains('minimized')) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                } else {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                }
            }
        });
    });

    const header = menuWidget.querySelector('.menu-header');
    
    let isDragging = false;
    let offsetX, offsetY;
    
    if (isMobile) {
        // 모바일에서는 드래그 비활성화
        isDraggable = false;
        
        // 초기 상태 설정
        const menuActive = localStorage.getItem('menu_widget_active') === 'true';
        
        if (menuActive) {
            menuWidget.classList.add('active');
            mobileToggle.querySelector('i').classList.remove('fa-bars');
            mobileToggle.querySelector('i').classList.add('fa-times');
        } else {
            menuWidget.classList.remove('active');
        }
        
        // 토글 버튼 클릭 이벤트
        mobileToggle.addEventListener('click', function() {
            menuWidget.classList.toggle('active');
            
            // 메뉴 상태 저장
            localStorage.setItem('menu_widget_active', menuWidget.classList.contains('active'));
            
            // 아이콘 변경
            const icon = this.querySelector('i');
            if (menuWidget.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // 모바일에서 메뉴 외부 클릭 시 닫기
        document.addEventListener('click', function(e) {
            if (menuWidget.classList.contains('active') && 
                !menuWidget.contains(e.target) && 
                !mobileToggle.contains(e.target)) {
                
                menuWidget.classList.remove('active');
                mobileToggle.querySelector('i').classList.remove('fa-times');
                mobileToggle.querySelector('i').classList.add('fa-bars');
                localStorage.setItem('menu_widget_active', false);
            }
        });
        
        // 모바일 터치 이벤트 처리
        document.addEventListener('touchstart', function(e) {
            if (menuWidget.classList.contains('active') && 
                !menuWidget.contains(e.target) && 
                !mobileToggle.contains(e.target)) {
                
                menuWidget.classList.remove('active');
                mobileToggle.querySelector('i').classList.remove('fa-times');
                mobileToggle.querySelector('i').classList.add('fa-bars');
                localStorage.setItem('menu_widget_active', false);
            }
        });
    } else {
        // PC에서는 토글 버튼 숨기기
        mobileToggle.style.display = 'none';
        
        // PC에서는 저장된 위치 적용
        if (performance.navigation.type !== 1) {
            const savedX = localStorage.getItem('menu_widget_x');
            const savedY = localStorage.getItem('menu_widget_y');
            
            if (savedX && savedY) {
                menuWidget.style.left = savedX;
                menuWidget.style.top = savedY;
            }
        }
        
        // 드래그 기능 구현
        header.addEventListener('mousedown', function(e) {
            if (e.target.closest('.menu-minimize-btn')) {
                return;
            }
            
            isDragging = true;
            
            const rect = menuWidget.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            
            header.style.cursor = 'grabbing';
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const newX = e.clientX - offsetX;
            const newY = e.clientY - offsetY;

            const maxX = window.innerWidth - menuWidget.offsetWidth;
            const maxY = window.innerHeight - menuWidget.offsetHeight;
            
            const boundedX = Math.max(0, Math.min(newX, maxX));
            const boundedY = Math.max(0, Math.min(newY, maxY));
            
            menuWidget.style.left = boundedX + 'px';
            menuWidget.style.top = boundedY + 'px';
            
            menuWidget.style.transform = 'none';
        });
        
        document.addEventListener('mouseup', function() {
            if (!isDragging) return;
            
            // 드래그 후 서브메뉴 방향 업데이트
            updateSubmenuDirection();
            
            isDragging = false;
            
            header.style.cursor = 'grab';
            localStorage.setItem('menu_widget_x', menuWidget.style.left);
            localStorage.setItem('menu_widget_y', menuWidget.style.top);
        });
        
        document.addEventListener('mouseleave', function() {
            if (isDragging) {
                isDragging = false;
                header.style.cursor = 'grab';
            }
        });
    }

    // 사용자 정보 토글 버튼
    const userInfoBtn = document.querySelector('.user-info-btn');
    const userInfoSubmenu = document.querySelector('.user-info-submenu');
    
    if (userInfoBtn && userInfoSubmenu) {
        userInfoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            userInfoSubmenu.classList.toggle('active');
            
            // 서브메뉴 위치 조정
            const widgetRect = menuWidget.getBoundingClientRect();
            const windowWidth = window.innerWidth;
            
            // 위젯이 화면 오른쪽에 있는지 왼쪽에 있는지 확인
            const isWidgetOnRightSide = widgetRect.left + widgetRect.width/2 > windowWidth/2;
            
            if (isWidgetOnRightSide) {
                // 위젯이 오른쪽에 있으면 서브메뉴는 왼쪽으로 정렬
                userInfoSubmenu.style.left = 'auto';
            } else {
                // 위젯이 왼쪽에 있으면 서브메뉴는 오른쪽으로 정렬
                userInfoSubmenu.style.right = 'auto';
            }
        });
        
        // 외부 클릭 시 서브메뉴 닫기
        document.addEventListener('click', function(e) {
            if (!userInfoBtn.contains(e.target) && !userInfoSubmenu.contains(e.target)) {
                userInfoSubmenu.classList.remove('active');
            }
        });
    }
});
</script>

