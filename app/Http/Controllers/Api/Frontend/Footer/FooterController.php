<?php

namespace App\Http\Controllers\Api\Frontend\Footer;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SocialLink;

class FooterController extends Controller
{
  public function index()
{
    $settings = Setting::first();
    $socialLinks = SocialLink::where('status', 'active')->get(); 

    return response()->json([
        'data' => [
            [
                'settings' => $settings,
                'social_link' => $socialLinks,
            ]
        ]
    ]);
}


}
