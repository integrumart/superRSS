<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap super-rss-wrap">
    <h1>
        <span class="dashicons dashicons-rss"></span>
        Super RSS - RSS Feed Yönetimi
    </h1>
    
    <div class="super-rss-container">
        <div class="super-rss-add-section">
            <h2>Yeni RSS Feed Ekle</h2>
            <form id="super-rss-add-form">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="feed_url">RSS URL *</label>
                        </th>
                        <td>
                            <input type="url" id="feed_url" name="feed_url" class="regular-text" required placeholder="https://example.com/feed" />
                            <p class="description">RSS feed'in tam URL'sini girin</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="feed_name">Feed Adı</label>
                        </th>
                        <td>
                            <input type="text" id="feed_name" name="feed_name" class="regular-text" placeholder="Örn: Teknoloji Haberleri" />
                            <p class="description">Boş bırakılırsa RSS'den otomatik alınır</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <span class="dashicons dashicons-plus-alt"></span>
                        RSS Feed Ekle
                    </button>
                </p>
            </form>
        </div>
        
        <div class="super-rss-list-section">
            <h2>RSS Feed Listesi</h2>
            
            <?php if (empty($feeds)): ?>
                <div class="notice notice-info">
                    <p>Henüz RSS feed eklenmemiş. Yukarıdaki formu kullanarak RSS feed ekleyebilirsiniz.</p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 25%;">Feed Adı</th>
                            <th style="width: 35%;">RSS URL</th>
                            <th style="width: 10%;">Durum</th>
                            <th style="width: 15%;">Son Çekilme</th>
                            <th style="width: 10%;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feeds as $feed): ?>
                            <tr id="feed-row-<?php echo esc_attr($feed->id); ?>">
                                <td><?php echo esc_html($feed->id); ?></td>
                                <td>
                                    <strong><?php echo esc_html($feed->feed_name); ?></strong>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url($feed->feed_url); ?>" target="_blank" title="Yeni sekmede aç">
                                        <?php echo esc_html(strlen($feed->feed_url) > 50 ? substr($feed->feed_url, 0, 50) . '...' : $feed->feed_url); ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo esc_attr($feed->status); ?>">
                                        <?php echo $feed->status === 'active' ? 'Aktif' : 'Pasif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    if ($feed->last_fetch) {
                                        echo esc_html(date('d.m.Y H:i', strtotime($feed->last_fetch)));
                                    } else {
                                        echo '<span style="color: #999;">Hiç çekilmedi</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="button button-small super-rss-fetch-now" data-feed-id="<?php echo esc_attr($feed->id); ?>">
                                        <span class="dashicons dashicons-update"></span>
                                        Şimdi Çek
                                    </button>
                                    <button class="button button-small super-rss-delete" data-feed-id="<?php echo esc_attr($feed->id); ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                        Sil
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="super-rss-info-section">
            <h2>Bilgi</h2>
            <div class="info-box">
                <p>
                    <span class="dashicons dashicons-info"></span>
                    <strong>Otomatik Çekim:</strong> RSS feedler her saat başı otomatik olarak çekilir ve yeni makaleler yazılar bölümüne eklenir.
                </p>
                <p>
                    <span class="dashicons dashicons-info"></span>
                    <strong>Manuel Çekim:</strong> "Şimdi Çek" butonunu kullanarak istediğiniz zaman manuel olarak RSS feed çekebilirsiniz.
                </p>
                <p>
                    <span class="dashicons dashicons-info"></span>
                    <strong>Tekrar Kontrolü:</strong> Daha önce içe aktarılmış makaleler tekrar eklenmez.
                </p>
            </div>
        </div>
    </div>
</div>
