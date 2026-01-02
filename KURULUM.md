# SuperRSS - Kurulum ve Kullanım Rehberi

## Hızlı Başlangıç

SuperRSS, WordPress sitenize sınırsız sayıda RSS kaynağı eklemenize ve otomatik olarak içerik çekmenize olanak tanıyan bir eklentidir.

## Kurulum

### Yöntem 1: Manuel Kurulum

1. Bu repository'yi ZIP dosyası olarak indirin
2. WordPress yöneticinize giriş yapın
3. **Eklentiler > Yeni Ekle** menüsüne gidin
4. **Eklenti Yükle** butonuna tıklayın
5. ZIP dosyasını seçin ve yükleyin
6. **Etkinleştir** butonuna tıklayın

### Yöntem 2: FTP ile Kurulum

1. Repository'yi indirin ve açın
2. `superrss` klasörünü FTP ile `/wp-content/plugins/` dizinine yükleyin
3. WordPress yöneticinizde **Eklentiler** sayfasına gidin
4. SuperRSS eklentisini bulun ve **Etkinleştir** butonuna tıklayın

## İlk Kurulum Sonrası

Eklenti etkinleştirildiğinde otomatik olarak:
- `wp_superrss_sources` veritabanı tablosu oluşturulur
- Saatlik cron işi zamanlanır
- Admin menüsüne SuperRSS seçeneği eklenir

## Kullanım

### 1. RSS Kaynağı Ekleme

1. WordPress yöneticinizde **SuperRSS** menüsüne tıklayın
2. **Yeni RSS Kaynağı Ekle** formunu doldurun:

   **Kaynak Adı**: Kaynağınıza tanımlayıcı bir isim verin
   - Örnek: "TechCrunch Türkiye", "BBC Teknoloji"

   **RSS Feed URL**: RSS feed adresini tam olarak girin
   - Örnek: `https://techcrunch.com/feed/`
   - Örnek: `http://www.bbc.com/turkce/index.xml`

   **Kategori**: İçe aktarılan yazıların hangi kategoriye ekleneceğini seçin
   - Boş bırakılabilir (kategori atanmaz)
   - Kategori yoksa önce WordPress'te oluşturun

   **Yazı Durumu**: İçe aktarılan yazıların durumunu seçin
   - **Taslak**: Yazılar taslak olarak kaydedilir (önerilen)
   - **Yayınla**: Yazılar otomatik yayınlanır
   - **İnceleme Bekliyor**: Yazılar inceleme için bekletilir

   **Yazar**: Yazıların hangi yazar adına ekleneceğini seçin
   - Varsayılan: Şu anki kullanıcı

   **Durum**: Kaynağın aktif olup olmayacağını işaretleyin
   - İşaretli: Kaynak aktif, otomatik çekim yapılır
   - İşaretsiz: Kaynak pasif, otomatik çekim yapılmaz

3. **Kaynak Ekle** butonuna tıklayın

### 2. Eklenen Kaynakları Görüntüleme

Eklediğiniz tüm RSS kaynakları tabloda listelenir. Her kaynak için:
- **Kaynak Adı**: Verdiğiniz isim
- **Feed URL**: RSS adresi (kısaltılmış)
- **Kategori**: Atanan kategori
- **Durum**: Aktif/Pasif durumu
- **Son Çekim**: En son ne zaman içerik çekildiği
- **İşlemler**: Aksiyon butonları

### 3. Manuel İçerik Çekme

Otomatik çekimi beklemeden hemen içerik çekmek için:

1. Kaynak listesinde istediğiniz kaynağı bulun
2. **Şimdi Çek** butonuna tıklayın
3. İşlem tamamlandığında kaç yazının içe aktarıldığı gösterilir

Not: Her çekim işleminde son 10 makale kontrol edilir ve sadece yeni olanlar içe aktarılır.

### 4. Kaynak Silme

Bir RSS kaynağını silmek için:

1. Kaynak listesinde silinecek kaynağı bulun
2. **Sil** butonuna tıklayın
3. Onay mesajında **Tamam**'a tıklayın

⚠️ **Dikkat**: Kaynak silindiğinde, daha önce içe aktarılan yazılar silinmez. Sadece kaynak kaydı silinir.

## Otomatik Çekim

SuperRSS, WordPress'in yerleşik cron sistemini kullanarak **saatte bir** otomatik olarak tüm aktif RSS kaynaklarını kontrol eder.

### Cron Nasıl Çalışır?

- WordPress'in wp-cron.php sistemi kullanılır
- Site ziyaret edildiğinde zamanlanmış işler kontrol edilir
- Her saat başı aktif kaynaklar taranır
- Yeni makaleler otomatik olarak içe aktarılır

### Cron Çalışmıyor mu?

Eğer otomatik çekim çalışmıyorsa:

1. WordPress'in cron sistemini kontrol edin
2. Hosting sağlayıcınızın cron desteğini doğrulayın
3. Manuel olarak "Şimdi Çek" kullanın
4. Server cron job'ı ekleyin (önerilen):

