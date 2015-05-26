<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\Hooks\News\Find;


class ApplyFilters extends \Frontend
{

	/**
	 * Store the IDs of items that have been obtained by searching for their body text
	 * @var array
	 */
	protected static $arrCachedBodyIds = array();
	

	/**
	 * Apply filter values to the custom news model
	 * 
	 * Namespace:	Rhyme\Model
	 * Class:		NewsModel
	 * Method:		find
	 * Hook:		$GLOBALS['RHYME_NEWS']['find']
	 *
	 * @access		public
	 * @param		array
	 * @param		string
	 * @return		void
	 */
	public static function run(&$arrOptions, $strCurrentMethod)
	{
		if (!static::validateFilterAndLister())
		{
			return;
		}
		
		$arrFilters = static::getFilters();
		
		if (!empty($arrFilters))
		{
			$t = \NewsModel::getTable();
			
			\System::loadLanguageFile($t);
			\Controller::loadDataContainer($t);
			
			foreach ($arrFilters as $key=>$val)
			{
				$strFilterType = $GLOBALS['TL_DCA'][$t]['fields'][$key]['attributes']['fe_filter_type'] ?: 'rgxp';
				
				if ($key == 'body')
				{
					$arrIds = static::getItemsIdsWithBody($val);
					$arrOptions['column'][] = "($t.id IN ('".implode("','", $arrIds)."') OR $t.teaser REGEXP ?)";
					$arrOptions['value'][] = $val;
					continue;
				}
				
				if (is_array($val) && !empty($val))
				{
					$strWhere = "(";
					foreach ($val as $i=>$opt)
					{
						if ($i != 0)
						{
							$strWhere .= " OR ";
						}
						
						list($key, $val, $strWhere, $strFilterType, $arrOptions) = static::getWhere($key, $val, $strWhere, $strFilterType, $arrOptions);
						
						$arrOptions['value'][] = $opt;
					}
					$strWhere .= ")";
					
					$arrOptions['column'][] = $strWhere;
					
				}
				else
				{
					$strWhere = "";
					list($key, $val, $strWhere, $strFilterType, $arrOptions) = static::getWhere($key, $val, $strWhere, $strFilterType, $arrOptions);
					$arrOptions['column'][] = $strWhere;
					$arrOptions['value'][] = $val;
				}
			}
		}
	}
	
	protected static function getWhere($key, $val, $strWhere, $strFilterType, $arrOptions)
	{
		$t = \NewsModel::getTable();
		$strWhere .= "$t.$key ";
		switch ($strFilterType)
		{
			// todo: add things like gte, lte, etc.
			case 'equals':
				$strWhere .= "=?";
				break;
				
			case 'rgxp':
			case 'rgxpstr':
				$strWhere .= "REGEXP ?";
				break;
				
			case 'rgxpint':
				$strWhere .= "REGEXP CONCAT(':', ?, ';')";
				break;
				
			case 'rgxpintstr':
				$strWhere .= "REGEXP CONCAT('\"', ?, '\"')";
				break;
			
			default:
		        // !HOOK: custom...
		        if (isset($GLOBALS['TL_HOOKS']['newsFiltersGetWhere']) && is_array($GLOBALS['TL_HOOKS']['newsFiltersGetWhere'])) {
		            foreach ($GLOBALS['TL_HOOKS']['newsFiltersGetWhere'] as $callback) {
		                $objCallback = \System::importStatic($callback[0]);
		                list($key, $val, $strWhere, $strFilterType, $arrOptions) = $objCallback->$callback[1]($key, $val, $strWhere, $strFilterType, $arrOptions);
		            }
		        }
				break;
		}
		
		return array($key, $val, $strWhere, $strFilterType, $arrOptions);
	}


	/**
	 * Get all news item IDs with body matching the given string
	 *
	 * @param integer $strBody    The body string to search for
	 * @param array   $arrOptions An optional options array
	 *
	 * @return array The IDs of the news items
	 */
	protected static function getItemsIdsWithBody($strBody, array $arrOptions=array())
	{
		if (empty($strBody))
		{
			return array();
		}
		
		$strKey = standardize($strBody);
		
		if (!isset(static::$arrCachedBodyIds[$strKey]))
		{
			$time = time();
			$t = \NewsModel::$strTable;
			$c = \ContentModel::getTable();
			
			$objResult = \Database::getInstance()->prepare("SELECT $c.pid FROM $c
															WHERE $c.ptable='$t' 
															AND $c.text REGEXP ? 
															AND ($c.start='' OR $c.start<$time) AND ($c.stop='' OR $c.stop>$time) AND $c.invisible=''")
												 ->executeUncached($strBody);
			
			if ($objResult->numRows)
			{
				static::$arrCachedBodyIds[$strKey] = array_unique($objResult->fetchEach('pid'));
			}
		}
		
		return (array)static::$arrCachedBodyIds[$strKey];
	}
	
	
	protected static function getFilters()
	{
		$arrFilters = array();
		$arrGetKeys = array_keys((array)$_GET);
		
		foreach ((array)$arrGetKeys as $key)
		{
			if ((\Database::getInstance()->fieldExists($key, \NewsModel::getTable()) || $key == 'body') && \Input::get($key))
			{
				if (is_array(\Input::get($key)))
				{
					$arrValues = array();
					
					foreach (\Input::get($key) as $val)
					{
						if ($val)
						{
							$arrValues[] = $val;
						}
					}
					
					if (!empty($arrValues))
					{
						$arrFilters[$key] = $arrValues;
					}
				}
				else
				{
					$arrFilters[$key] = \Input::get($key);
				}
			}
		}
		
		return $arrFilters;
	}
	
	
	protected static function validateFilterAndLister()
	{
		// See if we have a "last generated module" ID, lists in the GET params, and that the last generated module is one of the lists
		if (!isset($GLOBALS['RHYME_NEWS']['LAST_GENERATED_MODULE']) ||
			!$GLOBALS['RHYME_NEWS']['LAST_GENERATED_MODULE'] || 
			!\Input::get('lists') || 
			!in_array($GLOBALS['RHYME_NEWS']['LAST_GENERATED_MODULE'], trimsplit(',', \Input::get('lists')))
		)
		{
			return false;
		}

		$objRow = \ModuleModel::findByPk($GLOBALS['RHYME_NEWS']['LAST_GENERATED_MODULE']);

		// See if we have a row, and check the visibility (see #6311)
		if ($objRow === null || !\Controller::isVisibleElement($objRow))
		{
			return false;
		}
		
		return true;
	}
	
}
