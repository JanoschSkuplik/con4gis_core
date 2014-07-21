<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz <http://www.kuestenschmiede.de>;
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */



/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BACK'] 				= 'Back';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['CANCEL']				= 'Cancel';

$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['INTRO'] 				= 'intro Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['FOOTER'] 			= 'A %s Project.';

$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['HEADLINE'] 		= 'Migration (cfs_%1$s -> con4gis_%1$s)';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['INTRO'] 		= 'Running this migration will copy the existing data from "cfs_%1$s" to "con4gis_%1$s" and afterwards configurates Contao to run with "con4gis_%1$s".';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['INTROWARN'] 	= 'Please note that this will OVERRIDE every entry of "con4gis_%s".';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['NOMODULEERROR']	= 'You cannot run this migration, since %s is not installed. Please install the module and try again.';

$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESS']			= 'Success';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['TRANSFEREDROW']		= 'Successfully transfered %d of %d rows';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['TRANSFEREDCOL']		= 'Successfully transfered %d columns';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESSMSG_1']		= 'Transfer complete.';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESSMSG_2']		= 'Reconfiguration complete. You can now uninstall "cfs_%s".';

$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAIL']				= 'Failed';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAILMSG_1']			= '%d of %d transfers failed. Please check your installation and try again.';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAILMSG_2']			= 'Reconfiguration failed. Please check your installation and try again.';

// button
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BTN']['MIGRATE'] 	= 'Migrate data';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BTN']['UNINSTALL'] 	= 'Uninstall "cfs_%s"';

// links
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['CONTAO_BOARD'] 		= 'Community Board (DE)';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['GITHUB'] 			= 'Fork on github';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATIONTOOL'] 		= 'Migrationtool';
$GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['INSTALL'] 			= 'Install %s';
?>