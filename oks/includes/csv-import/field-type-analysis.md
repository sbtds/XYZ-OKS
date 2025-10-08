# フィールドタイプ分析結果（140項目）

## 分類ルール適用結果

### 1. xxx有無 → Boolean型（True/False）
すでに適切に`$boolean_fields`に分類済み：
- `fixed_overtime_pay` （固定残業代の有無）
- `discretionary_work` （裁量労働制の有無）
- `car_commute` （車通勤の可否）
- `bike_commute` （自転車通勤の可否）
- `passive_smoking` （受動喫煙対策の有無）
- `contract_period` （契約期間の定めの有無）
- `probation_period` （試用期間の有無）
- など

### 2. xxx詳細 → Textarea型
すでに適切に`$textarea_fields`に分類済み：
- `salary_details` （給与詳細）
- `fixed_overtime_details` （固定残業代詳細）
- `discretionary_work_details` （裁量労働制の詳細）
- `work_location_details` （就業場所詳細）
- `car_commute_details` （車通勤の詳細）
- `transfer_possibility_details` （転勤の可能性_詳細）
- `passive_smoking_details` （受動喫煙対策の詳細）
- `contract_period_details` （契約期間の詳細）
- など

### 3. おすすめPOINT項目 → Textarea型（新規追加）
以下を`$textarea_fields`に追加：
- `recommend_point_1` （おすすめPOINT_1）
- `recommend_point_2` （おすすめPOINT_2）
- `recommend_point_3` （おすすめPOINT_3）

### 4. 特定項目 → Text型
以下を新規`$text_fields`配列に分類：
- `incentive` （インセンティブ）
- `stock_option` （ストックオプション）
- `a_reward_rate` （A報酬割合（料率））

### 5. xxx日・日付項目 → Text型
以下を`$text_fields`に分類：
- `salary_closing_date` （給与締日）
- `salary_payment_date` （給与支払日）
- `a_recruitment_start_date` （A募集開始日（事業報告書用））
- `a_recruitment_end_date` （A募集終了日（事業報告書用））
- `h_established_date` （H_設立年月）

## 現在の分類状況

### Boolean型フィールド（37項目）
True/Falseの値を取る項目

### Number型フィールド（6項目）
数値のみを取る項目：
- `min_salary`, `max_salary`, `hiring_number`
- `a_recruitment_number`, `a_reward_rate`, `a_reward_amount`

### Textarea型フィールド（27項目）
複数行テキストを取る項目（詳細説明、条件、おすすめPOINT含む）

### Text型フィールド（新規：70項目）
単行テキストを取る項目（基本情報、日付、その他）

## 残りの項目について

全140項目は以下のように分類されました：
- Boolean型：37項目
- Number型：6項目  
- Textarea型：27項目
- Text型：70項目

**合計：140項目**

すべての項目が適切なフィールドタイプに分類されており、空欄の項目や未分類の項目はありません。

## 注意事項

1. **Boolean項目**: `1/0`, `する/しない`, `あり/なし`, `OK/NG`, `y/n`に対応
2. **Textarea項目**: 4行設定、改行は`<br>`タグに変換
3. **Text項目**: 単行テキスト、日付は`YYYY-MM-DD`形式
4. **Number項目**: 数値のみ、カンマ区切りなし