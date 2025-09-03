document.addEventListener('DOMContentLoaded', function() {
    // 親チェックボックスの状態を更新する共通関数
    function updateParentCheckbox(parentCheckbox, childCheckboxes) {
        if (!parentCheckbox) return;
        
        const parentLabel = document.querySelector(`label[for="${parentCheckbox.id}"]`);
        if (!parentLabel) return;
        
        // 全ての子がチェックされているか確認
        const allChecked = Array.from(childCheckboxes).every(checkbox => checkbox.checked);
        // 少なくとも1つの子がチェックされているか確認
        const someChecked = Array.from(childCheckboxes).some(checkbox => checkbox.checked);
        
        // 親のチェック状態を更新
        if (allChecked && childCheckboxes.length > 0) {
            parentCheckbox.checked = true;
            parentLabel.classList.remove('has-partial-checked');
        } else if (!someChecked) {
            parentCheckbox.checked = false;
            parentLabel.classList.remove('has-partial-checked');
        } else {
            // 一部だけチェックされている場合（中間状態）
            parentCheckbox.checked = false;
            parentLabel.classList.add('has-partial-checked');
        }
    }
    
    // 初期状態を設定する関数
    function initializeCheckboxStates() {
        // 全てのsearch_select__areaとsearch_select__typeを処理
        document.querySelectorAll('.search_select__area, .search_select__type').forEach(function(containerDiv) {
            const parentCheckbox = containerDiv.querySelector('.search_select__area_check, .search_select__type_check');
            if (!parentCheckbox) return;
            
            const childCheckboxes = containerDiv.querySelectorAll('.search_select__area_item_check, .search_select__type_item_check');
            if (childCheckboxes.length > 0) {
                updateParentCheckbox(parentCheckbox, childCheckboxes);
            }
        });
    }
    
    // 親チェックボックスがチェックされた時の処理（共通）
    document.querySelectorAll('.search_select__area_check, .search_select__type_check').forEach(function(parentCheckbox) {
        parentCheckbox.addEventListener('change', function() {
            // 親のdivを取得（areaまたはtype）
            const containerDiv = this.closest('.search_select__area, .search_select__type');
            if (!containerDiv) return;
            
            // 対応する子チェックボックスを全て取得
            const childCheckboxes = containerDiv.querySelectorAll('.search_select__area_item_check, .search_select__type_item_check');
            
            // 親のチェック状態を子に反映
            childCheckboxes.forEach(function(childCheckbox) {
                childCheckbox.checked = parentCheckbox.checked;
            });
            
            // 親のラベルからhas-partial-checkedクラスを削除
            const parentLabel = document.querySelector(`label[for="${this.id}"]`);
            if (parentLabel) {
                parentLabel.classList.remove('has-partial-checked');
            }
        });
    });
    
    // 子チェックボックスがチェックされた時の処理（共通）
    document.querySelectorAll('.search_select__area_item_check, .search_select__type_item_check').forEach(function(childCheckbox) {
        childCheckbox.addEventListener('change', function() {
            // 親のdivを取得（areaまたはtype）
            const containerDiv = this.closest('.search_select__area, .search_select__type');
            if (!containerDiv) return;
            
            // 親チェックボックスを取得
            const parentCheckbox = containerDiv.querySelector('.search_select__area_check, .search_select__type_check');
            if (!parentCheckbox) return;
            
            // 同じグループの子チェックボックスを全て取得
            const childCheckboxes = containerDiv.querySelectorAll('.search_select__area_item_check, .search_select__type_item_check');
            
            // 親の状態を更新
            updateParentCheckbox(parentCheckbox, childCheckboxes);
        });
    });
    
    // 初期状態を設定
    initializeCheckboxStates();
});