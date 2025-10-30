<?php
/**
 * Debug script for search conditions issue
 * 
 * This script helps debug the "上場企業" search condition issue.
 * Run this by accessing it directly in the browser or via wp-cli.
 */

// WordPress environment
if (!defined('ABSPATH')) {
    // Load WordPress
    require_once('../../../wp-load.php');
}

// Enable debug output
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

echo "<h1>Search Conditions Debug Report</h1>\n";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>\n";

// 1. Check if there are any job posts with listed_company field
echo "<h2>1. Job Posts with 'listed_company' Field</h2>\n";

global $wpdb;

$posts_with_listed_company = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm.meta_value
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'job'
    AND p.post_status = 'publish'
    AND pm.meta_key = 'listed_company'
    AND pm.meta_value != ''
    ORDER BY p.ID
    LIMIT 10
");

if (!empty($posts_with_listed_company)) {
    echo "<table>";
    echo "<tr><th>Post ID</th><th>Title</th><th>listed_company Value</th></tr>";
    foreach ($posts_with_listed_company as $post) {
        echo "<tr><td>{$post->ID}</td><td>" . esc_html($post->post_title) . "</td><td>" . esc_html($post->meta_value) . "</td></tr>";
    }
    echo "</table>";
    echo "<p><strong>Found " . count($posts_with_listed_company) . " job posts with 'listed_company' field.</strong></p>";
} else {
    echo "<p><strong>No job posts found with 'listed_company' field.</strong></p>";
}

// 2. Check if there are any job posts with 上場企業 field (wrong field name)
echo "<h2>2. Job Posts with '上場企業' Field (Wrong Field Name)</h2>\n";

$posts_with_wrong_field = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm.meta_value
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'job'
    AND p.post_status = 'publish'
    AND pm.meta_key = '上場企業'
    AND pm.meta_value != ''
    ORDER BY p.ID
    LIMIT 10
");

if (!empty($posts_with_wrong_field)) {
    echo "<table>";
    echo "<tr><th>Post ID</th><th>Title</th><th>上場企業 Value</th></tr>";
    foreach ($posts_with_wrong_field as $post) {
        echo "<tr><td>{$post->ID}</td><td>" . esc_html($post->post_title) . "</td><td>" . esc_html($post->meta_value) . "</td></tr>";
    }
    echo "</table>";
    echo "<p><strong>Found " . count($posts_with_wrong_field) . " job posts with '上場企業' field.</strong></p>";
} else {
    echo "<p><strong>No job posts found with '上場企業' field (this is expected).</strong></p>";
}

// 3. Show all meta keys related to job posts to identify the correct field names
echo "<h2>3. All Meta Keys for Job Posts (Sample)</h2>\n";