```bash
*/5 * * * * wget -q -O - http://siteniz.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1
```

## İçe Aktarılan Yazılar

### Yazı Özellikleri

İçe aktarılan her yazı:
- RSS'deki başlık kullanılır
- İçerik veya özet alınır
- Orijinal tarih korunur
- Seçilen kategoriye eklenir
- Seçilen yazara atanır
- Seçilen durumda (taslak/yayında) olur

### Meta Bilgiler

Her yazıya şu meta bilgiler eklenir:
- `superrss_source_url`: Orijinal makale linki
- `superrss_source_id`: Kaynak ID'si
- `superrss_feed_name`: Kaynak adı

Bu bilgileri görmek için yazı düzenleme sayfasında **Özel Alanlar** bölümüne bakın.

### Öne Çıkan Görsel

Eğer RSS feedinde görsel varsa:
- Otomatik olarak medya kütüphanesine indirilir
- Yazının öne çıkan görseli olarak ayarlanır
- Desteklenen formatlar: JPG, JPEG, PNG, GIF, WEBP

## Yinelenen İçerik Koruması

SuperRSS, aynı içeriği birden fazla kez içe aktarmaz:

1. **Başlık Kontrolü**: Aynı başlıklı yazı varsa atlanır
2. **URL Kontrolü**: Aynı kaynak URL'si varsa atlanır

Bu sayede aynı makale birden fazla eklenmez.

## Gereksinimler

### Minimum Gereksinimler
- WordPress 5.0 veya üzeri
- PHP 7.2 veya üzeri
- MySQL 5.6 veya üzeri

### PHP Eklentileri
- SimpleXML (RSS parse için)
- cURL veya allow_url_fopen (feed çekme için)
- GD veya ImageMagick (görsel işleme için)

### WordPress Yetkileri
- `manage_options` yetkisi (yönetim için)
- Yazı oluşturma yetkisi
- Medya yükleme yetkisi

## Sorun Giderme

### RSS Feed Çekilmiyor

**Kontrol Listesi:**
1. RSS feed URL'si geçerli mi? (tarayıcıda açıp test edin)
2. WordPress cron çalışıyor mu?
3. PHP SimpleXML yüklü mü?
4. Sunucu dış bağlantılara izin veriyor mu?

**Çözüm:**
- WordPress Debug Modu'nu açın: `define('WP_DEBUG', true);` (wp-config.php)
- Hata günlüklerini kontrol edin
- Manuel "Şimdi Çek" ile test edin

### Görseller İçe Aktarılmıyor

**Kontrol Listesi:**
1. `wp-content/uploads/` klasörü yazılabilir mi?
2. PHP `allow_url_fopen` veya cURL etkin mi?
3. Sunucu dış URL'lerden dosya indirmeye izin veriyor mu?

**Çözüm:**
```bash
# Klasör izinlerini kontrol edin
chmod 755 wp-content/uploads/
```

### Çok Fazla Yinelenen İçerik

**Olası Sebepler:**
- Aynı RSS kaynağı birden fazla eklenmiş
- RSS feed URL'leri farklı (http vs https, www vs www-siz)

**Çözüm:**
- Yinelenen kaynakları silin
- URL'leri standart hale getirin

### Yazılar Yanlış Kategoride

**Çözüm:**
1. SuperRSS sayfasında kaynağı bulun
2. Kaynağı silin ve doğru kategori ile tekrar ekleyin
3. Veya WordPress'te yazıları manuel olarak taşıyın

### Performans Sorunları

Eğer çok fazla kaynak eklediğinizde yavaşlama yaşıyorsanız:

1. Gereksiz kaynakları pasif yapın
2. Cron sıklığını azaltın
3. Sunucu kaynaklarını kontrol edin

## Güvenlik

SuperRSS güvenliğiniz için:
- ✅ Tüm kullanıcı girişleri temizlenir
- ✅ SQL injection koruması
- ✅ XSS saldırı koruması
- ✅ CSRF token kontrolü
- ✅ Yetki kontrolleri
- ✅ Güvenli dosya yükleme

## Yedekleme

Önemli: Eklentiyi silmeden önce:
1. Veritabanı yedeği alın
2. `wp_superrss_sources` tablosunu export edin
3. İçe aktarılan yazıları kontrol edin

## Kaldırma

Eklentiyi kaldırmak için:

1. WordPress yönetiminde **Eklentiler** sayfasına gidin
2. SuperRSS'i **Devre Dışı Bırak**
3. **Sil** butonuna tıklayın

**Not**: İçe aktarılan yazılar SİLİNMEZ. Sadece eklenti ve `wp_superrss_sources` tablosu silinir.

## Destek

Sorun yaşıyorsanız:
1. Bu dokümantasyonu okuyun
2. WordPress debug loglarını kontrol edin
3. GitHub Issues'da sorun bildirin: https://github.com/integrumart/superRSS/issues

## Lisans

GPLv2 veya üzeri - Özgürce kullanabilir ve değiştirebilirsiniz.
