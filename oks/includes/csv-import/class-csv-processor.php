<?php
/**
 * CSV Processor
 *
 * @package OKS
 * @subpackage CSV_Import
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * CSV Processor Class
 */
class OKS_CSV_Processor {

    /**
     * Last errors array
     */
    private $last_errors = array();

    /**
     * Expected columns - Updated to 146 columns
     */
    private $expected_columns = array(
        '削除', '社内求人ID', '採用中', '社外求人票の企業名表示', '企業',
        '管理用タイトル', 'A求人DB表示タイトル', '表示用タイトル', '業界', '職種',
        '仕事内容', '業務内容の変更の範囲', '最低提示年収', '最高提示年収', '表示用_想定年収',
        '給与形態', '給与', '固定残業代の有無', '給与詳細', '固定残業代詳細',
        '給与締日', '給与支払日', '昇給', '賞与', 'インセンティブ',
        '勤務時間', '休憩時間', '裁量労働制の有無', '裁量労働制の詳細', '月平均残業時間',
        '月平均残業時間詳細', '休日', '勤務地_都道府県', '勤務地_市区町村', '就業場所',
        '就業場所詳細', 'アクセス', '車通勤の可否', '車通勤の詳細', '自転車通勤の可否',
        '就業場所の変更の範囲', '転勤の可能性', '転勤の可能性_詳細', '受動喫煙対策の有無', '受動喫煙対策の詳細',
        '応募区分', '雇用形態', '契約期間の定めの有無', '契約期間の詳細', '契約の更新',
        '契約更新の上限', '試用期間の有無', '試用期間', '試用期間中の条件', '加入保険',
        '手当・福利厚生', 'ストックオプション', '働き方', '募集背景', '勤務時の服装',
        '採用人数', '公開用_必須条件', '公開用_歓迎条件', '選考フロー', 'A選考フロー詳細情報',
        '公開用_備考', 'A雇用期間（事業報告書用）', 'A募集開始日（事業報告書用）', 'A募集終了日（事業報告書用）', 'A募集人数（事業報告書用）',
        'A取扱業務等の区分(2024)（事業報告書用）', 'A有効期間', 'A報酬パターン', 'A報酬割合（料率）', 'A報酬金額（定額）',
        'A報酬詳細', 'A返金手数料', 'A応募条件（年齢）', 'A応募条件（性別）', 'A応募条件（国籍）',
        'A応募条件（経験社数）', 'A応募条件（学歴）', 'A応募条件（学歴詳細）', 'A応募条件（職種経験年数）', 'A応募条件（業種経験年数）',
        'A応募条件（その他求める経験）', 'A_NG対象', 'A内定の可能性が高い人', 'A推薦時の留意事項', 'A面接確約条件',
        'A面接確約条件詳細', 'A社名公開可否', 'A媒体公開可否', 'A公開可能範囲の詳細', 'A求人入手元',
        'A求人ID（求人入手元）', 'おすすめPOINT_1', 'おすすめPOINT_2', 'おすすめPOINT_3', '年間休日120日以上',
        '寮・社宅・住宅手当あり', '退職金制度', '資格取得支援制度あり', '産休・育休・介護休暇取得実績あり', '女性が活躍',
        '男性が活躍', 'インセンティブあり', 'U・Iターン支援あり', 'リモート面接OK', 'ミドル活躍中',
        'シニア活躍中', '新卒', '第二新卒', '中途採用', '交通費支給',
        '完全週休二日制', '土日祝休み', '残業少なめ（月20時間未満）', 'フレックスタイム制度あり', 'リモートワーク・在宅勤務制度',
        '転勤なし', '学歴不問', '正社員', '管理職・マネージャー職の求人', '設立10年以上の会社',
        'ベンチャー企業', '職種未経験OK', '業種未経験OK', '上場企業', '社会人経験不問',
        'ITスキル不問', 'H_従業員数', 'H_本社住所', 'H_URL', 'H_設立年月',
        'H_株式公開', 'H_仕事内容', 'H_応募資格', 'H_想定年収', 'H_給与詳細',
        'H_勤務地', 'H_勤務地詳細', 'H_アクセス', 'H_諸手当', 'H_休日休暇',
        'H_勤務時間'
    );

