-- Database Schema for Apex Coaching Portfolio

-- Coach Information Table
CREATE TABLE IF NOT EXISTS coach_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    bio TEXT NOT NULL,
    tagline VARCHAR(255),
    profile_image VARCHAR(500),
    featured_image VARCHAR(500),
    experience_years INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(500),
    icon VARCHAR(100),
    features JSON,
    benefits JSON,
    price DECIMAL(10,2),
    duration VARCHAR(100),
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Programs Table
CREATE TABLE IF NOT EXISTS programs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(500),
    level VARCHAR(100),
    level_color VARCHAR(100),
    duration VARCHAR(100),
    tags JSON,
    features JSON,
    price DECIMAL(10,2),
    featured BOOLEAN DEFAULT FALSE,
    col_span INT DEFAULT 1,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Transformations Table
CREATE TABLE IF NOT EXISTS transformations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(255) NOT NULL,
    before_image VARCHAR(500) NOT NULL,
    after_image VARCHAR(500) NOT NULL,
    before_label VARCHAR(100) DEFAULT 'Before',
    after_label VARCHAR(100) DEFAULT 'After',
    stats JSON,
    quote TEXT,
    goal VARCHAR(255),
    weight_lost VARCHAR(100),
    story TEXT,
    duration VARCHAR(100),
    category VARCHAR(100) DEFAULT 'general',
    featured BOOLEAN DEFAULT FALSE,
    span INT DEFAULT 6,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author_name VARCHAR(255) NOT NULL,
    author_role VARCHAR(255),
    author_location VARCHAR(255),
    author_image VARCHAR(500),
    quote TEXT NOT NULL,
    rating INT DEFAULT 5,
    featured BOOLEAN DEFAULT FALSE,
    with_image BOOLEAN DEFAULT FALSE,
    side_image VARCHAR(500),
    span_cols INT DEFAULT 1,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog Posts Table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    image VARCHAR(500),
    category VARCHAR(100),
    author VARCHAR(255),
    date DATE,
    read_time VARCHAR(50),
    meta_title VARCHAR(255),
    meta_description TEXT,
    views INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Statistics Table
CREATE TABLE IF NOT EXISTS statistics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    icon VARCHAR(100),
    description TEXT,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gallery Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    video_url VARCHAR(500),
    category VARCHAR(100) DEFAULT 'WORKOUT',
    aspect_ratio VARCHAR(20) DEFAULT '3/4',
    is_video BOOLEAN DEFAULT FALSE,
    description TEXT,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Newsletter Subscribers Table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    status ENUM('active', 'unsubscribed', 'bounced') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent TEXT
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    preferred_date DATE NOT NULL,
    preferred_time TIME NOT NULL,
    fitness_goal VARCHAR(100) NOT NULL,
    service_id INT,
    program_id INT,
    current_fitness_level VARCHAR(100),
    message TEXT,
    booking_status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
);

-- Insert Sample Data

