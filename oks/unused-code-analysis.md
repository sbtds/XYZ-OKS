# WordPress Theme "oks" - Unused Code Analysis

## 1. Incorrect File Paths

### Critical Issue
- **File:** `functions.php`
- **Lines:** 98-99
- **Issue:** Trying to include `/includes/search/job-search-loader.php`
- **Actual Path:** `/includes/job-search/job-search-loader.php`
- **Status:** This include is failing silently due to `file_exists()` check

### Non-existent CSS File Reference
- **File:** `functions.php`
- **Lines:** 44-49
- **Issue:** Trying to enqueue `/assets/css/single-job.css` which doesn't exist
- **Impact:** 404 error on single job pages

## 2. Functions That Appear Unused (May Be False Positives)

### WordPress Hook Functions (Actually Used)
These functions are registered as WordPress hooks and are actually in use:
- `oks_theme_setup` - Line 3 in functions.php (hook: after_setup_theme)
- `oks_acf_json_save_point` - Line 27 (filter: acf/settings/save_json)
- `oks_acf_json_load_point` - Line 33 (filter: acf/settings/load_json)
- `oks_enqueue_styles` - Line 39 (hook: wp_enqueue_scripts)
- `oks_enqueue_scripts` - Line 62 (hook: wp_enqueue_scripts)
- `oks_widgets_init` - Line 75 (hook: widgets_init)
- `oks_change_post_labels` - Line 112 (hook: admin_menu)
- `oks_change_post_object_labels` - Line 144 (hook: admin_init)
- `oks_change_post_type_labels` - Line 174 (hook: wp_loaded)
- `oks_enqueue_post_editor_styles` - Line 189 (hook: enqueue_block_editor_assets)
- `oks_add_id_to_h2_tags` - Line 287 (filter: the_content)
- `oks_enqueue_location_checkboxes_script` - Line 145 in location-checkboxes.php (hook: wp_footer)

### Functions That Need Further Investigation
These functions might be unused or called dynamically:
- `updateSelectAllState` - JavaScript function in location-checkboxes.php (line 204)
- Comment disable functions in `/includes/comment-disable/class-comment-disable.php`

## 3. Dead Code Blocks and Issues

### Unused Includes
- Multiple files include job-search-loader.php directly when it should be loaded via functions.php:
  - `page-search.php` line 12
  - `template-parts/search-sidebar.php` line 11
  - `index.php` line 9

### CSS/JS Files Not in Dist
These files exist but may not be properly bundled:
- `/includes/job-search/assets/job-search.js`
- `/includes/job-search/assets/job-search.css`
- `/includes/csv-import/assets/admin.css`

## 4. Recommendations

### Immediate Fixes Required
1. **Fix the incorrect path in functions.php:**
   - Change line 98 from: `/includes/search/job-search-loader.php`
   - To: `/includes/job-search/job-search-loader.php`

2. **Remove or fix the single-job.css reference:**
   - Either create the missing CSS file at `/assets/css/single-job.css`
   - Or remove lines 42-50 in functions.php

### Code Cleanup Suggestions
1. **Remove redundant includes:**
   - Remove direct includes of job-search-loader.php from:
     - page-search.php (line 12)
     - template-parts/search-sidebar.php (line 11)
     - index.php (line 9)
   - These are redundant since functions.php should handle loading

2. **Bundle assets properly:**
   - Consider moving job-search.css/js to the webpack build process
   - Ensure admin.css is only loaded on admin pages

3. **Review comment disable functionality:**
   - Verify if the comment disable module is actually needed
   - If not, remove the entire `/includes/comment-disable/` directory

## 5. File Structure Issues

The theme appears to have mixed approaches to asset management:
- Some assets are in `/dist/` (webpack bundled)
- Some are in `/includes/*/assets/` (directly included)
- Some referenced assets don't exist (`single-job.css`)

Consider standardizing the asset management approach for consistency.