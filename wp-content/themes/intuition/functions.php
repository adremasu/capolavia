<?php if(!isset($content_width)) $content_width = 640;
define('CPOTHEME_ID', 'intuition');
define('CPOTHEME_NAME', 'Intuition');
define('CPOTHEME_VERSION', '1.3.4');
//Other constants
define('CPOTHEME_LOGO_WIDTH', '150');
define('CPOTHEME_THUMBNAIL_HEIGHT', '350');
define('CPOTHEME_USE_PAGES', true);
define('CPOTHEME_USE_SLIDES', true);
define('CPOTHEME_USE_FEATURES', true);
define('CPOTHEME_USE_PORTFOLIO', true);

//Load Core; check existing core or load development core
$core_path = get_template_directory().'/core/';
if(defined('CPOTHEME_CORE')) $core_path = CPOTHEME_CORELITE;
require_once $core_path.'init.php';

$include_path = get_template_directory().'/includes/';

//Main components
require_once($include_path.'setup.php');