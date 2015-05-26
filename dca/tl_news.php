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
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['headline']['attributes']['fe_filter']						= true;
$GLOBALS['TL_DCA']['tl_news']['fields']['headline']['attributes']['fe_filter_type']					= 'rgxp';
$GLOBALS['TL_DCA']['tl_news']['fields']['author']['attributes']['fe_filter']						= true;
$GLOBALS['TL_DCA']['tl_news']['fields']['author']['attributes']['fe_filter_type']					= 'equals';
$GLOBALS['TL_DCA']['tl_news']['fields']['subheadline']['attributes']['fe_filter']					= true;
$GLOBALS['TL_DCA']['tl_news']['fields']['subheadline']['attributes']['fe_filter_type']				= 'rgxp';
