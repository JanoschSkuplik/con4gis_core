<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte & Tobias Dobbrunz <http://www.kuestenschmiede.de> 
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */


/**
 * Class C4GJQueryGUI
 */
class C4GJQueryGUI
{	
	public static function initializeTree ( $addCore=false, $addJQuery=true, $addJQueryUI=true )
	{
		C4GJQueryGUI::initializeLibraries( $addCore, $addJQuery, $addJQueryUI, true, false, false, false, false, false, false, false, true);
	}
	
	private static function optimizeJSForContao3 ( $libKey )
	{
		if (version_compare( VERSION, '3', '>=' ) && version_compare( VERSION, '3.2', '<' )) {
			$GLOBALS['TL_JAVASCRIPT'][$libKey] .= '|static';
		}
	}
	
	public static function initializeLibraries ( $addCore=true, $addJQuery=true, $addJQueryUI=true, $useTree=true, $useTable=true, $useHistory=true, $useTooltip=true, 
											   		$useMaps=false, $useGoogleMaps=false, $useMapsEditor=false, $useWswgEditor=false, $useScrollpane=false )
	{
   		if ($addJQuery) 
   		{	
   			if (version_compare( VERSION, '3', '>=' ) &&
   				is_array( $GLOBALS['TL_JAVASCRIPT'] ) && 
   				(array_search( 'assets/jquery/core/' . JQUERY . '/jquery.min.js|static', $GLOBALS['TL_JAVASCRIPT'] ) !== false))
   			{
   				// jQuery is already loaded by Contao 3, don't load again!
   			}
   			else {
				// Include JQuery JS
				$GLOBALS['TL_JAVASCRIPT']['c4g_jquery'] 			= 'system/modules/con4gis_core/lib/jQuery/jquery-1.11.1.min.js';
				// just until the old plugins are replaced
				// $GLOBALS['TL_JAVASCRIPT']['c4g_jquery_migrate']		= 'system/modules/con4gis_core/lib/jQuery/jquery-migrate-1.2.1.min.js';
				C4GJQueryGUI::optimizeJSForContao3('c4g_jquery');
				// Set JQuery to noConflict mode immediately after load of jQuery
				$GLOBALS['TL_JAVASCRIPT']['c4g_jquery_noconflict'] 	= 'system/modules/con4gis_core/assets/js/c4gjQueryNoConflict.js';	
		        C4GJQueryGUI::optimizeJSForContao3('c4g_jquery_noconflict');
   			}    
   		}

   		if ($addJQueryUI || $useTree || $useMaps) 
   		{
			// Include JQuery UI JS
			$GLOBALS['TL_JAVASCRIPT']['c4g_jquery_ui'] 		= 'system/modules/con4gis_core/lib/jQuery/ui-1.11.0.custom/jquery-ui.min.js';			
			C4GJQueryGUI::optimizeJSForContao3('c4g_jquery_ui');				
			$GLOBALS['TL_CSS']['c4g_jquery_ui_core'] 		= 'system/modules/con4gis_core/lib/jQuery/ui-1.11.0.custom/jquery-ui.min.css';
			// Set the JQuery UI theme to be used
			if (empty($GLOBALS['TL_CSS']['c4g_jquery_ui'])) {
				$GLOBALS['TL_CSS']['c4g_jquery_ui'] 		= 'system/modules/con4gis_core/lib/jQuery/ui-1.11.0.custom/jquery-ui.theme.min.css';
			}
   		}
   		
		if ($useTable) 
		{
			// Include DataTables JS
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_datatables'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/dataTables/js/jquery.dataTables.min.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_datatables');	
			// Include DataTables CSS
			$GLOBALS['TL_CSS']['c4g_jq_datatables'] 		= 'system/modules/con4gis_core/lib/jQuery/plugins/dataTables/css/jquery.dataTables_themeroller.css';
		}
		