$all_meta_keys = $wpdb->get_results("
    SELECT DISTINCT pm.meta_key, COUNT(*) as count
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'job'
    AND p.post_status = 'publish'
    AND pm.meta_key LIKE '%company%' OR pm.meta_key LIKE '%listed%' OR pm.meta_key LIKE '%上場%'
    GROUP BY pm.meta_key
    ORDER BY pm.meta_key
");

if (!empty($all_meta_keys)) {
    echo "<table>";
    echo "<tr><th>Meta Key</th><th>Count</th></tr>";
    foreach ($all_meta_keys as $key) {
        echo "<tr><td>" . esc_html($key->meta_key) . "</td><td>{$key->count}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No meta keys found matching the criteria.</p>";
}

// 4. Test search mapping discrepancies
echo "<h2>4. Search Field Mapping Issues</h2>\n";

// These are the known discrepancies between search handler and CSV mapping
$discrepancies = array(
    '上場企業' => array(
        'search_handler' => '上場企業',
        'csv_mapping' => 'listed_company',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    '正社員' => array(
        'search_handler' => 'regular_employee',
        'csv_mapping' => 'full_time_employee', 
        'description' => 'Field name mismatch - different English field names'
    ),
    '年間休日120日以上' => array(
        'search_handler' => '年間休日120日以上',
        'csv_mapping' => 'annual_holidays_120',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    '寮・社宅・住宅手当あり' => array(
        'search_handler' => '寮・社宅・住宅手当あり',
        'csv_mapping' => 'housing_allowance',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    '退職金制度' => array(
        'search_handler' => '退職金制度',
        'csv_mapping' => 'retirement_benefits',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    '資格取得支援制度' => array(
        'search_handler' => '資格取得支援制度',
        'csv_mapping' => 'qualification_support',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    'U・Iターン支援あり' => array(
        'search_handler' => 'U・Iターン支援あり',
        'csv_mapping' => 'ui_turn_support',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    'リモート面接OK' => array(
        'search_handler' => 'リモート面接OK',
        'csv_mapping' => 'remote_interview_ok',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    'インセンティブあり' => array(
        'search_handler' => 'インセンティブあり',
        'csv_mapping' => 'incentive_available',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    ),
    '管理職・マネージャー' => array(
        'search_handler' => '管理職・マネージャー職の求人',
        'csv_mapping' => 'management_position',
        'description' => 'Field name mismatch - search handler uses Japanese, CSV uses English field name'
    )
);

echo "<table>";
echo "<tr><th>Condition</th><th>Search Handler Field</th><th>CSV Mapping Field</th><th>Issue</th></tr>";
foreach ($discrepancies as $condition => $info) {
    echo "<tr>";
    echo "<td>" . esc_html($condition) . "</td>";
    echo "<td>" . esc_html($info['search_handler']) . "</td>";
    echo "<td>" . esc_html($info['csv_mapping']) . "</td>";
    echo "<td>" . esc_html($info['description']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// 5. Test actual search for listed companies
echo "<h2>5. Search Test for Listed Companies</h2>\n";

// Test with the correct field name
$correct_search_args = array(
    'post_type' => 'job',
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => 'listed_company',
            'value' => array('1', 'true', 'y'),
            'compare' => 'IN'
        )
    ),
    'posts_per_page' => 5
);

$correct_query = new WP_Query($correct_search_args);
echo "<h3>Search with correct field name 'listed_company':</h3>";
echo "<p>Found {$correct_query->found_posts} posts</p>";

if ($correct_query->have_posts()) {
    echo "<ul>";
    while ($correct_query->have_posts()) {
        $correct_query->the_post();
        $listed_value = get_field('listed_company');
        echo "<li>" . get_the_title() . " (listed_company: " . esc_html($listed_value) . ")</li>";
    }
    echo "</ul>";
    wp_reset_postdata();
}

// Test with the wrong field name (what search handler currently uses)
$wrong_search_args = array(
    'post_type' => 'job',
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => '上場企業',
            'value' => array('1', 'true', 'y'),
            'compare' => 'IN'
        )
    ),
    'posts_per_page' => 5
);

$wrong_query = new WP_Query($wrong_search_args);
echo "<h3>Search with wrong field name '上場企業':</h3>";
echo "<p>Found {$wrong_query->found_posts} posts</p>";

// 6. Summary and recommendations
echo "<h2>6. Summary and Recommendations</h2>\n";
echo "<div style='background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px;'>";
echo "<h3>Issues Found:</h3>";
echo "<ul>";
echo "<li>Search handler is using Japanese field names (like '上場企業') but ACF fields use English names (like 'listed_company')</li>";
echo "<li>This causes search conditions to fail because the field names don't match</li>";
echo "<li>Similar issues exist for many other condition fields</li>";
echo "</ul>";

echo "<h3>Solution:</h3>";
echo "<p><strong>Update the search handler field mapping to use the correct ACF field names from the CSV mapping.</strong></p>";

echo "<h3>Files to Fix:</h3>";
echo "<ul>";
echo "<li><code>/includes/job-search/class-search-handler.php</code> - Update the \$condition_mapping array (lines 190-228)</li>";
echo "</ul>";
echo "</div>";

echo "<p><em>Debug script completed. You can now fix the search handler mapping.</em></p>";
?>