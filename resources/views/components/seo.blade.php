@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'noindex' => false
])

@php
    $siteTitle = config('app.name', 'Unlimited Plug');
    $finalTitle = $title ? $title . ' - ' . $siteTitle : $siteTitle;
    $finalDescription = $description ?? 'Browse and purchase premium digital products, software, templates, and more on Unlimited Plug marketplace.';
    $finalUrl = $url ?? request()->url();
    $finalImage = $image ?? asset('images/og-default.jpg');
@endphp

<!-- Basic Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="author" content="{{ $siteTitle }}">
<link rel="canonical" href="{{ $finalUrl }}">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $finalUrl }}">
<meta property="og:image" content="{{ $finalImage }}">
<meta property="og:site_name" content="{{ $siteTitle }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">
<meta name="twitter:image" content="{{ $finalImage }}">

<!-- Robots Meta Tag -->
@if($noindex)
<meta name="robots" content="noindex, nofollow">
@else
<meta name="robots" content="index, follow">
@endif