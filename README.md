# Laravel Setting

Simple setting for Laravel application

## Install 

Install with composer:

```
composer require madnh/laravel-setting
```

Add `\MaDnh\LaravelSetting\LaravelSettingServiceProvider::class` to `config/app.php`

```php
'providers' => [
    ...
    \MaDnh\LaravelUpload\LaravelUploadServiceProvider::class,
    ...
]
```

## Usage

### Commands

#### 1. `app:setting.publish`

This command publish setting parts.

Syntax: `app:setting.publish [options] [--] [<part>]`

**Part:** setting parts, includes:

- model
- observer
- migration
- setting_init
- controller
- request
- vendor
- all

Use `all` to publish all of parts. 

If no parts are specified, then all of parts will be published.

**Options**

- `-f`, `--force`: Overwrite any existing files
- `--tag`: Publish tag (or group) registered by service provider. Examples: _config_, _styles_, _views_,... 
- `--subns`: Sub namespace of setting parts. Examples: _Dashboard_, _Admin_,... 

**Examples**

Publish all of parts

```
php artisan app:setting.publish
php artisan app:setting.publish all
```

Publish special parts

```
php artisan app:setting.publish request controller setting_init
```

Publish parts with sub namespace

```
php artisan app:setting.publish request controller setting_init --subns=Dashboard
```

Publish vendor with tag

```
php artisan app:setting.publish --tag=config --tag=styles
php artisan app:setting.publish controller vendor --subns=Dashboard --tag=scripts
```



#### 2. `app:setting`

Dump, init, import and export settings.

Syntax: `app:setting [options] [--] [<action>]`

**Actions:** action to do, optional. Supports:

- `dump`: Show settings detail
- `post`: Post settings from setting file (default is `config/setting.php`) to DB
- `make`: Write settings from DB to setting file (default is `config/setting.php`)
- `init`: Clean DB, do `post` then `make`

If no action is specified, then use `dump` as default action.

**Options**

- `--file=FILE`: File to load init settings, use in `init` and `make` action. If not specified then use `config/setting_init.php` in `init`, or `config/setting.php` in `make`.
- `--name`: Setting name, use with `dump` action. If missing then dump all of settings. Multiple values allowed
