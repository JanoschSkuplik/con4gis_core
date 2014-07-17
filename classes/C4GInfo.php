<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */

namespace c4g;


class C4GInfo extends \BackendModule
{
	protected $strTemplate = 'be_c4g_info';

	/**
     * Generate the module
     * @return string
     */
    public function generate()
    {
    	// check for actions (atm only "migrate")
    	if (\Input::get('act') != '' && \Input::get('act') == 'migrate') {
    		if (\Input::get('mod') != '') {
	    		$objCallback = new C4GMigration(\Input::get('mod'));
	    		return $objCallback->generate();
    		}
    	}

    	return parent::generate();
    }

	/**
     * Generate the module
     */
    protected function compile()
    {
    	$GLOBALS['TL_CSS'][] = 'system/modules/con4gis_core/assets/css/be_c4g_info.css';
        // $this->Template->c4gModules->con4gis_maps->installed = $GLOBALS[];
    }

}
?>