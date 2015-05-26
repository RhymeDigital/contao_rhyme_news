<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\Model;

use RhymeReplaced\NewsModel as Contao_NewsModel;


class NewsModel extends Contao_NewsModel
{
	
	/**
	 * Current method being called
	 * @var string
	 */
	protected static $strCurrentMethod = '';


	/**
	 * Find records and return the model or model collection
	 *
	 * Supported options:
	 *
	 * * column: the field name
	 * * value:  the field value
	 * * limit:  the maximum number of rows
	 * * offset: the number of rows to skip
	 * * order:  the sorting order
	 * * eager:  load all related records eagerly
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return \Model|\Model\Collection|null A model, model collection or null if the result is empty
	 */
	protected static function find(array $arrOptions)
	{
        // !HOOK: custom actions
        if (isset($GLOBALS['RHYME_NEWS']['find']) && is_array($GLOBALS['RHYME_NEWS']['find'])) {
            foreach ($GLOBALS['RHYME_NEWS']['find'] as $callback) {
                $objCallback = \System::importStatic($callback[0]);
                $objCallback->$callback[1]($arrOptions, static::$strCurrentMethod);
            }
        }
        
        return parent::find($arrOptions);
	}


	/**
	 * Find published news items by their parent ID and ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The NewsModel or null if there are no news
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'findPublishedByParentAndIdOrAlias';
		$varBuffer = parent::findPublishedByParentAndIdOrAlias($varId, $arrPids, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPids($arrPids, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'findPublishedByPids';
		$varBuffer = parent::findPublishedByPids($arrPids, $blnFeatured, $intLimit, $intOffset, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Count published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedByPids($arrPids, $blnFeatured=null, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'countPublishedByPids';
		$varBuffer = parent::countPublishedByPids($arrPids, $blnFeatured, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published news items with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The news archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'findPublishedDefaultByPid';
		$varBuffer = parent::findPublishedDefaultByPid($intPid, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published news items by their parent ID
	 *
	 * @param integer $intId      The news archive ID
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPid($intId, $intLimit=0, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'findPublishedByPid';
		$varBuffer = parent::findPublishedDefaultByPid($intId, $intLimit, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find all published news items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of news archive IDs
	 * @param integer $intLimit   An optional limit
	 * @param integer $intOffset  An optional offset
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news
	 */
	public static function findPublishedFromToByPids($intFrom, $intTo, $arrPids, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'findPublishedFromToByPids';
		$varBuffer = parent::findPublishedFromToByPids($intFrom, $intTo, $arrPids, $intLimit, $intOffset, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Count all published news items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of news archive IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedFromToByPids($intFrom, $intTo, $arrPids, array $arrOptions=array())
	{
		static::$strCurrentMethod = 'countPublishedFromToByPids';
		$varBuffer = parent::countPublishedFromToByPids($intFrom, $intTo, $arrPids, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}

}
