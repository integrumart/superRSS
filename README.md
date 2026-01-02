# SuperRSS - WordPress RSS İçerik Çekici

WordPress süper RSS eklentisi - Sınırsız kaynaktan yazı çeker ve WordPress'e ekler

## Özellikler

* **Sınırsız RSS Kaynağı**: İstediğiniz kadar RSS feed kaynağı ekleyebilirsiniz
* **Otomatik İçe Aktarma**: RSS feedlerinden makaleleri otomatik olarak WordPress yazılarına aktarır
* **Kategori Desteği**: Her RSS kaynağı için farklı kategori belirleyebilirsiniz
* **Yazar Seçimi**: İçe aktarılan yazılar için yazar seçebilirsiniz
* **Yazı Durumu**: Yazıların taslak, yayınlanmış veya inceleme bekliyor olarak eklenmesini seçebilirsiniz
* **Otomatik Zamanlanmış Çekim**: RSS feedleri saatte bir otomatik olarak kontrol edilir
* **Manuel Çekim**: İstediğiniz zaman manuel olarak feed çekebilirsiniz
* **Yinelenen İçerik Koruması**: Aynı makale birden fazla kez içe aktarılmaz
* **Öne Çıkan Görsel**: RSS feedlerindeki görseller otomatik olarak öne çıkan görsel olarak ayarlanır
* **Türkçe Dil Desteği**: Tam Türkçe arayüz

## Kurulum

1. `superrss` klasörünü `/wp-content/plugins/` dizinine yükleyin
2. WordPress yönetim panelinde 'Eklentiler' menüsünden eklentiyi etkinleştirin
3. 'SuperRSS' menüsünden RSS kaynaklarınızı yönetin

## Kullanım

1. WordPress yönetim panelinde "SuperRSS" menüsüne gidin
2. "Yeni RSS Kaynağı Ekle" formunu doldurun
3. RSS feed URL'sini, kaynak adını ve diğer ayarları girin
4. "Kaynak Ekle" butonuna tıklayın
5. Eklenen kaynaklar listede görünecektir
6. "Şimdi Çek" butonuyla manuel olarak içerik çekebilir veya otomatik çekimi bekleyebilirsiniz

## Dosya Yapısı

```
superrss/
├── superrss.php                 # Ana eklenti dosyası
├── includes/
│   ├── class-superrss-db.php    # Veritabanı işlemleri
│   ├── class-superrss-fetcher.php  # RSS çekme işlemleri
│   └── class-superrss-admin.php # Yönetim paneli
├── assets/
│   ├── css/
│   │   └── admin.css            # Yönetici CSS
│   └── js/
│       └── admin.js             # Yönetici JavaScript
├── languages/                   # Çeviri dosyaları
├── readme.txt                   # WordPress.org readme
└── README.md                    # GitHub readme
```

## Sık Sorulan Sorular

### RSS feedleri ne sıklıkla kontrol edilir?

RSS feedleri varsayılan olarak saatte bir kontrol edilir. Bu, WordPress'in yerleşik cron sistemi kullanılarak yapılır.

### Aynı makale birden fazla kez içe aktarılır mı?

Hayır, SuperRSS yinelenen içeriği algılar ve aynı makaleyi birden fazla kez içe aktarmaz.

### Kaç tane RSS kaynağı ekleyebilirim?

Sınırsız sayıda RSS kaynağı ekleyebilirsiniz.

## Teknik Detaylar

### Veritabanı

Eklenti, RSS kaynaklarını saklamak için `{prefix}_superrss_sources` adlı bir tablo oluşturur.

### Cron İşi

RSS feedlerini çekmek için `superrss_fetch_feeds` adlı bir WordPress cron işi zamanlanır.

### Yinelenen İçerik Kontrolü

Her makale içe aktarılmadan önce:
1. Yazı başlığı kontrol edilir
2. Kaynak URL'si `superrss_source_url` meta alanında kontrol edilir

## Lisans

GPLv2 or later

## Versiyon

1.0.0
