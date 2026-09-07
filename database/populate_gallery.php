<?php
require_once __DIR__ . '/../includes/helpers.php';

// Additional gallery items to add
$additionalItems = [
    [
        'title' => 'OLYMPIC LIFTING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIoK7FXO2SdI-kb9FFOiEE_rRBhQqB91y3XmFFJvhyugZgIJL4jYIcCebvASDIxRR-f_1eq6Jp3Emxzozz8e0-IBY2XHRHiLExU3qjmEepBMIPIp5iBG3Ve3b3v01S_S4b0ewzG3am6IMDfxo4EwkwsLDbKzadT62_m51T0o1LTG3shWCH9s3EtrzqIRHfoHg81wibvyJL0n3BamRc28cLkCJblX7qEMDeyv4BBB-eeVk5mQSzA68FuA',
        'video_url' => '',
        'category' => 'WORKOUT',
        'aspect_ratio' => '3/4',
        'is_video' => false,
        'description' => 'Advanced Olympic lifting techniques for explosive power development.',
        'featured' => false,
        'sort_order' => 7
    ],
    [
        'title' => 'MOBILITY TRAINING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgmPNyw1yP9JywI_c-hpWse7A1RjtHbc6_Gb9qBWkJmTRDIzhzPY6TvMTXOEQoraYuDS6yE7AnHVXn9Gu9b2iZOWEfZV_RT5a8GVUequCg9E2y1SiE5MpJO5tbnNekcHFRk_5ykBTTjEN7kiJ1GetBbF3ELHbygP7j3ndGMCLe6IAtnu-o82tmiqq9iipkfhpTt16I02Zo9GM7r80sGTmBL_mwSSUqLSMZZmrftvEuFRfJ7kjS0JBoOA',
        'video_url' => '',
        'category' => 'GYM',
        'aspect_ratio' => '1/1',
        'is_video' => false,
        'description' => 'Mobility and flexibility training sessions for injury prevention.',
        'featured' => false,
        'sort_order' => 8
    ],
    [
        'title' => 'TEAM TRAINING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFitCqs_du8jexcKjOIBu0k6IzN39u8vyjRvapgaONQ73dfreZI_4EmPASIRUDVrO72vNF_Xjd_qMONNXvsjewcmie6hPyKqLfB9UjJnMr_jjsMT9hcTZjoQMnKgWVFXYHNygpMwWhJLPRdf5dawNlV5HaGFTDLiu9oRxn4KxrXJB4R8zIkHv4bi4sbnzL6FCJYCvu_Ci2En4vHttxEtg5qjsukFvJAvWyaHlYNBaX9nGZgp-DNo482g',
        'video_url' => '',
        'category' => 'LIFESTYLE',
        'aspect_ratio' => '16/9',
        'is_video' => false,
        'description' => 'Group training sessions building community and competition.',
        'featured' => false,
        'sort_order' => 9
    ],
    [
        'title' => 'NUTRITION PLANNING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBpHjT9cCJ2fvKLUtbiC01OjZjOkI3GB0fuCLtnzqjGcSSxYVYOzwmNa370uwF2wykSz_FdJ5ObINMJdOsCES7NSwylotNKbykb6716Vcf-q02DAPb2IBHVIjwYxNB1Gp_PAumyitGdx6syD7UhnopyA09zenY1Qs0CJyvWMCauZzKQoreLe04oN2jQWzX8XupbW29OTaRAg2w1FXELVhyXXlW8wK-MlE5hEINJGZ3CQVNquKe_r_2iiQ',
        'video_url' => '',
        'category' => 'WORKOUT',
        'aspect_ratio' => '1/1',
        'is_video' => false,
        'description' => 'Personalized nutrition planning for optimal performance.',
        'featured' => false,
        'sort_order' => 10
    ],
    [
        'title' => 'MINDSET COACHING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBwo1Z8Jsj7SWp4Lm64wE2Ue4efLtsJCa9Ia-4jBcrw8NKh91xZ7VAZgzO3Yrh6xMpq-UQMEf4V6EaCCj4n6pEeqvJNVsI5N5wgDvm2mESBv80YWPLJfhF2IxAc5WM2VIJ_lc0evvQHThBAYg34zQMgNEnIkLiUe1S_9XtaHvJtzeqyit6Fo2_fxvRzHPfyWF6w8pAQVA2-e4XIjMmhs5NJtmJasjujzZa_VuzzHc-AFO_S5BYTAVTABQ',
        'video_url' => '',
        'category' => 'LIFESTYLE',
        'aspect_ratio' => '3/4',
        'is_video' => false,
        'description' => 'Mental conditioning and mindset coaching for elite athletes.',
        'featured' => false,
        'sort_order' => 11
    ],
    [
        'title' => 'PERFORMANCE TESTING',
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKv-vZATyk9DxRFj5VNwJRer9AFhTiI3pg2naqZ07_e_yzeZzYEuTtRt2Blm66tx1Cie1php8CBg2nyHrAzys6CCxw7Asn3bxevwBR2XriJ_c5GmC1LbdqW8jFyBKEZGbjCDLP0uBe3-HhmwFhEn5hf7TP5NGc1jCOGEA1m8FWONOno6Z1euNW1HDBexAKXLE6X06wfIZBfp33jSfvP2LUuEVLJ-i-WmLgA1RwtBMEqsguI1a42bET7A',
        'video_url' => '',
        'category' => 'GYM',
        'aspect_ratio' => '16/9',
        'is_video' => false,
        'description' => 'Comprehensive performance testing and analysis.',
        'featured' => false,
        'sort_order' => 12
    ]
];

foreach ($additionalItems as $item) {
    $result = db_insert('gallery', $item);
    if ($result) {
        echo "Added: " . $item['title'] . "\n";
    } else {
        echo "Failed to add: " . $item['title'] . "\n";
    }
}

echo "\nGallery population complete!\n";
