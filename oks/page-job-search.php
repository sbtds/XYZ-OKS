<?php
/**
 * Template Name: 求人検索ページ
 * Template for Job Search Page
 * 
 * @package OKS
 */

get_header(); ?>

<div class="job-search-page">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">求人検索</h1>
        </header>
        
        <div class="search-content">
            <?php
            // Render search form
            echo do_shortcode('[oks_job_search]');
            ?>
        </div>
        
        <div id="search-results-section" class="search-results-section" style="display: none;">
            <div class="results-header">
                <div class="results-summary"></div>
                <div class="results-sort">
                    <label for="results-orderby">並び順：</label>
                    <select id="results-orderby" name="orderby">
                        <option value="newest">新着順</option>
                        <option value="salary_high">年収が高い順</option>
                        <option value="salary_low">年収が低い順</option>
                        <option value="oldest">更新が古い順</option>
                    </select>
                </div>
            </div>
            
            <div id="oks-search-results" class="oks-search-results">
                <!-- Ajax results will be loaded here -->
            </div>
            
            <div class="pagination-wrapper">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="job-item-template">
    <div class="job-item" data-job-id="{{id}}">
        <div class="job-header">
            <h3 class="job-title">
                <a href="{{permalink}}">{{title}}</a>
            </h3>
            <div class="job-company">{{company}}</div>
        </div>
        
        <div class="job-details">
            <div class="job-location">
                <span class="detail-label">勤務地:</span>
                <span class="detail-value">{{prefecture}}{{city}}</span>
            </div>
            
            <div class="job-type">
                <span class="detail-label">職種:</span>
                <span class="detail-value">{{job_type}}</span>
            </div>
            
            <div class="job-salary">
                <span class="detail-label">年収:</span>
                <span class="detail-value">{{salary_range}}</span>
            </div>
            
            <div class="job-employment">
                <span class="detail-label">雇用形態:</span>
                <span class="detail-value">{{employment_type}}</span>
            </div>
        </div>
        
        <div class="job-conditions">
            {{conditions_html}}
        </div>
        
        <div class="job-description">
            {{job_description}}
        </div>
        
        <div class="job-footer">
            <div class="job-updated">更新日: {{updated}}</div>
            <a href="{{permalink}}" class="job-detail-link">詳細を見る</a>
        </div>
    </div>
</script>

<?php get_footer(); ?>