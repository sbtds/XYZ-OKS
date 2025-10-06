<?php
/**
 * Search Handler Class
 *
 * @package OKS
 * @subpackage Job_Search
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search Handler Class
 */
class OKS_Search_Handler {

    /**
     * Search keyword for custom search
     */
    private $search_keyword;

    /**
     * Search jobs
     */
    public function search($params) {
        $args = array(
            'post_type' => 'job',
            'post_status' => 'publish',
            'posts_per_page' => 20,
            'paged' => isset($params['paged']) ? intval($params['paged']) : 1,
            'meta_query' => array(
                'relation' => 'AND',
            )
        );

        // 都道府県・市区町村
        if (!empty($params['city']) && is_array($params['city'])) {
            $args['meta_query'][] = array(
                'key' => 'city',
                'value' => $params['city'],
                'compare' => 'IN'
            );
        }

        // 職種
        if (!empty($params['job_type']) && is_array($params['job_type'])) {
            $args['meta_query'][] = array(
                'key' => 'job_type',
                'value' => $params['job_type'],
                'compare' => 'IN'
            );
        }

        // 年収範囲（HTMLのselect nameが空なので、別の方法で取得）
        if (!empty($params['salary_min_select']) || !empty($params['salary_max_select'])) {
            $salary_query = array(
                'key' => 'annual_income',
                'type' => 'NUMERIC'
            );

            if (!empty($params['salary_min_select']) && !empty($params['salary_max_select'])) {
                $salary_query['value'] = array(
                    intval($params['salary_min_select']),
                    intval($params['salary_max_select'])
                );
                $salary_query['compare'] = 'BETWEEN';
            } elseif (!empty($params['salary_min_select'])) {
                $salary_query['value'] = intval($params['salary_min_select']);
                $salary_query['compare'] = '>=';
            } elseif (!empty($params['salary_max_select'])) {
                $salary_query['value'] = intval($params['salary_max_select']);
                $salary_query['compare'] = '<=';
            }

            $args['meta_query'][] = $salary_query;
        }

        // こだわり条件（チェックされた全ての条件を満たす）
        if (!empty($params['conditions']) && is_array($params['conditions'])) {
            $condition_mapping = array(
                '土日祝休み' => 'weekend_holiday',
                '残業少なめ' => 'low_overtime',
                'リモートワーク' => 'remote_work',
                '未経験OK' => 'inexperienced_ok',
                '寮・社宅あり' => 'company_housing',
                'マイカー通勤OK' => 'car_commute',
                '紹介予定派遣' => 'trial_employment',
                '直接雇用実績あり' => 'direct_hire_experience',
                '新着' => 'new_job',
                '年間休日120日以上' => 'weekend_120',
                '土日祝日休み' => 'weekend_holiday',
                '早朝勤務' => 'early_shift',
                '朝ゆっくり' => 'late_start',
                '夕方開始' => 'afternoon_start',
                '深夜勤務' => 'night_shift',
                '残業なし' => 'no_overtime',
                '残業多め' => 'high_overtime',
                '転勤なし' => 'no_transfer',
                '社員登用あり' => 'employee_conversion',
                '長期' => 'long_term',
                '単発' => 'short_term',
                '期間限定' => 'limited_period',
                '日勤固定' => 'day_shift_only',
                '夜勤固定' => 'night_shift_only',
                '２交替・３交替' => 'shift_work',
                '平日休み' => 'weekday_off',
                '高収入' => 'high_income',
                '賞与あり' => 'bonus_available',
                '給与前払い制度あり' => 'advance_payment',
                '交通費支給' => 'transportation_fee',
                '育児・介護休暇あり' => 'childcare_leave',
                '研修・教育制度充実' => 'training_system',
                '資格取得支援あり' => 'qualification_support',
                '女性が活躍' => 'women_active',
                '男性が活躍' => 'men_active',
                '20代活躍中' => 'active_20s',
                '30代活躍中' => 'active_30s',
                'ミドル活躍中' => 'active_middle',
                'シニア活躍中' => 'active_senior',
                '主婦・主夫活躍中' => 'active_homemaker',
                'フリーター歓迎' => 'freeter_welcome',
                'オフィスカジュアル' => 'office_casual',
                '制服あり' => 'uniform_required',
                '髪型・髪色自由' => 'free_hairstyle',
                '髭OK' => 'beard_ok',
                'ネイル・ピアスOK' => 'nail_pierce_ok',
                '長期休暇あり' => 'long_vacation',
                '資格・スキルが活かせる' => 'skill_utilization',
                '座り仕事' => 'sitting_work',
                '立ち仕事' => 'standing_work',
                '食堂' => 'canteen',
                '喫煙所あり' => 'smoking_area',
                '場内全面禁煙' => 'no_smoking',
                '空調完備' => 'air_conditioning',
                'アクティブワーク' => 'active_work',
                'コツコツ・モクモク集中' => 'focused_work',
                '副業・ＷワークOK' => 'side_job_ok',
                '急募' => 'urgent',
                '即日勤務OK' => 'same_day_work',
                '職場見学可' => 'workplace_visit',
                'リモート面接OK' => 'remote_interview',
                '面接時マスク着用' => 'mask_required',
                '新卒採用' => 'new_graduate',
                '第2新卒歓迎' => 'second_new_graduate',
                '中途採用' => 'mid_career',
                '固定残業代なし' => 'no_fixed_overtime',
                '完全週休2日制' => 'two_day_weekend',
                '学歴不問' => 'no_education_required',
                '正社員' => 'full_time',
                '管理職・マネージャー' => 'management',
                '設立10年以上の会社' => 'established_company',
                'ベンチャー企業' => 'venture_company',
                '車通勤可' => 'car_commute',
                '上場企業' => 'listed_company',
                'インセンティブあり' => 'incentive',
                'UIターン支援あり' => 'ui_turn_support',
                'ストックオプションあり' => 'stock_option',
                '企業年金あり' => 'company_pension',
                '健康診断あり' => 'health_check',
                'メンタルケアあり' => 'mental_care',
                '社内レクリエーションあり' => 'recreation',
                'リフレッシュ休暇あり' => 'refresh_leave',
                '誕生日休暇あり' => 'birthday_leave',
                '有給インターンあり' => 'paid_intern',
                'トライアル雇用あり' => 'trial_employment',
                '障害者への配慮あり' => 'handicapped_support',
                'LGBTフレンドリー' => 'lgbt_friendly',
                '成長企業' => 'growing_company',
                'グローバル企業' => 'global_company',
                'IT企業' => 'it_company',
                '製造業' => 'manufacturing',
                'サービス業' => 'service_industry',
                '医療・福祉' => 'medical_welfare',
                '教育' => 'education',
                '官公庁' => 'government',
                'NPO・NGO' => 'npo_ngo',
                '農業' => 'agriculture',
                '伝統産業' => 'traditional_industry',
                'クリエイティブ系' => 'creative_work',
                '営業系' => 'sales_work',
                '事務系' => 'office_work',
                '安定企業' => 'stable_company',
                '外資系企業' => 'foreign_company',
                '評価制度あり' => 'evaluation_system',
                '研修制度充実' => 'training_system',
                '退職金制度あり' => 'retirement_money',
                '育児支援あり' => 'childcare_support',
                '託児所あり' => 'nursery',
                '産休・育休取得実績あり' => 'maternity_leave',
                'フレックスタイム制度あり' => 'flextime',
                '受動喫煙対策あり' => 'passive_smoking',
                '自転車通勤可' => 'bike_commute',
                '契約期間あり' => 'contract_period',
                '試用期間あり' => 'probation_period',
                '裁量労働制あり' => 'discretionary_work',
                '固定残業代あり' => 'fixed_overtime_pay',
            );

            foreach ($params['conditions'] as $condition_label) {
                if (isset($condition_mapping[$condition_label])) {
                    $field_name = $condition_mapping[$condition_label];
                    $args['meta_query'][] = array(
                        'key' => $field_name,
                        'value' => '1',
                        'compare' => '='
                    );
                }
            }
        }

        // キーワード検索（投稿タイトル・内容 + カスタムフィールド）
        if (!empty($params['keyword'])) {
            $keyword = sanitize_text_field($params['keyword']);

            // Use post__in to limit results to posts that match the keyword in meta fields
            $matching_post_ids = $this->get_posts_by_keyword($keyword);

            if (!empty($matching_post_ids)) {
                $args['post__in'] = $matching_post_ids;
            } else {
                // If no matching posts found, force empty result
                $args['post__in'] = array(0);
            }
        }

        // ソート順
        if (!empty($params['orderby'])) {
            switch ($params['orderby']) {
                case 'salary_high':
                    $args['meta_key'] = 'annual_income';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'salary_low':
                    $args['meta_key'] = 'annual_income';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'newest':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'oldest':
                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                    break;
                default:
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
            }
        }

        // Add debug filter to see the actual query
        if (!empty($params['keyword']) && defined('WP_DEBUG') && WP_DEBUG) {
            add_filter('posts_request', function($request) {
                error_log('OKS Job Search Query: ' . $request);
                return $request;
            }, 10, 1);
        }

        $query = new WP_Query($args);

        // No need to remove filters since we're using post__in approach

        $results = array(
            'found_posts' => $query->found_posts,
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $args['paged'],
            'posts' => array(),
            'search_params' => $params
        );

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();

                $job_data = array(
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'company' => get_field('company', $post_id),
                    'prefecture' => get_field('prefecture', $post_id),
                    'city' => get_field('city', $post_id),
                    'job_type' => get_field('job_type', $post_id),
                    'annual_income' => get_field('annual_income', $post_id),
                    'min_salary' => get_field('min_salary', $post_id),
                    'max_salary' => get_field('max_salary', $post_id),
                    'working_hours' => get_field('working_hours', $post_id),
                    'holidays' => get_field('holidays', $post_id),
                    'employment_type' => get_field('employment_type', $post_id),
                    'job_description' => wp_trim_words(get_field('job_description', $post_id), 50),
                    'permalink' => get_permalink($post_id),
                    'conditions' => $this->get_job_conditions($post_id),
                    'updated' => get_the_modified_date('Y-m-d', $post_id)
                );

                $results['posts'][] = $job_data;
            }
            wp_reset_postdata();
        }

        return $results;
    }

    /**
     * Get job conditions
     */
    private function get_job_conditions($post_id) {
        $conditions = array();

        $condition_fields = array(
            'weekend_holiday' => '土日祝休み',
            'low_overtime' => '残業少なめ',
            'remote_work' => 'リモートワーク可',
            'car_commute' => '車通勤可',
            'bike_commute' => '自転車通勤可',
            'fixed_overtime_pay' => '固定残業代あり',
            'discretionary_work' => '裁量労働制',
            'passive_smoking' => '受動喫煙対策',
            'contract_period' => '契約期間あり',
            'probation_period' => '試用期間あり'
        );

        foreach ($condition_fields as $field => $label) {
            if (get_field($field, $post_id)) {
                $conditions[] = $label;
            }
        }

        return $conditions;
    }

    /**
     * Build search URL
     */
    public function build_search_url($params) {
        $base_url = home_url('/search/');

        if (!empty($params)) {
            $query_string = http_build_query($params);
            return $base_url . '?' . $query_string;
        }

        return $base_url;
    }

    /**
     * Get search summary
     */
    public function get_search_summary($params) {
        $summary_parts = array();

        // 勤務地
        if (!empty($params['city'])) {
            $cities = is_array($params['city']) ? $params['city'] : array($params['city']);
            $summary_parts[] = '勤務地: ' . implode('、', $cities);
        }

        // 職種
        if (!empty($params['job_type'])) {
            $job_types = is_array($params['job_type']) ? $params['job_type'] : array($params['job_type']);
            $summary_parts[] = '職種: ' . implode('、', $job_types);
        }

        // 年収
        if (!empty($params['salary_min']) || !empty($params['salary_max'])) {
            $salary_text = '年収: ';
            if (!empty($params['salary_min']) && !empty($params['salary_max'])) {
                $salary_text .= number_format($params['salary_min']) . '円 〜 ' . number_format($params['salary_max']) . '円';
            } elseif (!empty($params['salary_min'])) {
                $salary_text .= number_format($params['salary_min']) . '円以上';
            } else {
                $salary_text .= number_format($params['salary_max']) . '円以下';
            }
            $summary_parts[] = $salary_text;
        }

        // こだわり条件
        if (!empty($params['conditions'])) {
            $search_data = new OKS_Search_Data();
            $condition_labels = $search_data->get_conditions();
            $selected_conditions = array();

            foreach ($params['conditions'] as $condition) {
                if (isset($condition_labels[$condition])) {
                    $selected_conditions[] = $condition_labels[$condition];
                }
            }

            if (!empty($selected_conditions)) {
                $summary_parts[] = 'こだわり条件: ' . implode('、', $selected_conditions);
            }
        }

        // キーワード
        if (!empty($params['keyword'])) {
            $summary_parts[] = 'キーワード: ' . esc_html($params['keyword']);
        }

        return implode(' | ', $summary_parts);
    }

    /**
     * Get posts by keyword search in title, content and meta fields
     */
    private function get_posts_by_keyword($keyword) {
        global $wpdb;

        $search_terms = explode(' ', $keyword);
        $searchable_meta_keys = array(
            'company', 'job_type', 'prefecture', 'city', 'job_description',
            'work_location', 'industry', 'employment_type', 'benefits',
            'salary_details', 'working_hours', 'holidays', 'access',
            'required_conditions', 'welcome_conditions', 'recruitment_background'
        );

        $post_ids = array();

        foreach ($search_terms as $term) {
            $term = trim($term);
            if (empty($term)) continue;

            $like = '%' . $wpdb->esc_like($term) . '%';

            // Search in post title and content
            $title_content_posts = $wpdb->get_col($wpdb->prepare("
                SELECT DISTINCT ID
                FROM {$wpdb->posts}
                WHERE post_type = 'job'
                AND post_status = 'publish'
                AND (post_title LIKE %s OR post_content LIKE %s)
            ", $like, $like));

            // Search in meta fields
            $meta_keys_sql = "'" . implode("','", array_map('esc_sql', $searchable_meta_keys)) . "'";
            $meta_posts = $wpdb->get_col($wpdb->prepare("
                SELECT DISTINCT p.ID
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm.meta_key IN ($meta_keys_sql)
                AND pm.meta_value LIKE %s
                AND pm.meta_value != ''
                AND pm.meta_value IS NOT NULL
            ", $like));

            $term_posts = array_unique(array_merge($title_content_posts, $meta_posts));

            if (empty($post_ids)) {
                // First term - use all matching posts
                $post_ids = $term_posts;
            } else {
                // Subsequent terms - use intersection (AND logic)
                $post_ids = array_intersect($post_ids, $term_posts);
            }

            // If no intersection found, no need to continue
            if (empty($post_ids)) {
                break;
            }
        }

        return $post_ids;
    }
}