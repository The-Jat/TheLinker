UPDATE `settings` SET `value` = '{\"version\":\"59.0.0\", \"code\":\"5900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table track_links add project_id int null after biolink_block_id;

-- SEPARATOR --

UPDATE track_links JOIN links ON track_links.link_id = links.link_id SET track_links.project_id = links.project_id;

-- SEPARATOR --

alter table track_links add constraint track_links_projects_project_id_fk foreign key (project_id) references projects (project_id) on update cascade on delete set null;

-- SEPARATOR --

alter table pages add plans_ids text null after pages_category_id;

-- SEPARATOR --

alter table links add email_reports_count tinyint default 0 null after email_reports_last_datetime;

-- SEPARATOR --

create index links_email_reports_count_index on links (email_reports_count);

-- SEPARATOR --

INSERT INTO `links` (`project_id`, `splash_page_id`, `user_id`, `biolink_theme_id`, `domain_id`, `pixels_ids`, `email_reports`, `email_reports_last_datetime`, `type`, `subtype`, `url`, `location_url`, `clicks`, `settings`, `additional`, `start_date`, `end_date`, `is_verified`, `directory_is_enabled`, `is_enabled`, `last_datetime`, `datetime`) VALUES
(NULL, NULL, 1, NULL, 0, '[]', '[]', NULL, 'biolink', NULL, 'template-mobile-app', NULL, 4, '{\"pwa_file_name\":null,\"pwa_is_enabled\":0,\"pwa_display_install_bar\":0,\"pwa_display_install_bar_delay\":3,\"pwa_theme_color\":\"#000000\",\"pwa_icon\":null,\"verified_location\":\"top\",\"background_type\":\"preset\",\"background_attachment\":\"scroll\",\"background_blur\":0,\"background_brightness\":100,\"background\":\"zero\",\"background_color_one\":null,\"background_color_two\":null,\"favicon\":null,\"text_color\":\"#ffffff\",\"display_branding\":1,\"branding\":{\"name\":\"\",\"url\":\"\"},\"seo\":{\"block\":0,\"title\":\"\",\"meta_description\":\"\",\"meta_keywords\":\"\",\"image\":\"\"},\"utm\":{\"medium\":\"\",\"source\":\"\"},\"font\":\"default\",\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\",\"font_size\":16,\"password\":null,\"sensitive_content\":0,\"leap_link\":\"\",\"custom_css\":\"\",\"custom_js\":\"\",\"share_is_enabled\":1,\"scroll_buttons_is_enabled\":1}', '', NULL, NULL, 0, 1, 1, '2025-07-31 21:35:39', '2025-07-31 21:01:27');

-- SEPARATOR --
SET @new_link_id = LAST_INSERT_ID();
-- SEPARATOR --

INSERT INTO `biolinks_blocks` (`user_id`, `link_id`, `type`, `location_url`, `clicks`, `settings`, `order`, `start_date`, `end_date`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, @new_link_id, 'avatar', '', 0, '{\"image\":\"06e5bcf82b1b4642683b8c302336da5f.png\",\"image_alt\":\"\",\"size\":100,\"object_fit\":\"contain\",\"border_radius\":\"round\",\"border_width\":0,\"border_style\":\"solid\",\"border_color\":\"#000000\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":20,\"border_shadow_spread\":0,\"border_shadow_color\":\"#FFFFFF00\",\"open_in_new_tab\":0,\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 0, NULL, NULL, 1, '2025-07-31 21:39:26', '2025-07-31 21:41:11'),
(1, @new_link_id, 'link', 'https://app-store-app-link.com/', 0, '{\"name\":\"Android - play store\",\"open_in_new_tab\":0,\"text_color\":\"#FFFFFF\",\"text_alignment\":\"center\",\"background_color\":\"#5647F5\",\"border_radius\":\"round\",\"border_width\":0,\"border_style\":\"solid\",\"border_color\":\"#000000\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":20,\"border_shadow_spread\":0,\"border_shadow_color\":\"#00000010\",\"animation\":\"false\",\"animation_runs\":false,\"icon\":\"\",\"image\":\"0aeed08df8caf63a0b5fb245708badad.webp\",\"sensitive_content\":0,\"columns\":1,\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 4, NULL, NULL, 1, '2025-07-31 21:21:34', '2025-07-31 21:36:52'),
(1, @new_link_id, 'link', 'https://app-store-app-link.com/', 0, '{\"name\":\"IOS - app store\",\"open_in_new_tab\":0,\"text_color\":\"#FFFFFF\",\"text_alignment\":\"center\",\"background_color\":\"#214DFF\",\"border_radius\":\"round\",\"border_width\":0,\"border_style\":\"solid\",\"border_color\":\"#000000\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":20,\"border_shadow_spread\":0,\"border_shadow_color\":\"#00000010\",\"animation\":\"false\",\"animation_runs\":false,\"icon\":\"\",\"image\":\"8d65c20696a585964b49c4d4bb5e4236.png\",\"sensitive_content\":0,\"columns\":1,\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 3, NULL, NULL, 1, '2025-07-31 21:07:28', '2025-07-31 21:37:05'),
(1, @new_link_id, 'paragraph', NULL, 0, '{\"text\":\"<p class=\\\"ql-align-center text-center\\\">Your app description<\\/p>\",\"text_color\":\"#ffffff\",\"background_color\":\"#00000000\",\"border_radius\":\"rounded\",\"border_width\":0,\"border_style\":\"solid\",\"border_color\":\"#000000\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":20,\"border_shadow_spread\":0,\"border_shadow_color\":\"#00000000\",\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 2, NULL, NULL, 1, '2025-07-31 21:05:12', '2025-07-31 21:05:23'),
(1, @new_link_id, 'heading', NULL, 0, '{\"heading_type\":\"h3\",\"text\":\"Your app name\",\"text_color\":\"#ffffff\",\"text_alignment\":\"center\",\"verified_location\":\"\",\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 1, NULL, NULL, 1, '2025-07-31 21:04:51', '2025-07-31 21:06:32');

-- SEPARATOR --

INSERT INTO `biolinks_templates` (`link_id`, `name`, `url`, `settings`, `is_enabled`, `total_usage`, `order`, `last_datetime`, `datetime`) VALUES
(@new_link_id, 'App download', 'template-mobile-app', '[]', 1, 0, 1, NULL, '2025-07-31 21:44:49');
