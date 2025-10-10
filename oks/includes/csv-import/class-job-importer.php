<?php
/**
 * Job Importer
 * 
 * @package OKS
 * @subpackage CSV_Import
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Job Importer Class
 */
class OKS_Job_Importer {
    
    /**
     * CSV Processor instance
     */
    private $csv_processor;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->csv_processor = new OKS_CSV_Processor();
    }
    
    /**
     * Import CSV file
     */
    public function import($file_path, $mode = 'update') {
        $result = array(
            'success' => false,
            'message' => '',
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => array()
        );
        
        // Read CSV data
        $data = $this->csv_processor->read_csv($file_path);
        if ($data === false) {
            $result['message'] = 'CSVファイルの読み込みに失敗しました。ファイル形式を確認してください。';
            
            // Get detailed error information from CSV processor
            $detailed_errors = $this->csv_processor->get_last_errors();
            if (!empty($detailed_errors)) {
                $result['details'] = $detailed_errors;
            }
            
            return $result;
        }
        
        if (empty($data)) {
            $result['message'] = 'CSVファイルにデータが含まれていません。';
            return $result;
        }
        
        // Process each row
        foreach ($data as $row) {
            $row_number = $row['_row_number'];
            
            try {
                // Check if deletion flag is set
                if (isset($row['削除']) && $row['削除'] === '1') {
                    $this->delete_job($row['社内求人ID']);
                    continue;
                }
                
                // Check for internal job ID
                if (empty($row['社内求人ID'])) {
                    $result['errors'][$row_number] = '社内求人IDが空です';
                    continue;
                }
                
                // Process row data
                $processed_data = $this->csv_processor->process_row_data($row);
                
                // Find existing job
                $existing_job = $this->find_job_by_internal_id($row['社内求人ID']);
                
                if ($existing_job) {
                    if ($mode === 'skip') {
                        $result['skipped']++;
                    } else {
                        // Update existing job
                        $update_result = $this->update_job($existing_job->ID, $processed_data, $row);
                        if ($update_result) {
                            $result['updated']++;
                        } else {
                            $result['errors'][$row_number] = '更新に失敗しました';
                        }
                    }
                } else {
                    // Create new job
                    $create_result = $this->create_job($processed_data, $row);
                    if ($create_result) {
                        $result['created']++;
                    } else {
                        $result['errors'][$row_number] = '作成に失敗しました';
                    }
                }
                
            } catch (Exception $e) {
                $result['errors'][$row_number] = $e->getMessage();
            }
        }
        
        $result['success'] = true;
        return $result;
    }
    
    /**
     * Find job by internal ID
     */
    private function find_job_by_internal_id($internal_id) {
        $args = array(
            'post_type' => 'job',
            'meta_query' => array(
                array(
                    'key' => 'internal_job_id',
                    'value' => $internal_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        );
        
        $query = new WP_Query($args);
        return $query->have_posts() ? $query->posts[0] : null;
    }
    
    /**
     * Create new job
     */
    private function create_job($data, $raw_data) {
        // Prepare post data
        $post_title = !empty($data['display_title']) ? $data['display_title'] : $data['admin_title'];
        if (empty($post_title)) {
            $post_title = '求人情報 - ' . $data['internal_job_id'];
        }
        
        $post_data = array(
            'post_type' => 'job',
            'post_status' => 'publish',
            'post_title' => $post_title,
            'post_content' => '',
            'post_name' => sanitize_title($data['internal_job_id']) // Set slug from internal_job_id
        );
        
        // Insert post
        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            return false;
        }
        
        // Update ACF fields
        return $this->update_acf_fields($post_id, $data);
    }
    
    /**
     * Update existing job
     */
    private function update_job($post_id, $data, $raw_data) {
        // Prepare post data
        $post_title = !empty($data['display_title']) ? $data['display_title'] : $data['admin_title'];
        if (empty($post_title)) {
            $post_title = '求人情報 - ' . $data['internal_job_id'];
        }
        
        $post_data = array(
            'ID' => $post_id,
            'post_title' => $post_title,
            'post_content' => '',
            'post_name' => sanitize_title($data['internal_job_id']) // Set slug from internal_job_id
        );
        
        // Update post
        $result = wp_update_post($post_data);
        
        if (is_wp_error($result)) {
            return false;
        }
        
        // Update ACF fields
        return $this->update_acf_fields($post_id, $data);
    }
    
    /**
     * Delete job
     */
    private function delete_job($internal_id) {
        $job = $this->find_job_by_internal_id($internal_id);
        if ($job) {
            wp_delete_post($job->ID, true);
        }
    }
    
    /**
     * Update ACF fields
     */
    private function update_acf_fields($post_id, $data) {
        try {
            foreach ($data as $field_name => $value) {
                // Skip empty values for optional fields
                if ($value === '' && !in_array($field_name, array('internal_job_id'))) {
                    continue;
                }
                
                // Update field
                update_field($field_name, $value, $post_id);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('ACF field update error: ' . $e->getMessage());
            return false;
        }
    }
}