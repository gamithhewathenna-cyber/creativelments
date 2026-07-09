-- ============================================================
-- Creative Elements — Database Install Script
-- Run this in CPanel > phpMyAdmin on your database
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Hero Slider
-- ----------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT '',
  `badge` varchar(255) DEFAULT '',
  `title` varchar(255) NOT NULL,
  `title_font_size` int(11) DEFAULT NULL,
  `description` text,
  `button_text` varchar(100) DEFAULT '',
  `button_link` varchar(500) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `hero_slides` (`image`, `badge`, `title`, `description`, `button_text`, `button_link`, `sort_order`) VALUES
('slide1.jpg', 'Trusted by 130+ businesses across Australia & Sri Lanka', 'Your Digital Agency for Melbourne & Sydney', 'We combine global design standards with local market knowledge — so your business gets found, clicked, and remembered.', 'Get a Free Quote', 'https://wa.me/94777130597', 1);

-- ----------------------------
-- Client Logos
-- ----------------------------
CREATE TABLE IF NOT EXISTS `client_logos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Team Members
-- ----------------------------
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT '',
  `image` varchar(255) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Services
-- ----------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT '',
  `description` text NOT NULL,
  `content_heading` varchar(255) DEFAULT '',
  `content` longtext,
  `content2_heading` varchar(255) DEFAULT '',
  `content2` longtext,
  `detail_image1` varchar(255) DEFAULT '',
  `detail_image2` varchar(255) DEFAULT '',
  `icon` varchar(100) DEFAULT 'star',
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`title`, `slug`, `description`, `icon`, `sort_order`) VALUES
('Web Design & Development', 'web-design-development', 'High-performance websites built for Australian businesses – fast-loading, mobile-first, and optimised to rank on Google in Melbourne & Sydney.', 'monitor', 1),
('Graphic Design Subscriptions', 'graphic-design-subscriptions', 'On-demand creative for Australian brands – fresh social graphics, ads, and print assets delivered monthly. No briefs, no delays.', 'pen-tool', 2),
('Digital Marketing', 'digital-marketing', 'SEO, Google Ads, and social media strategies tailored for Melbourne and Sydney markets – more leads, more sales.', 'trending-up', 3),
('Shopify Store Setup', 'shopify-store-setup', 'Launch your Australian Shopify store ready to sell – design, payments, shipping & SEO all done for you from day one.', 'shopping-bag', 4),
('Social Media Management', 'social-media-management', 'Grow your Australian audience with content that converts. Strategy, creation and scheduling handled for you.', 'share-2', 5),
('Digital Menu Boards', 'digital-menu-boards', 'Eye-catching digital menus for Melbourne & Sydney hospitality businesses. Easy to update, beautiful to display.', 'tablet', 6);

-- ----------------------------
-- Project Categories
-- ----------------------------
CREATE TABLE IF NOT EXISTS `project_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `project_categories` (`name`, `sort_order`) VALUES
('Web Design', 1),
('Branding', 2),
('Graphic Design', 3);

-- ----------------------------
-- Projects / Portfolio
-- ----------------------------
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT '',
  `description` text,
  `image` varchar(255) DEFAULT '',
  `link` varchar(500) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `projects` (`title`, `category`, `description`, `sort_order`) VALUES
('SmartDocs', 'Web Design', 'A modern document management platform with clean UI and intuitive navigation.', 1),
('Tribe Lanka Corporate Profile', 'Branding', 'Complete corporate identity and branding package for a leading Sri Lankan company.', 2),
('Drunken Monkey', 'Branding', 'Bold restaurant branding that captures personality and drives footfall.', 3),
('Shack Restaurant Menu', 'Graphic Design', 'Stunning digital and print menu design for a Sydney-based restaurant.', 4),
('MAS Intimates Concept', 'Graphic Design', 'Creative concept designs for one of Sri Lanka''s largest apparel brands.', 5);

-- ----------------------------
-- Testimonials
-- ----------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT '',
  `location` varchar(100) DEFAULT '',
  `content` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `testimonials` (`name`, `company`, `location`, `content`, `rating`, `sort_order`) VALUES
('James K.', 'SmartDocs', 'Melbourne, AU', 'Creative Elements completely transformed our online presence. Our leads tripled within two months of launching the new site. Exceptional work and communication throughout.', 5, 1),
('Sarah M.', 'The Bungalow Petersham', 'Sydney, AU', 'The team at Creative Elements understood our brand vision immediately. The design subscription has been game-changing for our social media consistency.', 5, 2),
('Anil P.', 'Tribe Lanka', 'Colombo, LK', 'Professional, fast, and genuinely talented. They delivered a brand identity that we are incredibly proud of. Highly recommend to any business.', 5, 3),
('Emma T.', 'Drunken Monkey', 'Melbourne, AU', 'Best agency we have worked with. Responsive, creative, and they actually deliver on time. Our new branding has had amazing feedback from customers.', 5, 4);

-- ----------------------------
-- Stats / Counters
-- ----------------------------
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `suffix` varchar(20) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `stats` (`label`, `value`, `suffix`, `sort_order`) VALUES
('Projects delivered', '91', 'K', 1),
('Happy clients', '40', 'K', 2),
('Years of expertise', '15', '+', 3),
('Industry awards', '15', '', 4);

-- ----------------------------
-- Contact / Enquiries
-- ----------------------------
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT '',
  `service` varchar(100) DEFAULT '',
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `ip_address` varchar(45) DEFAULT '',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Blog Posts
-- ----------------------------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `excerpt` varchar(500) DEFAULT '',
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT '',
  `category` varchar(100) DEFAULT 'General',
  `tags` varchar(500) DEFAULT '',
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Site Settings
-- ----------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('phone', '0777130597'),
('email', 'reach@creativelements.co'),
('address', 'Creative Elements (Pvt) Ltd, 27/1, 1ST LANE, BORALESGAMUWA'),
('facebook', 'https://www.facebook.com/profile.php?id=100067224223105'),
('instagram', 'https://www.instagram.com/creativelements.co/'),
('whatsapp', '94777130597'),
('hero_title', 'Web Design & Development'),
('hero_subtitle', 'Built for Melbourne & Sydney businesses — fast, modern, and Google-ready'),
('about_text', 'Creative Elements is a trusted digital agency serving businesses across Melbourne, Sydney, and Sri Lanka. We specialise in web design, SEO, branding, and digital marketing — built around what actually converts. With 15+ years of expertise and 130+ clients worldwide, we deliver global-standard creative with the speed, transparency, and care your business deserves.');

-- ----------------------------
-- Admin Users
-- ----------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT '',
  `last_login` timestamp NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username=admin  password=Admin@1234  (CHANGE AFTER FIRST LOGIN)
INSERT INTO `admin_users` (`username`, `password_hash`, `email`) VALUES
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'reach@creativelements.co');

SET FOREIGN_KEY_CHECKS = 1;
