<?php
/*
 * Author: Bayu Aditya H. <b@yuah.web.id>
 * Copyright: 2015
 * Licence: see LICENCE.txt
 */
 
/* ----- Start edit. ----- */
// Configure.
$TEMPLATE_DIR="template";
$LANGUAGE="id";
$LOCALE_DIR="local";
$SKINS_DIR="skins";
$CLOAKS_DIR="cloaks";
$MAINSERVER_IP="54.235.156.109";
$MAINSERVER_HOSTNAME="s3.amazonaws.com";
$MAINSERVER_SKINS_PATH="MinecraftSkins";
$MAINSERVER_CLOAKS_PATH="MinecraftCloaks";
/* ----- Stop edit, unless you know what you're doing. ----- */
// Versioning.
$VERSION="0.1.0";
/*
 * Init.
 */
if (!defined('BASE_DIR'))
	define('BASE_DIR', dirname($_SERVER['PHP_SELF']));
// Load classes and functions.
require("./class-function.php");
// I18n.
$i18n=new i18n($LANGUAGE, $LOCALE_DIR);
$i18n->init();
// String.
$template=new template("$TEMPLATE_DIR/main.html");
// Load page.
require("./page-handler.php");
// Built.
$template->built();
$template->output(true);
?>