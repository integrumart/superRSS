# Super RSS Plugin - Installation Guide

## Installation

### Method 1: Manual Installation

1. Download the `super-rss.php` file
2. Upload it to your WordPress installation's `wp-content/plugins/super-rss/` directory
3. Go to your WordPress admin panel
4. Navigate to **Plugins** → **Installed Plugins**
5. Find "Super RSS" in the list and click **Activate**

### Method 2: ZIP Installation

1. Create a folder named `super-rss`
2. Place the `super-rss.php` file inside this folder
3. Compress the folder into a ZIP file (`super-rss.zip`)
4. In WordPress admin, go to **Plugins** → **Add New** → **Upload Plugin**
5. Choose the ZIP file and click **Install Now**
6. After installation, click **Activate Plugin**

## Configuration

1. After activation, you'll see "Super RSS" in your WordPress admin menu (with an RSS icon)
2. Click on **Super RSS** to access the settings page

## Usage

### Adding RSS Sources

1. Go to **Super RSS** in the admin menu
2. Fill in the form:
   - **Feed Name**: A friendly name for the RSS source (e.g., "Tech News", "My Blog")
   - **Feed URL**: The complete RSS feed URL (e.g., `https://example.com/feed`)
3. Click **Add RSS Source**
4. The source will appear in the list below

### Managing RSS Sources

- **Edit**: Click the "Edit" button next to any source to modify its name or URL
- **Delete**: Click the "Delete" button to remove a source (confirmation required)

### Automatic Import

- The plugin automatically fetches and imports posts from all RSS sources **every minute**
- New posts are published automatically to your WordPress site
- Duplicate checking ensures the same article isn't imported twice
- Each imported post includes a "Read more" link to the original source

## Features

✅ **Unlimited RSS Sources** - Add as many RSS feeds as you need  
✅ **Automatic Import** - Posts are fetched every minute via WordPress cron  
✅ **Duplicate Prevention** - Smart checking prevents duplicate posts  
✅ **Easy Management** - Add, edit, and delete sources from a simple interface  
✅ **Automatic Publishing** - Imported posts are published immediately  
✅ **Source Attribution** - Each post links back to the original article  

## Technical Details

### Database Table

The plugin creates a custom table `wp_super_rss_sources` to store RSS feed information:
- `id`: Unique identifier
- `feed_url`: The RSS feed URL
- `feed_name`: Display name for the feed
- `created_at`: Timestamp of when the source was added

### Cron Schedule

- Uses WordPress's `wp_schedule_event` with a custom `every_minute` interval
- Action hook: `super_rss_fetch_feeds`
- Automatically set up on plugin activation
- Automatically removed on plugin deactivation

### Duplicate Detection

Posts are checked for duplicates using:
1. **GUID** (Global Unique Identifier) from the RSS feed
2. **Source URL** comparison in post content

## Troubleshooting

### RSS feeds aren't importing

1. Check that WordPress cron is working: Install a cron viewer plugin
2. Verify the RSS feed URL is correct and accessible
3. Check WordPress error logs for any issues

### Cron not running

If WordPress cron isn't triggering:
- Make sure your site has regular traffic, or
- Set up a system cron job to trigger `wp-cron.php`

### Posts not appearing

- Check if the RSS feed is valid and contains items
- Verify you have permission to publish posts
- Check the post status in WordPress admin

## Deactivation

When you deactivate the plugin:
- The scheduled cron job is automatically removed
- The database table remains (for safety)
- Previously imported posts are not affected

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- MySQL 5.6 or higher

## Support

For issues or questions, please visit: https://github.com/integrumart/superRSS
