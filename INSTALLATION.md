# Super RSS - Kurulum ve Test Kılavuzu

## Hızlı Kurulum

### Adım 1: Plugin Dosyalarını Yükleme

WordPress sitenizin dizininde şu konuma gidin:
```
/wp-content/plugins/
```

3 farklı yöntemle yükleyebilirsiniz:

#### Yöntem 1: Klasör Yükleme (Önerilen)
1. Bu repository'yi ZIP olarak indirin
2. ZIP'i açın
3. `super-rss` klasörünü `/wp-content/plugins/` dizinine kopyalayın

#### Yöntem 2: Git Clone
```bash
cd /wp-content/plugins/
git clone https://github.com/integrumart/superRSS.git super-rss
```

#### Yöntem 3: WordPress Admin Panel
1. Repository'yi ZIP olarak indirin
2. WordPress Admin Panel'de: Eklentiler > Yeni Ekle > Eklenti Yükle
3. ZIP dosyasını seçin ve yükleyin

### Adım 2: Plugin'i Aktifleştirme

1. WordPress Admin Panel'de "Eklentiler" menüsüne gidin
2. "Super RSS" eklentisini bulun
3. "Etkinleştir" butonuna tıklayın
4. Plugin aktifleştirildiğinde:
   - Veritabanı tablosu otomatik oluşturulur
   - Cron job otomatik schedule edilir

### Adım 3: İlk RSS Feed Ekleme

1. Sol menüde "Super RSS" sekmesine tıklayın
2. "Yeni RSS Feed Ekle" bölümünde:
   - RSS URL: Feed URL'sini girin (örn: `https://techcrunch.com/feed/`)
   - Feed Adı: İsteğe bağlı bir ad girin
3. "RSS Feed Ekle" butonuna tıklayın
4. Feed başarıyla eklendi mesajını görün

### Adım 4: İlk Makaleleri Çekme

1. Feed listesinde yeni eklediğiniz feed'i bulun
2. "Şimdi Çek" butonuna tıklayın
3. Sistem feed'den makaleleri çekecek
4. Başarı mesajında kaç makale eklendiğini göreceksiniz
5. WordPress'in "Yazılar" bölümünde yeni makaleleri kontrol edin

## Test Senaryoları

### Test 1: Geçerli RSS Feed Ekleme

**Girdi:**
- RSS URL: `https://wordpress.org/news/feed/`
- Feed Adı: WordPress Haberleri

**Beklenen Sonuç:**
- ✅ Feed başarıyla eklenir
- ✅ Feed listesinde görünür
- ✅ Durum "Aktif" olarak gösterilir

### Test 2: Geçersiz URL Ekleme

**Girdi:**
- RSS URL: `https://example.com/invalid-feed`

**Beklenen Sonuç:**
- ❌ Hata mesajı: "Geçersiz RSS URL. Lütfen doğru bir RSS feed URL'si girdiğinizden emin olun."
- ❌ Feed eklenmez

### Test 3: Boş URL ile Ekleme Denemesi

**Girdi:**
- RSS URL: (boş)

**Beklenen Sonuç:**
- ❌ Browser validasyonu: "Bu alanı doldurun"
- ❌ Feed eklenmez

### Test 4: Feed Adı Olmadan Ekleme

**Girdi:**
- RSS URL: `https://wordpress.org/news/feed/`
- Feed Adı: (boş)

**Beklenen Sonuç:**
- ✅ Feed başarıyla eklenir
- ✅ Feed adı RSS'den otomatik alınır (WordPress News)

### Test 5: Manuel Feed Çekme

**İşlem:**
1. Bir feed ekleyin
2. "Şimdi Çek" butonuna tıklayın

**Beklenen Sonuç:**
- ✅ Feed çekilir
- ✅ Yeni makaleler WordPress'e eklenir
- ✅ "X yeni yazı içe aktarıldı" mesajı gösterilir
- ✅ "Son Çekilme" sütunu güncellenir

### Test 6: Duplicate Makale Kontrolü

**İşlem:**
1. Bir feed ekleyin ve çekin
2. Aynı feed'i tekrar çekin

**Beklenen Sonuç:**
- ✅ İkinci çekimde: "0 yeni yazı içe aktarıldı" mesajı
- ✅ Aynı makaleler tekrar eklenmez
- ✅ WordPress'te duplicate yazı yok

### Test 7: Feed Silme

**İşlem:**
1. Bir feed seçin
2. "Sil" butonuna tıklayın
3. Onay dialogunda "Tamam" seçin

**Beklenen Sonuç:**
- ✅ Onay dialogu gösterilir
- ✅ Feed silinir
- ✅ Feed listeden kaybolur
- ✅ "RSS feed silindi" mesajı gösterilir

### Test 8: Çoklu Feed Ekleme

**İşlem:**
1. 3-5 farklı RSS feed ekleyin
2. Her birinden "Şimdi Çek" ile çekim yapın

**Beklenen Sonuç:**
- ✅ Tüm feed'ler başarıyla eklenir
- ✅ Her feed'den makaleler çekilir
- ✅ Makaleler karışmadan WordPress'e eklenir
- ✅ Her makalede kaynak bilgisi (meta) saklanır

### Test 9: Otomatik Cron Çekimi

**İşlem:**
1. Bir feed ekleyin
2. 1 saat bekleyin

**Beklenen Sonuç:**
- ✅ WordPress cron otomatik çalışır
- ✅ Feed otomatik çekilir
- ✅ Yeni makaleler otomatik eklenir
- ✅ "Son Çekilme" otomatik güncellenir

