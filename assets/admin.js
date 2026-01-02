jQuery(document).ready(function($) {
    
    /**
     * RSS Feed ekleme formu
     */
    $('#super-rss-add-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.html();
        
        // Form verilerini al
        var feedUrl = $('#feed_url').val().trim();
        var feedName = $('#feed_name').val().trim();
        
        if (!feedUrl) {
            showNotice('RSS URL gereklidir', 'error');
            return;
        }
        
        // Butonu devre dışı bırak
        $submitBtn.prop('disabled', true);
        $submitBtn.html('<span class="dashicons dashicons-update"></span> Ekleniyor...');
        
        // AJAX isteği
        $.ajax({
            url: superRssAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'super_rss_add_feed',
                nonce: superRssAjax.nonce,
                feed_url: feedUrl,
                feed_name: feedName
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data, 'success');
                    // Formu temizle
                    $form[0].reset();
                    // Sayfayı yenile
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotice(response.data, 'error');
                }
            },
            error: function() {
                showNotice('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
            },
            complete: function() {
                $submitBtn.prop('disabled', false);
                $submitBtn.html(originalText);
            }
        });
    });
    
    /**
     * RSS Feed silme
     */
    $(document).on('click', '.super-rss-delete', function(e) {
        e.preventDefault();
        
        if (!confirm('Bu RSS feed\'i silmek istediğinizden emin misiniz?')) {
            return;
        }
        
        var $btn = $(this);
        var feedId = $btn.data('feed-id');
        var $row = $('#feed-row-' + feedId);
        var originalText = $btn.html();
        
        // Butonu devre dışı bırak
        $btn.prop('disabled', true);
        $btn.html('<span class="dashicons dashicons-update"></span> Siliniyor...');
        
        // AJAX isteği
        $.ajax({
            url: superRssAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'super_rss_delete_feed',
                nonce: superRssAjax.nonce,
                feed_id: feedId
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data, 'success');
                    // Satırı animasyonla kaldır
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        // Eğer tablo boşaldıysa sayfayı yenile
                        if ($('.wp-list-table tbody tr').length === 0) {
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    });
                } else {
                    showNotice(response.data, 'error');
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                }
            },
            error: function() {
                showNotice('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                $btn.prop('disabled', false);
                $btn.html(originalText);
            }
        });
    });
    
    /**
     * RSS Feed şimdi çek
     */
    $(document).on('click', '.super-rss-fetch-now', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var feedId = $btn.data('feed-id');
        var originalText = $btn.html();
        
        // Butonu devre dışı bırak
        $btn.prop('disabled', true);
        $btn.html('<span class="dashicons dashicons-update"></span> Çekiliyor...');
        
        // AJAX isteği
        $.ajax({
            url: superRssAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'super_rss_fetch_now',
                nonce: superRssAjax.nonce,
                feed_id: feedId
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data, 'success');
                    // Sayfayı yenile
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showNotice(response.data, 'error');
                }
            },
            error: function() {
                showNotice('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.html(originalText);
            }
        });
    });
    
    /**
     * Bildirim gösterme fonksiyonu
     */
    function showNotice(message, type) {
        // Önceki bildirimleri kaldır
        $('.super-rss-notice').remove();
        
        // Yeni bildirim oluştur
        var noticeClass = type === 'success' ? 'success' : 'error';
        var notice = $('<div class="super-rss-notice ' + noticeClass + '">' + message + '</div>');
        
        // Bildirimi sayfanın üstüne ekle
        $('.super-rss-wrap h1').after(notice);
        
        // Bildirimi animasyonla göster
        notice.hide().slideDown(300);
        
        // 5 saniye sonra bildirimi kaldır
        setTimeout(function() {
            notice.slideUp(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Sayfayı yukarı kaydır
        $('html, body').animate({
            scrollTop: $('.super-rss-wrap').offset().top - 32
        }, 300);
    }
});
