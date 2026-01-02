# Super RSS Plugin - Dosya Yapısı ve Teknik Detaylar

## Dizin Yapısı

```
super-rss/
├── super-rss.php              # Ana plugin dosyası
├── README.md                  # Dokümantasyon
├── .gitignore                 # Git ignore kuralları
├── assets/                    # CSS ve JavaScript dosyaları
│   ├── admin.css              # Admin panel stilleri
│   └── admin.js               # Admin panel JavaScript (AJAX işlemleri)
└── templates/                 # Şablon dosyaları
    └── admin-page.php         # Admin panel sayfası
```

## Ana Bileşenler

### 1. super-rss.php (Ana Plugin Dosyası)

**Sınıf: SuperRSS**

#### Özellikler:
- `$table_name`: Veritabanı tablo adı (wp_super_rss_feeds)

#### Ana Metodlar:

**`__construct()`**
- Plugin'i başlatır
- Hook'ları kaydeder
- Cron job'ı ayarlar

**`activate()`**
- Plugin aktivasyonu sırasında veritabanı tablosunu oluşturur
- Tablo yapısı:
  - id: Benzersiz feed ID'si
  - feed_url: RSS feed URL'si
  - feed_name: Feed adı
  - status: Aktif/Pasif durumu
  - last_fetch: Son çekilme zamanı
  - created_at: Oluşturulma zamanı

**`add_admin_menu()`**
- WordPress admin menüsüne "Super RSS" sekmesini ekler
- Ikon: dashicons-rss
- Menü pozisyonu: 30

**`enqueue_admin_scripts($hook)`**
- Admin sayfasına CSS ve JavaScript dosyalarını yükler
- AJAX için nonce ve URL'leri hazırlar

**`admin_page()`**
- Admin panel sayfasını render eder
- Tüm feed'leri veritabanından çeker
- Template dosyasını include eder

**AJAX İşlemleri:**

**`ajax_add_feed()`**
- Yeni RSS feed ekler
- URL validasyonu yapar
- RSS feed'in geçerliliğini kontrol eder
- Veritabanına kaydeder

**`ajax_delete_feed()`**
- RSS feed'i siler
- Yetki kontrolü yapar

**`ajax_fetch_now()`**
- Manuel feed çekme işlemini başlatır
- Tek bir feed'i hemen çeker

**`fetch_all_feeds()`**
- Tüm aktif feed'leri çeker
- Cron job tarafından her saat başı çağrılır

**`fetch_feed($feed_id)`**
- Tek bir feed'i çeker ve işler
- RSS parse eder
- Yeni makaleleri kontrol eder
- Duplicate kontrolü yapar
- WordPress post'ları oluşturur
- Meta veriler ekler:
  - super_rss_source_url: Kaynak URL
  - super_rss_feed_name: Feed adı

**`get_default_author()`**
- Post yazarını belirler
- Öncelik sırası:
  1. Mevcut kullanıcı
  2. İlk admin kullanıcı
  3. İlk kullanıcı
  4. Varsayılan: 1

### 2. templates/admin-page.php

**Admin Arayüzü Bölümleri:**

1. **Başlık**
   - Plugin logosu ve adı

2. **RSS Feed Ekleme Formu**
   - RSS URL input (zorunlu)
   - Feed adı input (opsiyonel)
   - Ekle butonu

3. **Feed Listesi**
   - Tablo formatında tüm feed'ler
   - Sütunlar: ID, Feed Adı, URL, Durum, Son Çekilme, İşlemler
   - Her satırda: "Şimdi Çek" ve "Sil" butonları

4. **Bilgi Kutusu**
   - Otomatik çekim bilgisi
   - Manuel çekim bilgisi
   - Tekrar kontrolü bilgisi

### 3. assets/admin.css

**Stil Özellikleri:**
- Modern, temiz tasarım
- WordPress admin temasıyla uyumlu
- Responsive tasarım
- Renkli durum badge'leri
- Hover efektleri
- Animasyonlar (fade, slide)

**Ana Sınıflar:**
- `.super-rss-wrap`: Ana container
- `.super-rss-add-section`: Form bölümü
- `.super-rss-list-section`: Tablo bölümü
- `.super-rss-info-section`: Bilgi bölümü
- `.status-badge`: Durum göstergesi
- `.super-rss-notice`: Bildirim mesajları

