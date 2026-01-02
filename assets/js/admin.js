/* SuperRSS Admin JavaScript */

jQuery(document).ready(function($) {
    
    // Add new source
    $('#superrss-add-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        var originalText = submitButton.text();
        
        // Disable submit button
        submitButton.prop('disabled', true).text('Ekleniyor...');
        
        var data = {
            action: 'superrss_add_source',
            nonce: superrss_ajax.nonce,
            feed_url: $('#feed_url').val(),
            feed_name: $('#feed_name').val(),
            category_id: $('#category_id').val(),
            post_status: $('#post_status').val(),
            author_id: $('#author_id').val(),
            active: $('#active').is(':checked') ? 1 : 0
        };
        
        $.post(superrss_ajax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
                location.reload();
            } else {
                alert(response.data.message);
                submitButton.prop('disabled', false).text(originalText);
            }
        }).fail(function() {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            submitButton.prop('disabled', false).text(originalText);
        });
    });
    
    // Delete source
    $('.superrss-delete').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Bu RSS kaynağını silmek istediğinizden emin misiniz?')) {
            return;
        }
        
        var button = $(this);
        var sourceId = button.data('id');
        var row = button.closest('tr');
        
        button.prop('disabled', true).text('Siliniyor...');
        
        var data = {
            action: 'superrss_delete_source',
            nonce: superrss_ajax.nonce,
            source_id: sourceId
        };
        
        $.post(superrss_ajax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
                row.fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                alert(response.data.message);
                button.prop('disabled', false).text('Sil');
            }
        }).fail(function() {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            button.prop('disabled', false).text('Sil');
        });
    });
    
    // Fetch feed now
    $('.superrss-fetch-now').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var sourceId = button.data('id');
        var originalText = button.text();
        
        button.prop('disabled', true).text('Çekiliyor...');
        
        var data = {
            action: 'superrss_fetch_now',
            nonce: superrss_ajax.nonce,
            source_id: sourceId
        };
        
        $.post(superrss_ajax.ajax_url, data, function(response) {
            if (response.success) {
                alert(response.data.message);
                location.reload();
            } else {
                alert(response.data.message);
            }
            button.prop('disabled', false).text(originalText);
        }).fail(function() {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            button.prop('disabled', false).text(originalText);
        });
    });
});
