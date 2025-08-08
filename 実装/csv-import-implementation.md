# CSVインポート機能 実装ガイド

## 概要
社内求人IDを一意のキーとして、CSVデータのインポート・更新を行う独自機能の実装方針

## なぜ独自実装が必要か

### WP All Import等の既存プラグインの制限
1. **更新キーの柔軟性不足**
   - 投稿IDやスラッグでの更新が前提
   - カスタムフィールドをキーとした更新が複雑

2. **95フィールドの大量マッピング**
   - GUI操作が煩雑
   - エラー時のデバッグが困難

3. **カスタマイズ性**
   - データ変換ロジックの追加が制限的
   - エラーハンドリングのカスタマイズが困難

## 実装方針

### 1. 管理画面メニューの追加
```php
// functions.php または専用プラグイン
add_action('admin_menu', 'oks_add_csv_import_menu');

function oks_add_csv_import_menu() {
    add_submenu_page(
        'edit.php?post_type=job',
        'CSVインポート',
        'CSVインポート',
        'manage_options',
        'job-csv-import',
        'oks_csv_import_page'
    );
}
```

### 2. インポート処理のフロー

```php
/**
 * CSVインポート処理
 */
function oks_process_csv_import($file_path) {
    $results = [
        'created' => 0,
        'updated' => 0,
        'errors' => []
    ];
    
    // CSVファイルを開く
    $handle = fopen($file_path, 'r');
    if ($handle === false) {
        return ['error' => 'ファイルを開けません'];
    }
    
    // BOMを除去
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
        rewind($handle);
    }
    
    // ヘッダー行を読み込み
    $headers = fgetcsv($handle);
    
    // データ行を処理
    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($headers, $row);
        
        // 社内求人IDで既存投稿を検索
        $existing_post = oks_find_job_by_internal_id($data['社内求人ID']);
        
        if ($existing_post) {
            // 更新処理
            $result = oks_update_job($existing_post->ID, $data);
            if ($result) {
                $results['updated']++;
            } else {
                $results['errors'][] = "更新失敗: ID {$data['社内求人ID']}";
            }
        } else {
            // 新規作成
            $result = oks_create_job($data);
            if ($result) {
                $results['created']++;
            } else {
                $results['errors'][] = "作成失敗: ID {$data['社内求人ID']}";
            }
        }
    }
    
    fclose($handle);
    return $results;
}

/**
 * 社内求人IDで投稿を検索
 */
function oks_find_job_by_internal_id($internal_id) {
    $args = [
        'post_type' => 'job',
        'meta_query' => [
            [
                'key' => 'internal_job_id',
                'value' => $internal_id,
                'compare' => '='
            ]
        ],
        'posts_per_page' => 1
    ];
    
    $query = new WP_Query($args);
    return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * 求人情報を作成
 */
function oks_create_job($data) {
    // 投稿データ
    $post_data = [
        'post_type' => 'job',
        'post_status' => 'publish',
        'post_title' => $data['表示用タイトル'] ?: $data['管理用タイトル'],
        'post_content' => $data['仕事内容']
    ];
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        return false;
    }
    
    // ACFフィールドを更新
    return oks_update_job_fields($post_id, $data);
}

/**
 * 求人情報を更新
 */
function oks_update_job($post_id, $data) {
    // 投稿データを更新
    $post_data = [
        'ID' => $post_id,
        'post_title' => $data['表示用タイトル'] ?: $data['管理用タイトル'],
        'post_content' => $data['仕事内容']
    ];
    
    $result = wp_update_post($post_data);
    
    if (is_wp_error($result)) {
        return false;
    }
    
    // ACFフィールドを更新
    return oks_update_job_fields($post_id, $data);
}

/**
 * ACFフィールドを更新
 */
function oks_update_job_fields($post_id, $data) {
    // フィールドマッピング
    $field_mapping = [
        '削除' => 'deletion_flag',
        '社内求人ID' => 'internal_job_id',
        '採用中' => 'recruiting_status',
        '社外求人票の企業名表示' => 'company_name_display',
        '企業' => 'company',
        '管理用タイトル' => 'admin_title',
        '表示用タイトル' => 'display_title',
        '業界' => 'industry',
        '職種' => 'job_type',
        '仕事内容' => 'job_description',
        '業務内容の変更の範囲' => 'job_scope_change',
        '最低提示年収' => 'min_salary',
        '最高提示年収' => 'max_salary',
        '給与形態' => 'salary_type',
        '給与' => 'salary',
        '固定残業代の有無' => 'fixed_overtime_pay',
        '給与詳細' => 'salary_details',
        '固定残業代詳細' => 'fixed_overtime_details',
        '給与締日' => 'salary_closing_date',
        '給与支払日' => 'salary_payment_date',
        '昇給' => 'salary_increase',
        '賞与' => 'bonus',
        '勤務時間' => 'working_hours',
        '休憩時間' => 'break_time',
        '裁量労働制の有無' => 'discretionary_work',
        '裁量労働制の詳細' => 'discretionary_work_details',
        '月平均残業時間' => 'avg_overtime_hours',
        '月平均残業時間詳細' => 'avg_overtime_details',
        '休日' => 'holidays',
        '都道府県' => 'prefecture',
        '就業場所' => 'work_location',
        '就業場所詳細' => 'work_location_details',
        'アクセス' => 'access',
        '車通勤の可否' => 'car_commute',
        '自転車通勤の可否' => 'bike_commute',
        '就業場所の変更の範囲' => 'work_location_change',
        '転勤の可能性' => 'transfer_possibility',
        '受動喫煙対策の有無' => 'passive_smoking',
        '受動喫煙対策の詳細' => 'passive_smoking_details',
        '雇用形態' => 'employment_type',
        '応募区分' => 'application_category',
        '契約期間の有無' => 'contract_period',
        '契約の更新' => 'contract_renewal',
        '更新上限' => 'renewal_limit',
        '試用期間の有無' => 'probation_period',
        '試用期間' => 'probation_duration',
        '試用期間中の条件' => 'probation_conditions',
        '加入保険' => 'insurance',
        '手当・福利厚生' => 'benefits',
        '選考フロー' => 'selection_process',
        '選考詳細情報' => 'selection_details',
        '雇用期間（事業報告書用）' => 'employment_period_report',
        '募集開始日（事業報告書用）' => 'recruitment_start_date',
        '募集終了日（事業報告書用）' => 'recruitment_end_date',
        '募集人数（事業報告書用）' => 'recruitment_number',
        '取扱業務等の区分(2024)（事業報告書用）' => 'business_category_2024',
        '有効期間' => 'validity_period',
        '報酬パターン' => 'reward_pattern',
        '報酬割合（料率）' => 'reward_rate',
        '報酬金額（定額）' => 'reward_amount',
        '報酬詳細' => 'reward_details',
        '返金手数料' => 'refund_fee',
        '歓迎条件' => 'welcome_conditions',
        '募集背景' => 'recruitment_background',
        '働き方' => 'work_style',
        '勤務時の服装' => 'work_attire',
        'インセンティブ' => 'incentive',
        'ストックオプション' => 'stock_option',
        '応募条件（年齢）' => 'req_age',
        '応募条件（性別）' => 'req_gender',
        '応募条件（国籍）' => 'req_nationality',
        '応募条件（経験社数）' => 'req_companies',
        '応募条件（学歴）' => 'req_education',
        '応募条件（学歴詳細）' => 'req_education_details',
        '応募条件（職種経験年数）' => 'req_job_years',
        '応募条件（業種経験年数）' => 'req_industry_years',
        '応募条件（その他求める経験）' => 'req_other_experience',
        '必須条件' => 'required_conditions',
        // '歓迎条件' => 'welcome_conditions_2', // 重複のため2番目
        'NG対象' => 'ng_target',
        '内定の可能性が高い人' => 'high_offer_person',
        '推薦時の留意事項' => 'recommendation_notes',
        '面接確約条件' => 'interview_guarantee',
        '面接確約条件詳細' => 'interview_guarantee_details',
        '公開可能範囲' => 'public_range',
        '採用人数' => 'hiring_number',
        '求人入手元' => 'job_source',
        '求人ID（求人入手元）' => 'source_job_id',
        // '都道府県' => 'prefecture_2', // 重複のため2番目
        '市区町村' => 'city',
        // '職種' => 'job_type_2', // 重複のため2番目
        '年収' => 'annual_income',
        '土日祝休み' => 'weekend_holiday',
        '残業少なめ' => 'low_overtime',
        'リモートワーク' => 'remote_work'
    ];
    
    try {
        foreach ($field_mapping as $csv_key => $acf_key) {
            if (isset($data[$csv_key])) {
                $value = $data[$csv_key];
                
                // True/Falseフィールドの変換
                $bool_fields = [
                    'deletion_flag', 'company_name_display', 'fixed_overtime_pay',
                    'discretionary_work', 'car_commute', 'bike_commute',
                    'passive_smoking', 'contract_period', 'probation_period',
                    'weekend_holiday', 'low_overtime', 'remote_work'
                ];
                
                if (in_array($acf_key, $bool_fields)) {
                    $value = ($value === '1' || $value === 'true' || $value === 'TRUE') ? 1 : 0;
                }
                
                // 数値フィールドの変換
                $number_fields = [
                    'min_salary', 'max_salary', 'recruitment_number',
                    'reward_amount', 'hiring_number', 'annual_income'
                ];
                
                if (in_array($acf_key, $number_fields)) {
                    $value = intval($value);
                }
                
                // ACFフィールドを更新
                update_field($acf_key, $value, $post_id);
            }
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log('ACFフィールド更新エラー: ' . $e->getMessage());
        return false;
    }
}
```

