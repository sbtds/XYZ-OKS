<?php
/**
 * Template for Single Job Post
 *
 * @package OKS
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="job-detail-page">
    <div class="container">

        <!-- Job Header -->
        <header class="job-header">
            <div class="job-meta">
                <span class="job-id">求人ID: <?php echo esc_html(get_field('internal_job_id')); ?></span>
                <span class="job-updated">更新日: <?php echo get_the_modified_date('Y年m月d日'); ?></span>
            </div>

            <h1 class="job-title"><?php the_title(); ?></h1>

            <div class="company-info">
                <h2 class="company-name"><?php echo esc_html(get_field('company')); ?></h2>
                <div class="job-basic-info">
                    <div class="job-location">
                        <span class="info-label">勤務地:</span>
                        <span class="info-value">
                            <?php echo esc_html(get_field('prefecture')); ?><?php echo esc_html(get_field('city')); ?>
                        </span>
                    </div>
                    <div class="job-type">
                        <span class="info-label">職種:</span>
                        <span class="info-value"><?php echo esc_html(get_field('job_type')); ?></span>
                    </div>
                    <div class="employment-type">
                        <span class="info-label">雇用形態:</span>
                        <span class="info-value"><?php echo esc_html(get_field('employment_type')); ?></span>
                    </div>
                </div>
            </div>
        </header>

        <div class="job-content">

            <!-- Job Conditions Tags -->
            <div class="job-conditions-tags">
                <?php
                $conditions = array(
                    'weekend_holiday' => '土日祝休み',
                    'low_overtime' => '残業少なめ',
                    'remote_work' => 'リモートワーク可',
                    'car_commute' => '車通勤可',
                    'bike_commute' => '自転車通勤可',
                    'fixed_overtime_pay' => '固定残業代あり',
                    'discretionary_work' => '裁量労働制',
                    'passive_smoking' => '受動喫煙対策',
                );

                foreach ($conditions as $field => $label) {
                    if (get_field($field)) {
                        echo '<span class="condition-tag">' . esc_html($label) . '</span>';
                    }
                }
                ?>
            </div>

            <div class="job-sections">

                <!-- 仕事内容 -->
                <?php if (get_field('job_description')): ?>
                <section class="job-section">
                    <h3>仕事内容</h3>
                    <div class="section-content">
                        <?php echo nl2br(esc_html(get_field('job_description'))); ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- 給与・待遇 -->
                <section class="job-section">
                    <h3>給与・待遇</h3>
                    <div class="section-content">
                        <div class="info-grid">
                            <?php if (get_field('annual_income')): ?>
                            <div class="info-item">
                                <span class="info-label">年収:</span>
                                <span class="info-value salary-highlight">
                                    <?php echo number_format(get_field('annual_income')); ?>円
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('min_salary') && get_field('max_salary')): ?>
                            <div class="info-item">
                                <span class="info-label">年収範囲:</span>
                                <span class="info-value salary-highlight">
                                    <?php echo number_format(get_field('min_salary')); ?>円 〜
                                    <?php echo number_format(get_field('max_salary')); ?>円
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('salary_type')): ?>
                            <div class="info-item">
                                <span class="info-label">給与形態:</span>
                                <span class="info-value"><?php echo esc_html(get_field('salary_type')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('bonus')): ?>
                            <div class="info-item">
                                <span class="info-label">賞与:</span>
                                <span class="info-value"><?php echo esc_html(get_field('bonus')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('salary_increase')): ?>
                            <div class="info-item">
                                <span class="info-label">昇給:</span>
                                <span class="info-value"><?php echo esc_html(get_field('salary_increase')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (get_field('salary_details')): ?>
                        <div class="salary-details">
                            <h4>給与詳細</h4>
                            <p><?php echo nl2br(esc_html(get_field('salary_details'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (get_field('benefits')): ?>
                        <div class="benefits">
                            <h4>手当・福利厚生</h4>
                            <p><?php echo nl2br(esc_html(get_field('benefits'))); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 勤務条件 -->
                <section class="job-section">
                    <h3>勤務条件</h3>
                    <div class="section-content">
                        <div class="info-grid">
                            <?php if (get_field('working_hours')): ?>
                            <div class="info-item">
                                <span class="info-label">勤務時間:</span>
                                <span class="info-value"><?php echo esc_html(get_field('working_hours')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('break_time')): ?>
                            <div class="info-item">
                                <span class="info-label">休憩時間:</span>
                                <span class="info-value"><?php echo esc_html(get_field('break_time')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('avg_overtime_hours')): ?>
                            <div class="info-item">
                                <span class="info-label">月平均残業時間:</span>
                                <span class="info-value"><?php echo esc_html(get_field('avg_overtime_hours')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('holidays')): ?>
                            <div class="info-item">
                                <span class="info-label">休日:</span>
                                <span class="info-value"><?php echo esc_html(get_field('holidays')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (get_field('discretionary_work_details')): ?>
                        <div class="work-details">
                            <h4>裁量労働制の詳細</h4>
                            <p><?php echo nl2br(esc_html(get_field('discretionary_work_details'))); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 勤務地・アクセス -->
                <section class="job-section">
                    <h3>勤務地・アクセス</h3>
                    <div class="section-content">
                        <div class="info-grid">
                            <?php if (get_field('work_location')): ?>
                            <div class="info-item full-width">
                                <span class="info-label">就業場所:</span>
                                <span class="info-value"><?php echo esc_html(get_field('work_location')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('access')): ?>
                            <div class="info-item full-width">
                                <span class="info-label">アクセス:</span>
                                <span class="info-value"><?php echo nl2br(esc_html(get_field('access'))); ?></span>
                            </div>
                            <?php endif; ?>

                            <div class="commute-options">
                                <?php if (get_field('car_commute')): ?>
                                <span class="commute-tag">車通勤可</span>
                                <?php endif; ?>
                                <?php if (get_field('bike_commute')): ?>
                                <span class="commute-tag">自転車通勤可</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (get_field('work_location_details')): ?>
                        <div class="location-details">
                            <h4>就業場所詳細</h4>
                            <p><?php echo nl2br(esc_html(get_field('work_location_details'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (get_field('transfer_possibility')): ?>
                        <div class="transfer-info">
                            <h4>転勤の可能性</h4>
                            <p><?php echo esc_html(get_field('transfer_possibility')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 応募条件 -->
                <section class="job-section">
                    <h3>応募条件</h3>
                    <div class="section-content">

                        <?php if (get_field('required_conditions')): ?>
                        <div class="conditions-block">
                            <h4>必須条件</h4>
                            <p><?php echo nl2br(esc_html(get_field('required_conditions'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (get_field('welcome_conditions')): ?>
                        <div class="conditions-block">
                            <h4>歓迎条件</h4>
                            <p><?php echo nl2br(esc_html(get_field('welcome_conditions'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="info-grid">
                            <?php if (get_field('req_age')): ?>
                            <div class="info-item">
                                <span class="info-label">年齢:</span>
                                <span class="info-value"><?php echo esc_html(get_field('req_age')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('req_education')): ?>
                            <div class="info-item">
                                <span class="info-label">学歴:</span>
                                <span class="info-value"><?php echo esc_html(get_field('req_education')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('req_job_years')): ?>
                            <div class="info-item">
                                <span class="info-label">職種経験年数:</span>
                                <span class="info-value"><?php echo esc_html(get_field('req_job_years')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('req_industry_years')): ?>
                            <div class="info-item">
                                <span class="info-label">業種経験年数:</span>
                                <span class="info-value"><?php echo esc_html(get_field('req_industry_years')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- 選考プロセス -->
                <?php if (get_field('selection_process') || get_field('selection_details')): ?>
                <section class="job-section">
                    <h3>選考プロセス</h3>
                    <div class="section-content">
                        <?php if (get_field('selection_process')): ?>
                        <div class="selection-flow">
                            <h4>選考フロー</h4>
                            <p><?php echo nl2br(esc_html(get_field('selection_process'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (get_field('selection_details')): ?>
                        <div class="selection-details">
                            <h4>選考詳細</h4>
                            <p><?php echo nl2br(esc_html(get_field('selection_details'))); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- その他の条件 -->
                <section class="job-section">
                    <h3>その他</h3>
                    <div class="section-content">
                        <div class="info-grid">
                            <?php if (get_field('probation_duration')): ?>
                            <div class="info-item">
                                <span class="info-label">試用期間:</span>
                                <span class="info-value"><?php echo esc_html(get_field('probation_duration')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('insurance')): ?>
                            <div class="info-item">
                                <span class="info-label">加入保険:</span>
                                <span class="info-value"><?php echo esc_html(get_field('insurance')); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (get_field('work_attire')): ?>
                            <div class="info-item">
                                <span class="info-label">服装:</span>
                                <span class="info-value"><?php echo esc_html(get_field('work_attire')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (get_field('recruitment_background')): ?>
                        <div class="recruitment-bg">
                            <h4>募集背景</h4>
                            <p><?php echo nl2br(esc_html(get_field('recruitment_background'))); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (get_field('passive_smoking_details')): ?>
                        <div class="smoking-policy">
                            <h4>受動喫煙対策</h4>
                            <p><?php echo nl2br(esc_html(get_field('passive_smoking_details'))); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="job-actions">
            <div class="action-buttons">
                <a href="#" class="btn btn-primary btn-apply">この求人に応募する</a>
                <a href="<?php echo home_url('/search/'); ?>" class="btn btn-secondary">求人検索に戻る</a>
            </div>
        </div>

    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>