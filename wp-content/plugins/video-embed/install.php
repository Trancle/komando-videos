<?php

global $embed_video_db_version;
$embed_video_db_version = '1.0';

function embed_video_install()
{
    global $embed_video_db_version;

    if (!isset($wpdb))
        $wpdb = $GLOBALS['wpdb'];

    $table_name = $wpdb->prefix . 'embed_video';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		video_id varchar(55) NOT NULL,
		embed_url varchar(255) NOT NULL,
		still_image varchar(255) NOT NULL,
		source varchar(55) NOT NULL,
		auto_play BOOL,
		related_video BOOL,
		video_info BOOL,
		offset mediumint(9),
		both_visible BOOL,
		auto_hide BOOL,
		auto_hide_progressbar BOOL,
		display_control BOOL,
		display_control_caption BOOL,
		loop_video BOOL,
		show_annotation BOOL,
		modest_branding BOOL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('embed_video_db_version', $embed_video_db_version);
}