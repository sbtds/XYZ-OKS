# OKS テーマ実装ガイド

## 概要

このディレクトリには、WordPress テーマ「OKS」の求人検索機能の実装に必要なファイルと仕様書が含まれています。

## ディレクトリ構成

```
実装/
├── README.md                      # このファイル
├── implementation-guidelines.md   # 実装ガイドライン（必読）
├── job-search-implementation.md   # 実装方針詳細
├── csv-import-implementation.md   # CSVインポート実装ガイド
├── LasCSV.csv                    # 求人データサンプル（95カラム）
├── acf-job-fields.json           # ACFフィールド定義（インポート用）
└── search.txt                    # 初期仕様メモ
```

## テーマ内実装構成

```
oks/
├── functions.php                  # メイン機能読み込み
├── page-job-search.php           # 求人検索ページテンプレート
├── single-job.php                # 求人詳細ページテンプレート
├── assets/
│   └── css/
│       └── single-job.css        # 求人詳細ページスタイル
├── includes/
│   ├── csv-import/               # CSVインポート機能
│   │   ├── csv-import-loader.php # メインローダー
│   │   ├── class-admin-page.php  # 管理画面UI
│   │   ├── class-csv-processor.php # CSV処理ロジック
│   │   ├── class-job-importer.php  # インポート処理
│   │   └── assets/
│   │       └── admin.css         # 管理画面スタイル
│   ├── job-search/               # 求人検索機能
│   │   ├── job-search-loader.php # メインローダー
│   │   ├── class-search-form.php # 検索フォーム
│   │   ├── class-search-handler.php # 検索処理
│   │   ├── class-search-data.php    # データ取得
│   │   └── assets/
│   │       ├── job-search.js     # JavaScript
│   │       └── job-search.css    # CSS
│   └── comment-disable/          # コメント無効化機能
│       ├── comment-disable-loader.php # メインローダー
│       └── class-comment-disable.php  # コメント無効化処理
├── header.php                   # 共通ヘッダー
├── footer.php                   # 共通フッター
├── archive-company.php          # 注目企業一覧ページ
├── single-company.php           # 注目企業詳細ページ
└── taxonomy-company_area.php    # エリア別企業一覧ページ
```

## 実装ガイドライン

新機能を追加する際は、必ず以下の実装ガイドラインに従ってください：

**→ [implementation-guidelines.md](implementation-guidelines.md)**

ガイドラインには以下の内容が含まれています：

- モジュール構造の規則（`includes/機能名/` ディレクトリ構成）
- 命名規則（クラス名、ファイル名、定数名）
- セキュリティ対策（nonce 検証、権限チェック、サニタイズ）
- ベストプラクティス

## 実装状況

### 完了済み

- [x] CSV データ構造の分析
- [x] ACF カスタムフィールドの設計
- [x] ACF インポート用 JSON の作成（95 フィールド）
- [x] カスタム投稿タイプ「job」の登録
- [x] CSV インポート機能の実装
- [x] 検索フォームの UI コンポーネント
- [x] 検索ロジックの実装（カスタムフィールド検索対応）
- [x] 検索結果表示テンプレート
- [x] Ajax 検索機能
- [x] ページネーション機能
- [x] ソート機能（新着順、年収順など）
- [x] 求人詳細ページテンプレート（single-job.php）
- [x] キーワード検索の精度向上（空値除外、重要フィールド限定）
- [x] コメント無効化機能（comment-disable）
- [x] 注目企業機能（company）
  - [x] 注目企業アーカイブページ（archive-company.php）
  - [x] 注目企業詳細ページ（single-company.php）
  - [x] エリア別アーカイブページ（taxonomy-company_area.php）
  - [x] カスタムタクソノミー「company_area」対応
  - [x] ACF フィールド 6 ブロック対応（タイトル空値非表示機能付き）

### 未実装

- [ ] 求人一覧ページテンプレート（archive-job.php）
- [ ] 検索結果の SEO 最適化
- [ ] 求人応募機能

## データ構造

### カスタム投稿タイプ

- **投稿タイプ名**: `job`
- **各求人情報を 1 記事として管理**

### カスタムフィールド構成（ACF）

#### 基本情報

- `internal_job_id`: 社内求人 ID
- `company`: 企業名
- `display_title`: 表示用タイトル
- `job_type`: 職種
- `job_description`: 仕事内容

#### 給与・待遇

- `min_salary`: 最低提示年収（数値）
- `max_salary`: 最高提示年収（数値）
- `salary_type`: 給与形態
- `bonus`: 賞与
- `benefits`: 手当・福利厚生

#### 勤務条件

- `working_hours`: 勤務時間
- `avg_overtime_hours`: 月平均残業時間
- `holidays`: 休日
- `weekend_holiday`: 土日祝休み（True/False）
- `low_overtime`: 残業少なめ（True/False）
- `remote_work`: リモートワーク（True/False）

#### 勤務地

- `prefecture`: 都道府県
- `city`: 市区町村
- `work_location`: 就業場所
- `access`: アクセス

#### 応募条件

- `required_conditions`: 必須条件
- `welcome_conditions`: 歓迎条件
- `req_education`: 応募条件（学歴）
- `req_job_years`: 応募条件（職種経験年数）

