<?php
require_once __DIR__ . '/../includes/helpers.php';

// Additional blog posts to add
$additionalPosts = [
    [
        'title' => 'The Science of Hypertrophy: Muscle Growth Explained',
        'slug' => 'science-of-hypertrophy-muscle-growth-explained',
        'excerpt' => 'Understanding the cellular mechanisms behind muscle growth can help you optimize your training for maximum gains.',
        'content' => 'Hypertrophy is the process of muscle fiber growth that occurs when the rate of muscle protein synthesis exceeds muscle protein breakdown. This comprehensive guide explores the science behind muscle growth, including mechanical tension, metabolic stress, and muscle damage.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVniC35zxG3Phyn4P9Q0BbLkcXx3nqv96or_l-NYxvjjSGPk3i2RnSgAOmaxeHPKmLuFiJXFLGpqVwfHSMhOXYumzP_IGjq9R60l1Y2qqjOQ0rBK32hlulz6PC_XS7mD7ILajQ_JyDQxIG4QdBQqUytXGLUV6JoQQ4NtlyjjFNNfUrCm3InqTm2fdQWjrMxCV9SbSwOVbDrTEGAQaSsfIblpsL8xUIgeQtoxxPNFvaF2mWDkscNOXq1w',
        'category' => 'TRAINING',
        'author' => 'Marcus Vance',
        'date' => '2024-10-15',
        'read_time' => '10 min read',
        'meta_title' => 'The Science of Hypertrophy: Muscle Growth Explained',
        'meta_description' => 'Understanding the cellular mechanisms behind muscle growth can help you optimize your training for maximum gains.',
        'featured' => true,
        'status' => 'published',
        'sort_order' => 1
    ],
    [
        'title' => 'Intermittent Fasting for Athletes: Pros and Cons',
        'slug' => 'intermittent-fasting-for-athletes-pros-and-cons',
        'excerpt' => 'Explore whether intermittent fasting can enhance athletic performance or if traditional meal timing is superior for athletes.',
        'content' => 'Intermittent fasting has gained popularity among athletes looking to optimize body composition and performance. This article examines the research behind IF protocols, their effects on muscle protein synthesis, and practical implementation strategies for competitive athletes.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2nPJyuCgCDiUzG-fEWouk6WEGXeAjgqReczb5f9AKHjIC__n7XKKcG2897yMUUUBCf3JVPndg-MpLwxZEzc8kNz8fYgZ4T70Wv9e2AGRyc7500CwLXrqbeaq6UjM4qj0YNeg32mLj2dEjvv-Nw1e1uVl2yzxb1MGbdq6FAi4uGkUHQCLvLp7sT_peykL77PPvbTNeqMow2oanK86kImcsEc4C-ZC6j8up-QYMN22gYkqGxdWN8FtTgQ',
        'category' => 'NUTRITION',
        'author' => 'Marcus Vance',
        'date' => '2024-10-10',
        'read_time' => '7 min read',
        'meta_title' => 'Intermittent Fasting for Athletes: Pros and Cons',
        'meta_description' => 'Explore whether intermittent fasting can enhance athletic performance or if traditional meal timing is superior for athletes.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 2
    ],
    [
        'title' => 'Mental Toughness Training for Elite Performance',
        'slug' => 'mental-toughness-training-for-elite-performance',
        'excerpt' => 'Develop the psychological edge that separates good athletes from great ones through proven mental conditioning techniques.',
        'content' => 'Mental toughness is the ability to consistently perform toward the upper range of your talent and skill regardless of competitive circumstances. This guide covers visualization techniques, pressure training, and cognitive strategies used by Olympic athletes and special forces operators.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDsRx8EPdKzUMJK-gOH-ExXMa7kjrzw3biypspA68drB4dPipPTKCwgI2vreldJRei9LU2bOiDaTQJvz_s4AMrT6Uw__fkc8YUHtWswjw18PefUbH2NjDDyhJ0HB6P8r4g8VjWJ78ZAd-Zu0mVQ6-n1CD3xq1aqU8eONMbSnqHV-T0obQb6ECYTDkbC_PZ5m3GgHOh_2fCVQLWxteYPROS7Fb7UkQ5YCxuNr5L2JheGvUIEH2PHKl5iHg',
        'category' => 'LIFESTYLE',
        'author' => 'Marcus Vance',
        'date' => '2024-10-08',
        'read_time' => '12 min read',
        'meta_title' => 'Mental Toughness Training for Elite Performance',
        'meta_description' => 'Develop the psychological edge that separates good athletes from great ones through proven mental conditioning techniques.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 3
    ],
    [
        'title' => 'Building Explosive Power: Olympic Lifting Basics',
        'slug' => 'building-explosive-power-olympic-lifting-basics',
        'excerpt' => 'Master the fundamentals of the clean and jerk and snatch to develop athletic power that transfers to your sport.',
        'content' => 'Olympic lifting develops explosive power, coordination, and full-body strength that transfers directly to athletic performance. Learn the proper progressions for the clean, jerk, and snatch, along with common mistakes to avoid and accessory exercises to support your main lifts.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm2AQlA5I8caVzsENMA_Qv5Sivm_p1LqcgcJsEtrJjagaJzn0Ec06b_ncobY3y6C8wKOSfmuKCdM-Fra1Ky81kAAwPsYK55C4RUWwafrb5ttT4wRW7RxvJ0Ii31jQbCTlL9MWw7EQV9c5LlJ2KzwI-cIdz-nZ-1vmM8tumHE56o_dKcsCvNJfYelhJsqv_eCiJWXNwYr-jJBpByHtU30-dgQo4yagTNpU-iCfcbIRt3Ka79j_k7fcxqw',
        'category' => 'TRAINING',
        'author' => 'Marcus Vance',
        'date' => '2024-10-05',
        'read_time' => '15 min read',
        'meta_title' => 'Building Explosive Power: Olympic Lifting Basics',
        'meta_description' => 'Master the fundamentals of the clean and jerk and snatch to develop athletic power that transfers to your sport.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 4
    ],
    [
        'title' => 'Recovery Protocols That Actually Work',
        'slug' => 'recovery-protocols-that-actually-work',
        'excerpt' => 'Cut through the recovery myths and focus on evidence-based methods that enhance muscle repair and reduce injury risk.',
        'content' => 'Recovery is where adaptation happens. This article examines sleep optimization, nutrition timing, active recovery techniques, and emerging modalities like cryotherapy and compression therapy. Learn how to create a personalized recovery protocol based on your training volume and goals.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVniC35zxG3Phyn4P9Q0BbLkcXx3nqv96or_l-NYxvjjSGPk3i2RnSgAOmaxeHPKmLuFiJXFLGpqVwfHSMhOXYumzP_IGjq9R60l1Y2qqjOQ0rBK32hlulz6PC_XS7mD7ILajQ_JyDQxIG4QdBQqUytXGLUV6JoQQ4NtlyjjFNNfUrCm3InqTm2fdQWjrMxCV9SbSwOVbDrTEGAQaSsfIblpsL8xUIgeQtoxxPNFvaF2mWDkscNOXq1w',
        'category' => 'LIFESTYLE',
        'author' => 'Marcus Vance',
        'date' => '2024-10-01',
        'read_time' => '9 min read',
        'meta_title' => 'Recovery Protocols That Actually Work',
        'meta_description' => 'Cut through the recovery myths and focus on evidence-based methods that enhance muscle repair and reduce injury risk.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 5
    ],
    [
        'title' => 'Carb Cycling: Strategic Carbohydrate Manipulation',
        'slug' => 'carb-cycling-strategic-carbohydrate-manipulation',
        'excerpt' => 'Learn how to strategically manipulate carbohydrate intake to optimize body composition while maintaining training performance.',
        'content' => 'Carb cycling involves alternating between high, moderate, and low carbohydrate days to manipulate insulin levels and optimize energy availability. This guide explains the science behind carb cycling, how to structure your macro cycles around training, and who this approach is best suited for.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2nPJyuCgCDiUzG-fEWouk6WEGXeAjgqReczb5f9AKHjIC__n7XKKcG2897yMUUUBCf3JVPndg-MpLwxZEzc8kNz8fYgZ4T70Wv9e2AGRyc7500CwLXrqbeaq6UjM4qj0YNeg32mLj2dEjvv-Nw1e1uVl2yzxb1MGbdq6FAi4uGkUHQCLvLp7sT_peykL77PPvbTNeqMow2oanK86kImcsEc4C-ZC6j8up-QYMN22gYkqGxdWN8FtTgQ',
        'category' => 'NUTRITION',
        'author' => 'Marcus Vance',
        'date' => '2024-09-28',
        'read_time' => '8 min read',
        'meta_title' => 'Carb Cycling: Strategic Carbohydrate Manipulation',
        'meta_description' => 'Learn how to strategically manipulate carbohydrate intake to optimize body composition while maintaining training performance.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 6
    ],
    [
        'title' => 'Velocity-Based Training: The Future of Strength',
        'slug' => 'velocity-based-training-future-of-strength',
        'excerpt' => 'Discover how measuring bar speed can revolutionize your training by providing objective feedback on daily readiness.',
        'content' => 'Velocity-based training uses devices to measure bar speed and provides immediate feedback on power output. This allows for auto-regulation based on daily readiness, optimal load selection, and fatigue management. Learn the fundamentals of VBT and how to implement it in your training program.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDsRx8EPdKzUMJK-gOH-ExXMa7kjrzw3biypspA68drB4dPipPTKCwgI2vreldJRei9LU2bOiDaTQJvz_s4AMrT6Uw__fkc8YUHtWswjw18PefUbH2NjDDyhJ0HB6P8r4g8VjWJ78ZAd-Zu0mVQ6-n1CD3xq1aqU8eONMbSnqHV-T0obQb6ECYTDkbC_PZ5m3GgHOh_2fCVQLWxteYPROS7Fb7UkQ5YCxuNr5L2JheGvUIEH2PHKl5iHg',
        'category' => 'TRAINING',
        'author' => 'Marcus Vance',
        'date' => '2024-09-25',
        'read_time' => '11 min read',
        'meta_title' => 'Velocity-Based Training: The Future of Strength',
        'meta_description' => 'Discover how measuring bar speed can revolutionize your training by providing objective feedback on daily readiness.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 7
    ],
    [
        'title' => 'Sleep Optimization for Maximum Recovery',
        'slug' => 'sleep-optimization-maximum-recovery',
        'excerpt' => 'Quality sleep is the most underrated performance enhancer. Learn how to optimize your sleep for better recovery and performance.',
        'content' => 'Sleep is when the body repairs tissues, consolidates memory, and releases essential hormones. This comprehensive guide covers sleep architecture, the impact of sleep deprivation on athletic performance, and practical strategies to improve sleep quality including environment optimization, supplementation, and sleep scheduling.',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm2AQlA5I8caVzsENMA_Qv5Sivm_p1LqcgcJsEtrJjagaJzn0Ec06b_ncobY3y6C8wKOSfmuKCdM-Fra1Ky81kAAwPsYK55C4RUWwafrb5ttT4wRW7RxvJ0Ii31jQbCTlL9MWw7EQV9c5LlJ2KzwI-cIdz-nZ-1vmM8tumHE56o_dKcsCvNJfYelhJsqv_eCiJWXNwYr-jJBpByHtU30-dgQo4yagTNpU-iCfcbIRt3Ka79j_k7fcxqw',
        'category' => 'LIFESTYLE',
        'author' => 'Marcus Vance',
        'date' => '2024-09-20',
        'read_time' => '13 min read',
        'meta_title' => 'Sleep Optimization for Maximum Recovery',
        'meta_description' => 'Quality sleep is the most underrated performance enhancer. Learn how to optimize your sleep for better recovery and performance.',
        'featured' => false,
        'status' => 'published',
        'sort_order' => 8
    ]
];

foreach ($additionalPosts as $post) {
    // Check if post already exists by slug
    $existing = db_fetch_one('SELECT id FROM blog_posts WHERE slug = :slug', ['slug' => $post['slug']]);
    
    if (!$existing) {
        $result = db_insert('blog_posts', $post);
        if ($result) {
            echo "Added blog post: " . $post['title'] . "\n";
        } else {
            echo "Failed to add blog post: " . $post['title'] . "\n";
        }
    } else {
        echo "Blog post already exists: " . $post['title'] . "\n";
    }
}

echo "\nBlog population complete!\n";
