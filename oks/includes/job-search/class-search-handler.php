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

// Include area mapping functions
require_once get_template_directory() . '/includes/area-mapping.php';

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
        // Handle posts per page parameter
        $posts_per_page = isset($params['posts_per_page']) ? intval($params['posts_per_page']) : 20;
        if ($posts_per_page <= 0 || $posts_per_page > 100) {
            $posts_per_page = 20; // Default fallback
        }
        
        $args = array(
            'post_type' => 'job',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => isset($params['paged']) ? intval($params['paged']) : 1,
            'meta_query' => array(
                'relation' => 'AND',
            )
        );

        // 都道府県・市区町村・エリア
        $location_query = array('relation' => 'OR');
        
        // Handle area parameter (convert to prefectures)
        $prefectures_from_area = array();
        if (!empty($params['area'])) {
            $prefectures_from_area = oks_convert_area_to_prefectures($params['area']);
        }
        
        // Combine prefecture parameter with prefectures from area
        $all_prefectures = array();
        if (!empty($params['prefecture']) && is_array($params['prefecture'])) {
            $all_prefectures = array_merge($all_prefectures, $params['prefecture']);
        }
        if (!empty($prefectures_from_area)) {
            $all_prefectures = array_merge($all_prefectures, $prefectures_from_area);
        }
        
        // Remove duplicates
        $all_prefectures = array_unique($all_prefectures);
        
        if (!empty($all_prefectures)) {
            $location_query[] = array(
                'key' => 'prefecture',
                'value' => $all_prefectures,
                'compare' => 'IN'
            );
        }

        if (!empty($params['city']) && is_array($params['city'])) {
            $location_query[] = array(
                'key' => 'city',
                'value' => $params['city'],
                'compare' => 'IN'
            );
        }

        if (count($location_query) > 1) {
            $args['meta_query'][] = $location_query;
        }

        // 業界
        if (!empty($params['industry']) && is_array($params['industry'])) {
            $args['meta_query'][] = array(
                'key' => 'industry',
                'value' => $params['industry'],
                'compare' => 'IN'
            );
        }

        // 職種（業界付き）の処理を優先
        if (!empty($params['job_type_with_industry']) && is_array($params['job_type_with_industry'])) {
            $job_types = array();
            
            foreach ($params['job_type_with_industry'] as $industry_job_type) {
                // 業界と職種を分離
                $parts = explode('|', $industry_job_type);
                if (count($parts) == 2) {
                    $job_type = $parts[1];
                    // 職種を配列に追加（重複を避ける）
                    if (!in_array($job_type, $job_types)) {
                        $job_types[] = $job_type;
                    }
                }
            }
            
            // 職種でフィルタリング
            if (!empty($job_types)) {
                $args['meta_query'][] = array(
                    'key' => 'job_type',
                    'value' => $job_types,
                    'compare' => 'IN'
                );
            }
        } elseif (!empty($params['job_type']) && is_array($params['job_type'])) {
            // 旧形式の職種パラメータ（後方互換性のため）
            $args['meta_query'][] = array(
                'key' => 'job_type',
                'value' => $params['job_type'],
                'compare' => 'IN'
            );
        }

        // 給与（income）- カスタム判定ロジック
        if (!empty($params['income']) && is_array($params['income'])) {
            // 給与条件に該当する投稿IDを取得
            $matching_post_ids = $this->get_posts_by_salary_conditions($params['income']);
            
            if (!empty($matching_post_ids)) {
                if (isset($args['post__in'])) {
                    // 既にpost__inが設定されている場合は積集合を取る
                    $args['post__in'] = array_intersect($args['post__in'], $matching_post_ids);
                } else {
                    $args['post__in'] = $matching_post_ids;
                }
            } else {
                // 条件に該当する投稿がない場合は空の結果を返す
                $args['post__in'] = array(0);
            }
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

        // 年収範囲（サイドバーからの選択）
        if (!empty($params['salary_range'])) {
            $range_parts = explode('-', $params['salary_range']);
            if (count($range_parts) == 2) {
                $min_salary = intval($range_parts[0]);
                $max_salary = intval($range_parts[1]);
                
                $args['meta_query'][] = array(
                    'key' => 'min_salary',
                    'value' => array($min_salary, $max_salary),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                );
            }
        }

        // こだわり条件（チェックされた全ての条件を満たす）
        if (!empty($params['conditions']) && is_array($params['conditions'])) {
            $condition_mapping = array(
                // 休日・勤務時間
                '完全週休2日制' => 'full_weekend_off',
                '土日祝休み' => 'weekend_holiday',
                '年間休日120日以上' => 'annual_holidays_120',
                '残業少なめ(20時間未満)' => 'low_overtime',
                '産休・育休・介護休暇取得実績あり' => 'maternity_leave_record',
                'リモートワーク・在宅勤務制度あり' => 'remote_work',
                
                // 福利厚生・待遇
                '退職金制度' => 'retirement_benefits',
                '寮・社宅・住宅手当あり' => 'housing_allowance',
                'UIターン支援あり' => 'ui_turn_support',
                '交通費支給' => 'transportation_allowance',
                '固定残業代なし' => 'fixed_overtime_pay',
                '資格取得支援制度' => 'qualification_support',
                
                // 職場環境・会社
                '女性が活躍' => 'women_active',
                '男性が活躍' => 'men_active',
                'ミドル活躍中' => 'middle_active',
                'シニア活躍中' => 'senior_active',
                '上場企業' => 'listed_company',
                '設立10年以上の会社' => 'established_10years',
                'ベンチャー企業' => 'venture_company',
                '車通勤可' => 'car_commute',
                
                // 応募条件・雇用形態
                '未経験でも可' => 'industry_inexperienced_ok',
                '学歴不問' => 'education_unnecessary',
                '新卒採用' => 'new_graduate_recruitment',
                '第二新卒採用' => 'second_new_graduate',
                '中途採用' => 'mid_career_recruitment',
                '転勤なし' => 'transfer_possibility',
                '正社員' => 'full_time_employee',
                'リモート面接OK' => 'remote_interview_ok',
                'インセンティブあり' => 'incentive_available',
                '管理職・マネージャー' => 'management_position',
            );

            foreach ($params['conditions'] as $condition_label) {
                if (isset($condition_mapping[$condition_label])) {
                    $field_name = $condition_mapping[$condition_label];
                    
                    // 特別な処理が必要な条件
                    if ($condition_label === '固定残業代なし') {
                        // fixed_overtime_pay が n、false、またはない場合
                        $args['meta_query'][] = array(
                            'relation' => 'OR',
                            array(
                                'key' => $field_name,
                                'value' => 'n',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => 'false',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => '0',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'compare' => 'NOT EXISTS'
                            )
                        );
                    } elseif ($condition_label === '転勤なし') {
                        // transfer_possibility が n、false、0、またはない場合（転勤なしを意味）
                        $args['meta_query'][] = array(
                            'relation' => 'OR',
                            array(
                                'key' => $field_name,
                                'value' => 'n',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => 'false',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => '0',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'compare' => 'NOT EXISTS'
                            )
                        );
                    } else {
                        // 通常の条件：フィールドが1、'true'、または'y'
                        $args['meta_query'][] = array(
                            'relation' => 'OR',
                            array(
                                'key' => $field_name,
                                'value' => '1',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => 'true',
                                'compare' => '='
                            ),
                            array(
                                'key' => $field_name,
                                'value' => 'y',
                                'compare' => '='
                            )
                        );
                    }
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
                    $args['meta_key'] = 'min_salary';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'salary_low':
                    $args['meta_key'] = 'min_salary';
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
                    'industry' => get_field('industry', $post_id),
                    'prefecture' => get_field('prefecture', $post_id),
                    'city' => get_field('city', $post_id),
                    'job_type' => get_field('job_type', $post_id),
                    'min_salary' => get_field('min_salary', $post_id),
                    'max_salary' => get_field('max_salary', $post_id),
                    'working_hours' => get_field('working_hours', $post_id),
                    'holidays' => get_field('holidays', $post_id),
                    'employment_type' => get_field('employment_type', $post_id),
                    'job_description' => get_field('job_description', $post_id),
                    'h_application_requirements' => get_field('h_application_requirements', $post_id),
                    'h_stock_public' => get_field('h_stock_public', $post_id),
                    'display_expected_salary' => get_field('display_expected_salary', $post_id),
                    'listed_company' => get_field('listed_company', $post_id),
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
            'annual_holidays_120' => '年間休日120日以上',
            'housing_allowance' => '寮・社宅・住宅手当あり',
            'retirement_benefits' => '退職金制度',
            'qualification_support' => '資格取得支援制度あり',
            'maternity_leave_record' => '産休・育休・介護休暇取得実績あり',
            'women_active' => '女性が活躍',
            'men_active' => '男性が活躍',
            'incentive_available' => 'インセンティブあり',
            'ui_turn_support' => 'U・Iターン支援あり',
            'remote_interview_ok' => 'リモート面接OK',
            'middle_active' => 'ミドル活躍中',
            'senior_active' => 'シニア活躍中',
            'new_graduate' => '新卒',
            'second_new_graduate' => '第二新卒',
            'mid_career' => '中途採用',
            'transportation_allowance' => '交通費支給',
            'full_weekend_off' => '完全週休二日制',
            'weekend_holiday' => '土日祝休み',
            'low_overtime' => '残業少なめ（月20時間未満）',
            'flex_time' => 'フレックスタイム制度あり',
            'remote_work' => 'リモートワーク・在宅勤務制度',
            'no_transfer' => '転勤なし',
            'education_unnecessary' => '学歴不問',
            'full_time_employee' => '正社員',
            'management_position' => '管理職・マネージャー職の求人',
            'established_10years' => '設立10年以上の会社',
            'venture_company' => 'ベンチャー企業',
            'job_inexperienced_ok' => '職種未経験OK',
            'industry_inexperienced_ok' => '業種未経験OK',
            'listed_company' => '上場企業',
            'work_experience_unnecessary' => '社会人経験不問',
            'it_skill_unnecessary' => 'ITスキル不問'
        );

        foreach ($condition_fields as $field => $label) {
            $field_value = get_field($field, $post_id);
            if ($field_value && strtolower(trim($field_value)) === 'y') {
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
        $summary_html = '';

        // 勤務地
        $location_text = $this->format_location_summary($params);
        if (!empty($location_text)) {
            $summary_html .= '<dl><dt>勤務地</dt><dd>' . $location_text . '</dd></dl>';
        }

        // 職種（業界付きを優先）
        if (!empty($params['job_type_with_industry'])) {
            $job_types_with_industry = is_array($params['job_type_with_industry']) ? $params['job_type_with_industry'] : array($params['job_type_with_industry']);
            $formatted_job_types = array();
            
            foreach ($job_types_with_industry as $industry_job_type) {
                $parts = explode('|', $industry_job_type);
                if (count($parts) == 2) {
                    $formatted_job_types[] = $parts[1] . '（' . $parts[0] . '）';
                }
            }
            
            if (!empty($formatted_job_types)) {
                $summary_html .= '<dl><dt>職種</dt><dd>' . esc_html(implode('、', $formatted_job_types)) . '</dd></dl>';
            }
        } elseif (!empty($params['job_type'])) {
            $job_types = is_array($params['job_type']) ? $params['job_type'] : array($params['job_type']);
            $summary_html .= '<dl><dt>職種</dt><dd>' . esc_html(implode('、', $job_types)) . '</dd></dl>';
        }

        // 給与
        if (!empty($params['income']) && is_array($params['income'])) {
            $summary_html .= '<dl><dt>給与</dt><dd>' . esc_html(implode('、', $params['income'])) . '</dd></dl>';
        }

        // 年収
        if (!empty($params['salary_min']) || !empty($params['salary_max'])) {
            $salary_text = '';
            if (!empty($params['salary_min']) && !empty($params['salary_max'])) {
                $salary_text = number_format($params['salary_min']) . '円〜' . number_format($params['salary_max']) . '円';
            } elseif (!empty($params['salary_min'])) {
                $salary_text = number_format($params['salary_min']) . '円以上';
            } else {
                $salary_text = number_format($params['salary_max']) . '円以下';
            }
            $summary_html .= '<dl><dt>年収</dt><dd>' . esc_html($salary_text) . '</dd></dl>';
        }

        // 年収範囲（サイドバーからの選択）
        if (!empty($params['salary_range'])) {
            $range_parts = explode('-', $params['salary_range']);
            if (count($range_parts) == 2) {
                $min_salary = intval($range_parts[0]);
                $max_salary = intval($range_parts[1]);
                $hundred_million = floor($min_salary / 1000000);
                
                if ($hundred_million >= 10) {
                    $salary_text = number_format($hundred_million / 10, 1) . ',000万円台';
                } else {
                    $salary_text = $hundred_million . '00万円台';
                }
                $summary_html .= '<dl><dt>年収</dt><dd>' . esc_html($salary_text) . '</dd></dl>';
            }
        }

        // 特徴（こだわり条件）
        if (!empty($params['conditions']) && is_array($params['conditions'])) {
            $summary_html .= '<dl><dt>特徴</dt><dd>' . esc_html(implode('、', $params['conditions'])) . '</dd></dl>';
        }

        // キーワード
        if (!empty($params['keyword'])) {
            $summary_html .= '<dl><dt>キーワード</dt><dd>' . esc_html($params['keyword']) . '</dd></dl>';
        }

        return $summary_html;
    }

    /**
     * Format location summary with prefecture and city combined
     */
    private function format_location_summary($params) {
        global $wpdb;
        
        $prefectures = array();
        $cities = array();
        
        // Handle area parameter - convert to prefectures
        if (!empty($params['area'])) {
            $area_ids = is_array($params['area']) ? $params['area'] : array($params['area']);
            $area_names = oks_get_area_name_mapping();
            
            foreach ($area_ids as $area_id) {
                if (isset($area_names[intval($area_id)])) {
                    $prefectures[] = $area_names[intval($area_id)];
                }
            }
        }
        
        // Handle prefecture parameter
        if (!empty($params['prefecture'])) {
            $prefecture_params = is_array($params['prefecture']) ? $params['prefecture'] : array($params['prefecture']);
            $prefectures = array_merge($prefectures, $prefecture_params);
        }
        
        // Remove duplicates from prefectures
        $prefectures = array_unique($prefectures);
        
        if (!empty($params['city'])) {
            $cities = is_array($params['city']) ? $params['city'] : array($params['city']);
        }
        
        // 都道府県のみ選択されている場合
        $prefecture_only = array();
        
        // 市区町村と都道府県のマッピングを作成
        $city_to_prefecture_map = array();
        if (!empty($cities)) {
            // 各市区町村がどの都道府県に属するか調べる
            foreach ($cities as $city) {
                $prefecture = $wpdb->get_var($wpdb->prepare("
                    SELECT DISTINCT pm_pref.meta_value
                    FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm_city ON p.ID = pm_city.post_id
                    INNER JOIN {$wpdb->postmeta} pm_pref ON p.ID = pm_pref.post_id
                    WHERE p.post_type = 'job'
                    AND p.post_status = 'publish'
                    AND pm_city.meta_key = 'city'
                    AND pm_city.meta_value = %s
                    AND pm_pref.meta_key = 'prefecture'
                    LIMIT 1
                ", $city));
                
                if ($prefecture) {
                    if (!isset($city_to_prefecture_map[$prefecture])) {
                        $city_to_prefecture_map[$prefecture] = array();
                    }
                    $city_to_prefecture_map[$prefecture][] = $city;
                }
            }
        }
        
        // 都道府県のみ（市区町村が選択されていない）を判定
        foreach ($prefectures as $prefecture) {
            if (!isset($city_to_prefecture_map[$prefecture])) {
                $prefecture_only[] = $prefecture;
            }
        }
        
        // 地域順（北から南）でソート
        $prefecture_order = array(
            '北海道',
            '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県',
            '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
            '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県',
            '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県',
            '沖縄県'
        );
        
        // 結果を格納する配列
        $formatted_locations = array();
        
        // 地域順で処理
        foreach ($prefecture_order as $pref) {
            // 市区町村が選択されている場合
            if (isset($city_to_prefecture_map[$pref])) {
                $pref_cities = $city_to_prefecture_map[$pref];
                $escaped_cities = array_map('esc_html', $pref_cities);
                $formatted_locations[] = esc_html($pref) . implode('・', $escaped_cities);
            }
            // 都道府県のみ選択されている場合
            elseif (in_array($pref, $prefecture_only)) {
                $formatted_locations[] = esc_html($pref);
            }
        }
        
        // エリア情報を先頭に追加
        if (!empty($areas)) {
            $area_text = implode('・', array_map('esc_html', $areas));
            array_unshift($formatted_locations, $area_text);
        }
        
        return implode('<br />', $formatted_locations);
    }

    /**
     * Get posts by keyword search in title, content and meta fields
     */
    private function get_posts_by_keyword($keyword) {
        global $wpdb;

        $search_terms = explode(' ', $keyword);
        $searchable_meta_keys = array(
            'company', 'job_type', 'prefecture', 'city', 'job_description',
            'work_location', 'work_location_details', 'industry', 'employment_type', 
            'benefits', 'salary_details', 'working_hours', 'holidays', 'access',
            'required_conditions', 'welcome_conditions_2', 'recruitment_background',
            'display_title', 'annual_income', 'salary'
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

    /**
     * Get unique prefectures from job posts
     */
    public function get_unique_prefectures() {
        global $wpdb;

        $prefectures = $wpdb->get_col($wpdb->prepare("
            SELECT DISTINCT pm.meta_value
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'job'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'prefecture'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            ORDER BY pm.meta_value ASC
        "));

        return $prefectures;
    }

    /**
     * Get unique cities by prefecture from job posts
     */
    public function get_unique_cities_by_prefecture($prefecture = null) {
        global $wpdb;

        if ($prefecture) {
            // Return cities for specific prefecture
            $query = "
                SELECT DISTINCT pm_city.meta_value as city
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm_pref ON p.ID = pm_pref.post_id AND pm_pref.meta_key = 'prefecture'
                INNER JOIN {$wpdb->postmeta} pm_city ON p.ID = pm_city.post_id AND pm_city.meta_key = 'city'
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm_city.meta_value != ''
                AND pm_city.meta_value IS NOT NULL
                AND pm_pref.meta_value = %s
                ORDER BY pm_city.meta_value ASC
            ";

            $cities = $wpdb->get_col($wpdb->prepare($query, $prefecture));
            return $cities;
        } else {
            // Return all cities grouped by prefecture
            $query = "
                SELECT DISTINCT pm_pref.meta_value as prefecture, pm_city.meta_value as city
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm_pref ON p.ID = pm_pref.post_id AND pm_pref.meta_key = 'prefecture'
                INNER JOIN {$wpdb->postmeta} pm_city ON p.ID = pm_city.post_id AND pm_city.meta_key = 'city'
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm_city.meta_value != ''
                AND pm_city.meta_value IS NOT NULL
                AND pm_pref.meta_value != ''
                AND pm_pref.meta_value IS NOT NULL
                ORDER BY pm_pref.meta_value ASC, pm_city.meta_value ASC
            ";

            $results = $wpdb->get_results($query);

            // Group cities by prefecture
            $cities_by_prefecture = array();
            foreach ($results as $result) {
                if (!isset($cities_by_prefecture[$result->prefecture])) {
                    $cities_by_prefecture[$result->prefecture] = array();
                }
                $cities_by_prefecture[$result->prefecture][] = $result->city;
            }

            return $cities_by_prefecture;
        }
    }

    /**
     * Get unique industries from job posts
     */
    public function get_unique_industries() {
        global $wpdb;

        $industries = $wpdb->get_col($wpdb->prepare("
            SELECT DISTINCT pm.meta_value
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'job'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'industry'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            ORDER BY pm.meta_value ASC
        "));

        return $industries;
    }

    /**
     * Get unique job types by industry from job posts
     */
    public function get_unique_job_types_by_industry($industry = null) {
        global $wpdb;

        if ($industry) {
            // Return job types for specific industry
            $query = "
                SELECT DISTINCT pm_job.meta_value as job_type
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm_ind ON p.ID = pm_ind.post_id AND pm_ind.meta_key = 'industry'
                INNER JOIN {$wpdb->postmeta} pm_job ON p.ID = pm_job.post_id AND pm_job.meta_key = 'job_type'
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm_job.meta_value != ''
                AND pm_job.meta_value IS NOT NULL
                AND pm_ind.meta_value = %s
                ORDER BY pm_job.meta_value ASC
            ";

            $job_types = $wpdb->get_col($wpdb->prepare($query, $industry));
            return $job_types;
        } else {
            // Return all job types grouped by industry
            $query = "
                SELECT DISTINCT pm_ind.meta_value as industry, pm_job.meta_value as job_type
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm_ind ON p.ID = pm_ind.post_id AND pm_ind.meta_key = 'industry'
                INNER JOIN {$wpdb->postmeta} pm_job ON p.ID = pm_job.post_id AND pm_job.meta_key = 'job_type'
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm_job.meta_value != ''
                AND pm_job.meta_value IS NOT NULL
                AND pm_ind.meta_value != ''
                AND pm_ind.meta_value IS NOT NULL
                ORDER BY pm_ind.meta_value ASC, pm_job.meta_value ASC
            ";

            $results = $wpdb->get_results($query);
            
            // Group job types by industry
            $job_types_by_industry = array();
            foreach ($results as $result) {
                if (!isset($job_types_by_industry[$result->industry])) {
                    $job_types_by_industry[$result->industry] = array();
                }
                $job_types_by_industry[$result->industry][] = $result->job_type;
            }

            return $job_types_by_industry;
        }
    }

    /**
     * Get unique salary types from job posts
     */
    public function get_unique_salary_types() {
        global $wpdb;

        $salary_types = $wpdb->get_col($wpdb->prepare("
            SELECT DISTINCT pm.meta_value
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'job'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'salary_type'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            ORDER BY pm.meta_value ASC
        "));

        return $salary_types;
    }

    /**
     * Get posts that match salary conditions
     */
    private function get_posts_by_salary_conditions($income_conditions) {
        global $wpdb;
        
        // 給与条件の定義（時給と月給の境界値）
        $hourly_conditions = array(
            '1,300円未満' => array('type' => 'hourly', 'max' => 1300),
            '1,300円以上' => array('type' => 'hourly', 'min' => 1300)
        );
        
        $monthly_conditions = array(
            '18万円未満' => array('type' => 'monthly', 'max' => 180000),
            '19万円以上' => array('type' => 'monthly', 'min' => 190000),
            '20万円以上' => array('type' => 'monthly', 'min' => 200000),
            '21万円以上' => array('type' => 'monthly', 'min' => 210000),
            '22万円以上' => array('type' => 'monthly', 'min' => 220000),
            '23万円以上' => array('type' => 'monthly', 'min' => 230000),
            '24万円以上' => array('type' => 'monthly', 'min' => 240000),
            '25万円以上' => array('type' => 'monthly', 'min' => 250000)
        );
        
        $all_conditions = array_merge($hourly_conditions, $monthly_conditions);
        
        // 全ての求人の給与データを取得
        $salary_data = $wpdb->get_results("
            SELECT p.ID, pm.meta_value as salary
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'job'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'salary'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
        ");
        
        $matching_post_ids = array();
        
        foreach ($salary_data as $row) {
            $salary_value = $row->salary;
            $post_id = $row->ID;
            
            // 給与値を解析（例: "206,000円 ～ 238,000円"）
            $salary_range = $this->parse_salary_value($salary_value);
            
            if (!$salary_range) continue;
            
            foreach ($income_conditions as $condition) {
                if (!isset($all_conditions[$condition])) continue;
                
                $condition_def = $all_conditions[$condition];
                
                // 条件に合致するかチェック
                if ($this->salary_matches_condition($salary_range, $condition_def)) {
                    $matching_post_ids[] = $post_id;
                    break; // 一つでも条件に合致すれば追加
                }
            }
        }
        
        return array_unique($matching_post_ids);
    }
    
    /**
     * Parse salary value and extract range
     */
    private function parse_salary_value($salary_value) {
        // 数字とカンマを抽出
        preg_match_all('/[\d,]+/', $salary_value, $matches);
        
        if (empty($matches[0])) return false;
        
        $numbers = array();
        foreach ($matches[0] as $match) {
            $number = intval(str_replace(',', '', $match));
            if ($number > 0) {
                $numbers[] = $number;
            }
        }
        
        if (empty($numbers)) return false;
        
        // 時給か月給かを判定（1万円未満なら時給、以上なら月給）
        $type = ($numbers[0] < 10000) ? 'hourly' : 'monthly';
        
        return array(
            'type' => $type,
            'min' => min($numbers),
            'max' => max($numbers)
        );
    }
    
    /**
     * Check if salary range matches condition
     */
    private function salary_matches_condition($salary_range, $condition_def) {
        // 給与形態が一致しない場合はfalse
        if ($salary_range['type'] !== $condition_def['type']) {
            return false;
        }
        
        // 最小値の条件チェック（例：20万円以上）
        if (isset($condition_def['min'])) {
            // 給与の最小値が条件の最小値以上である必要がある
            // 例：206,000円 ～ 238,000円 の場合、最小値206,000円が200,000円以上なので該当
            if ($salary_range['min'] < $condition_def['min']) {
                return false;
            }
        }
        
        // 最大値の条件チェック（例：18万円未満）
        if (isset($condition_def['max'])) {
            // 給与の最大値が条件の最大値未満である必要がある
            // 例：150,000円 ～ 170,000円 の場合、最大値170,000円が180,000円未満なので該当
            if ($salary_range['max'] >= $condition_def['max']) {
                return false;
            }
        }
        
        return true;
    }
}