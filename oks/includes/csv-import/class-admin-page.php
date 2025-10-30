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
                                        社内求人IDが既に存在する場合は、既存データを上書きして更新されます。
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php submit_button('インポート開始', 'primary', 'submit'); ?>
                </form>
                
                <div class="oks-import-notes">
                    <h3>インポート時の注意事項</h3>
                    <ul>
                        <li>CSVファイルは必ず146列すべてが含まれている必要があります</li>
                        <li>1行目はヘッダー行として扱われます</li>
                        <li>大量データの場合は、処理に時間がかかる場合があります</li>
                    </ul>
                    
                    <h3>CSVファイル形式</h3>
                    <p>新しい146列フォーマットに対応しています。主要なフィールド（抜粋）:</p>
                    <ul>
                        <li>削除 - 削除フラグ（1で削除実行）</li>
                        <li>社内求人ID - 必須</li>
                        <li>表示用タイトル - 求人のタイトル</li>
                        <li>企業 - 企業名</li>
                        <li>全フィールドはtextarea形式で処理されます</li>
                    </ul>
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
        
        // Always use update mode (overwrite existing data)
        $import_mode = 'update';
        
        // Process import
        $importer = new OKS_Job_Importer();
        $result = $importer->import($_FILES['csv_file']['tmp_name'], $import_mode);
        
        // Show results
        if ($result['success']) {
            $message = sprintf(
                'インポートが完了しました。新規作成: %d件、更新: %d件、スキップ: %d件（社内求人ID未入力）',
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
            
            // Display detailed error information if available
            if (isset($result['details']) && !empty($result['details'])) {
                $this->show_detailed_errors($result['details']);
            }
        }
    }
    
    /**
     * Show admin notice
     */
    private function show_notice($type, $message) {
        $class = 'notice notice-' . $type;
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
    
    /**
     * Show detailed error information
     */
    private function show_detailed_errors($details) {
        echo '<div class="notice notice-error" style="margin-top: 10px;">';
        echo '<h4>詳細エラー情報:</h4>';
        echo '<div style="background: #f8f8f8; padding: 10px; border-left: 4px solid #dc3232; font-family: monospace; white-space: pre-wrap;">';
        
        if (is_array($details)) {
            foreach ($details as $detail) {
                echo esc_html($detail) . "\n";
            }
        } else {
            echo esc_html($details);
        }
        
        echo '</div>';
        echo '</div>';
    }
}