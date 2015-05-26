<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\Backend\Module;


class NewsFilter extends \Backend
{
	
	public function __construct()
	{
		\System::loadLanguageFile('tl_news');
		\Controller::loadDataContainer('tl_news');
		
		parent::__construct();
	}
	

	/**
	 * Get filterable news fields
	 *
	 * @access		public
	 * @return		array
	 */
	public function getNewsFilterFields()
	{
		$arrReturn = array('body'=>'Body');
		
		foreach ($GLOBALS['TL_DCA']['tl_news']['fields'] as $key=>$data)
		{
			if ($data['attributes'] && $data['attributes']['fe_filter'])
			{
				$arrReturn[$key] = $data['label'][0];
			}
		}
		
		return $arrReturn;
	}


	/**
	 * Get news lister modules
	 *
	 * @access		public
	 * @return		array
	 */
	public function getNewsListModules()
	{
		$arrReturn = array();
		
		$objModules = \ModuleModel::findAll();
		
		if ($objModules === null)
		{
			return $arrReturn;
		}
		
		while ($objModules->next())
		{
			if (stripos($objModules->current()->type, 'news') !== false)
			{
				$arrReturn[strval($objModules->current()->id)] = $objModules->current()->name;
			}
		}
		
		return $arrReturn;
	}
	
	
}
