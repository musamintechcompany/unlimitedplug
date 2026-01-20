<?php

return [
    'types' => [
        'digital' => [
            'name' => 'Digital Product',
            'description' => 'Sell your knowledge and creativity effortlessly with digital products from ebooks, templates, printables, and more that customers can access instantly.',
            'enabled' => true,
            'badge' => null,
            'badge_color' => null,
        ],
        'ticket' => [
            'name' => 'Ticket & Masterclass',
            'description' => 'Sell tickets for events, webinars, and masterclasses, workshops, and more while seamlessly managing access for online, hybrid, and in-person attendees.',
            'enabled' => false,
            'badge' => 'With improved features',
            'badge_color' => 'yellow',
        ],
        'service' => [
            'name' => 'Service',
            'description' => 'Sell your services as a product, including coaching, consulting, design, counseling, and more, and get paid for your expertise.',
            'enabled' => false,
            'badge' => null,
            'badge_color' => null,
        ],
        'subscription' => [
            'name' => 'Subscription',
            'description' => 'Sell recurring payment subscriptions for your product or service and easily manage customer access.',
            'enabled' => false,
            'badge' => null,
            'badge_color' => null,
        ],
        'course' => [
            'name' => 'Course (Hosted on Selar)',
            'description' => 'Sell course products and turn your knowledge into income by delivering engaging video, audio, and interactive lessons that learners can watch or download after purchase.',
            'enabled' => false,
            'badge' => 'Pro',
            'badge_color' => 'red',
        ],
        'membership' => [
            'name' => 'Membership Course (Hosted on Selar)',
            'description' => 'Sell membership courses on Selar and give learners ongoing access to exclusive content. Access is lost when they cancel their subscription.',
            'enabled' => false,
            'badge' => 'BETA',
            'badge_color' => 'green',
        ],
        'stream' => [
            'name' => 'Stream online only video/audio',
            'description' => 'Sell a single video or audio file viewers can watch online while keeping your content secure and non-downloadable. Great for replays, webinar recordings, movies, podcasts, and more.',
            'enabled' => false,
            'badge' => 'Pro',
            'badge_color' => 'red',
        ],
        'flexible_subscription' => [
            'name' => 'Flexible Subscription',
            'description' => 'Sell flexible payment plans for high-ticket products, allowing customers to choose first payment amount while the remaining balance is split into installments.',
            'enabled' => false,
            'badge' => 'Pro',
            'badge_color' => 'red',
        ],
        'physical' => [
            'name' => 'Physical Product',
            'description' => 'Sell physical products like fashion, beauty items, accessories, and more. Collect customer shipping details in one place and handle delivery your way.',
            'enabled' => false,
            'badge' => null,
            'badge_color' => null,
        ],
    ],

    'badges' => [
        'NEW',
        'HOT',
        'BESTSELLER',
        'POPULAR',
        'TRENDING',
        'PREMIUM',
    ],
];