### 3. 管理画面UI

```php
/**
 * CSVインポート画面
 */
function oks_csv_import_page() {
    ?>
    <div class="wrap">
        <h1>求人情報CSVインポート</h1>
        
        <?php
        if (isset($_POST['submit']) && isset($_FILES['csv_file'])) {
            oks_handle_csv_upload();
        }
        ?>
        
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('oks_csv_import', 'oks_csv_import_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="csv_file">CSVファイル</label>
                    </th>
                    <td>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                        <p class="description">
                            UTF-8（BOM付き）のCSVファイルを選択してください。<br>
                            社内求人IDが既に存在する場合は、データが更新されます。
                        </p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('インポート開始'); ?>
        </form>
        
        <div class="oks-import-notes">
            <h3>注意事項</h3>
            <ul>
                <li>CSVファイルは必ず95列すべてが含まれている必要があります</li>
                <li>1行目はヘッダー行として扱われます</li>
                <li>True/Falseの値は「1」または「0」で指定してください</li>
                <li>年収は数値のみ（300万円の場合は「3000000」）で入力してください</li>
                <li>大量データの場合は、処理に時間がかかる場合があります</li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * CSVアップロード処理
 */
function oks_handle_csv_upload() {
    // セキュリティチェック
    if (!isset($_POST['oks_csv_import_nonce']) || 
        !wp_verify_nonce($_POST['oks_csv_import_nonce'], 'oks_csv_import')) {
        wp_die('不正なアクセスです');
    }
    
    // ファイルチェック
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="notice notice-error"><p>ファイルのアップロードに失敗しました。</p></div>';
        return;
    }
    
    // 一時ファイルパス
    $tmp_file = $_FILES['csv_file']['tmp_name'];
    
    // インポート実行
    $results = oks_process_csv_import($tmp_file);
    
    // 結果表示
    if (isset($results['error'])) {
        echo '<div class="notice notice-error"><p>' . esc_html($results['error']) . '</p></div>';
    } else {
        echo '<div class="notice notice-success"><p>';
        echo '新規作成: ' . $results['created'] . '件<br>';
        echo '更新: ' . $results['updated'] . '件';
        echo '</p></div>';
        
        if (!empty($results['errors'])) {
            echo '<div class="notice notice-warning"><p>エラー:<br>';
            foreach ($results['errors'] as $error) {
                echo esc_html($error) . '<br>';
            }
            echo '</p></div>';
        }
    }
}
```

