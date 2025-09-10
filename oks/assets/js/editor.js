/**
 * エディタ用JavaScript - 投稿タイプに応じてクラスを追加
 */
(function() {
    'use strict';
    
    // DOMが読み込まれた後に実行
    document.addEventListener('DOMContentLoaded', function() {
        // 現在のページURLから投稿タイプを判定
        const currentURL = window.location.href;
        const isNewPost = currentURL.includes('post-new.php');
        const isEditPost = currentURL.includes('post.php') || currentURL.includes('action=edit');
        
        // URLパラメータから投稿タイプを取得
        const urlParams = new URLSearchParams(window.location.search);
        const postType = urlParams.get('post_type') || 'post'; // デフォルトはpost
        
        // 投稿（post）の場合のみクラスを追加
        if ((isNewPost || isEditPost) && postType === 'post') {
            document.body.classList.add('post-type-post-editor');
        }
    });
    
    // Gutenbergエディタが初期化された後にも実行
    if (window.wp && window.wp.data) {
        const { subscribe, select } = window.wp.data;
        
        const unsubscribe = subscribe(function() {
            const postType = select('core/editor') ? select('core/editor').getCurrentPostType() : null;
            
            if (postType === 'post') {
                document.body.classList.add('post-type-post-editor');
            } else {
                document.body.classList.remove('post-type-post-editor');
            }
        });
    }
})();