    /**
     * Field mapping - Updated to 146 columns
     */
    private $field_mapping = array(
        '削除' => 'deletion_flag',
        '社内求人ID' => 'internal_job_id',
        '採用中' => 'recruiting_status',
        '社外求人票の企業名表示' => 'company_name_display',
        '企業' => 'company',
        '管理用タイトル' => 'admin_title',
        'A求人DB表示タイトル' => 'a_job_db_display_title',
        '表示用タイトル' => 'display_title',
        '業界' => 'industry',
        '職種' => 'job_type',
        '仕事内容' => 'job_description',
        '業務内容の変更の範囲' => 'job_scope_change',
        '最低提示年収' => 'min_salary',
        '最高提示年収' => 'max_salary',
        '表示用_想定年収' => 'display_expected_salary',
        '給与形態' => 'salary_type',
        '給与' => 'salary',
        '固定残業代の有無' => 'fixed_overtime_pay',
        '給与詳細' => 'salary_details',
        '固定残業代詳細' => 'fixed_overtime_details',
        '給与締日' => 'salary_closing_date',
        '給与支払日' => 'salary_payment_date',
        '昇給' => 'salary_increase',
        '賞与' => 'bonus',
        'インセンティブ' => 'incentive',
        '勤務時間' => 'working_hours',
        '休憩時間' => 'break_time',
        '裁量労働制の有無' => 'discretionary_work',
        '裁量労働制の詳細' => 'discretionary_work_details',
        '月平均残業時間' => 'avg_overtime_hours',
        '月平均残業時間詳細' => 'avg_overtime_details',
        '休日' => 'holidays',
        '勤務地_都道府県' => 'prefecture',
        '勤務地_市区町村' => 'city',
        '就業場所' => 'work_location',
        '就業場所詳細' => 'work_location_details',
        'アクセス' => 'access',
        '車通勤の可否' => 'car_commute',
        '車通勤の詳細' => 'car_commute_details',
        '自転車通勤の可否' => 'bike_commute',
        '就業場所の変更の範囲' => 'work_location_change',
        '転勤の可能性' => 'transfer_possibility',
        '転勤の可能性_詳細' => 'transfer_possibility_details',
        '受動喫煙対策の有無' => 'passive_smoking',
        '受動喫煙対策の詳細' => 'passive_smoking_details',
        '応募区分' => 'application_category',
        '雇用形態' => 'employment_type',
        '契約期間の定めの有無' => 'contract_period',
        '契約期間の詳細' => 'contract_period_details',
        '契約の更新' => 'contract_renewal',
        '契約更新の上限' => 'renewal_limit',
        '試用期間の有無' => 'probation_period',
        '試用期間' => 'probation_duration',
        '試用期間中の条件' => 'probation_conditions',
        '加入保険' => 'insurance',
        '手当・福利厚生' => 'benefits',
        'ストックオプション' => 'stock_option',
        '働き方' => 'work_style',
        '募集背景' => 'recruitment_background',
        '勤務時の服装' => 'work_attire',
        '採用人数' => 'hiring_number',
        '公開用_必須条件' => 'public_required_conditions',
        '公開用_歓迎条件' => 'public_welcome_conditions',
        '選考フロー' => 'selection_process',
        'A選考フロー詳細情報' => 'a_selection_details',
        '公開用_備考' => 'public_remarks',
        'A雇用期間（事業報告書用）' => 'a_employment_period_report',
        'A募集開始日（事業報告書用）' => 'a_recruitment_start_date',
        'A募集終了日（事業報告書用）' => 'a_recruitment_end_date',
        'A募集人数（事業報告書用）' => 'a_recruitment_number',
        'A取扱業務等の区分(2024)（事業報告書用）' => 'a_business_category_2024',
        'A有効期間' => 'a_validity_period',
        'A報酬パターン' => 'a_reward_pattern',
        'A報酬割合（料率）' => 'a_reward_rate',
        'A報酬金額（定額）' => 'a_reward_amount',
        'A報酬詳細' => 'a_reward_details',
        'A返金手数料' => 'a_refund_fee',
        'A応募条件（年齢）' => 'a_req_age',
        'A応募条件（性別）' => 'a_req_gender',
        'A応募条件（国籍）' => 'a_req_nationality',
        'A応募条件（経験社数）' => 'a_req_companies',
        'A応募条件（学歴）' => 'a_req_education',
        'A応募条件（学歴詳細）' => 'a_req_education_details',
        'A応募条件（職種経験年数）' => 'a_req_job_years',
        'A応募条件（業種経験年数）' => 'a_req_industry_years',
        'A応募条件（その他求める経験）' => 'a_req_other_experience',
        'A_NG対象' => 'a_ng_target',
        'A内定の可能性が高い人' => 'a_high_offer_person',
        'A推薦時の留意事項' => 'a_recommendation_notes',
        'A面接確約条件' => 'a_interview_guarantee',
        'A面接確約条件詳細' => 'a_interview_guarantee_details',
        'A社名公開可否' => 'a_company_name_public',
        'A媒体公開可否' => 'a_media_public',
        'A公開可能範囲の詳細' => 'a_public_range_details',
        'A求人入手元' => 'a_job_source',
        'A求人ID（求人入手元）' => 'a_source_job_id',
        'おすすめPOINT_1' => 'recommend_point_1',
        'おすすめPOINT_2' => 'recommend_point_2',
        'おすすめPOINT_3' => 'recommend_point_3',
        '年間休日120日以上' => 'annual_holidays_120',
        '寮・社宅・住宅手当あり' => 'housing_allowance',
        '退職金制度' => 'retirement_benefits',
        '資格取得支援制度あり' => 'qualification_support',
        '産休・育休・介護休暇取得実績あり' => 'maternity_leave_record',
        '女性が活躍' => 'women_active',
        '男性が活躍' => 'men_active',
        'インセンティブあり' => 'incentive_available',
        'U・Iターン支援あり' => 'ui_turn_support',
        'リモート面接OK' => 'remote_interview_ok',
        'ミドル活躍中' => 'middle_active',
        'シニア活躍中' => 'senior_active',
        '新卒' => 'new_graduate',
        '第二新卒' => 'second_new_graduate',
        '中途採用' => 'mid_career',
        '交通費支給' => 'transportation_allowance',
        '完全週休二日制' => 'full_weekend_off',
        '土日祝休み' => 'weekend_holiday',
        '残業少なめ（月20時間未満）' => 'low_overtime',
        'フレックスタイム制度あり' => 'flex_time',
        'リモートワーク・在宅勤務制度' => 'remote_work',
        '転勤なし' => 'no_transfer',
        '学歴不問' => 'education_unnecessary',
        '正社員' => 'full_time_employee',
        '管理職・マネージャー職の求人' => 'management_position',
        '設立10年以上の会社' => 'established_10years',
        'ベンチャー企業' => 'venture_company',
        '職種未経験OK' => 'job_inexperienced_ok',
        '業種未経験OK' => 'industry_inexperienced_ok',
        '上場企業' => 'listed_company',
        '社会人経験不問' => 'work_experience_unnecessary',
        'ITスキル不問' => 'it_skill_unnecessary',
        'H_従業員数' => 'h_employee_count',
        'H_本社住所' => 'h_head_office_address',
        'H_URL' => 'h_url',
        'H_設立年月' => 'h_established_date',
        'H_株式公開' => 'h_stock_public',
        'H_仕事内容' => 'h_job_content',
        'H_応募資格' => 'h_application_requirements',
        'H_想定年収' => 'h_expected_salary',
        'H_給与詳細' => 'h_salary_details',
        'H_勤務地' => 'h_work_location',
        'H_勤務地詳細' => 'h_work_location_details',
        'H_アクセス' => 'h_access',
        'H_諸手当' => 'h_allowances',
        'H_休日休暇' => 'h_holidays',
        'H_勤務時間' => 'h_working_hours'
    );

