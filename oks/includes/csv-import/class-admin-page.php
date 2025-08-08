<?php
/**
 * CSV Import Admin Page
 * 
 * @package OKS
 * @subpackage CSV_Import
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Page Class
 */
class OKS_CSV_Import_Admin_Page {
    
    /**
     * Initialize
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=job',
            'CSVインポート',
            'CSVインポート',
            'manage_options',
            'oks-csv-import',
            array($this, 'render_page')
        );
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        if ('job_page_oks-csv-import' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'oks-csv-import-admin',
            OKS_CSV_IMPORT_URL . 'assets/admin.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Render admin page
     */
    public function render_page() {
        // Handle form submission
        if (isset($_POST['submit']) && isset($_FILES['csv_file'])) {
            $this->handle_import();
        }
        
        ?>
        <div class="wrap">
            <h1>求人情報CSVインポート</h1>
            
            <div class="oks-import-container">
                <form method="post" enctype="multipart/form-data" class="oks-import-form">
                    <?php wp_nonce_field('oks_csv_import', 'oks_csv_import_nonce'); ?>
                    
                    <table class="form-table">
                        <tbody>
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
                            <tr>
                                <th scope="row">インポートモード</th>
                                <td>
                                    <label>
                                        <input type="radio" name="import_mode" value="update" checked>
                                        更新モード（既存データを上書き）
                                    </label><br>
                                    <label>
                                        <input type="radio" name="import_mode" value="skip">
                                        スキップモード（既存データはスキップ）
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php submit_button('インポート開始', 'primary', 'submit'); ?>
                </form>
                
                <div class="oks-import-notes">
                    <h3>インポート時の注意事項</h3>
                    <ul>
                        <li>CSVファイルは必ず95列すべてが含まれている必要があります</li>
                        <li>1行目はヘッダー行として扱われます</li>
                        <li>True/Falseの値は「1」または「0」で指定してください</li>
                        <li>年収は数値のみ（300万円の場合は「3000000」）で入力してください</li>
                        <li>日付は「YYYY-MM-DD」形式で入力してください</li>
                        <li>大量データの場合は、処理に時間がかかる場合があります</li>
                    </ul>
                    
                    <h3>CSVファイル形式</h3>
                    <p>以下の順番でカラムを配置してください：</p>
                    <ol style="font-size: 12px; column-count: 3;">
                        <li>削除</li>
                        <li>社内求人ID</li>
                        <li>採用中</li>
                        <li>社外求人票の企業名表示</li>
                        <li>企業</li>
                        <li>管理用タイトル</li>
                        <li>表示用タイトル</li>
                        <li>業界</li>
                        <li>職種</li>
                        <li>仕事内容</li>
                        <li>業務内容の変更の範囲</li>
                        <li>最低提示年収</li>
                        <li>最高提示年収</li>
                        <li>給与形態</li>
                        <li>給与</li>
                        <li>固定残業代の有無</li>
                        <li>給与詳細</li>
                        <li>固定残業代詳細</li>
                        <li>給与締日</li>
                        <li>給与支払日</li>
                        <li>昇給</li>
                        <li>賞与</li>
                        <li>勤務時間</li>
                        <li>休憩時間</li>
                        <li>裁量労働制の有無</li>
                        <li>裁量労働制の詳細</li>
                        <li>月平均残業時間</li>
                        <li>月平均残業時間詳細</li>
                        <li>休日</li>
                        <li>都道府県</li>
                        <li>就業場所</li>
                        <li>就業場所詳細</li>
                        <li>アクセス</li>
                        <li>車通勤の可否</li>
                        <li>自転車通勤の可否</li>
                        <li>就業場所の変更の範囲</li>
                        <li>転勤の可能性</li>
                        <li>受動喫煙対策の有無</li>
                        <li>受動喫煙対策の詳細</li>
                        <li>雇用形態</li>
                        <li>応募区分</li>
                        <li>契約期間の有無</li>
                        <li>契約の更新</li>
                        <li>更新上限</li>
                        <li>試用期間の有無</li>
                        <li>試用期間</li>
                        <li>試用期間中の条件</li>
                        <li>加入保険</li>
                        <li>手当・福利厚生</li>
                        <li>選考フロー</li>
                        <li>選考詳細情報</li>
                        <li>雇用期間（事業報告書用）</li>
                        <li>募集開始日（事業報告書用）</li>
                        <li>募集終了日（事業報告書用）</li>
                        <li>募集人数（事業報告書用）</li>
                        <li>取扱業務等の区分(2024)（事業報告書用）</li>
                        <li>有効期間</li>
                        <li>報酬パターン</li>
                        <li>報酬割合（料率）</li>
                        <li>報酬金額（定額）</li>
                        <li>報酬詳細</li>
                        <li>返金手数料</li>
                        <li>歓迎条件</li>
                        <li>募集背景</li>
                        <li>働き方</li>
                        <li>勤務時の服装</li>
                        <li>インセンティブ</li>
                        <li>ストックオプション</li>
                        <li>応募条件（年齢）</li>
                        <li>応募条件（性別）</li>
                        <li>応募条件（国籍）</li>
                        <li>応募条件（経験社数）</li>
                        <li>応募条件（学歴）</li>
                        <li>応募条件（学歴詳細）</li>
                        <li>応募条件（職種経験年数）</li>
                        <li>応募条件（業種経験年数）</li>
                        <li>応募条件（その他求める経験）</li>
                        <li>必須条件</li>
                        <li>歓迎条件</li>
                        <li>NG対象</li>
                        <li>内定の可能性が高い人</li>
                        <li>推薦時の留意事項</li>
                        <li>面接確約条件</li>
                        <li>面接確約条件詳細</li>
                        <li>公開可能範囲</li>
                        <li>採用人数</li>
                        <li>求人入手元</li>
                        <li>求人ID（求人入手元）</li>
                        <li>都道府県</li>
                        <li>市区町村</li>
                        <li>職種</li>
                        <li>年収</li>
                        <li>土日祝休み</li>
                        <li>残業少なめ</li>
                        <li>リモートワーク</li>
                    </ol>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle import
     */
    private function handle_import() {
        // Security check
        if (!isset($_POST['oks_csv_import_nonce']) || 
            !wp_verify_nonce($_POST['oks_csv_import_nonce'], 'oks_csv_import')) {
            wp_die('不正なアクセスです');
        }
        
        // Check file
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->show_notice('error', 'ファイルのアップロードに失敗しました。');
            return;
        }
        
        // Get import mode
        $import_mode = sanitize_text_field($_POST['import_mode'] ?? 'update');
        
        // Process import
        $importer = new OKS_Job_Importer();
        $result = $importer->import($_FILES['csv_file']['tmp_name'], $import_mode);
        
        // Show results
        if ($result['success']) {
            $message = sprintf(
                'インポートが完了しました。新規作成: %d件、更新: %d件、スキップ: %d件',
                $result['created'],
                $result['updated'],
                $result['skipped']
            );
            $this->show_notice('success', $message);
            
            if (!empty($result['errors'])) {
                $error_message = 'エラーが発生した行: ' . implode(', ', array_keys($result['errors']));
                $this->show_notice('warning', $error_message);
            }
        } else {
            $this->show_notice('error', $result['message']);
        }
    }
    
    /**
     * Show admin notice
     */
    private function show_notice($type, $message) {
        $class = 'notice notice-' . $type;
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}