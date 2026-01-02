# SuperRSS Eklenti Dokümantasyonu

## Genel Bakış

SuperRSS, WordPress sitelerinize sınırsız sayıda RSS feed kaynağı eklemenizi ve bu kaynaklardan otomatik olarak içerik çekmenizi sağlayan profesyonel bir WordPress eklentisidir.

## Kurulum ve Aktivasyon

### Adım 1: Eklentiyi Yükleme

1. SuperRSS eklentisini indirin
2. WordPress yöneticinizde **Eklentiler > Yeni Ekle** menüsüne gidin
3. **Eklenti Yükle** butonuna tıklayın
4. İndirdiğiniz ZIP dosyasını seçin ve yükleyin

### Adım 2: Etkinleştirme

1. Eklenti yüklendikten sonra **Etkinleştir** butonuna tıklayın
2. Eklenti etkinleştirildiğinde otomatik olarak gerekli veritabanı tablosu oluşturulur
3. Saatlik cron işi zamanlanır

## Kullanım Kılavuzu

### RSS Kaynağı Ekleme

1. WordPress yöneticinizde **SuperRSS** menüsüne gidin
2. **Yeni RSS Kaynağı Ekle** bölümünde aşağıdaki bilgileri girin:
   - **Kaynak Adı**: RSS kaynağınıza tanımlayıcı bir isim verin
   - **RSS Feed URL**: RSS feed adresini girin (örn: https://example.com/feed/)
   - **Kategori**: İçe aktarılan yazıların hangi kategoriye ekleneceğini seçin (opsiyonel)
   - **Yazı Durumu**: Yazıların durumunu seçin (Taslak, Yayınla, İnceleme Bekliyor)
   - **Yazar**: Yazıların hangi yazar adına ekleneceğini seçin
   - **Durum**: Kaynağın aktif olup olmayacağını işaretleyin

3. **Kaynak Ekle** butonuna tıklayın

### RSS Kaynağı Yönetimi

#### Kaynak Listesi

Eklenen tüm RSS kaynakları bir tabloda listelenir. Her kaynak için şu bilgiler gösterilir:
- Kaynak Adı
- Feed URL
- Kategori
- Durum (Aktif/Pasif)
- Son Çekim Zamanı
- İşlem Butonları

#### Manuel İçerik Çekme

- Her kaynak için **Şimdi Çek** butonuna tıklayarak o kaynaktan hemen içerik çekebilirsiniz
- Bu işlem, kaynaktaki son 10 makaleyi kontrol eder ve yeni olanları içe aktarır

#### Kaynak Silme

- **Sil** butonuna tıklayarak bir RSS kaynağını silebilirsiniz
- Onay iletişim kutusunda silme işlemini onaylamanız gerekir

### Otomatik İçerik Çekme

SuperRSS, WordPress'in yerleşik cron sistemini kullanarak saatte bir otomatik olarak tüm aktif RSS kaynaklarını kontrol eder ve yeni içerikleri çeker.

## Özellikler ve İşlevler

### Yinelenen İçerik Koruması

SuperRSS, aynı içeriğin birden fazla kez içe aktarılmasını önlemek için iki kontrol mekanizması kullanır:

1. **Başlık Kontrolü**: Aynı başlığa sahip bir yazı varsa içe aktarılmaz
2. **URL Kontrolü**: Kaynak URL'si `superrss_source_url` meta alanında saklanır ve kontrol edilir

### Öne Çıkan Görsel

RSS feedinde görsel varsa:
- Otomatik olarak WordPress medya kütüphanesine yüklenir
- Yazının öne çıkan görseli olarak ayarlanır

### Meta Bilgiler

Her içe aktarılan yazı için şu meta bilgiler saklanır:
- `superrss_source_url`: Orijinal makale URL'si
- `superrss_source_id`: RSS kaynağı ID'si
- `superrss_feed_name`: RSS kaynağı adı

## Veritabanı Yapısı

### superrss_sources Tablosu

```sql
CREATE TABLE wp_superrss_sources (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    feed_url varchar(500) NOT NULL,
    feed_name varchar(255) NOT NULL,
    category_id bigint(20) DEFAULT NULL,
    post_status varchar(20) DEFAULT 'draft',
    author_id bigint(20) DEFAULT NULL,
    last_fetch datetime DEFAULT NULL,
    active tinyint(1) DEFAULT 1,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY feed_url (feed_url(191))
);
```

## API ve Hook'lar

### Eklenti Aktivasyon Hook'u

```php
register_activation_hook(__FILE__, array('SuperRSS', 'activate'));
```

Aktivasyon sırasında:
- Veritabanı tablosu oluşturulur
- Cron işi zamanlanır

### Eklenti Deaktivasyon Hook'u

```php
register_deactivation_hook(__FILE__, array('SuperRSS', 'deactivate'));
```

Deaktivasyon sırasında:
- Zamanlanmış cron işi temizlenir

### Cron İşi

```php
add_action('superrss_fetch_feeds', array('SuperRSS_Fetcher', 'fetch_all_feeds'));
```

Saatte bir çalışır ve tüm aktif RSS kaynaklarını kontrol eder.

### AJAX İşlemleri

- `superrss_add_source`: Yeni kaynak ekleme
- `superrss_delete_source`: Kaynak silme
- `superrss_fetch_now`: Manuel içerik çekme

## Güvenlik

SuperRSS aşağıdaki güvenlik önlemlerini kullanır:

1. **Nonce Kontrolü**: Tüm AJAX isteklerinde nonce doğrulaması
2. **Yetki Kontrolü**: `manage_options` yetkisi kontrolü
3. **Veri Sanitizasyonu**: Tüm kullanıcı girdileri temizlenir
4. **SQL Injection Koruması**: WordPress $wpdb prepare() kullanımı
5. **XSS Koruması**: Çıktılarda esc_* fonksiyonları kullanımı

## Sorun Giderme

### RSS Feedleri Çekilmiyor

1. WordPress cron sisteminin çalıştığından emin olun
2. PHP `simplexml` eklentisinin yüklü olduğunu kontrol edin
3. RSS feed URL'sinin geçerli olduğunu kontrol edin
4. WordPress hata günlüklerini kontrol edin

### Yinelenen İçerikler

Normalde SuperRSS yinelenen içerikleri engeller, ancak sorun yaşıyorsanız:
1. Kaynak URL'lerin farklı olduğundan emin olun
2. Aynı RSS kaynağını birden fazla kez eklemediğinizi kontrol edin

### Görseller İçe Aktarılmıyor

1. WordPress medya yükleme ayarlarını kontrol edin
2. PHP `allow_url_fopen` veya cURL'ün etkin olduğundan emin olun
3. Klasör izinlerini kontrol edin (wp-content/uploads)

## Performans İpuçları

1. Çok fazla kaynak eklediyseniz cron sıklığını azaltmayı düşünün
2. Aktif olmayan kaynakları pasif yapın
3. Düzenli olarak eski yazıları temizleyin

## Destek ve Katkı

Sorularınız veya sorunlarınız için:
- GitHub Issues: https://github.com/integrumart/superRSS/issues

## Lisans

SuperRSS, GPLv2 veya sonraki sürümleri altında lisanslanmıştır.
