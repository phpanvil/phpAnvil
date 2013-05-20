<?php

//---- Path Constants ----------------------------------------------------------
defined('PHPANVIL2_COMPONENT_PATH') or define('PHPANVIL2_COMPONENT_PATH', PHPANVIL2_FRAMEWORK_PATH . 'component/');
defined('PHPANVIL2_LANG_PATH') or define('PHPANVIL2_LANG_PATH', PHPANVIL2_FRAMEWORK_PATH . 'lang/');
defined('PHPANVIL2_MODEL_PATH') or define('PHPANVIL2_MODEL_PATH', PHPANVIL2_FRAMEWORK_PATH . 'models/');
defined('PHPANVIL2_MODULE_PATH') or define('PHPANVIL2_MODULE_PATH', PHPANVIL2_FRAMEWORK_PATH . 'modules/');

//---- Required Models ---------------------------------------------------------
if (defined('USE_PHPANVIL2_VERSION') && USE_PHPANVIL2_VERSION == '2.1') {
    require_once(PHPANVIL2_MODEL_PATH . 'activity.model.php');
    require_once(PHPANVIL2_MODEL_PATH . 'activitytype.model.php');
} else {
    require_once('activity.model.php');
    require_once('activitytype.model.php');
}


//---- Required Special Classes ------------------------------------------------
require_once('EventListener.class.php');

require_once('Controller.collection.php');
require_once('Plugin.collection.php');
require_once('Database.collection.php');
require_once('Module.collection.php');
require_once('Option.collection.php');
require_once('Path.collection.php');

require_once('anvilModule.abstract.php');


//---- Initiate the phpAnvil Object
require_once('phpAnvil.class.php');

$phpAnvil = new phpAnvil2();

?>
