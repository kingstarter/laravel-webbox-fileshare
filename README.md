# Webbox Fileshare

Webbox Fileshare is a [Laravel 7.x app](https://laravel.com/docs/7.x) for uploading a set of files and generating a randomized, shared link to the files. It is kind of a web-dropbox. The app runs __without any database__.

## Main Features

- Simple authentication via a security pin with possible honeypot protection
- Uploading files via [Vue.js](https://vuejs.org/) and [Dropzone.js](https://www.dropzonejs.com/)
- Generation of random storage links using MD5 hashes
- Scheduled storage cleanup routines
- Configurable cleanup frequencies for storage links, e.g. storing for 1 month
- Mail support to share the generated link
- Localization (currently english and german)
- No Database

## Installation

1. Clone or download repository
1. Install composer packages (production optimized)
1. Generate app key and storage link
1. Change directory permissions
1. Configure cron scheduling
1. Configure `.env` file and webserver
1. Optimize routes, config-cache, views

_Download and store webbox-fileshare to `/var/www/box`_
```
# Download from master
cd /tmp
wget -c https://github.com/kingstarter/laravel-webbox-fileshare/archive/master.tar.gz -O -
mv laravel-webbox-fileshare-master /var/www/box

# Basic configuration
cd /var/www/box
cp .env.example .env
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan storage:link
chown -R www-data public storage

# Cron scheduler
CRONFILE=/etc/cron.d/webbox
[ -f $CRONFILE ] && cp $CRONFILE $CRONFILE.bak
echo "# Run laravel webbox fileshare scheduler" > $CRONFILE
echo "SHELL=/bin/sh" >> $CRONFILE
echo "* * * * * cd /var/www/box && php artisan schedule:run >> /dev/null 2>&1" >> $CRONFILE
```
Note: App configurations and optimzations are not included in the above script.

## Configuration options

Most important configurations can be handled via the `.env` file, see `.env.example`. The main application configuration is stored within `config/webbox.php`.

### Webbox `.env` configurations

| .env option | Description | Default value |
|---|---|---|---|
| APP_LOCALE | Localization to use. Short language code should be used, e.g. 'en' / 'de'. | en |
| APP_AUTH_PIN | General security pin | passw0rd! |
| SESSION_LIFETIME | Session lifetime in seconds. | 1800 |
| HONEYPOT_ENABLED | Activate honeypot field on login screen | true |
| HONEYPOT_FIELD | Honeypot field name for form input. Should contain a known field name and some random chars. | phone_number_4f3dx |
| MAX_FILESIZE_MB | Maximum allowed file size in megabytes. Should match also php and webserver config. | 256 |
| FOOTER_TEXT | App footer text to display in main application. No footer if text is empty. Can also contain HTML codes. | &amp;#169; 2020 powered by KingStarter GbR |
| FOOTER_LINK | a-href link used for footer-text | https://kingstarter.de |

### Additional webbox configurations

Beside the `.env` options, the selectable storage lifetime (select-field on upload page) can be configured within the `config/webbox.php` file. The `storage_lifetime` array has some predefined values that are all Carbon-compatible (simple strings for adding to time-values).

The `default_lifetime` config option should match one of the given `storage_lifetime` fields, e.g. _1 month_.

### Adding mail support

The upload page allows sending emails with the generated storage link. The send-email field on the modal will only appear if a mail driver is configured. Per default, the mail driver field is set to `null` for deactivating sending emails.
