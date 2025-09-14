UPDATE `settings` SET `value` = '{\"version\":\"57.0.0\", \"code\":\"5700\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('custom_images', '{}');

-- SEPARATOR --

alter table users add avatar varchar(40) null after name;

-- SEPARATOR --


INSERT INTO `settings` (`key`, `value`) VALUES ('notification_handlers', '{"twilio_sid":"","twilio_token":"","twilio_number":"","whatsapp_number_id":"","whatsapp_access_token":"","email_is_enabled":true,"webhook_is_enabled":true,"slack_is_enabled":true,"discord_is_enabled":true,"telegram_is_enabled":true,"microsoft_teams_is_enabled":true,"twilio_is_enabled":false,"twilio_call_is_enabled":false,"whatsapp_is_enabled":false}');

-- SEPARATOR --

UPDATE `settings`
SET `value` = JSON_SET(`value`, '$.biolinks_fonts',
JSON_OBJECT(
'default', JSON_OBJECT(
'id', 'default',
'name', 'Default',
'font_family', '',
'css_url', ''
),
'times new roman', JSON_OBJECT(
'id', 'times-new-roman',
'name', 'Times New Roman',
'font_family', 'Times New Roman, Times, serif',
'css_url', ''
),
'georgia', JSON_OBJECT(
'id', 'georgia',
'name', 'Georgia',
'font_family', 'Georgia, serif',
'css_url', ''
),
'courier', JSON_OBJECT(
'id', 'courier',
'name', 'Courier',
'font_family', 'Courier, monospace',
'css_url', ''
),
'arial', JSON_OBJECT(
'id', 'arial',
'name', 'Arial',
'font_family', 'Arial, sans-serif',
'css_url', ''
),
'helvetica', JSON_OBJECT(
'id', 'helvetica',
'name', 'Helvetica',
'font_family', 'Helvetica, Arial, sans-serif',
'css_url', ''
),
'verdana', JSON_OBJECT(
'id', 'verdana',
'name', 'Verdana',
'font_family', 'Verdana, sans-serif',
'css_url', ''
),
'tahoma', JSON_OBJECT(
'id', 'tahoma',
'name', 'Tahoma',
'font_family', 'Tahoma, Geneva, sans-serif',
'css_url', ''
),
'trebuchet ms', JSON_OBJECT(
'id', 'trebuchet-ms',
'name', 'Trebuchet MS',
'font_family', 'Trebuchet MS, sans-serif',
'css_url', ''
),
'courier new', JSON_OBJECT(
'id', 'courier-new',
'name', 'Courier New',
'font_family', 'Courier New, Courier, monospace',
'css_url', ''
),
'monaco', JSON_OBJECT(
'id', 'monaco',
'name', 'Monaco',
'font_family', 'Monaco, monospace',
'css_url', ''
),
'comic sans ms', JSON_OBJECT(
'id', 'comic-sans-ms',
'name', 'Comic Sans MS',
'font_family', 'Comic Sans MS, cursive',
'css_url', ''
),
'impact', JSON_OBJECT(
'id', 'impact',
'name', 'Impact',
'font_family', 'Impact, fantasy',
'css_url', ''
),
'luminari', JSON_OBJECT(
'id', 'luminari',
'name', 'Luminari',
'font_family', 'Luminari, fantasy',
'css_url', 'https://fonts.cdnfonts.com/css/luminari'
),
'baskerville', JSON_OBJECT(
'id', 'baskerville',
'name', 'Baskerville',
'font_family', 'Baskerville, serif',
'css_url', ''
),
'papyrus', JSON_OBJECT(
'id', 'papyrus',
'name', 'Papyrus',
'font_family', 'Papyrus, fantasy',
'css_url', ''
),
'brush script mt', JSON_OBJECT(
'id', 'brush-script-mt',
'name', 'Brush Script MT',
'font_family', 'Brush Script MT, cursive',
'css_url', ''
),
'inter', JSON_OBJECT(
'id', 'inter',
'name', 'Inter',
'font_family', 'Inter',
'css_url', 'https://rsms.me/inter/inter.css'
),
'lato', JSON_OBJECT(
'id', 'lato',
'name', 'Lato',
'font_family', 'Lato',
'css_url', 'https://fonts.googleapis.com/css?family=Lato&display=swap'
),
'open sans', JSON_OBJECT(
'id', 'open-sans',
'name', 'Open Sans',
'font_family', 'Open Sans',
'css_url', 'https://fonts.googleapis.com/css?family=Open+Sans&display=swap'
),
'montserrat', JSON_OBJECT(
'id', 'montserrat',
'name', 'Montserrat',
'font_family', 'Montserrat',
'css_url', 'https://fonts.googleapis.com/css?family=Montserrat&display=swap'
),
'karla', JSON_OBJECT(
'id', 'karla',
'name', 'Karla',
'font_family', 'Karla',
'css_url', 'https://fonts.googleapis.com/css?family=Karla&display=swap'
),
'inconsolata', JSON_OBJECT(
'id', 'inconsolata',
'name', 'Inconsolata',
'font_family', 'Inconsolata',
'css_url', 'https://fonts.googleapis.com/css?family=Inconsolata&display=swap'
),
'roboto', JSON_OBJECT(
'id', 'roboto',
'name', 'Roboto',
'font_family', 'Roboto',
'css_url', 'https://fonts.googleapis.com/css?family=Roboto&display=swap'
)
)
)
WHERE `key` = 'links';

-- SEPARATOR --

INSERT INTO `biolinks_themes` (`name`, `settings`, `is_enabled`, `order`, `last_datetime`, `datetime`) VALUES
('Barcelona', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"\"},\"biolink\":{\"background_type\":\"image\",\"background\":\"b634495133c9091655dab3c3c916722e.svg\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"inter\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"biolink_block\":{\"text_color\":\"#ffffff\",\"title_color\":\"#ffffff\",\"description_color\":\"#D5D5D5\",\"background_color\":\"#0C0127D1\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#FFFFFF00\"},\"biolink_block_socials\":{\"color\":\"#2B006C\",\"background_color\":\"#FFFFFF45\",\"border_radius\":\"round\"},\"biolink_block_paragraph\":{\"text_color\":\"#ffffff\",\"background_color\":\"#0C0127D1\",\"border_radius\":\"rounded\"},\"biolink_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 0, '2025-05-20 03:22:54', '2025-05-20 03:12:38');

-- SEPARATOR --

CREATE TABLE `notification_handlers` (
`notification_handler_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`type` varchar(32) DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`settings` text,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`notification_handler_id`),
UNIQUE KEY `notification_handler_id` (`notification_handler_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `notification_handlers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table links add email_reports text null after pixels_ids;

-- SEPARATOR --

alter table links add email_reports_last_datetime datetime null after email_reports;

-- SEPARATOR --

update links set email_reports_last_datetime = '2020-01-01';

-- SEPARATOR --

CREATE TABLE `email_reports` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`link_id` int DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `link_id` (`link_id`),
KEY `email_reports_datetime_idx` (`datetime`) USING BTREE,
CONSTRAINT `email_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `email_reports_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

-- X --
ALTER TABLE `track_links` DROP FOREIGN KEY `track_links_links_project_id_fk`;

-- SEPARATOR --

-- X --
ALTER TABLE `track_links` DROP FOREIGN KEY `track_links_projects_project_id_fk`;

-- SEPARATOR --

-- X --
ALTER TABLE `track_links` DROP INDEX `track_links_project_id_index`;

-- SEPARATOR --

-- X --
ALTER TABLE `track_links` DROP COLUMN `project_id`;
