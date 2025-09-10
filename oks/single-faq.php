<?php
/**
 * FAQ詳細ページ（使用しない）
 * アーカイブページにリダイレクト
 *
 * @package OKS
 */

// FAQ一覧ページにリダイレクト
wp_redirect(get_post_type_archive_link('faq'), 301);
exit;