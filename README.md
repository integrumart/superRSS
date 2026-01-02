# Super RSS

WordPress plugin for importing posts from unlimited RSS sources.

**Turkish**: WordPress süper RSS - sınırsız kaynaktan yazı çeker

## Description

Super RSS is a powerful WordPress plugin that allows you to add unlimited RSS feed sources and automatically import their posts into your WordPress site. Perfect for content aggregation, news sites, or keeping your site updated with fresh content from multiple sources.

## Key Features

- ✅ **Unlimited RSS Sources** - Add as many RSS feeds as you want
- 🔄 **Automatic Import** - Fetches and imports posts every minute
- 🛡️ **Duplicate Prevention** - Smart detection prevents duplicate posts
- ⚙️ **Easy Management** - Simple admin interface to add, edit, and delete sources
- 📝 **Auto-Publishing** - Imported posts are published automatically
- 🔗 **Source Attribution** - Links back to original articles

## Installation

See [INSTALLATION.md](INSTALLATION.md) for detailed installation instructions.

**Quick Start:**
1. Upload `super-rss.php` to `/wp-content/plugins/super-rss/` directory
2. Activate the plugin through WordPress admin
3. Go to "Super RSS" menu to add your RSS feeds

## Usage

1. Navigate to **Super RSS** in your WordPress admin menu
2. Add RSS feed sources with a name and URL
3. The plugin automatically imports posts every minute
4. Manage your sources: edit or delete them as needed

## Requirements

- WordPress 5.0+
- PHP 7.0+
- MySQL 5.6+

## Technical Details

- Uses WordPress cron with custom "every_minute" interval
- Stores RSS sources in custom database table
- Duplicate checking via GUID and URL comparison
- AJAX-powered admin interface

## License

GPL v2 or later

## Author

Integrumart - https://github.com/integrumart

## Support

For issues and questions: https://github.com/integrumart/superRSS/issues
