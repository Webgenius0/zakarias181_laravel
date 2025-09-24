<?php

namespace App\Enums;

enum SectionEnum: string
{
    
    const BG = 'bg_image';

    case EXAMPLE = 'example';
    case EXAMPLES = 'examples';

    case INTRO = 'intro';
    case BANNER = 'banner';

    case ABOUT = 'about';

    //common sections
    case FOOTER = 'footer';
    case HEADER = 'header';

    // how it works sections
    case HEROBANNER = 'hero';

    case SIMPLESELLING = 'simple-selling';
    case SIMPLESELLINGS = 'simple-sellings';

    case SERVICE = 'service';
    case SERVICES = 'services';

    case BLOGBANNER = 'blog-banner';

    case HOWITWORK = 'how-it-work';
    case HOWITWORKS = 'how-it-works';

}