-- Coach Info
INSERT INTO coach_info (name, title, bio, tagline, profile_image, featured_image, experience_years) VALUES
('Marcus Vance', 'Elite Performance Coach', 'I forge elite athletes and dedicated individuals into their absolute peak physical condition. My methodology is rooted in science, tested in the trenches, and refined over a decade of relentless pursuit of excellence. There are no shortcuts, only the work.', 'Discipline. Precision. Results.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWrcnfKCyrhyA6d08z21_ixeCrIDPFnWhUZlTtACSJqkzLPypE4fRU_gWhMJus93Y_BGYhbWGRLnAmi4uwa78jLmfg3JKFgkBodq6KztuWLV4Yo6-1-jVNhp5ZbvoxEQ8vtcwOb9eErMdaeXtM-RgJ1GqmmtnA_yatxzHDtz7DIipRSb6o0KNgYFYLJzGOZjxiXTPfFFInlq0mIz7Q3SfU_A_H4SzAH445AsWOZH0nGzFh7nRtLw0lAQ', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBBy2sxCDmXIZMiF5RqaSfuuGPx3GZexQEdcHBTxCDklQZGyjMmds5i95QhksA5NQXTi_bPGjJEpanAlNfh9Pm4mBVEoL8EwsMfpd0_VCMVCYSFoL3SvJWbPI7joifQ1tOAeivTBCohwNP5UjJaahcKMylTfZP3zYR-IW_Zue21gcx3PoKaNHXGHrqWTW54O3CCK0hl0TwEF287MPheL3aY2b6c6i1TAd1nnPwN2TERa7tSnmcURn8u0Q', 10);

-- Services
INSERT INTO services (title, description, image, icon, features, benefits, price, duration, featured, sort_order) VALUES
('Strength & Conditioning', 'Build raw power, functional strength, and explosive energy through periodized programming.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuC_DdF1wIdij7vDMTXvHPPbWYm8qn52duh7cehUwvpjb07qrYaBeX9e80kA454gTy_Rnh61mNQgVzlpHXkwTJxye3HXZxkKdHknRKbMBOjalOfwrV7AeUMY8yNdkyqV_tqA2Qn-zCvcYpX4k08DHW0AcaM5sAoebAifWTdB0Rm0ztp0nX4fk4sSbyd0_U_zVbzbAQmAlYNFJHpXKFsups1ksdoMH1bPeswSgEixyQ2jZZ-cqVFCwNPY1w', 'fitness_center', '["Custom programming", "Form correction", "Weekly check-ins"]', '["Increased power output", "Enhanced functional strength", "Improved explosive energy"]', 150.00, '60 min', TRUE, 1),
('Speed & Agility', 'Enhance acceleration, deceleration, and multi-directional movement.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDK3huKDgq0q16WjHl2GZbrzS_OCd4hj5Z8NumwQ8rFL9jZrQWBiXSSzlfRTbnh4BmOSfktGYMmoOgVx9CzH5Poi8feBiv-QVyebESyAKb_blUEr_IwWwoqtBAGvaLTTzao3ek8jZcu_YS7-VhUp6PWywZ-YhM23zWSZOkWN1Lc5IoDWIWTAo0dT6oCIVrUDYsKhQKgSeZk5Si-3kCnoBHgR16DYWMS27r9Wh1r0d-DFBjbHmtQDVdTKQ', 'sprint', '["Acceleration training", "Multi-directional drills", "Reaction time"]', '["Faster acceleration", "Better deceleration control", "Improved agility"]', 120.00, '45 min', TRUE, 2),
('Personal Training', '1-on-1 intensive sessions tailored to your specific biomechanics and goals. Maximum accountability.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuC_0q0mXYyvQV_ux_84KC4pHxFyX7seYVB5g1oeay-n8nfkdyGw9wgOuYXyTPpuc9uVgdxEXCUi6xxyYwx0TnzQHnaozWis1tfRofXAWGPqJ_dU55o1vpRMkjNDp6N-NSlhBE9tLv-W21Jnm3ZF2DG7C6zRcdDzfz0hGIlcaCrj-iBPbxQJ8Rr7RijTL05qZp5-8SOpPNmixgI_8NF_KIrgvknpxSHmU8AqXF2PEQykirMpdTRj3zuCLA', 'person', '["Custom programming", "Form correction", "Weekly check-ins"]', '["Personalized attention", "Maximum accountability", "Tailored biomechanics"]', 200.00, '60 min', FALSE, 3),
('Online Coaching', 'Elite programming delivered directly to your device. Train anywhere, anytime with expert guidance.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuC3NVBcP2-Ts7YBaHcBWs3aQwXSEMgtxeOMLHkHIfxqRjeG1msZts9fk8EHhBBOvsH7axjr7Ky0iYm7tPXwFE1SbwgDOCcQykCxp1D9uzTyEGHxqaYv7OyQNf4ieL3bXD58LUB4BQksUJPx6TUw9uklg6_wZTKzbwLFtnKnwzhTVDhtHjaVJk6KV5bKc_c6BD28Jypl2efZ8njSoSiyj3qAfrFY-PpMFj6l-l5MeABF2qnb2ZT25pndcg', 'devices', '["App access", "Video feedback", "24/7 support"]', '["Train anywhere", "Flexible schedule", "Expert guidance remotely"]', 100.00, '30 min', FALSE, 4),
('Weight Loss', 'Science-backed protocols to shred fat while preserving lean muscle mass. Sustainable, intense results.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQC7NYrDDSXm-dkviFJ37ZLSSuGFk__69dHIih3qVl7puKy7fImIm9pNDvIx03p1AxV3Q6hantiRAe5VxHthKTiljrqLA5ZTxtSoY0PWV4pNrmPHYD3JPFneOWq86vr_DSeJcbvnvAn090CovqblSeQ_TDd2SaGIav4MMNML5Oi3dDHolrT_fuG1o-_ISOeMfMUiwIXZUgeA1VFIW75-KPiom-I-XvbFT1MqP9oTP2qrAmOhg-N14Kwg', 'fitness_center', '["Metabolic conditioning", "Macronutrient planning", "Progress tracking"]', '["Fat loss", "Muscle preservation", "Sustainable results"]', 180.00, '60 min', FALSE, 5),
('Muscle Building', 'Hypertrophy-focused cycles designed to pack on dense, functional muscle tissue through progressive overload.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuA4AkYCCLF0z6qOb3802EaR-_LbpemK6Dm_AAWdNmvHm_5vslNUIzsnbtUkl-muAIBhUZU5IDYvDT8Dj8LjMXYSkV_mSmalVV0QKfewyXyo7cEvAL0gdiY40iUViwl5bScyW6ZQjV83sRt_qQyrYqNQMNxJwPQLhgQVEio3aYtW4EW_2-ROycOrsbCFqagzsZPGtQ3DdfgBLsn_Xa2BVwrqKv477-EuGRuNPDQlrDJt1neHL09qQTJtPA', 'fitness_center', '["Periodized cycles", "Volume management", "Recovery protocols"]', '["Muscle growth", "Functional strength", "Progressive overload"]', 160.00, '60 min', FALSE, 6);

-- Programs
INSERT INTO programs (title, description, image, level, level_color, duration, tags, features, price, featured, col_span, sort_order) VALUES
('Foundation Protocol', 'Master the mechanics. Build base strength, correct imbalances, and prepare your body for elite loading.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWsTxOh3eY6-7JFYJp2RPnc0NW3dt7XiLugI_Z3IgXySOnAd4kJQhIluTH5MrnoLqfjIhJTImawcMwOdNJxFdmYJ1kf0PTqW3BksG76mbJlr9Pek2QZcfEzfqMudciP4ilC9h0R0-EHfMsIbdOQCMephmjDlpoEYDGxrVa7NgC2T2_K7h1qveeZphRi85ZRW6liEC-1fgc9RWj-tD2ThuiOnyRVlc2mDolAYLNS1NWDKI5ueEAXDMSig', 'Beginner', 'bg-surface-container-high text-on-surface border-white/10', '12 Weeks', '["Form", "Mobility"]', '["Fundamental movement patterns", "Base strength building", "Injury prevention"]', 299.00, FALSE, 1, 1),
('Hypertrophy Engine', 'Shift into high gear. Structured progressive overload designed to maximize muscle fiber recruitment and sustained growth.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBfevM-1h9xKhDjt0j7vcp8JjJo0v3BekaqakilQceDXib7FVwUjXY8pabwNpxkudUnH81vKVfH7uw9l1YxEQiY0mpPkyXPTdjwI_eSl75s2RTjv5z-OnsXNt9cBhwVp_XVxE8IHpOQRcLeFGnPANKvqXW6bav8pCk0KMJumXHSsr1KGlTH8CwAfJ7pwh4_DplEEr1U69up8xHMiBq8w0tnuf8XqUpesnf8AydtvJNCkKaoExAMnsKaqg', 'Intermediate', 'bg-surface-container-high text-on-surface border-white/10', '16 Weeks', '["Volume", "Density", "Recovery"]', '["Progressive overload", "Muscle fiber recruitment", "Sustained growth"]', 399.00, TRUE, 2, 2),
('Apex Pinnacle', 'Break plateaus. High-intensity neural priming, complex periodization, and elite peaking strategies.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6BHrhIuxwfKPmBbHzNmViMehjRx04sftdjeKcvMerP8w13si0KvRM_nKGiT6SFlWUWc3lwZTkpL2zXSAvjoR2YmEjD7zBoA-aGRhUQhyGYWJ-nfMbt1FaJRMOJJmbBXovkyB6uLYTrxdpEb7ZoxKn87eEM2AJzh9Ph7h5rX8JTfU9HLzPFG2dq_dahEtfHskceMRREcR9hKCEtzOZipmcz5DwsZNDQc7KoiJoH7RudUu03GZsj9ZZ2Q', 'Advanced', 'bg-error-container text-on-error-container border-error/20', '12 Weeks', '[]', '["Neural priming", "Complex periodization", "Elite peaking"]', 499.00, FALSE, 1, 3),
('Metabolic Burn', 'Ignite your metabolism. High-output conditioning paired with strategic resistance training to reveal lean mass.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAp_gSs-VTyNFd8zJXbXG4YwYoUNU8SagPaeF-FHv3OMZYU_dgEajswrl4oqgtQr49BWIovGmcSXXHaKj0DeFWHzBdvsBm8vsibbgjXikhZ58IjwQQL2k-FIXskeLKffdKIpgqVYjC0Ryep4ju9aff-y2oTNWG1utfarE0y_gM7xBfqQVNpDuow3VrVeFww7fmufqNjjc8C3dHPijbtBRY-gaw4rgZvfw2144CcJCDQZUPfHFS-6aUiXA', 'All Levels', 'bg-surface-container-high text-on-surface border-white/10', '8 Weeks', '[]', '["Metabolic conditioning", "Strategic resistance", "Lean mass reveal"]', 249.00, FALSE, 1, 4),
('Valkyrie Strong', 'Designed for power. Focus on posterior chain development, core stability, and total-body athleticism.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDuD5ptQ_nlZDihAQQVi0tCq0v025xqJqXNb5X6kKee1joV-nhYTOhcSYsnFpw_L_6jbuL5jkMPSKnu32S7h8ZF6TNpTfLD3PHNi5x9iZOeybpj0f-ooPfoLMU_gVaaLwPjTzvvvLEmBjV-SRBCQJUzPLznK7XWvN5-jPRLWGYpYxGfmF_ccsZFVPERMcy_2chRY6QtcZcL-RTcQejILp39IFkuwFvyMcaQtJoJbbUM3kbrzNRx0p1Q3A', 'Intermediate', 'bg-surface-container-high text-on-surface border-white/10', '10 Weeks', '[]', '["Posterior chain development", "Core stability", "Total-body athleticism"]', 349.00, FALSE, 1, 5);

-- Transformations
INSERT INTO transformations (client_name, before_image, after_image, before_label, after_label, stats, quote, goal, weight_lost, story, duration, category, featured, span, sort_order) VALUES
('Marcus Vance', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-BcrYZWgsGvZKrZhlrxUoA1FdfXpntv6AeLChLNY0osh2zMVD30tXgNI3vXOV9bhO9nFO7T-E9p0cn06tnbru322Jm30sjii25ev8s8YTpK-j7di_KgXKR0clqe8_k4Qj4DohrgDNVQgMkZ6aqWLuMXeEthfHr1vmAAASUCeSi0zC18JH2ZEkvXFEX9u4Njai6yrjUR9yuSTAO_El3yT-wHQfcTtpTz41iOjbTALne__MHCuuUDYHgw', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCC19wHCTdawKlxxEzXUVjO72guBgNrzB7QSz1NogAQVrSo6MrP8AbSul398Sj9cdgoHNl3hwJIc28WtRajMmBKPLDQsqhH-XnqdjUvG11DLQu1tyWR9m7s8BBMZh73BS_t-uudbFpAakeM6qqsEyIjguSrN2P5eLmW8Lj87v4Ty6JXHhr-mlEb9iPIlwx5uQUGbbHcD2pDCSIbUd6uyZ6bV-suh3tLMAP9AZjODG9ohUIDIG3QzCZwdw', 'DAY 01', 'DAY 180', '[{"label": "FAT LOSS", "value": "-24 lbs", "highlight": true}, {"label": "MUSCLE GAIN", "value": "+8 lbs", "highlight": false}]', 'The Apex program completely rewired my approach to training. It wasn\'t just about the physical changes, but the mental fortitude built during those dark, early morning sessions.', 'Overall Fitness', '-24 lbs', 'As a coach, I needed to practice what I preach. The Apex program completely rewired my approach to training. It wasn\'t just about the physical changes, but the mental fortitude built during those dark, early morning sessions.', '6 months', 'fitness', TRUE, 8, 1),
('Sarah Jenkins', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB1imn7G9RmepGa82YueixG-rJS5hmUhTUeS4MhjoWwcVX9hoU6mJIoR04QG5PYhmqQAQbwEKMJrJZEtQ7c_asmg8T3zFK4rwgn2U8Bi1n0OUYQLvd4QbANJhdghTrpm4Wks3Xyd2YflePirm5cmQLiggYQcG3JG4Frq7ELVg1EbLhb7_lglqDHzInzmBCzFtUtP3Jccx7iDCkjqakiA5FecuPvMyOUkK1x2ETSVG17XdjHdWdWtPm-sA', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDw-zKJrIIwCqwFfAIou_v9ZATakp9vVPrH4Qf578FS1zPCrXoWXpe-7A6UuegRcE4rVkkrr21iNlAGBPnEsyGdGaSrcETyODymE1UXy2_pHiqE-8O_4Tu1F6l4C2-YMhq_VDQAvzw4RSxxpbIet0bWeVWj2smJtVPFxdhIeZSZKZGKGWGt8-z3JyoIfnww9ZjqzXAMyCHQ3tnsSkfgv41RX2Bg0eJgav9S34bz6PzYA-coQabUHbyFfw', 'Before', 'After', '[{"label": "Fat", "value": "-18 lbs", "highlight": true}, {"label": "Path", "value": "Functional Strength", "highlight": false}]', 'Precision coaching and no-BS accountability made the difference.', 'Weight Loss', '-18 lbs', 'Precision coaching and no-BS accountability made the difference. I tried everything before, but the structured approach finally got me results.', '4 months', 'weight-loss', FALSE, 6, 2),
('David Chen', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQX1nTwA7B4YJBcnOJS95l7xne5Si-3zo0kc0EZJShEldUMXRo23bhaW0HQGkIvOOnQamjWsD_iNCvSsm3zLgORSnUcU6pKIq7wDIGRrr_EcKtOOTb0vrYr1Pun3DI00UEmrgt2GGEtzo4bCOPDmDu7DR7QbX3HSdWPgNIgv7EwFo-CLYGBXu1FUe6Xcih7gjpzYJkR_CSxFsEFSBPiiJwmZAtHjC-vxUwkme_K-TeYRkMZRPDilv0Dg', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDSUTTbPCSjEllHJDlzwxxteOpJUefI__3Oujc6ZCGVhiJfWk9ITf4QH6xR4WmnVxvl-HWE57Ytqv_E6UHtnCvCPQMXxlJbrlIGRX0RlJoWj_BgY_5dASGLNLhtS1CBuKh5DQUGAKZxDpHNWw-8965hUKiy7vqPZbi98MNowIbLOl7nIsMESt9R_QrcGEbbh6Wt1RlRsIeSPiwbYvl9tiur6AkA2FwLiVLPW8PpTMb0fjV0soVbxdmUgw', 'Before', 'After', '[{"label": "Lean Mass", "value": "+15 lbs", "highlight": true}, {"label": "Path", "value": "Hypertrophy", "highlight": false}]', 'The systematic approach to nutrition and progressive overload transformed my physique.', 'Muscle Building', '+15 lbs', 'The systematic approach to nutrition and progressive overload transformed my physique. I gained 15 lbs of lean mass while maintaining low body fat.', '5 months', 'muscle-gain', FALSE, 6, 3),
('Emily Rodriguez', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-BcrYZWgsGvZKrZhlrxUoA1FdfXpntv6AeLChLNY0osh2zMVD30tXgNI3vXOV9bhO9nFO7T-E9p0cn06tnbru322Jm30sjii25ev8s8YTpK-j7di_KgXKR0clqe8_k4Qj4DohrgDNVQgMkZ6aqWLuMXeEthfHr1vmAAASUCeSi0zC18JH2ZEkvXFEX9u4Njai6yrjUR9yuSTAO_El3yT-wHQfcTtpTz41iOjbTALne__MHCuuUDYHgw', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCC19wHCTdawKlxxEzXUVjO72guBgNrzB7QSz1NogAQVrSo6MrP8AbSul398Sj9cdgoHNl3hwJIc28WtRajMmBKPLDQsqhH-XnqdjUvG11DLQu1tyWR9m7s8BBMZh73BS_t-uudbFpAakeM6qqsEyIjguSrN2P5eLmW8Lj87v4Ty6JXHhr-mlEb9iPIlwx5uQUGbbHcD2pDCSIbUd6uyZ6bV-suh3tLMAP9AZjODG9ohUIDIG3QzCZwdw', 'Week 1', 'Week 12', '[{"label": "Endurance", "value": "+40%", "highlight": true}, {"label": "Strength", "value": "+25%", "highlight": false}]', 'The endurance training completely changed my athletic performance.', 'Athletic Performance', '+40% endurance', 'The endurance training completely changed my athletic performance. I can now push harder and longer than ever before.', '3 months', 'athletic', FALSE, 6, 4);

-- Testimonials
INSERT INTO testimonials (author_name, author_role, author_image, quote, featured, sort_order) VALUES
('MARCUS V.', 'Professional MMA Fighter', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGYelBuZHvS7qQz-l9_y7DMdpY0ccwq8fzug6Cz5Kkjyol7YfR21XHUJ9pigbUwxwzzWNdScmFlz-fZ2eebVaMOAVW9jiHjwnaZGs0cL76trA_ju-ZtAtTa-XtWO75DHfhWgVwy4TBIU06J-f4Bjycsv4mYg3JgqezV6xwz_oK7GjR704_LuX13UpHE3L_8Ttqg9DbJ0Vc__zfBhMUwq02Xmu4q4s4Per7SfImVTqKbIjF57CAwQAq-Q', 'Apex Coaching completely rebuilt my physical foundation. The intensity is unmatched, but the programming is pure precision. I\'m lifting heavier, moving faster, and recovering better than ever before in my entire professional career.', TRUE, 1),
('SARAH J.', 'Triathlete', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCV7HdtmbBJH2YNZ5WB9GqQSKppcu9qjoAKJ3_NWBsyoUf3vdPYU9YiulTozdHKRnLJ-CuxSmT0ITZc3wR3L6A7Dn5rjo-3xLVS_2UYYboTBuJ7AM6YbVFHE2CfgqDlkKDcov6ynCQtqDsnxPNeVQfLzDUzpVQ4NaKoCb9SWNw4i9knpirIKAFqACYS16ZSNF3BccKp06g1ofoqAyYVXlEQzUK5ntzhsqcnjQ6zj5Qnc2t-3lxOvS9zWA', 'The dark mode aesthetic isn\'t just a look; it\'s a mindset. Every session pushes me past what I thought were my absolute limits. The data-driven approach keeps me accountable every single day.', FALSE, 2),
('DAVID R.', 'Ultra-Marathoner', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB_tBEP5sCbCHtAICB3nmfbt8RS9-qbAKqxZKrCNOC5fOMS8RRFTPwpBQGEH7aAcujz3BuVMyGvJze9zpb4zFb2ssb4e3iWxTzw6lPvwc8Eramru6SrDM6DsA6wA2L-uYnEByY0wK05fsWYNmkYWFtxrAn_w7V98ozwQlRTAqG-Eb1bTP053w0oTTMzV8OdqxcClupcbI_71eG2y4rt_KGAm-71_OZYuI679yYg_9h-XUp3yb2usJBrMg', 'I\'ve worked with top trainers globally, but the level of granular detail and performance tracking here is revolutionary. It\'s a luxury experience forged in absolute grit.', FALSE, 3);

-- Blog Posts
INSERT INTO blog_posts (title, slug, excerpt, content, image, category, date, read_time, featured, status) VALUES
('Overcoming the Plateau: Advanced Periodization Techniques', 'overcoming-plateau-advanced-periodization', 'When linear progression stops, your strategy must evolve. Discover the methods elite lifters use to shatter sticking points.', 'Full article content here...', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVniC35zxG3Phyn4P9Q0BbLkcXx3nqv96or_l-NYxvjjSGPk3i2RnSgAOmaxeHPKmLuFiJXFLGpqVwfHSMhOXYumzP_IGjq9R60l1Y2qqjOQ0rBK32hlulz6PC_XS7mD7ILajQ_JyDQxIG4QdBQqUytXGLUV6JoQQ4NtlyjjFNNfUrCm3InqTm2fdQWjrMxCV9SbSwOVbDrTEGAQaSsfIblpsL8xUIgeQtoxxPNFvaF2mWDkscNOXq1w', 'TRAINING', '2024-10-12', '8 min read', TRUE, 'published'),
('Macronutrient Timing: Does It Actually Matter?', 'macronutrient-timing-does-it-matter', 'Cutting through the noise of anabolic windows and fasted cardio.', 'Full article content here...', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2nPJyuCgCDiUzG-fEWouk6WEGXeAjgqReczb5f9AKHjIC__n7XKKcG2897yMUUUBCf3JVPndg-MpLwxZEzc8kNz8fYgZ4T70Wv9e2AGRyc7500CwLXrqbeaq6UjM4qj0YNeg32mLj2dEjvv-Nw1e1uVl2yzxb1MGbdq6FAi4uGkUHQCLvLp7sT_peykL77PPvbTNeqMow2oanK86kImcsEc4C-ZC6j8up-QYMN22gYkqGxdWN8FtTgQ', 'NUTRITION', '2024-10-08', '', FALSE, 'published'),
('The Architecture of Sleep for Recovery', 'architecture-sleep-recovery', 'Optimize your circadian rhythm to maximize muscle repair and cognitive function.', 'Full article content here...', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDsRx8EPdKzUMJK-gOH-ExXMa7kjrzw3biypspA68drB4dPipPTKCwgI2vreldJRei9LU2bOiDaTQJvz_s4AMrT6Uw__fkc8YUHtWswjw18PefUbH2NjDDyhJ0HB6P8r4g8VjWJ78ZAd-Zu0mVQ6-n1CD3xq1aqU8eONMbSnqHV-T0obQb6ECYTDkbC_PZ5m3GgHOh_2fCVQLWxteYPROS7Fb7UkQ5YCxuNr5L2JheGvUIEH2PHKl5iHg', 'LIFESTYLE', '2024-10-05', '', FALSE, 'published');

-- Statistics
INSERT INTO statistics (label, value, icon, featured, sort_order) VALUES
('CLIENTS TRANSFORMED', '500+', 'groups', TRUE, 1),
('YEARS EXPERIENCE', '10+', 'military_tech', TRUE, 2),
('PROGRAMS COMPLETED', '1200+', 'fitness_center', TRUE, 3),
('SATISFACTION RATE', '98%', 'thumb_up', TRUE, 4);

-- Gallery
INSERT INTO gallery (title, image_url, video_url, category, aspect_ratio, is_video, description, featured, sort_order) VALUES
('RAW POWER', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIoK7FXO2SdI-kb9FFOiEE_rRBhQqB91y3XmFFJvhyugZgIJL4jYIcCebvASDIxRR-f_1eq6Jp3Emxzozz8e0-IBY2XHRHiLExU3qjmEepBMIPIp5iBG3Ve3b3v01S_S4b0ewzG3am6IMDfxo4EwkwsLDbKzadT62_m51T0o1LTG3shWCH9s3EtrzqIRHfoHg81wibvyJL0n3BamRc28cLkCJblX7qEMDeyv4BBB-eeVk5mQSzA68FuA', '', 'WORKOUT', '3/4', FALSE, 'Raw power training moment showcasing strength and determination.', TRUE, 1),
('THE FACILITY', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgmPNyw1yP9JywI_c-hpWse7A1RjtHbc6_Gb9qBWkJmTRDIzhzPY6TvMTXOEQoraYuDS6yE7AnHVXn9Gu9b2iZOWEfZV_RT5a8GVUequCg9E2y1SiE5MpJO5tbnNekcHFRk_5ykBTTjEN7kiJ1GetBbF3ELHbygP7j3ndGMCLe6IAtnu-o82tmiqq9iipkfhpTt16I02Zo9GM7r80sGTmBL_mwSSUqLSMZZmrftvEuFRfJ7kjS0JBoOA', '', 'GYM', '1/1', FALSE, 'Overview of our elite training facility with state-of-the-art equipment.', FALSE, 2),
('SPRINT MECHANICS', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFitCqs_du8jexcKjOIBu0k6IzN39u8vyjRvapgaONQ73dfreZI_4EmPASIRUDVrO72vNF_Xjd_qMONNXvsjewcmie6hPyKqLfB9UjJnMr_jjsMT9hcTZjoQMnKgWVFXYHNygpMwWhJLPRdf5dawNlV5HaGFTDLiu9oRxn4KxrXJB4R8zIkHv4bi4sbnzL6FCJYCvu_Ci2En4vHttxEtg5qjsukFvJAvWyaHlYNBaX9nGZgp-DNo482g', '', 'LIFESTYLE', '16/9', TRUE, 'Video demonstration of proper sprint mechanics and acceleration techniques.', FALSE, 3),
('THE GRIP', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBpHjT9cCJ2fvKLUtbiC01OjZjOkI3GB0fuCLtnzqjGcSSxYVYOzwmNa370uwF2wykSz_FdJ5ObINMJdOsCES7NSwylotNKbykb6716Vcf-q02DAPb2IBHVIjwYxNB1Gp_PAumyitGdx6syD7UhnopyA09zenY1Qs0CJyvWMCauZzKQoreLe04oN2jQWzX8XupbW29OTaRAg2w1FXELVhyXXlW8wK-MlE5hEINJGZ3CQVNquKe_r_2iiQ', '', 'WORKOUT', '1/1', FALSE, 'Focus on grip strength development exercises.', FALSE, 4),
('COACH PROFILE', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBwo1Z8Jsj7SWp4Lm64wE2Ue4efLtsJCa9Ia-4jBcrw8NKh91xZ7VAZgzO3Yrh6xMpq-UQMEf4V6EaCCj4n6pEeqvJNVsI5N5wgDvm2mESBv80YWPLJfhF2IxAc5WM2VIJ_lc0evvQHThBAYg34zQMgNEnIkLiUe1S_9XtaHvJtzeqyit6Fo2_fxvRzHPfyWF6w8pAQVA2-e4XIjMmhs5NJtmJasjujzZa_VuzzHc-AFO_S5BYTAVTABQ', '', 'LIFESTYLE', '3/4', FALSE, 'Behind the scenes with our elite coaching team.', FALSE, 5),
('RECOVERY ZONE', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKv-vZATyk9DxRFj5VNwJRer9AFhTiI3pg2naqZ07_e_yzeZzYEuTtRt2Blm66tx1Cie1php8CBg2nyHrAzys6CCxw7Asn3bxevwBR2XriJ_c5GmC1LbdqW8jFyBKEZGbjCDLP0uBe3-HhmwFhEn5hf7TP5NGc1jCOGEA1m8FWONOno6Z1euNW1HDBexAKXLE6X06wfIZBfp33jSfvP2LUuEVLJ-i-WmLgA1RwtBMEqsguI1a42bET7A', '', 'GYM', '16/9', FALSE, 'Dedicated recovery zone with cryotherapy and massage equipment.', FALSE, 6),
('OLYMPIC LIFTING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIoK7FXO2SdI-kb9FFOiEE_rRBhQqB91y3XmFFJvhyugZgIJL4jYIcCebvASDIxRR-f_1eq6Jp3Emxzozz8e0-IBY2XHRHiLExU3qjmEepBMIPIp5iBG3Ve3b3v01S_S4b0ewzG3am6IMDfxo4EwkwsLDbKzadT62_m51T0o1LTG3shWCH9s3EtrzqIRHfoHg81wibvyJL0n3BamRc28cLkCJblX7qEMDeyv4BBB-eeVk5mQSzA68FuA', '', 'WORKOUT', '3/4', FALSE, 'Advanced Olympic lifting techniques for explosive power development.', FALSE, 7),
('MOBILITY TRAINING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgmPNyw1yP9JywI_c-hpWse7A1RjtHbc6_Gb9qBWkJmTRDIzhzPY6TvMTXOEQoraYuDS6yE7AnHVXn9Gu9b2iZOWEfZV_RT5a8GVUequCg9E2y1SiE5MpJO5tbnNekcHFRk_5ykBTTjEN7kiJ1GetBbF3ELHbygP7j3ndGMCLe6IAtnu-o82tmiqq9iipkfhpTt16I02Zo9GM7r80sGTmBL_mwSSUqLSMZZmrftvEuFRfJ7kjS0JBoOA', '', 'GYM', '1/1', FALSE, 'Mobility and flexibility training sessions for injury prevention.', FALSE, 8),
('TEAM TRAINING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFitCqs_du8jexcKjOIBu0k6IzN39u8vyjRvapgaONQ73dfreZI_4EmPASIRUDVrO72vNF_Xjd_qMONNXvsjewcmie6hPyKqLfB9UjJnMr_jjsMT9hcTZjoQMnKgWVFXYHNygpMwWhJLPRdf5dawNlV5HaGFTDLiu9oRxn4KxrXJB4R8zIkHv4bi4sbnzL6FCJYCvu_Ci2En4vHttxEtg5qjsukFvJAvWyaHlYNBaX9nGZgp-DNo482g', '', 'LIFESTYLE', '16/9', FALSE, 'Group training sessions building community and competition.', FALSE, 9),
('NUTRITION PLANNING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBpHjT9cCJ2fvKLUtbiC01OjZjOkI3GB0fuCLtnzqjGcSSxYVYOzwmNa370uwF2wykSz_FdJ5ObINMJdOsCES7NSwylotNKbykb6716Vcf-q02DAPb2IBHVIjwYxNB1Gp_PAumyitGdx6syD7UhnopyA09zenY1Qs0CJyvWMCauZzKQoreLe04oN2jQWzX8XupbW29OTaRAg2w1FXELVhyXXlW8wK-MlE5hEINJGZ3CQVNquKe_r_2iiQ', '', 'WORKOUT', '1/1', FALSE, 'Personalized nutrition planning for optimal performance.', FALSE, 10),
('MINDSET COACHING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBwo1Z8Jsj7SWp4Lm64wE2Ue4efLtsJCa9Ia-4jBcrw8NKh91xZ7VAZgzO3Yrh6xMpq-UQMEf4V6EaCCj4n6pEeqvJNVsI5N5wgDvm2mESBv80YWPLJfhF2IxAc5WM2VIJ_lc0evvQHThBAYg34zQMgNEnIkLiUe1S_9XtaHvJtzeqyit6Fo2_fxvRzHPfyWF6w8pAQVA2-e4XIjMmhs5NJtmJasjujzZa_VuzzHc-AFO_S5BYTAVTABQ', '', 'LIFESTYLE', '3/4', FALSE, 'Mental conditioning and mindset coaching for elite athletes.', FALSE, 11),
('PERFORMANCE TESTING', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKv-vZATyk9DxRFj5VNwJRer9AFhTiI3pg2naqZ07_e_yzeZzYEuTtRt2Blm66tx1Cie1php8CBg2nyHrAzys6CCxw7Asn3bxevwBR2XriJ_c5GmC1LbdqW8jFyBKEZGbjCDLP0uBe3-HhmwFhEn5hf7TP5NGc1jCOGEA1m8FWONOno6Z1euNW1HDBexAKXLE6X06wfIZBfp33jSfvP2LUuEVLJ-i-WmLgA1RwtBMEqsguI1a42bET7A', '', 'GYM', '16/9', FALSE, 'Comprehensive performance testing and analysis.', FALSE, 12);