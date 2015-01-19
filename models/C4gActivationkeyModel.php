<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */


namespace c4g;

/**
 * Handels Activationkeys
 */
class C4gActivationkeyModel extends \Model
{
	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_c4g_activationkey';

	/**
	 * generates an activationlink from geiven key
	 * @param  string $key
	 * @return string
	 */
	public static function generateActivationLinkFromKey( $key )
	{
		// check if key exists
		if (empty( $key )) {
			return false;
		}

		// get action for this key
		$keyAction = static::getActionForKey($key);
		$keyAction = explode(':', $keyAction);

		// find an appropriate activationpage
		//
		// try to find a page with a specific handler for the key-action
		$objActivationPages = \ContentModel::findBy(
			array
			(
				'type=?',
				'c4g_activationpage_action_handler=?'
			),
			array
			(
				'c4g_activationpage',
				$keyAction[0]
			)
		);
		if (!$objActivationPages) {
			// if no page was found, try to find pages with automatic-handlers
			$objActivationPages = \ContentModel::findBy(
				array
				(
					'type=?',
					'c4g_activationpage_action_handler=?'
				),
				array
				(
					'c4g_activationpage',
					''
				)
			);
			// if still no page is found, the function failed
			if (!$objActivationPages) {
				return false;
			}
		}

		// use the first page (even if more pages are found)
		$objActivationPages->next();

		// get the article for this content-element
		$objArticle = \ArticleModel::findByPk( $objActivationPages->pid );
		if ($objArticle) {
			// if found, find the Page, where this article is nested
			$objPage = \PageModel::findByPk( $objArticle->pid );
			if ($objPage) {
				// if found build the desired URL (base + page-url + key)
				return \Environment::get('base') . \Controller::generateFrontendUrl( $objPage->row() ) . '?key=' . $key;
			}
		}

		// article or page not found
		return false;
	}

	/**
	 * Generates an Activationkey and saves it in the DB, if $saveInDB is true
	 * @param  string  $action
	 * @param  boolean $saveInDB
	 * @return string
	 */
	public static function generateActivationkey( $action, $saveInDB=true, $durability=1 )
	{
		// generate a unique key
		$attempts = 42;
		do {
			$key = md5(uniqid(rand(), true));
			$hasKey = static::findOneBy( 'activationkey', $key );
			$attempts--;
		} while (!empty( $hasKey ) && $attempts > 0);
		if (!empty( $hasKey )) { return false; }

		// save key in database
		if ($saveInDB) {
			$objKey = new C4gActivationkeyModel();
			$objKey->activationkey = $key;
			$objKey->expiration_date = strtotime('+' . $durability . ' month', time());
			$objKey->key_action = $action;
			$objKey->save();
		}

		return $key;
	}

	/**
	 * assigns an user to a key, if not already claimed
	 * @param  int $userId
	 * @param  string $key
	 * @return boolean
	 */
	public static function assignUserToKey( $userId, $key )
	{
		$objKey = static::findBy( 'activationkey', $key );
		if (empty( $objKey ) || $objKey->used_by != 0) { return false; }
		$objKey->used_by = $userId;
		$objKey->save();
		return true;
	}

	/**
	 * returns the action connected to the given key
	 * @param  string $key
	 * @return string
	 */
	public static function getActionForKey( $key )
	{
		return static::findOneBy( 'activationkey', $key )->key_action;
	}

	/**
	 * returns the action connected to the given key
	 * @param  string $key
	 * @return string
	 */
	public static function keyIsValid( $key )
	{
		$key = static::findOneBy( 'activationkey', $key );
		// the key exists, is not already claimed and is not expired
		return (!empty( $key ) && empty( $key->used_by ) && ($key->expiration_date == 0 || $key->expiration_date > time()));
	}
}