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
                SectionEnum::REVIEW,
                SectionEnum::REVIEWS,
                SectionEnum::HOWITWORK,
                SectionEnum::HOWITWORKS,
                SectionEnum::CONTACTUS,
                SectionEnum::CONTACTUSES,
            ])
            ->get();


        $data = [];

        $intro     = $cmsItems->where('section', SectionEnum::INTRO)->first();
        $service   = $cmsItems->where('section', SectionEnum::SERVICE)->first();
        $services  = $cmsItems->where('section', SectionEnum::SERVICES)->values();
        $why_us    = $cmsItems->where('section', SectionEnum::EXAMPLE)->first();
        $why_uses  = $cmsItems->where('section', SectionEnum::EXAMPLES)->values();
        $review    = $cmsItems->where('section', SectionEnum::REVIEW)->first();
        $reviews   = $cmsItems->where('section', SectionEnum::REVIEWS)->values();
        $howitwork = $cmsItems->where('section', SectionEnum::HOWITWORK)->first();
        $howitworks = $cmsItems->where('section', SectionEnum::HOWITWORKS)->values();
        $contactus  = $cmsItems->where('section', SectionEnum::CONTACTUS)->first();
        $contactuses = $cmsItems->where('section', SectionEnum::CONTACTUSES)->values();

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
        // Review
        if ($review) {
            $data['review'] = $clean($review);
        }
        // Reviews (collection)
        if ($reviews->isNotEmpty()) {
            $data['reviews'] = $reviews->map(fn($rev) => $clean($rev))->values();
        }

        //  How it Works
        if ($howitwork) {
            $data['howitwork'] = $clean($howitwork);
        }
        // How it Works (collection)
        if ($howitworks->isNotEmpty()) {
            $data['howitworks'] = $howitworks->map(fn($hw) => $clean($hw))->values();
        }

        // Contact Us
        if ($contactus) {
            $data['contactus'] = $clean($contactus);
        }
        // Contact Us (collection)
        if ($contactuses->isNotEmpty()) {
            $data['contactuses'] = $contactuses->map(fn($cu) => $clean($cu))->values();
        }
        // Footer settings
        if ($footer) {
            $data['footer'] = [
                'id'         => $footer->id,
                'title'      => $footer->name ?? null,
                'description' => $footer->description ?? null,
                'email'     => $footer->email ?? null,
                'phone'     => $footer->phone ?? null,
                'address'   => $footer->address ?? null,
                'logo'      => $footer->logo ? asset($footer->logo) : null,
                'copyright' => $footer->copyright ?? null,
            ];
        }

        return Helper::jsonResponse(true, 'Home Page', 200, $data);
    }
}
