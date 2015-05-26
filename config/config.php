<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Override default namespace
 */
class_alias('Contao\NewsModel', 'RhymeReplaced\NewsModel');
class_alias('Rhyme\Model\NewsModel', '\NewsModel');

class_alias('Contao\ModuleNews', 'RhymeReplaced\ModuleNews');
class_alias('Rhyme\Module\News\ModuleNews', '\ModuleNews');


/**
 * Frontend Modules
 */
$GLOBALS['FE_MOD']['news']['newsfilter']			= 'Rhyme\Module\News\NewsFilter';


/**
 * Hooks
 */
$GLOBALS['RHYME_NEWS']['find'][]					= array('Rhyme\Hooks\News\Find\ApplyFilters', 'run');
