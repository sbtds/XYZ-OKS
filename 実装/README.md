# OKSテーマ実装ガイド

## 概要

このディレクトリには、WordPressテーマ「OKS」の求人検索機能の実装に必要なファイルと仕様書が含まれています。

## ディレクトリ構成

```
実装/
├── README.md                      # このファイル
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
├── includes/
│   ├── csv-import/               # CSVインポート機能
│   │   ├── csv-import-loader.php # メインローダー
│   │   ├── class-admin-page.php  # 管理画面UI
│   │   ├── class-csv-processor.php # CSV処理ロジック
│   │   ├── class-job-importer.php  # インポート処理
│   │   └── assets/
│   │       └── admin.css         # 管理画面スタイル
│   └── job-search/               # 求人検索機能
│       ├── job-search-loader.php # メインローダー
│       ├── class-search-form.php # 検索フォーム
│       ├── class-search-handler.php # 検索処理
│       ├── class-search-data.php    # データ取得
│       └── assets/
│           ├── job-search.js     # JavaScript
│           └── job-search.css    # CSS
```

## 実装状況

### 完了済み
- [x] CSVデータ構造の分析
- [x] ACFカスタムフィールドの設計
- [x] ACF インポート用JSONの作成（95フィールド）
- [x] カスタム投稿タイプ「job」の登録
- [x] CSVインポート機能の実装
- [x] 検索フォームのUIコンポーネント
- [x] 検索ロジックの実装
- [x] 検索結果表示テンプレート
- [x] Ajax検索機能
- [x] ページネーション機能
- [x] ソート機能（新着順、年収順など）

### 未実装
- [ ] 求人詳細ページテンプレート（single-job.php）
- [ ] 求人一覧ページテンプレート（archive-job.php）
- [ ] 検索結果のSEO最適化

## データ構造

### カスタム投稿タイプ
- **投稿タイプ名**: `job`
- **各求人情報を1記事として管理**

### カスタムフィールド構成（ACF）

#### 基本情報
- `internal_job_id`: 社内求人ID
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

### CSVインポート仕様

#### CSVフォーマット
- **カラム数**: 95列
- **文字コード**: UTF-8（BOM付き）
- **True/False値**: 1/0で表現
- **年収**: 数値のみ（例：3000000 = 300万円）

#### インポート手順
1. ACFプラグインをインストールして `acf-job-fields.json` をインポート
2. WordPress管理画面 → 求人管理 → CSVインポート
3. UTF-8（BOM付き）のCSVファイルをアップロード
4. インポートモード選択（更新/スキップ）
5. インポート実行（社内求人IDでの重複チェック・更新）

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
   - 約50項目のチェックボックス
   - AND検索（選択した全条件を満たす）

5. **キーワード検索**
   - フリーテキスト入力

### 検索URLパターン
```
/job-search/?prefecture=北海道&city[]=札幌市中央区&job_type[]=営業&salary_min=3000000&salary_max=5000000&conditions[]=weekend_holiday&conditions[]=remote_work
```

## セットアップ手順

1. **テーマの有効化**
   WordPress管理画面 → 外観 → テーマ → OKSテーマを有効化

2. **ACFプラグインのインストール**
   WordPress管理画面 → プラグイン → Advanced Custom Fields（無料版）をインストール・有効化

3. **ACFフィールドのインポート**
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

5. **CSVデータのインポート**
   ```
   管理画面 → 求人管理 → CSVインポート
   LasCSV.csv を選択してアップロード・インポート
   ```

## 機能の使い方

### CSVインポート機能
- 管理画面の「求人管理」→「CSVインポート」からアクセス
- 社内求人IDをキーとした重複チェック・更新
- 更新モード/スキップモードの選択可能
- インポート結果の詳細表示

### 検索機能
- 固定ページテンプレート「求人検索ページ」を使用
- ショートコード `[oks_job_search]` で任意の場所に設置可能
- Ajax検索によるリアルタイム結果更新
- 複数条件での絞り込み検索

## 開発時の注意事項

- CSVインポート時は文字コードに注意（UTF-8 BOM付き）
- True/Falseフィールドは1/0で自動変換される
- 年収は数値型で保存（表示時に「万円」を付与）
- 検索パフォーマンスを考慮してインデックスを検討
- 大量データの場合はAjax検索の実装を推奨

## 技術仕様

### アーキテクチャ
- **モジュール化**: includes/ディレクトリ内に機能別クラス分割
- **オブジェクト指向**: 各機能をクラスベースで実装
- **シングルトンパターン**: メインローダーでインスタンス管理
- **フック活用**: WordPressのアクション・フィルターフックを適切に使用

### セキュリティ
- **nonce検証**: フォーム送信時のCSRF対策
- **権限チェック**: 管理機能へのアクセス制限
- **データサニタイズ**: ユーザー入力のサニタイズ・エスケープ
- **ファイルアップロード検証**: CSVファイルの妥当性チェック

### パフォーマンス
- **遅延読み込み**: 必要な場面でのみスクリプト・スタイル読み込み
- **Ajax検索**: ページリロードなしの検索結果更新
- **ページネーション**: 大量データの分割表示
- **インデックス活用**: データベースクエリの最適化

## AIチャット連携用情報

このテーマを理解するために必要な情報：
- **完全実装済み**: CSVインポート機能と検索機能が動作可能
- カスタム投稿タイプ「job」で求人を管理
- 95個のACFフィールドでデータを保存
- 社内求人IDをキーとした独自CSVインポート機能
- Ajax対応の高機能検索システム
- レスポンシブデザイン対応

## 関連ファイル

- **実装方針**: `job-search-implementation.md`
- **CSVインポート実装**: `csv-import-implementation.md`
- **ACF定義**: `acf-job-fields.json`
- **サンプルデータ**: `LasCSV.csv`
- **初期仕様**: `search.txt`