### CSV インポート仕様

#### CSV フォーマット

- **カラム数**: 95 列
- **文字コード**: UTF-8（BOM 付き）
- **True/False 値**: 1/0 で表現
- **年収**: 数値のみ（例：3000000 = 300 万円）

#### インポート手順

1. ACF プラグインをインストールして `acf-job-fields.json` をインポート
2. WordPress 管理画面 → 求人管理 → CSV インポート
3. UTF-8（BOM 付き）の CSV ファイルをアップロード
4. インポートモード選択（更新/スキップ）
5. インポート実行（社内求人 ID での重複チェック・更新）

## 検索機能仕様

### 検索条件

1. **勤務地**

   - 都道府県・市区町村の階層型選択
   - 複数選択可能

2. **職種**

   - プルダウンまたはチェックボックス
   - 複数選択可能

3. **年収**

   - 範囲指定（最小値・最大値）
   - 数値入力またはプルダウン選択

4. **こだわり条件**

   - 約 50 項目のチェックボックス
   - AND 検索（選択した全条件を満たす）

5. **キーワード検索**
   - フリーテキスト入力

### 検索 URL パターン

```
/search/?prefecture=北海道&city[]=札幌市中央区&job_type[]=営業&salary_min=3000000&salary_max=5000000&conditions[]=weekend_holiday&conditions[]=remote_work
```

## セットアップ手順

1. **テーマの有効化**
   WordPress 管理画面 → 外観 → テーマ → OKS テーマを有効化

2. **ACF プラグインのインストール**
   WordPress 管理画面 → プラグイン → Advanced Custom Fields（無料版）をインストール・有効化

3. **ACF フィールドのインポート**

   ```
   管理画面 → カスタムフィールド → ツール → インポート
   acf-job-fields.json を選択してインポート
   ```

4. **求人検索ページの作成**

   ```
   管理画面 → 固定ページ → 新規追加
   ページタイトル: 求人検索
   テンプレート: 求人検索ページ
   ```

5. **CSV データのインポート**
   ```
   管理画面 → 求人管理 → CSVインポート
   LasCSV.csv を選択してアップロード・インポート
   ```

## 機能の使い方

### CSV インポート機能

- 管理画面の「求人管理」→「CSV インポート」からアクセス
- 社内求人 ID をキーとした重複チェック・更新
- 更新モード/スキップモードの選択可能
- インポート結果の詳細表示

### 検索機能

- 固定ページテンプレート「求人検索ページ」を使用
- ショートコード `[oks_job_search]` で任意の場所に設置可能
- Ajax 検索によるリアルタイム結果更新
- 複数条件での絞り込み検索
- キーワード検索：投稿タイトル・内容・カスタムフィールド対応
- 空値・NULL 値を除外した正確な検索結果
- 重要フィールドに限定した高速検索

## 開発時の注意事項

- CSV インポート時は文字コードに注意（UTF-8 BOM 付き）
- True/False フィールドは 1/0 で自動変換される
- 年収は数値型で保存（表示時に「万円」を付与）
- 検索パフォーマンスを考慮してインデックスを検討
- 大量データの場合は Ajax 検索の実装を推奨

## 技術仕様

### アーキテクチャ

- **モジュール化**: includes/ディレクトリ内に機能別クラス分割
- **オブジェクト指向**: 各機能をクラスベースで実装
- **シングルトンパターン**: メインローダーでインスタンス管理
- **フック活用**: WordPress のアクション・フィルターフックを適切に使用

### セキュリティ

- **nonce 検証**: フォーム送信時の CSRF 対策
- **権限チェック**: 管理機能へのアクセス制限
- **データサニタイズ**: ユーザー入力のサニタイズ・エスケープ
- **ファイルアップロード検証**: CSV ファイルの妥当性チェック

### パフォーマンス

- **遅延読み込み**: 必要な場面でのみスクリプト・スタイル読み込み
- **Ajax 検索**: ページリロードなしの検索結果更新
- **ページネーション**: 大量データの分割表示
- **最適化されたキーワード検索**: post\_\_in を使用した効率的なクエリ
- **検索対象フィールドの限定**: 重要な 16 フィールドのみに絞り込み

## AI チャット連携用情報

このテーマを理解するために必要な情報：

- **完全実装済み**: CSV インポート機能と検索機能が動作可能
- カスタム投稿タイプ「job」で求人を管理
- 95 個の ACF フィールドでデータを保存
- 社内求人 ID をキーとした独自 CSV インポート機能
- Ajax 対応の高機能検索システム
- 求人詳細ページテンプレート完備
- 精密なキーワード検索（企業名「ハーゼスト」等で正確に検索可能）
- レスポンシブデザイン対応
- コメント機能の完全無効化

## 関連ファイル

- **実装ガイドライン**: `implementation-guidelines.md` ※新機能追加時は必読
- **実装方針**: `job-search-implementation.md`
- **CSV インポート実装**: `csv-import-implementation.md`
- **ACF 定義**: `acf-job-fields.json`
- **サンプルデータ**: `LasCSV.csv`
- **初期仕様**: `search.txt`
