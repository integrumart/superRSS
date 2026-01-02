# Super RSS - WordPress RSS Eklentisi

WordPress için geliştirilmiş, sınırsız RSS kaynağından otomatik makale çekme eklentisi.

## Özellikler

- ✅ **Sınırsız RSS Feed Ekleme:** İstediğiniz kadar RSS feed ekleyebilirsiniz
- ✅ **Otomatik Makale Çekme:** RSS feed'lerden otomatik olarak makaleler çekilir
- ✅ **WordPress'e Entegrasyon:** Çekilen makaleler doğrudan WordPress yazılar bölümüne eklenir
- ✅ **Otomatik Cron İşlemi:** Her saat başı otomatik olarak RSS feed'ler kontrol edilir
- ✅ **Manuel Çekim Özelliği:** İstediğiniz zaman manuel olarak feed çekebilirsiniz
- ✅ **Tekrar Kontrolü:** Daha önce eklenen makaleler tekrar eklenmez
- ✅ **Kolay Yönetim Paneli:** Kullanıcı dostu admin paneli
- ✅ **Feed Bilgileri:** Her feed için son çekilme tarihi ve durum bilgisi

## Kurulum

1. Plugin dosyalarını `/wp-content/plugins/super-rss/` dizinine yükleyin
2. WordPress admin panelinde "Eklentiler" bölümüne gidin
3. "Super RSS" eklentisini etkinleştirin
4. Sol menüde "Super RSS" sekmesine tıklayın

## Kullanım

### RSS Feed Ekleme

1. Admin panelde "Super RSS" menüsüne tıklayın
2. "Yeni RSS Feed Ekle" bölümünde:
   - **RSS URL:** Feed'in tam URL'sini girin (örn: `https://example.com/feed`)
   - **Feed Adı:** İsteğe bağlı - Boş bırakılırsa RSS'den otomatik alınır
3. "RSS Feed Ekle" butonuna tıklayın

### Feed Yönetimi

- **Şimdi Çek:** Bir feed'i hemen çekmek için "Şimdi Çek" butonunu kullanın
- **Sil:** Feed'i silmek için "Sil" butonunu kullanın
- **Durum:** Her feed'in aktif/pasif durumunu görüntüleyin
- **Son Çekilme:** Her feed'in en son ne zaman çekildiğini görüntüleyin

### Otomatik İşlemler

- RSS feed'ler her saat başı otomatik olarak çekilir
- Yeni makaleler WordPress yazılar bölümüne otomatik olarak eklenir
- Daha önce eklenen makaleler tekrar eklenmez (kaynak URL kontrolü yapılır)

## Teknik Detaylar

### Veritabanı

Plugin kurulumda otomatik olarak `wp_super_rss_feeds` tablosunu oluşturur.

### Cron İşlemi

WordPress'in WP-Cron sistemini kullanarak her saat başı otomatik çekim yapar.

### Meta Veriler

Her içe aktarılan yazıya şu meta veriler eklenir:
- `super_rss_source_url`: Orijinal makale URL'si
- `super_rss_feed_name`: Feed adı

## Gereksinimler

- WordPress 5.0 veya üzeri
- PHP 7.0 veya üzeri
- MySQL 5.6 veya üzeri

## Sık Sorulan Sorular

**S: Kaç tane RSS feed ekleyebilirim?**  
C: Sınırsız! İstediğiniz kadar RSS feed ekleyebilirsiniz.

**S: Makaleler ne sıklıkla çekilir?**  
C: Varsayılan olarak her saat başı otomatik çekilir. Ayrıca manuel olarak da çekebilirsiniz.

**S: Aynı makale birden fazla kez eklenir mi?**  
C: Hayır, her makale sadece bir kez eklenir. Kaynak URL kontrolü yapılır.

**S: Çekilen makaleler hangi durumdadır?**  
C: Makaleler "Yayımlandı" (publish) durumunda eklenir.

## Destek

Sorunlarınız veya önerileriniz için [GitHub Issues](https://github.com/integrumart/superRSS/issues) sayfasını kullanabilirsiniz.

## Lisans

GPL v2 veya üzeri

## Geliştirici

IntegrumArt - [GitHub](https://github.com/integrumart)
