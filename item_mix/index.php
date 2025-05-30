<link rel="stylesheet" href="./item_mix.css" />

<?php
include_once('../common.php');
include_once('../head.php');

// 캐릭터 상태 테이블에서 sc_max 가져오기
$status = sql_fetch("SELECT sc_max FROM {$g5['status_table']} 
                         WHERE ch_id = '{$character['ch_id']}' AND st_id = 2 
                         LIMIT 1");
$sc_max = isset($status['sc_max']) ? (int) $status['sc_max'] : 0;

// 인벤토리 검색 쿼리 실행
$re_result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and it.it_use_recepi = 1 and inven.it_id = it.it_id");

// 인벤토리 검색 결과 배열에 담기
$userItems = [];
while ($row = sql_fetch_array($re_result)) {
    $userItems[] = $row;
}
$jsonUserItems = json_encode($userItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// 레시피 검색 결과
$recipe_result = sql_query("SELECT * FROM {$g5['item_table']}_recepi WHERE re_use = '1'");
$recipes = [];
while ($row = sql_fetch_array($recipe_result)) {
    $recipes[] = $row;
}
$jsonRecipes = json_encode($recipes, JSON_UNESCAPED_UNICODE);

// 아이템 리스트 검색 결과
$item_result = sql_query("SELECT * FROM {$g5['item_table']}");
$items = [];
while ($row = sql_fetch_array($item_result)) {
    $items[] = $row;
}
$jsonItems = json_encode($items, JSON_UNESCAPED_UNICODE);

?>



<div class="combination-container">
    <div class="combination-area">
        <div class="combination-wrapper">
            <!-- 재료 마름모들 - 삼각형 배치 -->
            <div class="ingredients">
                <div class="diamond" id="ingredient-1" onclick="toggleDropdown(1)">
                    <div class="diamond-content">
                    </div>
                </div>
                <div class="diamond" id="ingredient-2" onclick="toggleDropdown(2)">
                    <div class="diamond-content">
                    </div>
                </div>
                <div class="diamond" id="ingredient-3" onclick="toggleDropdown(3)">
                    <div class="diamond-content">
                    </div>
                </div>

                <!-- 드롭다운들을 별도로 배치 -->
                <div class="diamond-dropdown" id="dropdown-1">
                    <!-- 드롭다운 아이템들이 여기에 동적으로 생성됩니다 -->
                </div>
                <div class="diamond-dropdown" id="dropdown-2">
                    <!-- 드롭다운 아이템들이 여기에 동적으로 생성됩니다 -->
                </div>
                <div class="diamond-dropdown" id="dropdown-3">
                    <!-- 드롭다운 아이템들이 여기에 동적으로 생성됩니다 -->
                </div>

                <!-- 연결선 제거됨 -->

                <!-- 성공률 원형 게이지 - 중앙 -->
                <div class="success-rate-circle" onclick="performCombination()">
                    <div class="circle-background">
                        <div class="circle-progress" id="circle-progress"></div>
                        <div class="success-rate-text" id="success-rate-text">0%</div>
                    </div>
                </div>

                <!-- 결과선도 제거됨 -->
            </div>

            <!-- 결과 마름모 -->
            <div class="result-diamond" id="result-diamond">
                <div class="result-content" id="result-content">
                    <span>?</span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- 함수 영역 -->
<script>
    // 슬롯 구성하는 변수들
    let selectedItems = [null, null, null];
    let currentSlot = 0;
    let playerWisdom = <?= $sc_max ?>;

    const userItems = <?= $jsonUserItems ?>;
    const allItems = <?= $jsonItems ?>

    // 레시피 저장하는 변수
    // PHP에서 받은 JSON 문자열을 파싱
    const recipesRaw = <?= $jsonRecipes ?>;

    // 변환된 레시피 배열
    const recipes = recipesRaw.map(r => {
        return {
            ...r,
            ingredients: r.re_item_order
                .split('||'),
        };
    });


    // --------------- 인벤토리 아이템의 중복 갯수를 알아내기위한 과정...
    // 임시 맵 객체를 만들어 it_id 기준으로 카운트 및 데이터 합치기

    const groupedItems = [];
    const itemMap = {};

    userItems.forEach(item => {
        if (itemMap[item.it_id]) {
            itemMap[item.it_id].count += 1;
            itemMap[item.it_id].in_ids.push(item.in_id); // 삭제를 위해 in_id 저장
        } else {
            // 새로운 it_id 발견하면 복사해서 count 1로 초기화
            itemMap[item.it_id] = { ...item, count: 1, in_ids: [item.in_id] };
        }
    });

    // itemMap 객체의 값들만 배열로 변환
    for (const key in itemMap) {
        groupedItems.push(itemMap[key]);
    }

    // ------------  계산 종료

    //------------- 드롭다운 제어 

    // 드롭다운 토글
    function toggleDropdown(slot) {
        currentSlot = slot - 1;
        const dropdown = document.getElementById(`dropdown-${slot}`);

        // 다른 드롭다운들 닫기
        for (let i = 1; i <= 3; i++) {
            if (i !== slot) {
                document.getElementById(`dropdown-${i}`).classList.remove('show');
            }
        }

        populateDropdown(dropdown, slot);

        //현재 드롭다운 토글
        if (dropdown.classList.contains('show')) {
            closeAllDropdowns();
        } else {
            populateDropdown(dropdown, slot);
            dropdown.classList.add('show');
        }
    }

    // 모든 드롭다운 닫기 
    function closeAllDropdowns() {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`dropdown-${i}`).classList.remove('show');
        }
    }

    // 드롭다운 채우기
    function populateDropdown(dropdown, slot) {
        dropdown.innerHTML = '';

        // 선택 해제 항목 추가
        const clearItem = document.createElement('div');
        clearItem.className = 'dropdown-item clear-selection';
        clearItem.textContent = '선택 해제';
        clearItem.onclick = (e) => {
            e.stopPropagation();
            selectedItems[slot - 1] = null;

            const diamond = document.getElementById(`ingredient-${slot}`);
            const content = diamond.querySelector('.diamond-content');
            content.innerHTML = '';  // 이미지 제거
            diamond.classList.remove('selected');

            closeAllDropdowns();
            updateCombinationResult();
        };
        dropdown.appendChild(clearItem);

        groupedItems.forEach(item => {
            const selectedCountElsewhere = selectedItems.reduce((acc, selectedItem, idx) => {
                if (selectedItem && selectedItem.it_id === item.it_id) {
                    return acc + 1;
                }
                return acc;
            }, 0);

            const availableCount = itemMap[item.it_id].count - selectedCountElsewhere;

            const isDisabled = availableCount <= 0;
            //const isDisabled = isItemDisabled(item);
            const dropdownItem = document.createElement('div');
            dropdownItem.className = `dropdown-item ${isDisabled ? 'disabled' : ''}`;


            if (!isDisabled) {
                dropdownItem.onclick = (e) => {
                    e.stopPropagation();
                    selectItem(item, slot);
                    closeAllDropdowns();
                };
            }

            dropdownItem.innerHTML = `
                    <img src="${item.it_img}" alt="${item.it_name}">
                    <div class="dropdown-item-info">
                        <div class="dropdown-item-name">${item.it_name}</div>
                        <div class="dropdown-item-count">보유: ${availableCount}개</div>
                    </div>
                `;

            dropdown.appendChild(dropdownItem);
        });
    }

    // 모든 드롭다운 닫기
    function closeAllDropdowns() {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`dropdown-${i}`).classList.remove('show');
        }
    }


    //---------------- 아이템 관리 

    // 아이템 선택 불가 여부 확인
    function isItemDisabled(item) {
        // 해당 아이템이 1개뿐이고 이미 다른 슬롯에서 선택되었다면 비활성화
        if (itemMap[item.it_id].count === 1) {
            return selectedItems.some((selected, index) =>
                index !== currentSlot && selected && selected.it_id === item.it_id
            );
        }
        return false;
    }

    // 아이템 선택
    function selectItem(item, slot) {
        const slotIndex = slot - 1;

        // 사용 중인 in_id들 확인
        const usedInIds = selectedItems
            .filter(i => i !== null && i.in_id)
            .map(i => i.in_id);
        // 해당 it_id의 in_id 목록에서 아직 안쓴 것 추출
        const availableInIds = itemMap[item.it_id].in_ids.filter(id => !usedInIds.includes(id));

        // 새 객체에 in_id 추가
        const selectedItem = {
            ...item,
            in_id: availableInIds[0] // 사용 안된 in_id 하나 할당
        };

        // 이전에 선택된 아이템이 있다면 카운트 원복
        const prevItem = selectedItems[slotIndex];
        if (prevItem) {
            // 이전 아이템 보유 수량 복원 (필요시)
            //itemMap[prevItem.it_id].count++; // 원래 보유수 유지한다고 가정하면 불필요
        }

        // 새로운 아이템 선택
        selectedItems[slotIndex] = selectedItem;

        // UI 업데이트
        const diamond = document.getElementById(`ingredient-${slot}`);
        const content = diamond.querySelector('.diamond-content');

        content.innerHTML = `<img src="${item.it_img}" alt="${item.it_name}">`;
        diamond.classList.add('selected');

        // 결과 및 연결선 업데이트
        updateCombinationResult();
        updateConnections();

        // 드롭다운 다시 갱신 (카운트 반영 위해)
        for (let i = 1; i <= 3; i++) {
            const dropdown = document.getElementById(`dropdown-${i}`);
            if (dropdown.classList.contains('show')) {
                populateDropdown(dropdown, i);
            }
        }
    }

    // 조합 결과 업데이트
    function updateCombinationResult() {
        const resultDiamond = document.getElementById('result-diamond');
        const resultContent = document.getElementById('result-content');

        // 선택된 아이템들의 ID 배열
        const selectedIds = selectedItems
            .map(item => item ? item.it_id : "")  // null이면 빈 문자열
            .sort();

        /* 레시피는 무조건 3개 다 채워야만 반응하도록 하는 코드 
    if (selectedIds.length < 3) {
        resultContent.innerHTML = '<span>?</span>';
        resultDiamond.classList.remove('has-result');
        updateSuccessRate(0);
        return;
    }
        */

        // 레시피 찾기
        const recipe = recipes.find(r => {
            const recipeIds = [...r.ingredients].sort();
            return JSON.stringify(recipeIds) === JSON.stringify(selectedIds);
        });

        if (recipe) {
            // 결과 아이템 정보 가져오기
            const resultItem = allItems.find(item => item.it_id === recipe.it_id);

            if (resultItem) {
                resultContent.innerHTML = `<img src="${resultItem.it_img}" alt="${resultItem.it_name}">`;
            } else {
                resultContent.innerHTML = '<span>?</span>';
            }
            resultDiamond.classList.add('has-result');
            updateSuccessRate(playerWisdom);
        } else {
            resultContent.innerHTML = '<span>?</span>';
            resultDiamond.classList.remove('has-result');
            updateSuccessRate(0);
        }
    }

    // 성공률 업데이트 (원형 게이지)
    function updateSuccessRate(rate) {
        const progressCircle = document.getElementById('circle-progress');
        const rateText = document.getElementById('success-rate-text');

        // 모든 테두리 초기화
        progressCircle.style.borderTopColor = 'transparent';
        progressCircle.style.borderRightColor = 'transparent';
        progressCircle.style.borderBottomColor = 'transparent';
        progressCircle.style.borderLeftColor = 'transparent';

        // 원형 진행률 계산 (360도 기준)
        const degree = (rate / 100) * 360;

        if (rate > 0) {
            if (degree <= 90) {
                // 0-90도: 위쪽만
                progressCircle.style.borderTopColor = '#8BBCFF';
                progressCircle.style.transform = `rotate(${degree - 90}deg)`;
            } else if (degree <= 180) {
                // 90-180도: 위쪽 + 오른쪽
                progressCircle.style.borderTopColor = '#8BBCFF';
                progressCircle.style.borderRightColor = '#8BBCFF';
                progressCircle.style.transform = `rotate(${degree - 90}deg)`;
            } else if (degree <= 270) {
                // 180-270도: 위쪽 + 오른쪽 + 아래쪽
                progressCircle.style.borderTopColor = '#8BBCFF';
                progressCircle.style.borderRightColor = '#8BBCFF';
                progressCircle.style.borderBottomColor = '#8BBCFF';
                progressCircle.style.transform = `rotate(${degree - 90}deg)`;
            } else {
                // 270-360도: 모든 면
                progressCircle.style.borderTopColor = '#8BBCFF';
                progressCircle.style.borderRightColor = '#8BBCFF';
                progressCircle.style.borderBottomColor = '#8BBCFF';
                progressCircle.style.borderLeftColor = '#8BBCFF';
                progressCircle.style.transform = `rotate(${degree - 90}deg)`;
            }
        }

        rateText.textContent = `${rate}%`;
    }

    // 연결선 애니메이션 함수
    function updateConnections() {
        const resultConnection = document.getElementById('result-connection');
        if (!resultConnection) return;  // 없으면 함수 종료
        const hasAllItems = selectedItems.every(item => item !== null);

        if (hasAllItems) {
            resultConnection.classList.add('active');
        } else {
            resultConnection.classList.remove('active');
        }
    }

    // 조합 실행
    async function performCombination() {
        const rateText = document.getElementById('success-rate-text');
        const inIds = selectedItems.map(item => item ? item.in_id : null).filter(id => id !== null);
        const selectedIds = selectedItems
            .map(item => item ? item.it_id : "")  // null이면 빈 문자열
            .sort();

        const recipe = recipes.find(r => {
            const recipeIds = [...r.ingredients].sort();
            return JSON.stringify(recipeIds) === JSON.stringify(selectedIds);
        });

        if (!recipe) {
            alert('레시피가 올바른지 확인해 주세요.');
            return;
        }


        // 여기에 실제 조합 로직을 구현
        // 성공 확률 판단
        const random = Math.floor(Math.random() * 100) + 1; // 1 ~ 100
        const isSuccess = random <= parseInt(rateText.textContent);

        console.log('조합 실행:', selectedItems);
        console.log('랜덤값:', random, '/ 성공 기준:', parseInt(rateText.textContent));

        if (isSuccess) {

            const resultItemId = recipe.it_id;

            if (!resultItemId) {
                alert('결과 아이템 정보를 찾을 수 없습니다.');
                return;
            }

            // 서버에 아이템 지급 요청
            try {
                const response = await fetch('./give_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        characterId: <?= json_encode($character['ch_id']) ?>,
                        itemId: resultItemId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('조합 성공! 아이템이 지급되었습니다. 🎉');

                    // 성공 시 아이템 삭제
                    await deleteInventoryItems(inIds).then(result => {
                        if (result.success) {
                            updateInventoryAfterDeletion(inIds);
                        }
                    });
                } else {
                    alert('조합 성공했지만 아이템 지급에 실패했습니다. 😥');
                }
            } catch (error) {
                console.error('서버 오류:', error);
                alert('조합 성공했지만 서버 통신에 실패했습니다.');
            }
        } else {
            alert(`조합 실패 (뽑힌 숫자: ${random})`);

            try {
                await deleteInventoryItems(inIds).then(result => {
                    if (result.success) {
                        updateInventoryAfterDeletion(inIds);
                    }
                });
                if (!deleteResult.success) {
                    console.error('아이템 삭제 실패:', deleteResult.message);
                }
            } catch (error) {
                console.error('삭제 요청 중 에러:', error);
            }

        }
    }

    // 조합 종료 후 인벤토리 안의 아이템 삭제
    function deleteInventoryItems(inventoryIds) {
        return fetch('./delete_inventory.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ in_ids: inventoryIds })
        })
            .then(response => response.json());
    }

    // 아이템 삭제 후 드롭다운 정보 갱신 
    function updateInventoryAfterDeletion(deletedInIds) {
        // 1. userItems에서 삭제
        for (const inId of deletedInIds) {
            const index = userItems.findIndex(item => item.in_id === inId);
            if (index !== -1) {
                userItems.splice(index, 1); // 제거
            }
        }

        // 2. itemMap과 groupedItems 재구성
        groupedItems.length = 0;
        const newItemMap = {};
        userItems.forEach(item => {
            if (newItemMap[item.it_id]) {
                newItemMap[item.it_id].count += 1;
                newItemMap[item.it_id].in_ids.push(item.in_id);
            } else {
                newItemMap[item.it_id] = { ...item, count: 1, in_ids: [item.in_id] };
            }
        });
        for (const key in newItemMap) {
            groupedItems.push(newItemMap[key]);
        }

        // 전역 itemMap도 갱신
        Object.keys(itemMap).forEach(key => delete itemMap[key]);
        Object.assign(itemMap, newItemMap);

        for (let i = 1; i <= 3; i++) {
            const dropdown = document.getElementById(`dropdown-${i}`);
            if (dropdown.classList.contains('show')) {
                populateDropdown(dropdown, i);
            }
        }
    }

    // 외부 클릭 시 드롭다운 닫기
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.diamond')) {
            closeAllDropdowns();
        }
    });

    // 초기 성공률 설정
    updateSuccessRate(0);


</script>