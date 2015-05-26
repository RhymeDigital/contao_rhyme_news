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

use RhymeReplaced\ModuleNews as Contao_ModuleNews;


/**
 * Class ModuleNews
 *
 * Parent class for news modules.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
abstract class ModuleNews extends Contao_ModuleNews
{

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		// Store this ID so the NewsModel class and accompanying
		// hooks know which module is currently being generated.
		$GLOBALS['RHYME_NEWS']['LAST_GENERATED_MODULE'] = $this->id;
		
		return parent::generate();
	}
}
