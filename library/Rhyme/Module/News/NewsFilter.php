<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\Module\News;


/**
 * Class NewsList
 *
 * Front end module "news list" custom for NECEC
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class NewsFilter extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsfilter';

	/**
	 * Selected filters
	 * @var array
	 */
	protected $arrFilters = array();


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['newsfilter'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		\System::loadLanguageFile(\NewsModel::getTable());
		\Controller::loadDataContainer(\NewsModel::getTable());

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;
		$arrFilters = array();
		$arrFields = deserialize($this->news_filterfields, true);
		
		if (!empty($arrFields))
		{
			foreach ($arrFields as $strField)
			{
				$varValue = \Input::get($strField) ?: null;
				
				if ($strField == 'body')
				{
					$objWidget = new \FormTextField(array
					(
						'name'			=> $strField,
						'id'			=> $strField,
						'value'			=> $varValue,
					));
				}
				else
				{
					$GLOBALS['TL_DCA'][\NewsModel::getTable()]['fields'][$strField]['eval']['mandatory'] = false;
					$GLOBALS['TL_DCA'][\NewsModel::getTable()]['fields'][$strField]['eval']['required'] = false;
					$GLOBALS['TL_DCA'][\NewsModel::getTable()]['fields'][$strField]['eval']['includeBlankOption'] = true;
					
					$objWidget = static::getFrontendWidgetFromDca(\NewsModel::getTable(), $strField, $varValue);
				}

		        // !HOOK: get news filter widget
		        if (isset($GLOBALS['TL_HOOKS']['getRhymeNewsFilterWidget']) && is_array($GLOBALS['TL_HOOKS']['getRhymeNewsFilterWidget'])) {
		            foreach ($GLOBALS['TL_HOOKS']['getRhymeNewsFilterWidget'] as $callback) {
		                $objCallback = \System::importStatic($callback[0]);
		                $objWidget = $objCallback->$callback[1]($objWidget, $strField, $varValue, $arrFilters, $this);
		            }
		        }
				
				$strBuffer = $objWidget->generate();
				
				$arrFilters[$strField] = $strBuffer;
				$this->Template->{'filter_'.$strField} = $strBuffer;
			}
		}
		
		$objJumpTo = $this->jumpTo ? \PageModel::findByPk($this->jumpTo) : \PageModel::findByPk($objPage->id);
		
		$this->Template->action					= $this->generateFrontendUrl($objJumpTo->row());
		$this->Template->filters				= $arrFilters;
		$this->Template->targetlistmodules 		= implode(',', deserialize($this->news_targetlistmodules, true));
		$this->Template->submit_label			= $GLOBALS['TL_LANG']['MSC']['newsfilter_submit'];
	}
	
	
	
	/**
	 * Create a widget using the table, field, and optional current value
	 */
	protected static function getFrontendWidgetFromDca($strTable, $strField, $varValue=null)
	{
		$strClass = $GLOBALS['TL_FFL'][$GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType']];

		if (class_exists($strClass))
		{
			return new $strClass(\Widget::getAttributesFromDca($GLOBALS['TL_DCA'][$strTable]['fields'][$strField], $strField, $varValue, $strField, $strTable));
		}
		
		return null;
	}
}
