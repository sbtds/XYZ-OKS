# OKSテーマ - 求人検索機能 実装方針

## 概要
CSVインポートによる求人情報登録と、複数条件での絞り込み検索機能の実装

## データ構造設計

### カスタム投稿タイプ
- 投稿タイプ名: `job`
- 各求人情報を1記事として登録

### カスタムフィールド（ACF）構成
すべてCSVインポートの簡便性を考慮してカスタムフィールドで実装

1. **都道府県**
   - フィールド名: `prefecture` (都道府県)
   - フィールドタイプ: テキスト
   - フィールド名: `city` (市区町村)
   - フィールドタイプ: テキスト

2. **職種**
   - フィールド名: `job_type`
   - フィールドタイプ: テキスト
   - CSVの職種名をそのまま格納

3. **年収**
   - フィールド名: `salary`
   - フィールドタイプ: 数値
   - 例: 3000000（300万円）

4. **こだわり条件**
   - 各条件ごとにTrue/Falseフィールドを作成
   - 例:
     - `weekend_120`: 年間休日120日以上
     - `weekend_off`: 土日祝休み
     - `low_overtime`: 残業少なめ（20時間未満）
     - `maternity_leave`: 産休・育休・介護休暇取得実績あり
     - `remote_work`: リモートワーク・在宅勤務制度あり
     - `flextime`: フレックスタイム制度あり
     - その他約50項目

## CSVインポート

### CSV形式
```csv
都道府県,市区町村,職種,年収,土日祝休み,残業少なめ,リモートワーク...
北海道,札幌市北区,営業,3000000,1,1,0...
```

### インポート方法
- WP All Import等のプラグインを使用
- CSVの値を直接ACFフィールドにマッピング
- 1/0の値はTrue/Falseフィールドに自動変換

## 検索機能の実装

### 検索フォームUI
1. **勤務地選択**
   - 階層型（折りたたみ式）のチェックボックス
   - JavaScriptで都道府県クリック時に市区町村を表示/非表示

2. **職種選択**
   - セレクトボックスまたはチェックボックス

3. **年収範囲**
   - 最小値・最大値の入力フィールド
   - またはプルダウンでの範囲選択

4. **こだわり条件**
   - 複数選択可能なチェックボックス群
   - カテゴリ分けして表示

5. **キーワード検索**
   - フリーテキスト入力

### 検索処理の実装例

```php
// 検索クエリの構築
$args = array(
    'post_type' => 'job',
    'posts_per_page' => 20,
    'meta_query' => array(
        'relation' => 'AND',
    )
);

// 都道府県・市区町村
if (!empty($_GET['prefecture'])) {
    $args['meta_query'][] = array(
        'key' => 'prefecture',
        'value' => $_GET['prefecture'],
        'compare' => '='
    );
}

if (!empty($_GET['city'])) {
    $args['meta_query'][] = array(
        'key' => 'city',
        'value' => $_GET['city'],
        'compare' => 'IN'
    );
}

// 職種
if (!empty($_GET['job_type'])) {
    $args['meta_query'][] = array(
        'key' => 'job_type',
        'value' => $_GET['job_type'],
        'compare' => 'IN'
    );
}

// 年収範囲
if (!empty($_GET['salary_min']) && !empty($_GET['salary_max'])) {
    $args['meta_query'][] = array(
        'key' => 'salary',
        'value' => array($_GET['salary_min'], $_GET['salary_max']),
        'type' => 'NUMERIC',
        'compare' => 'BETWEEN'
    );
}

// こだわり条件（チェックされた全ての条件を満たす）
if (!empty($_GET['conditions'])) {
    foreach ($_GET['conditions'] as $condition) {
        $args['meta_query'][] = array(
            'key' => $condition,
            'value' => '1',
            'compare' => '='
        );
    }
}

// キーワード検索
if (!empty($_GET['keyword'])) {
    $args['s'] = $_GET['keyword'];
}

$query = new WP_Query($args);
```

### 検索URL例
```
# 単一条件
/job-search/?prefecture=北海道

# 複数条件
/job-search/?prefecture=北海道&city[]=札幌市中央区&city[]=札幌市北区&job_type[]=営業&salary_min=3000000&salary_max=5000000&conditions[]=weekend_off&conditions[]=remote_work&keyword=化粧品
```

## 実装の利点

1. **CSVインポートが簡単**
   - データ型変換不要
   - 直接マッピング可能
   - 更新も同じCSV形式で可能

2. **検索の柔軟性**
   - 複数条件の組み合わせ検索
   - AND/OR条件の切り替え可能
   - 範囲検索にも対応

3. **管理のしやすさ**
   - ACFの管理画面で値を確認・編集可能
   - カスタムフィールドの追加・削除が容易

4. **パフォーマンス対策**
   - 検索頻度の高いフィールドにインデックス追加
   - 検索結果のキャッシュ
   - Ajax検索で負荷分散

## 今後の実装手順

1. ACFプラグインのインストール・設定
2. カスタム投稿タイプ「job」の登録
3. 必要なカスタムフィールドの作成
4. 検索フォームのテンプレート作成
5. 検索処理の実装
6. CSVインポートのテスト
7. 検索機能のテスト・調整