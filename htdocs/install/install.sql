SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
CREATE TABLE IF NOT EXISTS `ad` (
  `id`                     INT(11)      NOT NULL AUTO_INCREMENT,
  `name`                   VARCHAR(256) NOT NULL,
  `local_image_name`       VARCHAR(256) NOT NULL,
  `remote_image_url`       VARCHAR(512) NOT NULL,
  `image_height`           INT(11)      NOT NULL DEFAULT '0',
  `image_width`            INT(11)      NOT NULL DEFAULT '0',
  `text`                   TEXT         NOT NULL,
  `link`                   VARCHAR(256) NOT NULL,
  `visible_in_sidebar`     INT(4)       NOT NULL DEFAULT '1',
  `visible_in_lists`       INT(4)       NOT NULL DEFAULT '1',
  `visible_on_review_page` INT(4)       NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `article` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(512)  NOT NULL,
  `seo_title`        VARCHAR(512)  NOT NULL,
  `description`      TEXT          NOT NULL,
  `meta_keywords`    VARCHAR(1000) NOT NULL,
  `meta_description` VARCHAR(1000) NOT NULL,
  `link_text`        VARCHAR(512)  NOT NULL,
  `link_url`         VARCHAR(512)  NOT NULL,
  `last_modified`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `captcha` (
  `captcha_id`   BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT,
  `captcha_time` INT(10) UNSIGNED    NOT NULL,
  `ip_address`   VARCHAR(16)         NOT NULL DEFAULT '0',
  `word`         VARCHAR(20)         NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `category` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(255) NOT NULL,
  `seo_name`      VARCHAR(255) NOT NULL,
  `last_modified` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
INSERT INTO `category` (`id`, `name`, `seo_name`, `last_modified`)
VALUES (1, 'Main Category', 'main_category', '2011-11-22 13:31:01');
CREATE TABLE IF NOT EXISTS `category_default_feature` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `feature_id`  INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `category_default_rating` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `rating_id`   INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `comment` (
  `id`                 INT(11)      NOT NULL AUTO_INCREMENT,
  `review_id`          INT(11)      NOT NULL,
  `quotation`          VARCHAR(512) NOT NULL,
  `source`             VARCHAR(512) NOT NULL,
  `site_link`          VARCHAR(512) NOT NULL,
  `approved`           INT(4)       NOT NULL DEFAULT '0',
  `visitor_rating`     INT(11)      NOT NULL,
  `visitor_ip_address` VARCHAR(40)  NOT NULL,
  `last_modified`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `feature` (
  `id`   INT(11)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `page` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(512)  NOT NULL,
  `seo_name`         VARCHAR(512)  NOT NULL,
  `content`          TEXT          NOT NULL,
  `meta_keywords`    VARCHAR(1000) NOT NULL,
  `meta_description` VARCHAR(1000) NOT NULL,
  `last_modified`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 3;
INSERT INTO `page` (`id`, `name`, `seo_name`, `content`, `meta_keywords`, `meta_description`, `last_modified`) VALUES
  (1, 'Privacy Policy & Terms And Conditions', 'privacy_policy_terms_and_conditions',
   '<p><strong><span style="text-decoration: underline; font-size: medium;">Privacy Policy &amp; Terms And Conditions</span></strong><br /><br /><span style="font-size: medium;">You can edit this page in Manager. Select &lsquo;Custom Pages&rsquo; from the menu.</span></p>',
   '', '', '0000-00-00 00:00:00'), (2, 'About Us', 'about_us',
                                    '<p><span style="text-decoration: underline;"><strong><span style="font-family: arial,helvetica,sans-serif; font-size: medium;">About Us</span></strong></span></p>\n<p><span style="font-family: arial,helvetica,sans-serif; font-size: medium;">You can edit this page in Manager. Select &lsquo;Custom Pages&rsquo; from the menu.</span></p>',
                                    '', '', '0000-00-00 00:00:00');
CREATE TABLE IF NOT EXISTS `rating` (
  `id`   INT(11)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `review` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `tags`             VARCHAR(1000) NOT NULL,
  `category_id`      INT(11)       NOT NULL,
  `featured`         TINYINT(4)    NOT NULL DEFAULT '0',
  `approved`         INT(4)        NOT NULL DEFAULT '0',
  `title`            VARCHAR(300)  NOT NULL,
  `seo_title`        VARCHAR(300)  NOT NULL,
  `description`      TEXT          NOT NULL,
  `image_name`       VARCHAR(512)           DEFAULT NULL,
  `image_extension`  VARCHAR(10)            DEFAULT NULL,
  `last_modified`    TIMESTAMP     NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `meta_keywords`    VARCHAR(1000) NOT NULL,
  `meta_description` VARCHAR(1000)          DEFAULT NULL,
  `vendor`           VARCHAR(128)           DEFAULT NULL,
  `link`             VARCHAR(300)           DEFAULT NULL,
  `clicks`           INT(11)       NOT NULL DEFAULT '0',
  `views`            INT(11)       NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_title` (`seo_title`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `titledesc` (`title`, `description`),
  FULLTEXT KEY `tags` (`tags`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `review_feature` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `review_id`  INT(11)      NOT NULL,
  `feature_id` INT(11)      NOT NULL,
  `value`      VARCHAR(512) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `review_rating` (
  `id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `review_id` INT(11)      NOT NULL,
  `rating_id` INT(11)      NOT NULL,
  `value`     VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `session` (
  `id`         VARCHAR(40)                NOT NULL,
  `ip_address` VARCHAR(45)                NOT NULL,
  `timestamp`  INT(10) UNSIGNED DEFAULT 0 NOT NULL,
  `data`       BLOB                       NOT NULL,
  PRIMARY KEY (id),
  KEY `ci_sessions_timestamp` (`timestamp`)
);
CREATE TABLE IF NOT EXISTS `setting` (
  `id`    INT(11)       NOT NULL AUTO_INCREMENT,
  `name`  VARCHAR(255)  NOT NULL,
  `value` VARCHAR(2048) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 59;
INSERT INTO `setting` (`id`, `name`, `value`)
VALUES (1, 'site_name', 'CIOpenReview'), (2, 'site_email', ''), (3, 'captcha_verification', '1'),
  (4, 'thumbnail_is_link', '0'), (5, 'featured_section_home', '1'), (6, 'featured_section_review', '1'),
  (7, 'featured_section_article', '1'), (8, 'featured_section_search', '1'), (9, 'featured_count', '3'),
  (10, 'recent_review_sidebar', '1'), (11, 'tag_cloud_sidebar', '1'), (12, 'categories_sidebar', '1'),
  (13, 'max_ads_home_sidebar', '5'), (14, 'max_ads_review_sidebar', '5'), (15, 'max_ads_article_sidebar', '5'),
  (16, 'max_ads_search_sidebar', '5'), (17, 'max_ads_home_lists', '5'), (18, 'max_ads_articles_lists', '5'),
  (19, 'max_ads_results_lists', '5'), (20, 'review_approval', '0'), (21, 'review_auto', '1'),
  (22, 'comment_approval', '1'), (23, 'comment_auto', '1'), (24, 'current_theme', 'bootstrap'),
  (25, 'featured_minimum', '3'), (26, 'max_ads_custom_page_sidebar', '5'), (27, 'perpage_site_home', '5'),
  (28, 'perpage_site_search', '5'), (29, 'perpage_site_category', '5'), (30, 'perpage_site_articles', '5'),
  (31, 'perpage_manager_reviews', '5'), (32, 'perpage_manager_reviews_pending', '5'),
  (33, 'perpage_manager_comments', '5'), (34, 'perpage_manager_categories', '5'), (35, 'perpage_manager_features', '5'),
  (36, 'perpage_manager_ratings', '5'), (37, 'perpage_manager_articles', '5'),
  (38, 'perpage_manager_custom_pages', '5'), (39, 'perpage_manager_ads', '5'), (40, 'perpage_manager_users', '5'),
  (41, 'perpage_manager_comments_pending', '5'), (42, 'site_summary_title', 'Welcome To CIOpenReview'),
  (43, 'site_summary_text', 'You can edit this text in the Manager area under \'Site Settings\''),
  (44, 'number_of_reviews_sidebar', '3'), (45, 'current_manager_theme', 'manager_light'),
  (46, 'show_visitor_rating', '1'), (47, 'site_logo', 'orslogo_67257690.png'),
  (48, 'site_logo_name', 'orslogogray_38824462'), (49, 'site_logo_extension', 'png'), (50, 'max_upload_width', '2048'),
  (51, 'max_upload_height', '1536'), (52, 'max_upload_filesize', '20000'), (53, 'review_thumb_max_width', '210'),
  (54, 'review_thumb_max_height', '140'), (55, 'search_thumb_max_width', '150'), (56, 'search_thumb_max_height', '100'),
  (57, 'debug', '0'), (58, 'search_sidebar', '1'), (61, 'template_color_theme', 'default');
CREATE TABLE IF NOT EXISTS `tags` (
  `id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `review_id` INT(11)      NOT NULL,
  `tag`       VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `user` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `name`     VARCHAR(255) NOT NULL,
  `email`    VARCHAR(128) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `level`    INT(4)       NOT NULL DEFAULT '1',
  `key`      VARCHAR(64)           DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci
  AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `logs` (
  `id`      INT(12)                 NOT NULL,
  `type`    VARCHAR(20)
            COLLATE utf8_unicode_ci NOT NULL,
  `user`    VARCHAR(50)
            COLLATE utf8_unicode_ci NOT NULL,
  `ip`      VARCHAR(15)
            COLLATE utf8_unicode_ci NOT NULL,
  `message` VARCHAR(255)
            COLLATE utf8_unicode_ci NOT NULL,
  `time`    TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;
ALTER TABLE `logs`
ADD PRIMARY KEY (`id`);
ALTER TABLE `logs`
MODIFY `id` INT(12) NOT NULL AUTO_INCREMENT;
CREATE TABLE IF NOT EXISTS `hits` (
  `id`        INT(12)                 NOT NULL,
  `review_id` INT(12)                 NOT NULL,
  `time`      TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote_ip` VARCHAR(15)
              COLLATE utf8_unicode_ci NOT NULL
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;
ALTER TABLE `hits`
ADD PRIMARY KEY (`id`),
ADD KEY `review_id` (`review_id`),
ADD KEY `remote_ip` (`remote_ip`);
ALTER TABLE `hits`
MODIFY `id` INT(12) NOT NULL AUTO_INCREMENT;
