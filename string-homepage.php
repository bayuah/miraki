<?php
/*
 * Author: Bayu Aditya H. <b@yuah.web.id>
 * Copyright: 2015
 * Licence: see LICENCE.txt
 */

if (!defined('BASE_DIR')){
	header("HTTP/1.0 501 Not Implemented");
	die("<h1>Direct access not permitted.</h1>");
};

// String.
$template->set("underdevelopment_str",
	sprintf(__('Miraki is is currently under development. Several functions may not work properly. Please consider doing a follow-up <a href="%1$s">Development page</a> to get the latest updates.'), 
	"https://github.com/bayuah/miraki")
	);

$template->set("title", __("Miraki"));
$template->set("home_title", __("Miraki"));
$template->set("home_subtitle", sprintf(__("Minecraft skin cache server. Version %1s."), $VERSION));
$template->set("form_action", BASE_DIR);
$template->set("form_username", __("Minecraft username."));
$template->set("form_username_desc", __("Your Minecraft username."));
$template->set("form_file", __("Skin file."));
$template->set("form_file_desc", __("Your new skin."));
$template->set("form_password", __("Password."));
$template->set("form_submit", __("Do it!"));
$template->set("form_password_desc", __("Your skin password protection. Use this password to update your old skin on this server later."));
$template->set("home_js_focus", "username");
$template->set("language_code", "en");
// Template file.
$template->subtemplate("home_upload_form", "$TEMPLATE_DIR/upload-form.html");
$template->subtemplate("home_underdevelopment", "$TEMPLATE_DIR/underdevelopment.html");
$template->subtemplate("main_body", "$TEMPLATE_DIR/home.html");
$template->subtemplate("main_css", "$TEMPLATE_DIR/style.css");
$template->subtemplate("main_css_reset", "$TEMPLATE_DIR/reset.css");
$template->subtemplate("main_js", "$TEMPLATE_DIR/home.js");

?>