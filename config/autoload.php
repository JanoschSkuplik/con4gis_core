<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Con4gis_common
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'c4g',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
    'c4g\HttpResultHelper'      => 'system/modules/con4gis_core/classes/HttpResultHelper.php',
	'c4g\C4GHTMLFactory'        => 'system/modules/con4gis_core/classes/C4GHTMLFactory.php',
	'C4GJQueryGUI'              => 'system/modules/con4gis_core/classes/C4GJQueryGUI.php',
	'c4g\C4GUtils'              => 'system/modules/con4gis_core/classes/C4GUtils.php',

	// Models
	'c4g\C4gActivationkeyModel' => 'system/modules/con4gis_common/models/C4gActivationkeyModel.php',
));
