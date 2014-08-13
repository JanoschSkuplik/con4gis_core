<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz &#40;http://www.kuestenschmiede.de&#41;
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */


$GLOBALS['con4gis_core_extension']['installed'] = true;


/**
 * Backend Modules
 */
array_insert( $GLOBALS['BE_MOD'], array_search('content', array_keys($GLOBALS['BE_MOD']))+1, array
(
	'con4gis' => array
	(
	    'c4g_core' => array
	    (
	    	'callback' 	=> 'c4g\C4GInfo',
			'tables' 	=> array(),
	 		'icon'	 	=> 'system/modules/con4gis_core/assets/images/core.png'
		)
	) 
));

?>