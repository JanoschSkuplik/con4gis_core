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


class C4GMigration extends \BackendModule
{
	protected $strTemplate = 'be_c4g_migration';
    protected $module = '';

    /**
     * [__construct description]
     * @param [type] $mod [description]
     */
    public function __construct( $mod )
    {
        $this->module = $mod;
    }

	/**
     * Generate the module
     * @return string
     */
    public function generate()
    {
        
        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
    	$this->Template->mod = $this->module;
    	// $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_core/assets/css/be_c4g_info.css';
        // $this->Template->c4gModules->con4gis_maps->installed = $GLOBALS[];
    }

}
?>