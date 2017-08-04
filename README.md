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

Syntax: `app:setting.publish [options] [--] [<methods>]...`

**Methods:** publish parts, includes:
- `model`
- `observer`
- `migration`
- `setting_init`
- `controller`
- `request`
 



#### 2. `app:setting`

Syntax: `app:setting [action] [options]`

Actions:
- `dump`: dump settings, this is default action.
- `init`: initialize settings from `config/setting_init.php` file 
