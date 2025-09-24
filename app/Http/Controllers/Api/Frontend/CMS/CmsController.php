<?php

namespace App\Http\Controllers\Api\Frontend\CMS;

use App\Models\CMS;
use App\Enums\PageEnum;
use App\Helpers\Helper;
use App\Enums\SectionEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CmsController extends Controller
{
   public function index()
   {
      $data = [];

      $cmsItems = CMS::query()
         ->where('page', PageEnum::HOME)
         ->where('status', 'active')
         ->whereIn('section', [SectionEnum::INTRO,  SectionEnum::BANNER])
         ->get();

      $data['home_intro']       = $cmsItems->where('section', SectionEnum::INTRO)->first();
      $data['home_banner']      = $cmsItems->where('section', SectionEnum::BANNER)->first();

      return Helper::jsonResponse(true, 'Home Page', 200, $data);
   }
}
