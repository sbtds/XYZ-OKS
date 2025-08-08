<?php
/**
 * Search Data Class
 * 
 * @package OKS
 * @subpackage Job_Search
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search Data Class
 */
class OKS_Search_Data {
    
    /**
     * Get prefectures with cities
     */
    public function get_prefectures_with_cities() {
        global $wpdb;
        
        $results = $wpdb->get_results("
            SELECT DISTINCT 
                pm1.meta_value as prefecture,
                pm2.meta_value as city
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'prefecture'
            INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'city'
            WHERE p.post_type = 'job' 
            AND p.post_status = 'publish'
            AND pm1.meta_value != ''
            AND pm2.meta_value != ''
            ORDER BY pm1.meta_value, pm2.meta_value
        ");
        
        $prefectures = array();
        foreach ($results as $row) {
            if (!isset($prefectures[$row->prefecture])) {
                $prefectures[$row->prefecture] = array();
            }
            $prefectures[$row->prefecture][] = $row->city;
        }
        
        return $prefectures;
    }
    
    /**
     * Get job types
     */
    public function get_job_types() {
        global $wpdb;
        
        $results = $wpdb->get_col("
            SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = 'job_type'
            AND p.post_type = 'job'
            AND p.post_status = 'publish'
            AND pm.meta_value != ''
            ORDER BY pm.meta_value
        ");
        
        return $results;
    }
    
    /**
     * Get conditions
     */
    public function get_conditions() {
        return array(
            'weekend_holiday' => '土日祝休み',
            'low_overtime' => '残業少なめ（20時間未満）',
            'remote_work' => 'リモートワーク・在宅勤務制度あり',
            'flextime' => 'フレックスタイム制度あり',
            'weekend_120' => '年間休日120日以上',
            'maternity_leave' => '産休・育休・介護休暇取得実績あり',
            'no_transfer' => '転勤なし',
            'station_near' => '駅近（徒歩10分以内）',
            'car_commute' => '車通勤可',
            'clothes_free' => '服装自由',
            'side_job_ok' => '副業OK',
            'inexperienced_ok' => '未経験者歓迎',
            'senior_welcome' => 'シニア（60歳以上）歓迎',
            'english_use' => '英語力を活かせる',
            'stock_option' => 'ストックオプションあり',
            'company_housing' => '社宅・家賃補助あり',
            'childcare_support' => '託児所・育児支援あり',
            'qualification_support' => '資格取得支援あり',
            'training_system' => '研修制度充実',
            'evaluation_system' => '評価制度あり',
            'bonus_twice' => '賞与年2回以上',
            'retirement_money' => '退職金制度あり',
            'company_pension' => '企業年金あり',
            'health_check' => '健康診断あり',
            'mental_care' => 'メンタルケアあり',
            'recreation' => '社内レクリエーションあり',
            'refreshment_leave' => 'リフレッシュ休暇あり',
            'birthday_leave' => '誕生日休暇あり',
            'paid_intern' => '有給インターンあり',
            'trial_employment' => 'トライアル雇用あり',
            'handicapped_consideration' => '障害者への配慮あり',
            'lgbt_friendly' => 'LGBTフレンドリー',
            'women_active' => '女性活躍中',
            'startup' => 'ベンチャー企業',
            'listed_company' => '上場企業',
            'foreign_company' => '外資系企業',
            'stable_company' => '安定企業',
            'growing_company' => '成長企業',
            'global_company' => 'グローバル企業',
            'it_company' => 'IT企業',
            'manufacturing' => '製造業',
            'service_industry' => 'サービス業',
            'medical_welfare' => '医療・福祉',
            'education' => '教育',
            'government' => '官公庁',
            'nonprofit' => 'NPO・NGO',
            'agriculture' => '農業',
            'traditional_industry' => '伝統産業',
            'creative_work' => 'クリエイティブ系',
            'sales_work' => '営業系',
            'office_work' => '事務系'
        );
    }
}