### 4. バッチ処理対応（大量データ用）

```php
/**
 * Ajax経由でのバッチ処理
 */
add_action('wp_ajax_oks_import_batch', 'oks_ajax_import_batch');

function oks_ajax_import_batch() {
    $batch_size = 50; // 一度に処理する件数
    $offset = intval($_POST['offset'] ?? 0);
    
    // セッションからファイルパスを取得
    session_start();
    $file_path = $_SESSION['oks_csv_file'] ?? null;
    
    if (!$file_path || !file_exists($file_path)) {
        wp_send_json_error('ファイルが見つかりません');
    }
    
    // バッチ処理実行
    $results = oks_process_csv_batch($file_path, $offset, $batch_size);
    
    wp_send_json_success($results);
}
```

## 推奨される追加機能

### 1. プレビュー機能
インポート前にデータをプレビューし、マッピングを確認

### 2. エラーログ出力
失敗したレコードの詳細をCSVファイルで出力

### 3. ドライラン機能
実際の登録を行わずに、処理結果をシミュレーション

### 4. 進捗表示
大量データインポート時のプログレスバー表示

### 5. スケジュール実行
WP-Cronを使用した定期的な自動インポート

## まとめ

独自実装により以下のメリットが得られます：

1. **柔軟な重複チェック** - 社内求人IDでの確実な更新
2. **エラーハンドリング** - 詳細なエラー情報の取得
3. **パフォーマンス** - バッチ処理による大量データ対応
4. **カスタマイズ性** - 将来的な要件変更への対応が容易
5. **デバッグ性** - 問題発生時の原因特定が容易