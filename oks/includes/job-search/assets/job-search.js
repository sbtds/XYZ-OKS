/**
 * Job Search JavaScript
 */

var OKS_Job_Search = {
    
    init: function() {
        this.bindEvents();
        this.initPrefectureToggle();
    },
    
    bindEvents: function() {
        var self = this;
        
        // Form submission
        jQuery('#oks-job-search-form').on('submit', function(e) {
            e.preventDefault();
            self.performSearch();
        });
        
        // Reset form
        jQuery('.search-reset').on('click', function(e) {
            e.preventDefault();
            self.resetForm();
        });
        
        // Sort change
        jQuery(document).on('change', '#results-orderby', function() {
            self.performSearch();
        });
        
        // Pagination
        jQuery(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = jQuery(this).data('page');
            if (page) {
                self.performSearch(page);
            }
        });
        
        // Prefecture checkbox change
        jQuery(document).on('change', '.prefecture-checkbox', function() {
            var prefecture = jQuery(this).data('prefecture');
            var isChecked = jQuery(this).is(':checked');
            var cityCheckboxes = jQuery('.city-list input[data-prefecture="' + prefecture + '"]');
            
            cityCheckboxes.prop('checked', isChecked);
        });
        
        // City checkbox change
        jQuery(document).on('change', '.city-list input[type="checkbox"]', function() {
            var prefecture = jQuery(this).data('prefecture');
            var allCityCheckboxes = jQuery('.city-list input[data-prefecture="' + prefecture + '"]');
            var checkedCityCheckboxes = allCityCheckboxes.filter(':checked');
            var prefectureCheckbox = jQuery('.prefecture-checkbox[data-prefecture="' + prefecture + '"]');
            
            if (checkedCityCheckboxes.length === allCityCheckboxes.length) {
                prefectureCheckbox.prop('checked', true);
                prefectureCheckbox.prop('indeterminate', false);
            } else if (checkedCityCheckboxes.length > 0) {
                prefectureCheckbox.prop('checked', false);
                prefectureCheckbox.prop('indeterminate', true);
            } else {
                prefectureCheckbox.prop('checked', false);
                prefectureCheckbox.prop('indeterminate', false);
            }
        });
    },
    
    initPrefectureToggle: function() {
        jQuery('.prefecture-label').on('click', function(e) {
            if (jQuery(e.target).is('input')) {
                return; // Let checkbox handle its own event
            }
            
            var cityList = jQuery(this).siblings('.city-list');
            var toggleIcon = jQuery(this).find('.toggle-cities');
            
            cityList.slideToggle();
            toggleIcon.text(cityList.is(':visible') ? '▲' : '▼');
        });
    },
    
    performSearch: function(page) {
        var formData = this.getFormData();
        
        if (page) {
            formData.paged = page;
        }
        
        // Add sort order
        var orderby = jQuery('#results-orderby').val();
        if (orderby) {
            formData.orderby = orderby;
        }
        
        // Show loading
        this.showLoading();
        
        jQuery.ajax({
            url: oks_job_search.ajax_url,
            type: 'POST',
            data: {
                action: 'oks_job_search',
                nonce: oks_job_search.nonce,
                ...formData
            },
            success: function(response) {
                if (response.success) {
                    OKS_Job_Search.displayResults(response.data);
                } else {
                    OKS_Job_Search.showError('検索中にエラーが発生しました。');
                }
            },
            error: function() {
                OKS_Job_Search.showError('検索中にエラーが発生しました。');
            },
            complete: function() {
                OKS_Job_Search.hideLoading();
            }
        });
    },
    
    getFormData: function() {
        var formData = {};
        var form = jQuery('#oks-job-search-form');
        
        // Cities
        var cities = [];
        form.find('input[name="city[]"]:checked').each(function() {
            cities.push(jQuery(this).val());
        });
        if (cities.length > 0) {
            formData.city = cities;
        }
        
        // Job types
        var jobTypes = [];
        form.find('input[name="job_type[]"]:checked').each(function() {
            jobTypes.push(jQuery(this).val());
        });
        if (jobTypes.length > 0) {
            formData.job_type = jobTypes;
        }
        
        // Salary range
        var salaryMin = form.find('select[name="salary_min"]').val();
        var salaryMax = form.find('select[name="salary_max"]').val();
        if (salaryMin) formData.salary_min = salaryMin;
        if (salaryMax) formData.salary_max = salaryMax;
        
        // Conditions
        var conditions = [];
        form.find('input[name="conditions[]"]:checked').each(function() {
            conditions.push(jQuery(this).val());
        });
        if (conditions.length > 0) {
            formData.conditions = conditions;
        }
        
        // Keyword
        var keyword = form.find('input[name="keyword"]').val().trim();
        if (keyword) {
            formData.keyword = keyword;
        }
        
        return formData;
    },
    
    displayResults: function(data) {
        var resultsSection = jQuery('#search-results-section');
        var resultsContainer = jQuery('#oks-search-results');
        var summaryContainer = jQuery('.results-summary');
        
        // Show results section
        resultsSection.show();
        
        // Update summary
        var summaryText = data.found_posts + '件の求人が見つかりました';
        summaryContainer.html(summaryText);
        
        // Clear previous results
        resultsContainer.empty();
        
        if (data.posts && data.posts.length > 0) {
            var template = jQuery('#job-item-template').html();
            
            jQuery.each(data.posts, function(index, job) {
                var jobHtml = template;
                
                // Replace template variables
                jobHtml = jobHtml.replace(/{{id}}/g, job.id);
                jobHtml = jobHtml.replace(/{{title}}/g, job.title || '求人情報');
                jobHtml = jobHtml.replace(/{{company}}/g, job.company || '');
                jobHtml = jobHtml.replace(/{{permalink}}/g, job.permalink);
                jobHtml = jobHtml.replace(/{{prefecture}}/g, job.prefecture || '');
                jobHtml = jobHtml.replace(/{{city}}/g, job.city || '');
                jobHtml = jobHtml.replace(/{{job_type}}/g, job.job_type || '');
                jobHtml = jobHtml.replace(/{{employment_type}}/g, job.employment_type || '');
                jobHtml = jobHtml.replace(/{{job_description}}/g, job.job_description || '');
                jobHtml = jobHtml.replace(/{{updated}}/g, job.updated || '');
                
                // Salary range
                var salaryRange = '';
                if (job.min_salary && job.max_salary) {
                    salaryRange = OKS_Job_Search.formatSalary(job.min_salary) + ' 〜 ' + OKS_Job_Search.formatSalary(job.max_salary);
                } else if (job.min_salary) {
                    salaryRange = OKS_Job_Search.formatSalary(job.min_salary) + '以上';
                } else if (job.max_salary) {
                    salaryRange = OKS_Job_Search.formatSalary(job.max_salary) + '以下';
                } else {
                    salaryRange = '応相談';
                }
                jobHtml = jobHtml.replace(/{{salary_range}}/g, salaryRange);
                
                // Conditions
                var conditionsHtml = '';
                if (job.conditions && job.conditions.length > 0) {
                    jQuery.each(job.conditions, function(i, condition) {
                        conditionsHtml += '<span class="condition-tag">' + condition + '</span>';
                    });
                }
                jobHtml = jobHtml.replace(/{{conditions_html}}/g, conditionsHtml);
                
                resultsContainer.append(jobHtml);
            });
            
            // Pagination
            this.renderPagination(data);
            
        } else {
            resultsContainer.html('<div class="no-results">条件に合う求人が見つかりませんでした。</div>');
        }
    },
    
    renderPagination: function(data) {
        var paginationWrapper = jQuery('.pagination-wrapper');
        paginationWrapper.empty();
        
        if (data.max_num_pages > 1) {
            var pagination = '<div class="pagination">';
            
            // Previous
            if (data.current_page > 1) {
                pagination += '<a href="#" class="page-link" data-page="' + (data.current_page - 1) + '">« 前へ</a>';
            }
            
            // Page numbers
            for (var i = 1; i <= data.max_num_pages; i++) {
                if (i === data.current_page) {
                    pagination += '<span class="page-current">' + i + '</span>';
                } else {
                    pagination += '<a href="#" class="page-link" data-page="' + i + '">' + i + '</a>';
                }
            }
            
            // Next
            if (data.current_page < data.max_num_pages) {
                pagination += '<a href="#" class="page-link" data-page="' + (data.current_page + 1) + '">次へ »</a>';
            }
            
            pagination += '</div>';
            paginationWrapper.html(pagination);
        }
    },
    
    formatSalary: function(amount) {
        var num = parseInt(amount);
        if (num >= 10000000) {
            return Math.floor(num / 10000000) + ',' + String(Math.floor(num / 10000) % 1000).padStart(3, '0') + '万円';
        } else {
            return Math.floor(num / 10000) + '万円';
        }
    },
    
    resetForm: function() {
        var form = jQuery('#oks-job-search-form');
        form[0].reset();
        
        // Reset prefecture checkboxes
        jQuery('.prefecture-checkbox').prop('checked', false).prop('indeterminate', false);
        
        // Hide city lists
        jQuery('.city-list').hide();
        jQuery('.toggle-cities').text('▼');
        
        // Hide results
        jQuery('#search-results-section').hide();
    },
    
    showLoading: function() {
        jQuery('#oks-search-results').html('<div class="loading">検索中...</div>');
    },
    
    hideLoading: function() {
        // Loading will be replaced by results or error message
    },
    
    showError: function(message) {
        jQuery('#oks-search-results').html('<div class="error-message">' + message + '</div>');
    }
};

// Initialize when document is ready
jQuery(document).ready(function() {
    OKS_Job_Search.init();
});