    /**
     * All fields are now textarea type according to ACF configuration
     * Keeping the classifications for backward compatibility but all will be processed as textarea
     */

    /**
     * Boolean fields (legacy - now processed as textarea)
     */
    private $boolean_fields = array();

    /**
     * Number fields (legacy - now processed as textarea)
     */
    private $number_fields = array();

    /**
     * All fields are now textarea fields according to the updated ACF configuration
     */
    private $textarea_fields = array();

    /**
     * Read CSV file
     */
    public function read_csv($file_path) {
        // Clear previous errors
        $this->last_errors = array();
        
        // Set auto_detect_line_endings for better multiline support
        // ini_set('auto_detect_line_endings', TRUE); // Deprecated
        $data = array();

        if (!file_exists($file_path) || !is_readable($file_path)) {
            $error_msg = "CSV file not found or not readable: " . $file_path;
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            $error_msg = "Failed to open CSV file: " . $file_path;
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        // Remove BOM if exists
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Read header
        $headers = fgetcsv($handle, 0, ',', '"', '"');
        if (!$headers) {
            fclose($handle);
            $error_msg = "Failed to read CSV headers from: " . $file_path;
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        error_log("CSV headers count: " . count($headers));
        error_log("Expected columns count: " . count($this->expected_columns));
        error_log("First 5 CSV headers: " . implode(', ', array_slice($headers, 0, 5)));
        error_log("Last 5 CSV headers: " . implode(', ', array_slice($headers, -5)));

        // Validate headers
        if (!$this->validate_headers($headers)) {
            fclose($handle);
            $error_msg = "CSV headers validation failed. Expected: " . count($this->expected_columns) . ", Got: " . count($headers);
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        // Read data rows
        $row_number = 1; // Will be incremented immediately, so start at 1
        $valid_rows = 0;
        $skipped_rows = 0;
        $debug_first_row = true;
        
        while (!feof($handle)) {
            $row = fgetcsv($handle, 0, ',', '"', '"');
            if ($row === false) {
                break;
            }
            $row_number++;
            // Empty row check
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Check if this row has the expected number of columns
            if (count($row) !== count($headers)) {
                // Only log first few errors to avoid log overflow
                if ($skipped_rows < 5) {
                    error_log("CSV Row $row_number: Expected " . count($headers) . " columns, got " . count($row) . " columns");
                    error_log("First field content: " . (isset($row[0]) ? substr($row[0], 0, 50) : 'EMPTY'));
                }
                
                $skipped_rows++;
                continue;
            }
            
            $valid_rows++;
            
            // Debug first few valid rows
            if ($debug_first_row && $valid_rows <= 3) {
                error_log("Valid row $valid_rows debug:");
                error_log("  Row number: $row_number");
                error_log("  社内求人ID: " . (isset($row[1]) ? $row[1] : 'NOT SET'));
                if ($valid_rows == 3) {
                    $debug_first_row = false;
                }
            }

            // Process multiline text fields
            // CSVファイル内で改行を\nとしてエスケープしている場合の処理
            // ダブルクォート内の実際の改行はfgetcsvが自動的に処理する
            foreach ($row as $key => $value) {
                // \n（文字列）を実際の改行文字に変換
                $row[$key] = str_replace('\\n', "\n", $value);
            }

            $row_data = array_combine($headers, $row);
            $row_data['_row_number'] = $row_number;
            $data[] = $row_data;
            $row_number++;
        }

        fclose($handle);
        
        // Reset auto_detect_line_endings
        // ini_restore('auto_detect_line_endings'); // Deprecated

        error_log("CSV parsing summary: Valid rows: $valid_rows, Skipped rows: $skipped_rows");

        if (empty($data)) {
            $error_msg = "No valid data rows found in CSV file: " . $file_path;
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        error_log("Successfully parsed CSV file with " . count($data) . " rows");
        return $data;
    }

    /**
     * Validate headers
     */
    private function validate_headers($headers) {
        // Check if all expected columns exist
        $missing_columns = array_diff($this->expected_columns, $headers);
        $extra_columns = array_diff($headers, $this->expected_columns);

        if (!empty($missing_columns)) {
            $error_msg = "Missing CSV columns: " . implode(', ', $missing_columns);
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        if (!empty($extra_columns)) {
            $error_msg = "Extra CSV columns: " . implode(', ', $extra_columns);
            error_log($error_msg);
            $this->last_errors[] = $error_msg;
            return false;
        }

        // Compare each header for exact match
        for ($i = 0; $i < count($this->expected_columns); $i++) {
            if (!isset($headers[$i]) || trim($headers[$i]) !== trim($this->expected_columns[$i])) {
                $error_msg = "Header mismatch at position $i. Expected: '" . $this->expected_columns[$i] . "', Got: '" . (isset($headers[$i]) ? $headers[$i] : 'NULL') . "'";
                error_log($error_msg);
                
                // Add character-level debugging
                if (isset($headers[$i])) {
                    error_log("Expected bytes: " . bin2hex($this->expected_columns[$i]));
                    error_log("Actual bytes: " . bin2hex($headers[$i]));
                }
                
                $this->last_errors[] = $error_msg;
                return false;
            }
        }

        return true;
    }

    /**
     * Process row data
     */
    public function process_row_data($row_data) {
        $processed = array();

        foreach ($row_data as $key => $value) {
            if ($key === '_row_number') {
                continue;
            }

            if (isset($this->field_mapping[$key])) {
                $field_name = $this->field_mapping[$key];
                $processed[$field_name] = $this->process_field_value($field_name, $value);
            }
        }

        // No duplicate fields in the new format

        return $processed;
    }

    /**
     * Process field value
     * All fields are now processed as textarea (text) according to ACF configuration
     */
    private function process_field_value($field_name, $value) {
        // All fields are now textarea type - just return trimmed text value
        // This maintains the CSV data exactly as provided without any conversion
        return trim($value);
    }

    /**
     * Convert value to boolean (legacy function - no longer used)
     * Kept for backward compatibility
     */
    private function convert_to_boolean($value) {
        // This function is no longer used since all fields are now textarea
        // Keeping for backward compatibility only
        return trim($value);
    }

    /**
     * Get last errors
     */
    public function get_last_errors() {
        return $this->last_errors;
    }
}