		if ($useTree || $useMaps) 
		{	
			// Include dynatree JS
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_dynatree'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/dynatree/jquery.dynatree.min.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_dynatree');	
			// Include dynatree CSS
			$GLOBALS['TL_CSS']['c4g_jq_dynatree'] 			= 'system/modules/con4gis_core/lib/jQuery/plugins/dynatree/skin/ui.dynatree.css';			
		}
		
		if ($useHistory) 
		{
   			$GLOBALS ['TL_JAVASCRIPT'] ['c4g_jq_history'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jquery.history.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_history');
		}

		if ($useTooltip) 
		{
   			$GLOBALS ['TL_JAVASCRIPT'] ['c4g_jq_tooltip'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jquery.tooltip.pack.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_tooltip');
		}

		if ($useWswgEditor) 
		{
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_bbc'] 		= 'system/modules/con4gis_core/lib/wswgEditor/editor.js';
			$GLOBALS['TL_CSS']['c4g_jq_bbc'] 				= 'system/modules/con4gis_core/lib/wswgEditor/css/editor.css';
			$GLOBALS['TL_CSS']['c4g_jq_bbc2'] 				= 'system/modules/con4gis_core/lib/wswgEditor/css/bbcodes.css';
			// also use file-upload
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_fileupload'] 	= 'system/modules/con4gis_core/lib/fileUpload/upload.js';
		}

		if ($useScrollpane) 
		{
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_scrollpane'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jScrollPane/js/jquery.jscrollpane.min.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_scrollpane');	
			$GLOBALS['TL_JAVASCRIPT']['c4g_jq_mousewheel'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jScrollPane/js/jquery.mousewheel.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_jq_mousewheel');
			$GLOBALS['TL_JAVASCRIPT']['c4g_mwheelintent'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jScrollPane/js/mwheelIntent.js';
			C4GJQueryGUI::optimizeJSForContao3('c4g_mwheelintent');
			$GLOBALS['TL_CSS']['c4g_jq_scrollpane'] 		= 'system/modules/con4gis_core/lib/jQuery/plugins/jScrollPane/css/jquery.jscrollpane.css';
		}
		
		if ($useMaps && $GLOBALS['c4g_maps_extension']['installed']) 
		{
			$GLOBALS['TL_JAVASCRIPT']['c4g_openlayers'] = $GLOBALS['c4g_maps_extension']['js_openlayers_libs']['DEFAULT'];
	    	$GLOBALS['TL_JAVASCRIPT']['c4g_maps'] 		= 'system/modules/con4gis_maps/html/js/C4GMaps.js';
			
	    	if ($useGoogleMaps) {
	    		$GLOBALS['TL_JAVASCRIPT']['c4g_google'] = $GLOBALS['c4g_maps_extension']['js_google'];
	    	}	
		   	if ($useMapsEditor) {
		   		if ($GLOBALS['c4g_maps_extension']['js_editor']) {
		    		$GLOBALS['TL_JAVASCRIPT']['c4g_maps_edit'] 	= $GLOBALS['c4g_maps_extension']['js_editor'];
		    		$GLOBALS['TL_CSS']['c4g_maps_edit'] 		= $GLOBALS['c4g_maps_extension']['css_editor'];
		   		}	
		   	}
		   	// Include Extended LayerSwitcher JS
		   	$GLOBALS['TL_JAVASCRIPT']['c4g_layerswitcher'] 		= 'system/modules/con4gis_maps/html/js/C4GLayerSwitcher.js';
		   	// Include LayerSwitcher CSS (Dynatree styling)
		   	$GLOBALS['TL_CSS']['c4g_layerswitcher'] 			= 'system/modules/con4gis_maps/html/css/C4GLayerSwitcher.css';
		}
		
		if ($addCore) 
		{
	        $GLOBALS ['TL_JAVASCRIPT'] ['c4g_jquery_gui'] 	= 'system/modules/con4gis_core/lib/jQuery/plugins/jquery.c4gGui.js';
	        C4GJQueryGUI::optimizeJSForContao3('c4g_jquery_gui');
	        $GLOBALS ['TL_CSS'] ['c4g_jquery_gui'] 			= 'system/modules/con4gis_core/assets/css/c4gGui.css';
		}    		
	}
} 

?>