<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */


/***
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['c4g_activationpage'] =  '{type_legend},type,headline;'.
                                                                      '{c4g_activationpage_function_legend},c4g_activationpage_action_handler,c4g_activationpage_confirmation;'.
                                                                      '{c4g_activationpage_custom_message_legend:hide},c4g_activationpage_success_msg,c4g_activationpage_invalid_key_msg,c4g_activationpage_handler_error_msg;'.
                                                                      '{template_legend:hide},c4g_activationpage_use_default_css,customTpl;'.
                                                                      '{protected_legend:hide},protected;'.
                                                                      '{expert_legend:hide},guests,cssID,space';
/***
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'c4g_activationpage_confirmation';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['c4g_activationpage_confirmation'] = 'c4g_activationpage_confirmation_text,c4g_activationpage_confirmation_button';

/***
 * Fields
 */

// function group
//
$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_action_handler'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['action_handler'],
  'exclude'                 => true,
  'inputType'               => 'select',
  'options_callback'        => array('tl_content_c4g_activationpage', 'get_registered_action_handlers'),
  'eval'                    => array('includeBlankOption' => true, 'blankOptionLabel' => $GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['msc']['auto_action_handler']),
  'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_confirmation'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['confirmation'] ,
  'exclude'                 => true,
  'inputType'               => 'checkbox',
  'eval'                    => array('submitOnChange' => true),
  'sql'                     => "char(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_confirmation_text'] = array
(
  'label'           => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['confirmation_text'] ,
  'search'          => true,
  'inputType'       => 'textarea',
  'eval'            => array('rte'=>'tinyMCE'),
  'sql'             => "text NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_confirmation_button'] = array
(
  'label'           => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['confirmation_button'] ,
  'search'          => true,
  'inputType'       => 'text',
  'sql'             => "varchar(255) NOT NULL default ''"
);

// custom message group
//
$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_success_msg'] = array
(
  'label'           => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['success_msg'],
  'search'          => true,
  'inputType'       => 'textarea',
  'eval'            => array('rte'=>'tinyMCE'),
  'sql'             => "text NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_invalid_key_msg'] = array
(
  'label'           => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['invalid_key_msg'],
  'search'          => true,
  'inputType'       => 'textarea',
  'eval'            => array('rte'=>'tinyMCE'),
  'sql'             => "text NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_handler_error_msg'] = array
(
  'label'           => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['handler_error_msg'],
  'search'          => true,
  'inputType'       => 'textarea',
  'eval'            => array('rte'=>'tinyMCE'),
  'sql'             => "text NULL"
);

// template-group
//
$GLOBALS['TL_DCA']['tl_content']['fields']['c4g_activationpage_use_default_css'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_content']['c4g_activationpage']['fields']['use_default_css'] ,
  'exclude'                 => true,
  'inputType'               => 'checkbox',
  'sql'                     => "char(1) NOT NULL default '1'"
);

/**
 * Class tl_content_c4g_activationpage
 *
 * Provide methods that are used by the data configuration array.
 */
class tl_content_c4g_activationpage extends \Backend
{
  public function get_registered_action_handlers ()
  {
    // prepare the "registered-action-handlers"-array
    // for returning
    $arrHandlerList = array();
    foreach ($GLOBALS['C4G_ACTIVATIONACTION'] as $action => $handler) {
      $arrHandlerList[$action] = $action . ' (' . $handler . ')';
    }

    return $arrHandlerList;
  }
}
