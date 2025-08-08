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
     * Expected columns
     */
    private $expected_columns = array(
        '削除', '社内求人ID', '採用中', '社外求人票の企業名表示', '企業',
        '管理用タイトル', '表示用タイトル', '業界', '職種', '仕事内容',
        '業務内容の変更の範囲', '最低提示年収', '最高提示年収', '給与形態', '給与',
        '固定残業代の有無', '給与詳細', '固定残業代詳細', '給与締日', '給与支払日',
        '昇給', '賞与', '勤務時間', '休憩時間', '裁量労働制の有無',
        '裁量労働制の詳細', '月平均残業時間', '月平均残業時間詳細', '休日', '都道府県',
        '就業場所', '就業場所詳細', 'アクセス', '車通勤の可否', '自転車通勤の可否',
        '就業場所の変更の範囲', '転勤の可能性', '受動喫煙対策の有無', '受動喫煙対策の詳細', '雇用形態',
        '応募区分', '契約期間の有無', '契約の更新', '更新上限', '試用期間の有無',
        '試用期間', '試用期間中の条件', '加入保険', '手当・福利厚生', '選考フロー',
        '選考詳細情報', '雇用期間（事業報告書用）', '募集開始日（事業報告書用）', '募集終了日（事業報告書用）', '募集人数（事業報告書用）',
        '取扱業務等の区分(2024)（事業報告書用）', '有効期間', '報酬パターン', '報酬割合（料率）', '報酬金額（定額）',
        '報酬詳細', '返金手数料', '歓迎条件', '募集背景', '働き方',
        '勤務時の服装', 'インセンティブ', 'ストックオプション', '応募条件（年齢）', '応募条件（性別）',
        '応募条件（国籍）', '応募条件（経験社数）', '応募条件（学歴）', '応募条件（学歴詳細）', '応募条件（職種経験年数）',
        '応募条件（業種経験年数）', '応募条件（その他求める経験）', '必須条件', '歓迎条件', 'NG対象',
        '内定の可能性が高い人', '推薦時の留意事項', '面接確約条件', '面接確約条件詳細', '公開可能範囲',
        '採用人数', '求人入手元', '求人ID（求人入手元）', '都道府県', '市区町村',
        '職種', '年収', '土日祝休み', '残業少なめ', 'リモートワーク'
    );
    
    /**
     * Field mapping
     */
    private $field_mapping = array(
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
        'NG対象' => 'ng_target',
        '内定の可能性が高い人' => 'high_offer_person',
        '推薦時の留意事項' => 'recommendation_notes',
        '面接確約条件' => 'interview_guarantee',
        '面接確約条件詳細' => 'interview_guarantee_details',
        '公開可能範囲' => 'public_range',
        '採用人数' => 'hiring_number',
        '求人入手元' => 'job_source',
        '求人ID（求人入手元）' => 'source_job_id',
        '市区町村' => 'city',
        '年収' => 'annual_income',
        '土日祝休み' => 'weekend_holiday',
        '残業少なめ' => 'low_overtime',
        'リモートワーク' => 'remote_work'
    );
    
    /**
     * Boolean fields
     */
    private $boolean_fields = array(
        'deletion_flag', 'company_name_display', 'fixed_overtime_pay',
        'discretionary_work', 'car_commute', 'bike_commute',
        'passive_smoking', 'contract_period', 'probation_period',
        'weekend_holiday', 'low_overtime', 'remote_work'
    );
    
    /**
     * Number fields
     */
    private $number_fields = array(
        'min_salary', 'max_salary', 'recruitment_number',
        'reward_amount', 'hiring_number', 'annual_income'
    );
    
    /**
     * Read CSV file
     */
    public function read_csv($file_path) {
        $data = array();
        
        if (!file_exists($file_path) || !is_readable($file_path)) {
            return false;
        }
        
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return false;
        }
        
        // Remove BOM if exists
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        // Read header
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return false;
        }
        
        // Validate headers
        if (!$this->validate_headers($headers)) {
            fclose($handle);
            return false;
        }
        
        // Read data rows
        $row_number = 2; // Start from 2 (header is 1)
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($headers)) {
                continue; // Skip invalid rows
            }
            
            $row_data = array_combine($headers, $row);
            $row_data['_row_number'] = $row_number;
            $data[] = $row_data;
            $row_number++;
        }
        
        fclose($handle);
        return $data;
    }
    
    /**
     * Validate headers
     */
    private function validate_headers($headers) {
        // Check if all expected columns exist
        $missing_columns = array_diff($this->expected_columns, $headers);
        if (!empty($missing_columns)) {
            return false;
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
        
        // Handle duplicate fields
        if (isset($row_data['都道府県'])) {
            $processed['prefecture_2'] = $row_data['都道府県'];
        }
        if (isset($row_data['職種'])) {
            $processed['job_type_2'] = $row_data['職種'];
        }
        if (isset($row_data['歓迎条件'])) {
            $processed['welcome_conditions_2'] = $row_data['歓迎条件'];
        }
        
        return $processed;
    }
    
    /**
     * Process field value
     */
    private function process_field_value($field_name, $value) {
        // Boolean fields
        if (in_array($field_name, $this->boolean_fields)) {
            return ($value === '1' || $value === 'true' || $value === 'TRUE') ? 1 : 0;
        }
        
        // Number fields
        if (in_array($field_name, $this->number_fields)) {
            return intval($value);
        }
        
        // Date fields
        if (in_array($field_name, array('recruitment_start_date', 'recruitment_end_date'))) {
            // Convert date format if needed
            if (!empty($value) && strtotime($value)) {
                return date('Y-m-d', strtotime($value));
            }
            return '';
        }
        
        // Default: return as string
        return trim($value);
    }
}