**Manuel Cron Test:**
```bash
# WordPress cron'u manuel tetikle
wp cron event run super_rss_fetch_feeds

# Veya
curl https://yoursite.com/wp-cron.php?doing_wp_cron
```

### Test 10: Plugin Deaktivasyonu ve Aktivasyonu

**İşlem:**
1. Feed'ler ekli iken plugin'i deaktive edin
2. Tekrar aktive edin

**Beklenen Sonuç:**
- ✅ Deaktivasyon: Cron job temizlenir
- ✅ Feed verileri korunur
- ✅ Aktivasyon: Cron job yeniden schedule edilir
- ✅ Eklenen feed'ler kaybolmaz

## Örnek RSS Feed Kaynakları

Test için kullanabileceğiniz geçerli RSS feed'ler:

### Türkçe Kaynaklar
- Sözcü: `https://www.sozcu.com.tr/feed/`
- Hürriyet: `https://www.hurriyet.com.tr/rss/anasayfa`
- Donanimhaber: `https://www.donanimhaber.com/rss`
- Webrazzi: `https://webrazzi.com/feed/`
- ShiftDelete: `https://shiftdelete.net/feed`

### İngilizce Kaynaklar
- WordPress News: `https://wordpress.org/news/feed/`
- TechCrunch: `https://techcrunch.com/feed/`
- The Verge: `https://www.theverge.com/rss/index.xml`
- Wired: `https://www.wired.com/feed/rss`
- BBC News: `https://feeds.bbci.co.uk/news/rss.xml`

## Sorun Giderme

### Sorun 1: "Geçersiz RSS URL" Hatası

**Çözüm:**
1. RSS URL'sinin doğru olduğundan emin olun
2. URL'nin sonunda `/feed` veya `/rss` olmalı
3. HTTPS/HTTP kontrolü yapın
4. Tarayıcıda URL'yi açarak test edin

### Sorun 2: Makaleler Çekilmiyor

**Çözüm:**
1. WordPress Admin > Araçlar > Site Sağlığı'nı kontrol edin
2. WordPress cron'un çalıştığından emin olun
3. PHP `allow_url_fopen` ayarının aktif olduğunu kontrol edin
4. Sunucu firewall ayarlarını kontrol edin

### Sorun 3: Cron Çalışmıyor

**Çözüm:**
1. WP-Cron'u manuel tetikleyin:
```bash
wp cron event list
wp cron event run super_rss_fetch_feeds
```

2. Gerçek cron kullanın (wp-config.php):
```php
define('DISABLE_WP_CRON', true);
```

Sonra sunucu cron'una ekleyin:
```bash
*/60 * * * * wget -q -O - https://yoursite.com/wp-cron.php?doing_wp_cron
```

### Sorun 4: Duplicate Makaleler Ekleniyor

**Çözüm:**
1. Meta verileri kontrol edin:
```sql
SELECT * FROM wp_postmeta WHERE meta_key = 'super_rss_source_url';
```

2. Plugin'i deaktive edip tekrar aktive edin
3. Duplicate yazıları manuel silin

### Sorun 5: Admin Sayfası Açılmıyor

**Çözüm:**
1. Plugin dosya izinlerini kontrol edin (644 veya 755)
2. WordPress debug modunu açın
3. PHP error log'larını kontrol edin
4. Tema/plugin çakışması olup olmadığını kontrol edin

## Debug Modu

Debug bilgilerini görmek için `wp-config.php` dosyasına ekleyin:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Log dosyası: `/wp-content/debug.log`

## Veritabanı Kontrolü

Feed'leri ve durumlarını kontrol etmek için:

```sql
-- Tüm feed'leri göster
SELECT * FROM wp_super_rss_feeds;

-- Aktif feed'leri göster
SELECT * FROM wp_super_rss_feeds WHERE status = 'active';

-- İçe aktarılan makaleleri göster
SELECT p.*, pm.meta_value as source_url 
FROM wp_posts p 
JOIN wp_postmeta pm ON p.ID = pm.post_id 
WHERE pm.meta_key = 'super_rss_source_url';

-- Feed başına makale sayısı
SELECT 
    pm.meta_value as feed_name, 
    COUNT(*) as article_count 
FROM wp_postmeta pm 
WHERE pm.meta_key = 'super_rss_feed_name' 
GROUP BY pm.meta_value;
```

## Performans İpuçları

1. **Sınırlı Feed Kullanın**: Başlangıçta 5-10 feed ile başlayın
2. **Cron Periyodunu Ayarlayın**: Çok sık çekim yapmayın
3. **Max Items Filtresi**: Her feed'den çekilecek makale sayısını sınırlayın
4. **Eski Makaleleri Temizleyin**: Düzenli olarak eski yazıları temizleyin
5. **Cache Plugin Kullanın**: WP Super Cache veya W3 Total Cache

## Güvenlik Kontrol Listesi

- ✅ WordPress güncel mi?
- ✅ PHP güncel mi?
- ✅ SSL sertifikası var mı?
- ✅ Güvenlik plugin'i aktif mi?
- ✅ Admin şifreleri güçlü mü?
- ✅ Dosya izinleri doğru mu?
- ✅ WordPress debug modu kapalı mı? (production)

## Destek

Sorunlarınız için:
1. [GitHub Issues](https://github.com/integrumart/superRSS/issues)
2. README.md dosyasını okuyun
3. TECHNICAL.md dosyasını inceleyin

## Güncellemeler

Plugin'i güncel tutmak için:
```bash
cd /wp-content/plugins/super-rss/
git pull origin main
```

## Lisans

GPL v2 veya üzeri
