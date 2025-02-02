<?php

namespace App\Helpers;

use App\Data\IpInfoData;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonTimeZone;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class GeneralHelper
{
    protected static array $additionalJS = [];
    protected static array $additionalCSS = [];
    protected static array $additionalVendorJS = [];
    protected static array $additionalVendorCSS = [];
    protected static array $additionalBreadCrumb = [];
    protected static ?string $id = null;
    protected static string $action = 'create';
    protected static ?string $title = null;

    public static function compressImage(string $path, int $height): void
    {
        // create new manager instance with desired driver
        $image = ImageManager::gd()->read($path);
        $image->scaleDown(height: $height);
        $image->toJpeg()->save($path);
    }

    public static function compressImageWidth(string $path, int $width): void
    {
        // create new manager instance with desired driver
        $image = ImageManager::gd()->read($path);
        $image->scaleDown(width: $width);
        $image->toJpeg()->save($path);
    }

    public static function getId(): ?string
    {
        return self::$id;
    }

    public static function setId(string $id): void
    {
        self::$id = $id;
    }

    public static function getAction(): string
    {
        return self::$action;
    }

    public static function setAction(string $action): void
    {
        self::$action = $action;
    }

    public static function addAdditionalJS(mixed $file_urls): void
    {
        if (is_array($file_urls)) {
            self::$additionalJS = array_merge(self::$additionalJS, $file_urls);
        } else {
            self::$additionalJS[] = $file_urls;
        }
    }

    public static function getAdditionalJS(): array
    {
        return self::$additionalJS;
    }

    public static function addAdditionalCSS(mixed $file_urls): void
    {
        if (is_array($file_urls)) {
            self::$additionalCSS = array_merge(self::$additionalCSS, $file_urls);
        } else {
            self::$additionalCSS[] = $file_urls;
        }
    }

    public static function getAdditionalCSS(): array
    {
        return self::$additionalCSS;
    }

    public static function addAdditionalVendorJS(mixed $file_urls): void
    {
        if (is_array($file_urls)) {
            self::$additionalVendorJS = array_merge(self::$additionalVendorJS, $file_urls);
        } else {
            self::$additionalVendorJS[] = $file_urls;
        }
    }

    public static function getAdditionalVendorJS(): array
    {
        return self::$additionalVendorJS;
    }

    public static function addAdditionalVendorCSS(mixed $file_urls): void
    {
        if (is_array($file_urls)) {
            self::$additionalVendorCSS = array_merge(self::$additionalVendorCSS, $file_urls);
        } else {
            self::$additionalVendorCSS[] = $file_urls;
        }
    }

    public static function getAdditionalVendorCSS(): array
    {
        return self::$additionalVendorCSS;
    }

    public static function addAdditionalBreadCrumb(array $data): void
    {
        self::$additionalBreadCrumb = array_merge(self::$additionalBreadCrumb, $data);
    }

    public static function getTimezone(): array
    {
        $data = [];
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        foreach ($timezones as $timezone) {
            $data[] = [
                'value' => $timezone,
                'text' => '(' . CarbonTimeZone::create($timezone)->toOffsetName() . ') ' . $timezone,
            ];
        }

        return $data;
    }

    public static function isMethodAllowed(string $method = null): bool
    {
        $request = app(Request::class);

        if ($method === null) {
            $method = $request->method();
        }

        if (!in_array($method, (array) config('setting.method.allowed'))) {
            return false;
        }

        return true;
    }

    public static function getMenuByCode(string $accessCode): ?array
    {
        return self::getMenuFromNestedByCode(collect(config('access.menus'))->toArray(), $accessCode);
    }

    public static function renderMenuHtml(array $menuData, string $selectedMenuCode): string
    {
        // variable
        $menuHTML = '';

        // take visible group
        $groups = collect(config('access.groups'))->where('visible', true)->toArray();

        // set selected parent
        $selectedMenuParentCodes = collect(self::getBreadCrumbData($menuData, $selectedMenuCode))->pluck('code')->toArray();

        // loop group
        foreach ($groups as $idx => $group) {
            // get menu according group code
            $menus = collect($menuData)->where('group_code', $group['code'])->toArray();

            // render group
            if (count($menus) > 0) {
                $menuHTML .= '<li class="nav-header text-uppercase font-weight-bold' . ($idx != 0 ? " pt-5" : "") . '">' . $group['name'] . '</li>';
            }

            // loop menus
            foreach ($menus as $menu) {
                $menuIcon = empty($menu['icon']) ? '<i class="fas fa-angle-right nav-icon"></i>' : '<i class="nav-icon ' . $menu['icon'] . '"></i>';
                $menuActive = $menu['code'] === $selectedMenuCode ? ' active' : '';

                // check if menu has children
                if (empty($menu['children'])) {
                    $menuHTML .= '<li class="nav-item">';
                    $menuHTML .= '  <a class="nav-link' . $menuActive . '" href="' . url((string)$menu['path']) . '">';
                    $menuHTML .= '      ' . $menuIcon;
                    $menuHTML .= '      <p>' . $menu['name'] . '</p>';
                    $menuHTML .= '  </a>';
                    $menuHTML .= '</li>';
                } else {
                    // show menu dropdown
                    $menuShow = in_array($menu['code'], $selectedMenuParentCodes) ? ' menu-is-opening menu-open' : '';
                    $subMenuActive = in_array($menu['code'], $selectedMenuParentCodes) ? ' active' : '';

                    $menuHTML .= '<li class="nav-item' . $menuShow . '">';
                    $menuHTML .= '  <a class="nav-link' . $subMenuActive . '" href="javascript:;">';
                    $menuHTML .= '          ' . $menuIcon;
                    $menuHTML .= '      <p>' . $menu['name'] . '</p>';
                    $menuHTML .= '      <i class="right fas fa-angle-left"></i>';
                    $menuHTML .= '  </a>';
                    $menuHTML .= '  <ul class="nav nav-treeview' . $menuShow . '">';
                    $menuHTML .= '  ' . self::renderMenuSubHtml($menu['children'], $selectedMenuCode, $selectedMenuParentCodes);
                    $menuHTML .= '	</ul>';
                    $menuHTML .= '</li>';
                }
            }
        }

        return $menuHTML;
    }

    private static function renderMenuSubHtml(array $menuData, string $selectedMenuCode, $selectedMenuParentCodes = []): string
    {
        // if menu data empty then return empty string
        if (empty($menuData)) {
            return "";
        }

        // variabel
        $menuHTML = "";

        // render menu
        foreach ($menuData as $menu) {
            $menuIcon = empty($menu['icon']) ? '<i class="fas fa-angle-right nav-icon"></i>' : '<i class="nav-icon ' . $menu['icon'] . '"></i>';
            $menuActive = $menu['code'] === $selectedMenuCode ? ' active' : '';

            // if menu has children
            if (empty($menu['children'])) {
                $menuHTML .= '<li class="nav-item">';
                $menuHTML .= '  <a class="nav-link' . $menuActive . '" href="' . url($menu['path']) . '">';
                $menuHTML .= '      ' . $menuIcon;
                $menuHTML .= '      <p>' . $menu['name'] . '</p>';
                $menuHTML .= '  </a>';
                $menuHTML .= '</li>';
            } else {
                // show menu dropdown
                $menuShow = in_array($menu['code'], $selectedMenuParentCodes) ? ' menu-is-opening menu-open' : '';
                $subMenuActive = in_array($menu['code'], $selectedMenuParentCodes) ? ' bg-secondary' : '';

                $menuHTML .= '<li class="nav-item' . $menuShow . '">';
                $menuHTML .= '  <a class="nav-link' . $subMenuActive . '" href="javascript:;">';
                $menuHTML .= '          ' . $menuIcon;
                $menuHTML .= '      <p>' . $menu['name'];
                $menuHTML .= '      	<i class="right fas fa-angle-left"></i>';
                $menuHTML .= '      </p>';
                $menuHTML .= '  </a>';
                $menuHTML .= '  <ul class="nav nav-treeview' . $menuShow . '">';
                $menuHTML .= '  ' . self::renderMenuSubHtml($menu['children'], $selectedMenuCode, $selectedMenuParentCodes);
                $menuHTML .= '	</ul>';
                $menuHTML .= '</li>';
            }
        }

        return $menuHTML;
    }

    public static function createBreadCrumbHtml(array $menuData, string $menuCode): string
    {
        // variable
        $html = '<small class="text-muted ml-2"><ul class="list-inline">';

        // get data breadcrumb
        $dataBreadcrumbs = self::getBreadCrumbData($menuData, $menuCode);

        // loop
        foreach ($dataBreadcrumbs as $index => $breadcrumb) {
            // if index = 0
            if ($index === 0) {
                // get data group
                $group = collect(config('access.groups'))->firstWhere('code', $breadcrumb['group_code']);
                $html .= '<li class="list-inline-item">' . $group['name'] . '</li>';
            }

            // render breadcrumb
            $html .= '<li class="list-inline-item"><i class="fas fa-chevron-right text-xs"></i></li>';
            $html .= '<li class="list-inline-item">' . $breadcrumb['name'] . '</li>';
        }

        foreach (self::$additionalBreadCrumb as $index => $breadcrumb) {
            if (!empty($dataBreadcrumbs) || $index > 0) {
                $html .= '<li class="list-inline-item"><i class="fas fa-chevron-right text-xs"></i></li>';
            }
            $html .= '<li class="list-inline-item">' . $breadcrumb . '</li>';
        }

        $html .= '</ul></small>';

        return $html;
    }

    public static function getBreadCrumbData(array $menuData, string $selectedMenuCode): array
    {
        // variable
        $data = [];
        $stop = false;

        // loop while
        while (!$stop) {
            // get menu from nested
            $menu = self::getMenuFromNestedByCode($menuData, $selectedMenuCode);

            // if menu empty
            if (empty($menu)) {
                $stop = true;
            } else {
                // insert data menu
                unset($menu['children']);
                $data[] = $menu;
            }

            // if parent menu code different with code then continue looping
            if (!empty($menu) && $menu['parent_menu_code'] !== $menu['code']) {
                $selectedMenuCode = $menu['parent_menu_code'];
            } else {
                $stop = true;
            }
        }

        // get group if menu empty
        /* if (!empty($menu)) {
            $data[] = collect(config('access.groups'))->firstWhere('code', $menu['group_code']);
        } */

        return array_reverse($data);
    }

    public static function getMenuFromNestedByCode(array $menuData, string $selectedMenuCode): ?array
    {
        // loop $menuData
        foreach ($menuData as $menu) {
            // if menu found then return the data
            if ($menu['code'] === $selectedMenuCode) {
                return $menu;
            }

            // if menu children not empty then loop again this function
            if (!empty($menu['children'])) {
                $menu = self::getMenuFromNestedByCode($menu['children'], $selectedMenuCode);

                // if menu empty then return it
                if (!empty($menu)) {
                    return $menu;
                }
            }
        }

        return null;
    }

    public static function setTitle(string $title, bool $createDummyMenu = false): void
    {
        self::$title = $title;

        if ($createDummyMenu) {
            View::share('menu', self::generateDummyMenu($title));
        } else {
            View::share('title', $title);
        }
    }

    private static function generateDummyMenu(?string $menuName = null): array
    {
        $data = collect([]);

        $code = Str::random(30);
        $data->put('group_code', $code);
        $data->put('parent_menu_code', $code);
        $data->put('code', $code);
        $data->put('name', empty($menuName) ? Str::random(30) : $menuName);
        $data->put('children', null);

        return $data->toArray();
    }

    public static function prettyErrorMessage(MessageBag $messageBags): string
    {
        // variables
        $newMessage = "";

        // convert to collection
        $messageBags = collect($messageBags);

        // loop $messageBags
        foreach ($messageBags as $keyName => $messages) {
            $newMessage .= $keyName . ":";

            // loop $messages
            foreach ($messages as $message) {
                $newMessage .= PHP_EOL . $message;
            }
        }

        // add new line
        $newMessage .= empty($newMessage) ? "" : PHP_EOL;

        return $newMessage;
    }

    public static function numberFormat(int|float $value, ?int $precision = null): string
    {
        $precision = $precision ?? config('setting.local.numeric_precision_length');

        return Number::format(number: $value, precision: $precision);
    }

    public static function dateFormat(DateTime $datetime, ?string $timezone = null): string
    {
        return Date::parse($datetime)->setTimezone($timezone ?? session('timezone'))->format(config('setting.local.backend_date_format'));
    }

    public static function timeFormat(DateTime $datetime, ?string $timezone = null): string
    {
        return Date::parse($datetime)->setTimezone($timezone ?? session('timezone'))->format(config('setting.local.backend_time_format'));
    }

    public static function dateTimeFormat(DateTime $datetime, ?string $format = null, ?string $timezone = null): string
    {
        return Date::parse($datetime)->setTimezone($timezone ?? session('timezone'))->format($format ?? config('setting.local.backend_datetime_format'));
    }

    public static function durationToHumanReadable(int $seconds, ?string $timezone = null): string
    {
        return CarbonInterval::seconds($seconds)->setTimezone($timezone ?? session('timezone'))->cascade()->forHumans([
            'parts' => 3,
            'options' => Carbon::JUST_NOW
        ]);
    }

    public static function getDurationInSecondsBetweenDateTimes(DateTime $dateTimeStart, DateTime $dateTimeEnd, ?string $timezone = null): int
    {
        return Date::parse($dateTimeStart)->setTimezone($timezone ?? session('timezone'))->diffInSeconds($dateTimeEnd);
    }

    public static function replaceTextPresetsWithValues(string $text, array $presets = [], array $presetValues = [], bool $fillBlankWhenNull = true): string
    {
        foreach ($presets as $preset) {
            $text = str($text)->replace($preset->value, empty($presetValues[$preset->value]) ? ($fillBlankWhenNull ? "" : $preset->value) : $presetValues[$preset->value]);
        }

        return $text;
    }

    public static function imageToBase64(string $path, string $type = 'png'): string
    {
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public static function getIpInfo(): ?IpInfoData
    {
        try {
            $request = Http::get('https://ipinfo.io/json');

            if ($request->status() === 200) {
                return IpInfoData::from($request->json());
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
