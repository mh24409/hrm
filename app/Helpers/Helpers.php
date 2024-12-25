<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Accounts\Entities\AccCoa;
use Modules\Localize\Entities\Language;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Setting\Entities\Application;
use Modules\UserManagement\Entities\PerMenu;


/**
 * generate asset url
 */
function custom_asset(?string $file = null, ?string $default = null, ?string $path = null): string
{
    if ($file) {
        return app('url')->asset($path . '/' . $file . '?v=1');
    }

    return $default;
}

/**
 * module asset url
 */
function module_asset(?string $file = null, ?string $default = null): string
{
    return custom_asset($file, $default, 'module-assets');
}

if (!function_exists('age')) {
    function age($dob)
    {
        $age = Carbon::parse($dob)->age;
        return $age . " Years";
    }
}

function parentMenu($menuId)
{
    $menuDetail = PerMenu::where('id', $menuId)->first();

    if (empty($menuDetail)) {
        return null;
    }
    return $menuDetail->menu_name;
}

if (!function_exists('app_setting')) {
    function app_setting()
    {
        $appSetting = Cache::remember('appSetting', 3600, function () {
            return Application::with('currency')->first();
        });

        if (storage_path('app/public/' . $appSetting->logo)) {
            $appSetting->logo = asset('storage/' . $appSetting->logo);
        } else {
            $appSetting->logo = asset('assets/HRM2.png');
        }

        if (storage_path('app/public/' . $appSetting->sidebar_logo)) {
            $appSetting->sidebar_logo = asset('storage/' . $appSetting->sidebar_logo);
        } else {
            $appSetting->sidebar_logo = asset('assets/HRM2.png');
        }

        if (storage_path('app/public/' . $appSetting->sidebar_collapsed_logo)) {
            $appSetting->sidebar_collapsed_logo = asset('storage/' . $appSetting->sidebar_collapsed_logo);
        } else {
            $appSetting->sidebar_collapsed_logo = asset('assets/mini-logo.png');
        }

        if (storage_path('app/public/' . $appSetting->favicon)) {
            $appSetting->favicon = asset('storage/' . $appSetting->favicon);
        } else {
            $appSetting->favicon = asset('assets/favicon.png');
        }
        if (storage_path('app/public/' . $appSetting->login_image)) {
            $appSetting->login_image = asset('storage/' . $appSetting->login_image);
        } else {
            $appSetting->login_image = asset('assets/HRM2.png');
        }

        return $appSetting;
    }
}

// currencies
if (!function_exists('currency')) {
    function currency()
    {
        $currency = Cache::remember('currency', 3600, function () {
            return app_setting()?->currency->symbol ?? null;
        });
        return $currency;
    }
}

if (!function_exists('logo_64_data')) {
    function logo_64_data()
    {
        // Forever cache
        $appSetting = Cache::remember('appSetting', 3600, function () {
            return Application::first();
        });

        $logo = null;

        if (file_exists(asset($appSetting->logo))) {
            $logo = 'storage/' . $appSetting->logo;
        } else {
            $logo = __DIR__ . "/backend/assets/dist/img/logo-preview.png";
        }

        if (file_exists($logo) && is_readable($logo)) {
            $type = pathinfo($logo, PATHINFO_EXTENSION);
            $data = file_get_contents($logo);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return $base64;
        } else {
            // Handle error here (e.g., log, return a placeholder image, etc.)
            return null;
        }
    }
}

if (!function_exists('lang_setting')) {
    function lang_setting()
    {
        return cache()->remember('lang_setting', 120, function () {
            return Language::all();
        });
    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return ucwords($f->format($number) . ' ' . app_setting()->currency->title . ' ' . 'Only');
    }
}

if (!function_exists('numberToMillionBillion')) {
    function numberToMillionBillion($number = '')
    {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 2) . 'B';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 2) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 2) . 'K';
        } else {
            return number_format($number);
        }
    }
}

if (!function_exists('current_date')) {
    function current_date()
    {
        return Carbon::today()->toDateString();
    }
}
if (!function_exists('current_date_for_account')) {
    function current_date_for_account()
    {
        $startDate = Carbon::today()->format('d/m/Y');
        $endDate = Carbon::today()->addDays(30)->subDay()->format('d/m/Y');

        return $startDate . ' - ' . $endDate;
    }
}

if (!function_exists('getVouchersByNo')) {
    function getVouchersByNo($voucher_no)
    {
        $vouchers = Cache::remember($voucher_no, 3600, function () use ($voucher_no) {
            return AccVoucher::where('voucher_no', $voucher_no)->get();
        });

        return $vouchers;
    }
}

if (!function_exists('orderByData')) {
    function orderByData($req = null)
    {
        $orderBY = "DESC";
        if ($req != null && ($req[0]["dir"] == "desc")) {
            $orderBY = "ASC";
        }
        return $orderBY;
    }
}

