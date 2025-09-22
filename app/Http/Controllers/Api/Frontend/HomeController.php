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
      $intro   = $cmsItems->where('section', SectionEnum::INTRO)->first();
      $service  = $cmsItems->where('section', SectionEnum::SERVICE)->first();
      $services = $cmsItems->where('section', SectionEnum::SERVICES)->values();
      $why_us  = $cmsItems->where('section', SectionEnum::EXAMPLE)->first();
      $why_uses = $cmsItems->where('section', SectionEnum::EXAMPLES)->values();

      // fetch settings (assuming single row)
      $footer = Setting::first();

      // Helper function to clean null fields
      $clean = function ($item) {
         if (!$item) return null;
         return collect($item->toArray())
            ->filter(fn($value) => !is_null($value))
            ->all();
      };

      if ($intro) {
         $data['intro'] = $clean($intro);
      }
      if ($service) {
         $data['service'] = $clean($service);
      }

      if ($services->isNotEmpty()) {
         $data['services'] = $services->map(fn($s) => $clean($s));
      }

      if ($why_us) {
         $data['example'] = $clean($why_us);
      }

      if ($why_uses->isNotEmpty()) {
         $data['examples'] = $why_uses->map(fn($ex) => $clean($ex));
      }
      if ($footer) {
         $data['footer'] = $clean($footer); // return footer as object
      }

      return Helper::jsonResponse(true, 'Home Page', 200, $data);
   }
}
