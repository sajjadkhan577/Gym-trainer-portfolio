<?php 
$pageTitle = 'Services'; 
$pageDescription = 'Explore our comprehensive fitness services including personal training, group coaching, performance assessments, and nutrition planning designed to help you achieve elite results.';
require __DIR__ . '/includes/head.php';

// Load services dynamically from database
$services = db_select('services', '*', 'status = :status', ['status' => 'active'], 'sort_order ASC');

require __DIR__ . '/includes/navigation.php'; 
?>
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-16">
<header class="mb-16 text-center md:text-left">
<h1 class="font-display-xl text-display-xl text-on-surface mb-4">ELITE SERVICES</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Precision-engineered programs designed to break plateaus and redefine your limits. Choose your path to performance.</p>
</header>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
<?php if ($services && count($services) > 0): ?>
    <?php foreach ($services as $service): ?>
        <?php
        $serviceData = [
            'image' => $service['image'],
            'title' => $service['title'],
            'description' => $service['description'],
            'features' => json_decode($service['features'], true) ?: [],
            'benefits' => json_decode($service['benefits'], true) ?: [],
            'duration' => $service['duration'],
            'price' => $service['price'],
            'buttonText' => 'Book Now',
            'buttonLink' => 'book.php?service=' . $service['id']
        ];
        extract($serviceData);
        require __DIR__ . '/includes/components/service_card_grid.php';
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    // Fallback to static data if database is empty
    $services = [
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC_0q0mXYyvQV_ux_84KC4pHxFyX7seYVB5g1oeay-n8nfkdyGw9wgOuYXyTPpuc9uVgdxEXCUi6xxyYwx0TnzQHnaozWis1tfRofXAWGPqJ_dU55o1vpRMkjNDp6N-NSlhBE9tLv-W21Jnm3ZF2DG7C6zRcdDzfz0hGIlcaCrj-iBPbxQJ8Rr7RijTL05qZp5-8SOpPNmixgI_8NF_KIrgvknpxSHmU8AqXF2PEQykirMpdTRj3zuCLA',
            'title' => 'Personal Training',
            'description' => '1-on-1 intensive sessions tailored to your specific biomechanics and goals. Maximum accountability.',
            'features' => ['Custom programming', 'Form correction', 'Weekly check-ins'],
            'benefits' => ['Personalized attention', 'Maximum accountability', 'Tailored biomechanics'],
            'duration' => '60 min',
            'price' => 200.00,
            'buttonText' => 'Book Now',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC3NVBcP2-Ts7YBaHcBWs3aQwXSEMgtxeOMLHkHIfxqRjeG1msZts9fk8EHhBBOvsH7axjr7Ky0iYm7tPXwFE1SbwgDOCcQykCxp1D9uzTyEGHxqaYv7OyQNf4ieL3bXD58LUB4BQksUJPx6TUw9uklg6_wZTKzbwLFtnKnwzhTVDhtHjaVJk6KV5bKc_c6BD28Jypl2efZ8njSoSiyj3qAfrFY-PpMFj6l-l5MeABF2qnb2ZT25pndcg',
            'title' => 'Online Coaching',
            'description' => 'Elite programming delivered directly to your device. Train anywhere, anytime with expert guidance.',
            'features' => ['App access', 'Video feedback', '24/7 support'],
            'benefits' => ['Train anywhere', 'Flexible schedule', 'Expert guidance remotely'],
            'duration' => '30 min',
            'price' => 100.00,
            'buttonText' => 'Book Now',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQC7NYrDDSXm-dkviFJ37ZLSSuGFk__69dHIih3qVl7puKy7fImIm9pNDvIx03p1AxV3Q6hantiRAe5VxHthKTiljrqLA5ZTxtSoY0PWV4pNrmPHYD3JPFneOWq86vr_DSeJcbvnvAn090CovqblSeQ_TDd2SaGIav4MMNML5Oi3dDHolrT_fuG1o-_ISOeMfMUiwIXZUgeA1VFIW75-KPiom-I-XvbFT1MqP9oTP2qrAmOhg-N14Kwg',
            'title' => 'Weight Loss',
            'description' => 'Science-backed protocols to shred fat while preserving lean muscle mass. Sustainable, intense results.',
            'features' => ['Metabolic conditioning', 'Macronutrient planning', 'Progress tracking'],
            'benefits' => ['Fat loss', 'Muscle preservation', 'Sustainable results'],
            'duration' => '60 min',
            'price' => 180.00,
            'buttonText' => 'Book Now',
            'buttonLink' => 'book.php'
        ],
        [
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA4AkYCCLF0z6qOb3802EaR-_LbpemK6Dm_AAWdNmvHm_5vslNUIzsnbtUkl-muAIBhUZU5IDYvDT8Dj8LjMXYSkV_mSmalVV0QKfewyXyo7cEvAL0gdiY40iUViwl5bScyW6ZQjV83sRt_qQyrYqNQMNxJwPQLhgQVEio3aYtW4EW_2-ROycOrsbCFqagzsZPGtQ3DdfgBLsn_Xa2BVwrqKv477-EuGRuNPDQlrDJt1neHL09qQTJtPA',
            'title' => 'Muscle Building',
            'description' => 'Hypertrophy-focused cycles designed to pack on dense, functional muscle tissue through progressive overload.',
            'features' => ['Periodized cycles', 'Volume management', 'Recovery protocols'],
            'benefits' => ['Muscle growth', 'Functional strength', 'Progressive overload'],
            'duration' => '60 min',
            'price' => 160.00,
            'buttonText' => 'Book Now',
            'buttonLink' => 'book.php'
        ]
    ];
    foreach ($services as $s) {
        extract($s);
        require __DIR__ . '/includes/components/service_card_grid.php';
    }
    ?>
<?php endif; ?>
</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
