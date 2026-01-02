# Örnek RSS Feed Kaynakları

Bu dosya, SuperRSS eklentisine ekleyebileceğiniz örnek RSS feed kaynaklarını içerir.

## Türkçe Haber Siteleri

### Genel Haberler
- **NTV Haber**: https://www.ntv.com.tr/gundem.rss
- **Hürriyet**: http://www.hurriyet.com.tr/rss/anasayfa
- **Milliyet**: http://www.milliyet.com.tr/rss/rssnew/gundemrss.xml
- **Sabah**: https://www.sabah.com.tr/rss/gundem.xml
- **BBC Türkçe**: https://feeds.bbci.co.uk/turkce/rss.xml

### Teknoloji
- **Webrazzi**: https://webrazzi.com/feed/
- **ShiftDelete.Net**: https://www.shiftdelete.net/feed
- **Chip Online**: https://www.chip.com.tr/rss/news.xml
- **DonanimHaber**: https://www.donanimhaber.com/rss/haber
- **Teknoseyir**: https://www.teknoseyir.com/feed

### Spor
- **NTV Spor**: https://www.ntvspor.net/rss
- **Fanatik**: https://www.fanatik.com.tr/rss/anasayfa.xml
- **Sporx**: https://www.sporx.com/rss

### Ekonomi
- **Bloomberg HT**: https://www.bloomberght.com/rss
- **Para Analiz**: https://www.paraanaliz.com/feed/

## Uluslararası Kaynaklar (İngilizce)

### Teknoloji
- **TechCrunch**: https://techcrunch.com/feed/
- **The Verge**: https://www.theverge.com/rss/index.xml
- **Ars Technica**: http://feeds.arstechnica.com/arstechnica/index
- **Wired**: https://www.wired.com/feed/rss
- **Engadget**: https://www.engadget.com/rss.xml
- **Gizmodo**: https://gizmodo.com/rss
- **TechRadar**: https://www.techradar.com/rss
- **CNET**: https://www.cnet.com/rss/news/
- **ZDNet**: http://www.zdnet.com/news/rss.xml

### Genel Haberler
- **BBC News**: http://feeds.bbci.co.uk/news/rss.xml
- **CNN**: http://rss.cnn.com/rss/edition.rss
- **Reuters**: http://feeds.reuters.com/reuters/topNews
- **The Guardian**: https://www.theguardian.com/world/rss
- **New York Times**: https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml

### Bilim
- **Science Daily**: https://www.sciencedaily.com/rss/all.xml
- **Phys.org**: https://phys.org/rss-feed/
- **Popular Science**: https://www.popsci.com/feed/

### İş Dünyası
- **Harvard Business Review**: http://feeds.harvardbusiness.org/harvardbusiness
- **Forbes**: https://www.forbes.com/real-time/feed2/
- **Business Insider**: https://www.businessinsider.com/rss

## RSS Feed Nasıl Bulunur?

### Yöntem 1: Site İçinde Arama
1. Web sitesine gidin
2. Sayfanın alt kısmında "RSS" veya RSS ikonu arayın
3. RSS linkine tıklayın ve URL'yi kopyalayın

### Yöntem 2: Tarayıcı Eklentileri
- Chrome/Edge: "RSS Feed Reader" eklentisi
- Firefox: Yerleşik RSS algılama özelliği

### Yöntem 3: URL Ekleme
Çoğu site için bu formatları deneyin:
- `https://example.com/feed/`
- `https://example.com/rss/`
- `https://example.com/feed.xml`
- `https://example.com/rss.xml`

## RSS Feed Test Etme

Bir RSS feed'in geçerli olup olmadığını test etmek için:

1. **W3C Feed Validator**: https://validator.w3.org/feed/
2. **FeedBurner**: https://feedburner.google.com/
3. **RSS Feed Checker**: Tarayıcınızda URL'yi açın ve XML görüntülenirse geçerlidir

## Kullanım İpuçları

### Kategori Organizasyonu
```
Teknoloji
├── Türkçe Teknoloji (Webrazzi, ShiftDelete)
└── Uluslararası Teknoloji (TechCrunch, The Verge)

Haberler
├── Türkiye Gündemi (NTV, Hürriyet)
└── Dünya Haberleri (BBC, CNN)

Spor
└── Futbol (NTV Spor, Fanatik)

Ekonomi
└── Finans (Bloomberg HT, Para Analiz)
```

### Yazar Organizasyonu
- Türkçe içerikler için: "İçerik Editörü"
- İngilizce içerikler için: "International Editor"
- Teknoloji için: "Tech Editor"

### Yazı Durumu Önerileri
- **Taslak**: Editöryal kontrol istiyorsanız (önerilen)
- **İnceleme Bekliyor**: Onay süreci varsa
- **Yayınla**: Güvendiğiniz kaynaklardan otomatik yayın

## Dikkat Edilmesi Gerekenler

⚠️ **Telif Hakları**: RSS feedlerini kullanırken:
- Tam metni kopyalamak yerine alıntı yapın
- Kaynak linkini mutlaka gösterin
- Görseller için telif haklarına dikkat edin
- Ticari kullanımda site sahiplerinden izin alın

⚠️ **Feed Yoğunluğu**: 
- Çok sık güncellenen feedler sunucu yükü oluşturabilir
- Başlangıçta az sayıda feed ile test edin
- Performansı izleyin

⚠️ **İçerik Kalitesi**:
- Kalitesiz içerik SEO'nuza zarar verebilir
- İçerikleri editöryal kontrolden geçirin
- "Taslak" durumunu kullanın

## Örnek Kurulum Senaryosu

### Senaryo 1: Teknoloji Blogu
```
Kaynak 1: TechCrunch
- Kategori: Teknoloji Haberleri
- Durum: Taslak
- Yazar: Tech Editor

Kaynak 2: Webrazzi
- Kategori: Teknoloji Haberleri
- Durum: Taslak
- Yazar: Türkçe Editor

Kaynak 3: The Verge
- Kategori: Gadget'lar
- Durum: İnceleme Bekliyor
- Yazar: Tech Editor
```

### Senaryo 2: Haber Portalı
```
Kaynak 1: BBC Türkçe
- Kategori: Dünya Haberleri
- Durum: Taslak
- Yazar: Haber Editörü

Kaynak 2: NTV Haber
- Kategori: Türkiye Gündemi
- Durum: Taslak
- Yazar: Haber Editörü

Kaynak 3: Bloomberg HT
- Kategori: Ekonomi
- Durum: Yayınla
- Yazar: Ekonomi Editörü
```

## RSS Feed Kaynakları Bulamıyor musunuz?

Alternatif çözümler:
1. **Feedly**: Popüler sitelerin RSS feedlerini bulun
2. **RSS.app**: RSS feed'i olmayan siteler için RSS oluşturun
3. **Google Alerts**: Anahtar kelime bazlı RSS feedler oluşturun

## Güncellemeler

Bu liste düzenli olarak güncellenir. Yeni RSS kaynakları önerileri için:
- GitHub Issues'da öneride bulunun
- Pull Request gönderin

## Yasal Uyarı

Bu listedeki RSS feedleri sadece örnek amaçlıdır. Feed kullanımından önce:
- İlgili sitenin kullanım koşullarını okuyun
- Telif hakları ve lisans bilgilerini kontrol edin
- Ticari kullanım için gerekli izinleri alın

RSS feed'lerin kullanımı tamamen sizin sorumluluğunuzdadır.
