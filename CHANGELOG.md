# Changelog

All notable changes to Super RSS WordPress Plugin will be documented in this file.

## [1.0.0] - 2026-01-02

### Added
- Initial release of Super RSS WordPress Plugin
- Unlimited RSS feed support
- Automatic RSS feed fetching with WordPress cron (hourly)
- Manual RSS feed fetching capability
- Comprehensive admin interface for feed management
- AJAX-powered feed addition and deletion
- Duplicate article prevention using source URL tracking
- Automatic post creation from RSS articles
- Feed status monitoring (active/inactive)
- Last fetch timestamp tracking for each feed
- Modern, responsive admin UI with WordPress design standards
- Turkish language support throughout the interface
- Security features:
  - Nonce verification for all AJAX requests
  - Capability checks (manage_options)
  - Input sanitization for all user inputs
  - Output escaping to prevent XSS
  - Error message sanitization to prevent information disclosure
- Robust author assignment:
  - Uses current user if available
  - Falls back to first admin user
  - Falls back to first user
  - Safe default fallback
- Configurable max items per feed via filter hook
- Database table creation on activation
- Cron job automatic scheduling on activation
- Cron job cleanup on deactivation
- Meta data storage for tracking article sources
- Feed validation before addition
- RSS feed title auto-detection
- Detailed documentation:
  - README.md with user guide
  - TECHNICAL.md with developer documentation
  - INSTALLATION.md with setup and testing guide

### Features
- **Feed Management**
  - Add unlimited RSS feeds
  - Delete feeds with confirmation
  - View all feeds in organized table
  - Status indicators for each feed
  - Last fetch timestamp display

- **Article Import**
  - Automatic hourly fetching
  - Manual "Fetch Now" option per feed
  - Duplicate prevention
  - Preserves article dates
  - Stores source URL and feed name as meta data

- **User Interface**
  - Clean, modern design
  - Responsive layout
  - Real-time feedback with AJAX
  - Success/error notifications
  - Loading indicators
  - Smooth animations

- **Developer Features**
  - `super_rss_max_items` filter hook
  - Well-documented code
  - WordPress coding standards
  - Extensible architecture
  - Database schema documentation

### Technical Details
- **Requirements**
  - WordPress 5.0+
  - PHP 7.0+
  - MySQL 5.6+

- **Database**
  - Creates `wp_super_rss_feeds` table
  - Stores: ID, URL, name, status, last_fetch, created_at

- **Cron**
  - Event: `super_rss_fetch_feeds`
  - Schedule: Hourly
  - Action: Fetches all active feeds

- **Meta Keys**
  - `super_rss_source_url`: Original article URL
  - `super_rss_feed_name`: Source feed name

### Security
- Passed CodeQL security analysis
- No SQL injection vulnerabilities
- No XSS vulnerabilities
- No information disclosure issues
- Proper WordPress nonce implementation
- Capability-based access control

### Files
- `super-rss.php` (11KB) - Main plugin file
- `templates/admin-page.php` (6KB) - Admin interface template
- `assets/admin.css` (3.8KB) - Admin styling
- `assets/admin.js` (6KB) - Admin JavaScript with AJAX handlers
- `README.md` (3.1KB) - User documentation
- `TECHNICAL.md` (6.9KB) - Developer documentation
- `INSTALLATION.md` (8.1KB) - Installation and testing guide
- `.gitignore` (284B) - Git ignore rules

### Known Limitations
- Maximum 10 articles per feed per fetch (configurable via filter)
- Hourly cron schedule (not configurable via UI)
- No category/tag mapping from RSS
- No media downloading capability
- No custom post type selection
- No scheduling UI for per-feed intervals

### Future Enhancements
See TECHNICAL.md for detailed list of potential future features including:
- Feed categories
- Custom post type support
- Custom author selection per feed
- Article filtering rules
- Configurable scheduling per feed
- Content transformation options
- Media download support
- Multi-language interface
- Import history tracking
- Statistics and reporting

## [Unreleased]

### Planned for v1.1.0
- Settings page for global configuration
- Configurable cron schedule
- Feed categorization
- Import statistics dashboard

---

**Legend:**
- `Added`: New features
- `Changed`: Changes in existing functionality
- `Deprecated`: Soon-to-be removed features
- `Removed`: Removed features
- `Fixed`: Bug fixes
- `Security`: Security improvements
