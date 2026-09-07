<?php
require_once __DIR__ . '/../includes/helpers.php';

// Additional testimonials to add
$additionalTestimonials = [
    [
        'author_name' => 'JAMES M.',
        'author_role' => 'Corporate Executive',
        'author_location' => 'New York, NY',
        'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGYelBuZHvS7qQz-l9_y7DMdpY0ccwq8fzug6Cz5Kkjyol7YfR21XHUJ9pigbUwxwzzWNdScmFlz-fZ2eebVaMOAVW9jiHjwnaZGs0cL76trA_ju-ZtAtTa-XtWO75DHfhWgVwy4TBIU06J-f4Bjycsv4mYg3JgqezV6xwz_oK7GjR704_LuX13UpHE3L_8Ttqg9DbJ0Vc__zfBhMUwq02Xmu4q4s4Per7SfImVTqKbIjF57CAwQAq-Q',
        'quote' => 'The efficiency of the training protocols is unmatched. In 12 weeks, I achieved more than in years of traditional gym memberships. The ROI on time invested is incredible.',
        'rating' => 5,
        'featured' => false,
        'with_image' => false,
        'side_image' => '',
        'span_cols' => 1,
        'sort_order' => 4
    ],
    [
        'author_name' => 'MICHELLE T.',
        'author_role' => 'Physical Therapist',
        'author_location' => 'Miami, FL',
        'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCV7HdtmbBJH2YNZ5WB9GqQSKppcu9qjoAKJ3_NWBsyoUf3vdPYU9YiulTozdHKRnLJ-CuxSmT0ITZc3wR3L6A7Dn5rjo-3xLVS_2UYYboTBuJ7AM6YbVFHE2CfgqDlkKDcov6ynCQtqDsnxPNeVQfLzDUzpVQ4NaKoCb9SWNw4i9knpirIKAFqACYS16ZSNF3BccKp06g1ofoqAyYVXlEQzUK5ntzhsqcnjQ6zj5Qnc2t-3lxOvS9zWA',
        'quote' => 'From a clinical perspective, the biomechanics are sound. As a patient, the results speak for themselves. My chronic back pain is gone, and I\'m stronger than ever.',
        'rating' => 5,
        'featured' => false,
        'with_image' => false,
        'side_image' => '',
        'span_cols' => 1,
        'sort_order' => 5
    ],
    [
        'author_name' => 'ALEXANDER K.',
        'author_role' => 'Tech Entrepreneur',
        'author_location' => 'San Francisco, CA',
        'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB_tBEP5sCbCHtAICB3nmfbt8RS9-qbAKqxZKrCNOC5fOMS8RRFTPwpBQGEH7aAcujz3BuVMyGvJze9zpb4zFb2ssb4e3iWxTzw6lPvwc8Eramru6SrDM6DsA6wA2L-uYnEByY0wK05fsWYNmkYWFtxrAn_w7V98ozwQlRTAqG-Eb1bTP053w0oTTMzV8OdqxcClupcbI_71eG2y4rt_KGAm-71_OZYuI679yYg_9h-XUp3yb2usJBrMg',
        'quote' => 'The data-driven approach resonates with my analytical mindset. Every session is measurable, trackable, and optimized. This is what peak performance looks like in the digital age.',
        'rating' => 4,
        'featured' => false,
        'with_image' => false,
        'side_image' => '',
        'span_cols' => 1,
        'sort_order' => 6
    ],
    [
        'author_name' => 'SOPHIA L.',
        'author_role' => 'Fitness Competitor',
        'author_location' => 'Austin, TX',
        'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCSXCJK5ZiC7TrE6fZ1tgS2u_8SU3LAO9c7fF7IKLybfFucN5cyhsSW5pu4ifB-OMM278mZloUGl9EKYOnq8qxcMakyjJsJMp1SmH2w4MGgTEwwV5r6qrg7eAEahqAL4jZ6PvbIaMs8dygS-ziMt4JLH7CSP0c-ptU7fCZlBGIs0uEPwBXSKNnMa2YY1SHevkeW-sBqSs3LcYLvR5NU5Av4vvQXQebe9ISowRrvklx6bvkb9GxCCGPItQ',
        'quote' => 'The conditioning protocols transformed my competition prep. I stepped on stage at my absolute peak, with confidence that came from knowing every rep had purpose.',
        'rating' => 5,
        'featured' => true,
        'with_image' => true,
        'side_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCSXCJK5ZiC7TrE6fZ1tgS2u_8SU3LAO9c7fF7IKLybfFucN5cyhsSW5pu4ifB-OMM278mZloUGl9EKYOnq8qxcMakyjJsJMp1SmH2w4MGgTEwwV5r6qrg7eAEahqAL4jZ6PvbIaMs8dygS-ziMt4JLH7CSP0c-ptU7fCZlBGIs0uEPwBXSKNnMa2YY1SHevkeW-sBqSs3LcYLvR5NU5Av4vvQXQebe9ISowRrvklx6bvkb9GxCCGPItQ',
        'span_cols' => 2,
        'sort_order' => 7
    ]
];

foreach ($additionalTestimonials as $testimonial) {
    $result = db_insert('testimonials', $testimonial);
    if ($result) {
        echo "Added testimonial: " . $testimonial['author_name'] . "\n";
    } else {
        echo "Failed to add testimonial: " . $testimonial['author_name'] . "\n";
    }
}

echo "\nTestimonials population complete!\n";
