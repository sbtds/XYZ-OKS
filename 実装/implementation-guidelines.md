# OKSテーマ 実装ガイドライン

## 機能追加の実装ルール

### ディレクトリ構成規則

新機能を追加する際は、以下のモジュール構造に従って実装してください：

```
oks/
└── includes/
    └── [機能名]/                        # 機能ごとのディレクトリ（ハイフン区切り）
        ├── [機能名]-loader.php          # メインローダーファイル（シングルトン）
        ├── class-*.php                  # 機能実装クラス
        └── assets/                      # CSS/JS等のアセット
            ├── [機能名].css
            └── [機能名].js
```

### 実装手順

#### 1. モジュールディレクトリの作成

```bash
# 例: コメント無効化機能の場合
mkdir -p includes/comment-disable
```

#### 2. ローダーファイルの作成

`[機能名]-loader.php` ファイルを作成し、以下のテンプレートに従って実装：

```php
<?php
/**
 * OKS [機能名] Module Loader
 *
 * @package OKS
 * @subpackage [機能名]
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('OKS_[機能名]_Loader')) {
    /**
     * [機能名] Module Loader Class
     */
    class OKS_[機能名]_Loader {
        /**
         * Instance
         *
         * @var OKS_[機能名]_Loader
         */
        private static $instance = null;

        /**
         * Get instance
         *
         * @return OKS_[機能名]_Loader
         */
        public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        private function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
        }

        /**
         * Define constants
         */
        private function define_constants() {
            define('OKS_[機能名大文字]_DIR', get_template_directory() . '/includes/[機能名]/');
            define('OKS_[機能名大文字]_URL', get_template_directory_uri() . '/includes/[機能名]/');
        }

        /**
         * Include required files
         */
        private function includes() {
            require_once OKS_[機能名大文字]_DIR . 'class-[機能名].php';
        }

        /**
         * Initialize hooks
         */
        private function init_hooks() {
            $instance = OKS_[機能名]::get_instance();
            $instance->init();
        }
    }

    OKS_[機能名]_Loader::get_instance();
}
```

#### 3. 機能実装クラスの作成

`class-[機能名].php` ファイルを作成：

```php
<?php
/**
 * OKS [機能名] Class
 *
 * @package OKS
 * @subpackage [機能名]
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('OKS_[機能名]')) {
    /**
     * [機能名] Class
     */
    class OKS_[機能名] {
        /**
         * Instance
         *
         * @var OKS_[機能名]
         */
        private static $instance = null;

        /**
         * Get instance
         *
         * @return OKS_[機能名]
         */
        public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        private function __construct() {
        }

        /**
         * Initialize
         */
        public function init() {
            // フックの登録
            // add_action(), add_filter() など
        }

        // 機能実装メソッド
    }
}
```

#### 4. functions.phpへの登録

`functions.php` に以下のコードを追加：

```php
/**
 * Include [機能名] functionality
 */
if (file_exists(get_template_directory() . '/includes/[機能名]/[機能名]-loader.php')) {
    require_once get_template_directory() . '/includes/[機能名]/[機能名]-loader.php';
}
```

### 命名規則

#### ディレクトリ・ファイル名
- **小文字**、**ハイフン区切り**を使用
- 例: `comment-disable`, `user-profile`, `custom-widget`

#### クラス名
- **PascalCase**を使用
- プレフィックス `OKS_` を必ず付与
- 例: `OKS_Comment_Disable`, `OKS_User_Profile`

#### 定数名
- **大文字**、**アンダースコア区切り**を使用
- プレフィックス `OKS_` を必ず付与
- 例: `OKS_COMMENT_DISABLE_DIR`, `OKS_USER_PROFILE_URL`

#### 関数・メソッド名
- **小文字**、**アンダースコア区切り**を使用
- 例: `disable_comments()`, `get_user_profile()`

### アセット管理

#### CSS/JSファイルの配置
```
includes/[機能名]/assets/
├── [機能名].css      # メインスタイルシート
├── [機能名].js       # メインJavaScript
└── admin.css         # 管理画面用CSS（必要な場合）
```

#### アセットの読み込み
```php
// フロントエンド用
add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));

// 管理画面用
add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
```

### セキュリティ対策

#### 1. 直接アクセス防止
全てのPHPファイルの冒頭に追加：
```php
if (!defined('ABSPATH')) {
    exit;
}
```

#### 2. nonce検証
フォーム送信時は必ずnonce検証を実装：
```php
// nonce生成
wp_nonce_field('oks_[機能名]_action', 'oks_[機能名]_nonce');

// nonce検証
if (!isset($_POST['oks_[機能名]_nonce']) || 
    !wp_verify_nonce($_POST['oks_[機能名]_nonce'], 'oks_[機能名]_action')) {
    wp_die('Security check failed');
}
```

#### 3. 権限チェック
管理機能へのアクセス時は権限を確認：
```php
if (!current_user_can('manage_options')) {
    wp_die('Permission denied');
}
```

#### 4. データサニタイズ
ユーザー入力は必ずサニタイズ：
```php
// テキスト入力
$text = sanitize_text_field($_POST['text']);

// URL
$url = esc_url_raw($_POST['url']);

// HTML出力
echo esc_html($variable);
```

### ベストプラクティス

#### 1. シングルトンパターンの使用
- リソースの効率的な管理
- グローバルアクセスの提供
- インスタンスの一意性保証

#### 2. フックの適切な使用
- WordPressのライフサイクルに従う
- 適切なタイミングでの処理実行
- 優先度の考慮

#### 3. 条件付き読み込み
```php
// 特定のページでのみアセットを読み込む
if (is_page('contact')) {
    wp_enqueue_script('oks-contact-form');
}
```

#### 4. エラーハンドリング
```php
try {
    // 処理
} catch (Exception $e) {
    error_log('OKS Theme Error: ' . $e->getMessage());
}
```

### 実装済み機能の例

#### 1. CSVインポート機能 (`csv-import`)
- 管理画面でのCSVファイルアップロード
- データ処理とインポート
- 進捗表示とエラーハンドリング

#### 2. 求人検索機能 (`job-search`)
- 検索フォームの表示
- Ajax検索処理
- 検索結果の表示

#### 3. コメント無効化機能 (`comment-disable`)
- 全投稿タイプでのコメント無効化
- 管理画面からのコメント機能削除
- フロントエンドでのコメント非表示

### トラブルシューティング

#### 機能が読み込まれない場合
1. ファイルパスが正しいか確認
2. クラス名の重複がないか確認
3. PHPエラーログを確認

#### アセットが読み込まれない場合
1. URLパスが正しいか確認
2. wp_enqueue_*の優先度を確認
3. 依存関係を確認

### 今後の拡張予定

- [ ] 自動テスト環境の構築
- [ ] コード品質チェックツールの導入
- [ ] ドキュメント自動生成
- [ ] パフォーマンス最適化ガイドライン