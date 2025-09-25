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
        ->whereIn('page', [PageEnum::HOME, PageEnum::BLOG])
        ->where('status', 'active')
        ->whereIn('section', [
            SectionEnum::INTRO,
            SectionEnum::SERVICE,
            SectionEnum::SERVICES,
            SectionEnum::EXAMPLE,
            SectionEnum::EXAMPLES,
            SectionEnum::INSTALLATION,
            SectionEnum::INSTALLATIONS,
            SectionEnum::OURWORK,
            SectionEnum::OURWORKS,
            SectionEnum::REVIEW,
            SectionEnum::REVIEWS,
            SectionEnum::HOWITWORK,
            SectionEnum::HOWITWORKS,
            SectionEnum::CONTACTUS,
            SectionEnum::CONTACTUSES,
        ])
        ->get();

    $footer = Setting::first();

    // Define clean formatter
    $clean = function ($item) {
        if (!$item) return null;

        return [
            'id'         => $item->id,
            'page'       => $item->page,
            'section'    => $item->section,
            'description'=> $item->description,
            'price'      => $item->price,
            'slug'       => $item->slug,
            'title'      => $item->title,
            'sub_title'  => $item->sub_title,
            'image'      => $item->image ? asset($item->image) : null,
            'status'     => $item->status,
        ];
    };

    // Prepare CMS content
    $intro        = $cmsItems->where('section', SectionEnum::INTRO)->first();
    $service      = $cmsItems->where('section', SectionEnum::SERVICE)->first();
    $services     = $cmsItems->where('section', SectionEnum::SERVICES)->values();
    $why_us       = $cmsItems->where('section', SectionEnum::EXAMPLE)->first();
    $why_uses     = $cmsItems->where('section', SectionEnum::EXAMPLES)->values();
    $installation  = $cmsItems->where('section', SectionEnum::INSTALLATION)->first();
    $installations = $cmsItems->where('section', SectionEnum::INSTALLATIONS)->values();
    $ourwork      = $cmsItems->where('section', SectionEnum::OURWORK)->first();
    $ourworks     = $cmsItems->where('section', SectionEnum::OURWORKS)->values();
    $review       = $cmsItems->where('section', SectionEnum::REVIEW)->first();
    $reviews      = $cmsItems->where('section', SectionEnum::REVIEWS)->values();
    $howitwork    = $cmsItems->where('section', SectionEnum::HOWITWORK)->first();
    $howitworks   = $cmsItems->where('section', SectionEnum::HOWITWORKS)->values();
    $contactus    = $cmsItems->where('section', SectionEnum::CONTACTUS)->first();
    $contactuses  = $cmsItems->where('section', SectionEnum::CONTACTUSES)->values();

    // Default structure with null/empty fallback
    $data = [
        'intro'        => $intro ? $clean($intro) : null,
        'service'      => $service ? $clean($service) : null,
        'services'     => $services->isNotEmpty() ? $services->map(fn($s) => $clean($s))->values() : [],
        'example'      => $why_us ? $clean($why_us) : null,
        'examples'     => $why_uses->isNotEmpty() ? $why_uses->map(fn($ex) => $clean($ex))->values() : [],
        'installation'  => $installation ? $clean($installation) : null,
        'installations' => $installations->isNotEmpty() ? $installations->map(fn($ins) => $clean($ins))->values() : [],
        'ourwork'      => $ourwork ? $clean($ourwork) : null,
        'ourworks'     => $ourworks->isNotEmpty() ? $ourworks->map(fn($ow) => $clean($ow))->values() : [],
        'review'       => $review ? $clean($review) : null,
        'reviews'      => $reviews->isNotEmpty() ? $reviews->map(fn($rev) => $clean($rev))->values() : [],
        'howitwork'    => $howitwork ? $clean($howitwork) : null,
        'howitworks'   => $howitworks->isNotEmpty() ? $howitworks->map(fn($hw) => $clean($hw))->values() : [],
        'contactus'    => $contactus ? $clean($contactus) : null,
        'contactuses'  => $contactuses->isNotEmpty() ? $contactuses->map(fn($cu) => $clean($cu))->values() : [],
        'footer'       => $footer ? [
            'id'          => $footer->id,
            'title'       => $footer->name ?? null,
            'description' => $footer->description ?? null,
            'email'       => $footer->email ?? null,
            'phone'       => $footer->phone ?? null,
            'address'     => $footer->address ?? null,
            'logo'        => $footer->logo ? asset($footer->logo) : null,
            'copyright'   => $footer->copyright ?? null,
        ] : null,
    ];

    return Helper::jsonResponse(true, 'Home Page', 200, $data);
}

}