if (!function_exists('bt_number_format')) {
    function bt_number_format($number)
    {
        $type = app_setting()->floating_number;
        $negative_symbol_type = app_setting()->negative_amount_symbol;
        $negative = false;

        if ($number < 0) {
            $negative = true;
            $number = (float) $number * -1;
        }

        if ($type == 1) {
            if ($negative_symbol_type == 2) {
                if ($negative) {
                    return '(' . number_format((float) (preg_replace('/[^\d.]/', '', $number)), 0, '.', ',') . ')';
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 0, '.', ',');
                }
            } else {
                if ($negative) {
                    return number_format(-(float) (preg_replace('/[^\d.]/', '', $number)), 0, '.', ',');
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 0, '.', ',');
                }
            }
        } elseif ($type == 2) {
            if ($negative_symbol_type == 2) {
                if ($negative) {
                    return '(' . number_format((float) (preg_replace('/[^\d.]/', '', $number)), 1, '.', ',') . ')';
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 1, '.', ',');
                }
            } else {
                if ($negative) {
                    return number_format(-(float) (preg_replace('/[^\d.]/', '', $number)), 1, '.', ',');
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 1, '.', ',');
                }
            }
        } elseif ($type == 3) {
            if ($negative_symbol_type == 2) {
                if ($negative) {
                    return '(' . number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',') . ')';
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                }
            } else {
                if ($negative) {
                    return number_format(-(float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                }
            }
        } elseif ($type == 4) {
            if ($negative_symbol_type == 2) {
                if ($negative) {
                    return '(' . number_format((float) (preg_replace('/[^\d.]/', '', $number)), 3, '.', ',') . ')';
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 3, '.', ',');
                }
            } else {
                if ($negative) {
                    return number_format(-(float) (preg_replace('/[^\d.]/', '', $number)), 3, '.', ',');
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 3, '.', ',');
                }
            }
        } else {
            if ($negative_symbol_type == 2) {
                if ($negative) {
                    return '(' . number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',') . ')';
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                }
            } else {
                if ($negative) {
                    return number_format(-(float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                } else {
                    return number_format((float) (preg_replace('/[^\d.]/', '', $number)), 2, '.', ',');
                }
            }
        }
    }
}

if (!function_exists('isBankNature')) {
    function isBankNature($id)
    {
        $nature = AccCoa::select('is_bank_nature', 'id')->where('id', $id)->where('is_bank_nature', 1)->first();

        if ($nature) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('check_expiry')) {
    function check_expiry(string $expiry_date, int $interval = null): bool
    {
        $today = Carbon::today();

        if ($interval) {
            $interval_date = Carbon::today()->addDays($interval);
            $expiry = Carbon::parse($expiry_date)->lte($interval_date);

            if ($expiry && Carbon::parse($expiry_date)->gt($today)) {
                return true;
            } else {
                return false;
            }
        } else {
            $expiry = Carbon::parse($expiry_date)->lt($today);
        }
        return $expiry;
    }
}

if (!function_exists('check_expiry')) {
    function check_expiry(string $expiry_date, int $interval = null): bool
    {
        $today = Carbon::today();

        if ($interval) {
            $interval_date = Carbon::today()->addDays($interval);
            $expiry = Carbon::parse($expiry_date)->lte($interval_date);

            if ($expiry && Carbon::parse($expiry_date)->gt($today)) {
                return true;
            } else {
                return false;
            }
        } else {
            $expiry = Carbon::parse($expiry_date)->lt($today);
        }
        return $expiry;
    }
}

/**
 * Convert size to human readable format (KB, MB, GB, TB, PB)
 */
function size_convert(int $size): string
{
    $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

/**
 * Activity Log Now
 *
 * @param  mixed  $user
 */
function logNow(array $response = [], string $name = 'Default', string $log = 'error', string $user = null): void
{
    if (!$user) {
        $user = auth()->user();
    }
    activity()
        ->causedBy($user)
        ->withProperties([
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'input' => request()->all(),
            'response' => $response,
        ])
        ->useLog($name)
        ->log($log);
}

/**
 * Get The Localize Data From File
 */
function localize(?string $key, ?string $default_value = null, ?string $locale = null): ?string
{
    if (is_null($key) || $key == '' || $key == ' ' || empty($key)) {
        return '';
    }

    return \App\Facades\Localizer::localize($key, $default_value, $locale);
}

/**
 * Get The Localize Data From File
 */
function ___(?string $key, ?string $default_value = null, ?string $locale = null): ?string
{
    return localize($key, $default_value, $locale);
}
/**
 * Get The Localize Data From File
 */
function get_phrases(?string $key, ?string $default_value = null, ?string $locale = null): ?string
{
    return localize($key, $default_value, $locale);
}
