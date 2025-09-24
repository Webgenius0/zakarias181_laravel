<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use App\Models\FAQ;
use App\Models\Setting;

class HomeController extends Controller
{
  public function index()
{
    $cmsItems = CMS::query()
        ->where('page', PageEnum::HOME)
        ->where('status', 'active')
        ->whereIn('section', [
            SectionEnum::INTRO,
            SectionEnum::SERVICE,
            SectionEnum::SERVICES,
            SectionEnum::EXAMPLE,
            SectionEnum::EXAMPLES
        ])
        ->get();

    $data = [];

    $intro     = $cmsItems->where('section', SectionEnum::INTRO)->first();
    $service   = $cmsItems->where('section', SectionEnum::SERVICE)->first();
    $services  = $cmsItems->where('section', SectionEnum::SERVICES)->values();
    $why_us    = $cmsItems->where('section', SectionEnum::EXAMPLE)->first();
    $why_uses  = $cmsItems->where('section', SectionEnum::EXAMPLES)->values();

    $footer    = Setting::first();

    // Define clean formatter
    $clean = function ($item) {
        if (!$item) return null;

        return [
            'id'         => $item->id,
            'page'       => $item->page,
            'section'    => $item->section,
            'slug'       => $item->slug,
            'title'      => $item->title,
            'sub_title'  => $item->sub_title,
            'image'      => $item->image ? asset($item->image) : null,
            'status'     => $item->status,
        ];
    };

    // Intro
    if ($intro) {
        $data['intro'] = $clean($intro);
    }

    // Service
    if ($service) {
        $data['service'] = $clean($service);
    }

    // Services (collection)
    if ($services->isNotEmpty()) {
        $data['services'] = $services->map(fn($s) => $clean($s))->values();
    }

    // Why Us (example)
    if ($why_us) {
        $data['example'] = $clean($why_us);
    }

    // Why Uses (examples - collection)
    if ($why_uses->isNotEmpty()) {
        $data['examples'] = $why_uses->map(fn($ex) => $clean($ex))->values();
    }

    // Footer settings
    if ($footer) {
        $data['footer'] = [
            'id'         => $footer->id,
            'site_name'  => $footer->site_name,
            'email'      => $footer->email,
            'phone'      => $footer->phone,
            'address'    => $footer->address,
            'logo'       => $footer->logo ? asset($footer->logo) : null,
        ];
    }

    return Helper::jsonResponse(true, 'Home Page', 200, $data);
}

}
