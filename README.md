# Webbox Fileshare

Webbox Fileshare is a [Laravel 7.x app](https://laravel.com/docs/7.x) for uploading a set of files and generating a randomized, shared link to the files. It is kind of a web-dropbox. The app runs __without any database__.

![Webbox preview](PREVIEW.png?raw=true "Webbox preview")

__Table of contents__

- [Main Features](#main-features)
- [Installation](#installation)
- [Configuration options](#configuration-options)
  + [Webbox `.env` configurations](#webbox--env--configurations)
  + [Additional webbox configurations](#additional-webbox-configurations)
  + [Adding mail support](#adding-mail-support)

## Main Features

- Simple authentication via a security pin with possible honeypot protection
- Uploading files via [Vue.js](https://vuejs.org/) and [Dropzone.js](https://www.dropzonejs.com/)
- Generation of random storage links using MD5 hashes
- Public shared file page with image preview, single file download and zip-download of all files
- [Fontawesome](https://fontawesome.com/) file icons for corresponding mime types on shared file pages
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
# Set public path
WEBPATH=/var/www/box

# Download from master
cd /tmp
wget -c https://github.com/kingstarter/laravel-webbox-fileshare/archive/master.tar.gz -O -
mv laravel-webbox-fileshare-master $WEBPATH

# Basic configuration
cd $WEBPATH
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
echo "* * * * * www-data /usr/bin/php $WEBPATH/artisan schedule:run >> /dev/null 2>&1" >> $CRONFILE
```
Note: App configurations and optimizations are not included in the script above.

### Test scheduler is called by cron

Normally adding a crontab file to `/etc/cron.d/` should work without any problems, yet in some cases the system seems not to load the file properly.
To test that the cronjob is called, simply add following line for testing purposes:

```
WEBPATH=/var/www/box
CRONFILE=/etc/cron.d/webbox

# Minutely log a timestamp into /tmp/webbox-test-cron.log
echo "* * * * * www-data /usr/bin/php $WEBPATH/artisan cron:test > /tmp/webbox-test-cron.log" >> $CRONFILE
```

The cronjob works as expected if the logfile `/tmp/webbox-test-cron.log` is created (after a minute) and contains a current local or UTC timestamp. In case the cronjob is not loaded, it might be helpful to move the cron command to `/etc/crontab`:

```
WEBPATH=/var/www/box
CRONFILE=/etc/cron.d/webbox
CRONTAB=/etc/crontab

# Remove cronfile and move to crontab
rm -f $CRONFILE
echo "* * * * * www-data /usr/bin/php $WEBPATH/artisan schedule:run >> /dev/null 2>&1" >> $CRONTAB

# Testing crontab is working
echo "* * * * * www-data /usr/bin/php $WEBPATH/artisan cron:test > /tmp/webbox-test-cron.log" >> $CRONTAB
```

## Configuration options

Most important configurations can be handled via the `.env` file, see `.env.example`. The main application configuration is stored within `config/webbox.php`.

### Webbox `.env` configurations

| .env option | Description | Default value |
| ----------- | ----------- | ------------- |
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

To add mail support, the mail configuration in the `.env` file needs to be filled. Simplest configuration would be using `MAIL_MAILER=smtp` as driver. For other possibilities, please refer to the [laravel documentation](https://laravel.com/docs/7.x/mail).