### 4. assets/admin.js

**JavaScript İşlevleri:**

**Form Submit Handler**
- RSS feed ekleme formunu işler
- AJAX ile veri gönderir
- Başarı/hata mesajları gösterir
- Formu temizler ve sayfayı yeniler

**Delete Handler**
- Silme onayı ister
- AJAX ile feed siler
- Satırı animasyonla kaldırır

**Fetch Now Handler**
- Manuel çekme işlemini başlatır
- AJAX ile feed çeker
- Sonucu gösterir

**showNotice() Fonksiyonu**
- Bildirim mesajlarını gösterir
- 5 saniye sonra otomatik kaybolur
- Başarı/hata türlerine göre farklı stiller

## Güvenlik Özellikleri

1. **Nonce Kontrolü**: Tüm AJAX istekleri nonce ile doğrulanır
2. **Yetki Kontrolü**: `manage_options` yetkisi kontrolü
3. **Input Sanitization**: Tüm kullanıcı girdileri temizlenir
4. **Error Message Sanitization**: Hassas bilgi sızıntısı engellenir
5. **SQL Injection Koruması**: WordPress $wpdb hazır metodları kullanılır
6. **XSS Koruması**: Output'larda esc_html, esc_url kullanılır

## Veritabanı

**Tablo: wp_super_rss_feeds**

```sql
CREATE TABLE IF NOT EXISTS wp_super_rss_feeds (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    feed_url varchar(500) NOT NULL,
    feed_name varchar(200) NOT NULL,
    status varchar(20) DEFAULT 'active',
    last_fetch datetime DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)
```

## Cron Job

**Event: super_rss_fetch_feeds**
- Periyot: Hourly (her saat başı)
- Callback: `SuperRSS::fetch_all_feeds()`
- Aktivasyon: Plugin aktifleştirildiğinde otomatik schedule edilir
- Deaktivasyon: Plugin deaktive edildiğinde temizlenir

## Filtreler ve Hook'lar

**`super_rss_max_items` Filter**
- Feed başına çekilecek maksimum makale sayısı
- Varsayılan: 10
- Kullanım örneği:
```php
add_filter('super_rss_max_items', function($max) {
    return 20; // Her feed'den 20 makale çek
});
```

## Kullanım Senaryoları

### Senaryo 1: Manuel Feed Ekleme ve Çekme
1. Admin panelde "Super RSS" menüsüne tıkla
2. RSS URL'sini gir
3. "RSS Feed Ekle" butonuna tıkla
4. Feed listesinde görüntüle
5. "Şimdi Çek" butonuna tıklayarak hemen çek

### Senaryo 2: Otomatik Çekim
1. Feed'ler eklendikten sonra otomatik çalışır
2. Her saat başı tüm aktif feed'ler kontrol edilir
3. Yeni makaleler otomatik olarak yazılara eklenir
4. Duplicate kontrol sayesinde aynı makale tekrar eklenmez

### Senaryo 3: Feed Yönetimi
1. Feed listesinde tüm feed'leri görüntüle
2. Son çekilme zamanlarını kontrol et
3. İstenmeyen feed'leri sil
4. Durum bilgilerini takip et

## Performans İyileştirmeleri

1. **Pagination**: Büyük feed listelerinde sayfalama kullanılabilir
2. **Caching**: RSS feed'ler cache'lenebilir
3. **Batch Processing**: Çok sayıda feed için batch işleme
4. **Background Processing**: Uzun süren işlemler için background job
5. **Rate Limiting**: API rate limit koruması

## Gelecek Geliştirmeler

1. Feed kategorileri
2. Özel post type seçimi
3. Özel yazar seçimi
4. Makale filtreleme kuralları
5. Zamanlama ayarları
6. İçerik dönüştürme seçenekleri
7. Medya indirme özelliği
8. Çoklu dil desteği
9. Import geçmişi
10. İstatistikler ve raporlama

## Hata Ayıklama

**WordPress Debug Modu:**
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

**Cron Job Kontrolü:**
```php
// Zamanlanmış cron'ları göster
wp_get_schedules();
wp_get_ready_cron_jobs();
```

**Feed Test:**
```php
// Tek bir feed'i test et
$super_rss = new SuperRSS();
$result = $super_rss->fetch_feed(1); // Feed ID: 1
print_r($result);
```

## Lisans

GPL v2 veya üzeri
