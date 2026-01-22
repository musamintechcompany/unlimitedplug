@php
    $siteTitle = config('app.name', 'Unlimited Plug');
    $finalTitle = isset($title) && $title ? $title . ' - ' . $siteTitle : $siteTitle;
    $finalDescription = $description ?? 'Discover and purchase premium digital products, physical goods, services, and more on Unlimited Plug - Your trusted marketplace for quality products.';
    $finalUrl = $url ?? request()->url();
    $finalImage = $image ?? asset('images/og-default.jpg');
    $type = $type ?? 'website';
    $noindex = $noindex ?? false;
    $logoUrl = asset('images/logos/logo1.png');
    $appUrl = config('app.url');
@endphp

<!-- Basic Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
@if(isset($keywords) && $keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="author" content="{{ $siteTitle }}">
<link rel="canonical" href="{{ $finalUrl }}">

<!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $finalUrl }}">
<meta property="og:image" content="{{ $finalImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ $siteTitle }}">
<meta property="og:locale" content="en_US">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">
<meta name="twitter:image" content="{{ $finalImage }}">
<meta name="twitter:site" content="@unlimitedplug">
<meta name="twitter:creator" content="@unlimitedplug">

<!-- Robots & Crawling -->
@if($noindex)
<meta name="robots" content="noindex, nofollow">
@else
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endif
<meta name="googlebot" content="index, follow">
<meta name="bingbot" content="index, follow">
