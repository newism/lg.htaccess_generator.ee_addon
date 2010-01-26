<?php
/**
* English language file for LG .htaccess Generator
* 
* This file must be placed in the
* /system/language/english/ folder in your ExpressionEngine installation.
*
* @package LGHtaccessGenerator
* @version 1.0.0
* @author Leevi Graham <http://leevigraham.com>
* @see http://leevigraham.com/cms-customisation/expressionengine/addon/lg-htaccess-generator/
* @copyright Copyright (c) 2007-2008 Leevi Graham
* @license {@link http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported} All source code commenting and attribution must not be removed. This is a condition of the attribution clause of the license.
*/

global $PREFS;

$L = array(

'enable_extension_title'	=> 'Enable extension',
'enable_extension_label'	=> 'Enable {addon_name} for this site?',

'check_for_updates_title' 	=> 'Check for updates',
'check_for_updates_info' 	=> '{addon_name} can call home, check for recent updates and display them on your CP homepage? This feature requires <a href="http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/">LG Addon Updater</a> to be installed and activated.',
'check_for_updates_label' 	=> 'Would you like this extension to check for updates?',

'success_extension_settings_saved'	=> 'Extension settings saved successfully',

'htaccess_options_title'	=> '.htaccess configuration',
'htaccess_options_info'		=> '<p>LG .htaccess Generator requires that an existing .htaccess file exists and is writable. Non LG .htacess rules these will not be modified by this extension.</p>
								<p style="margin-top:9px">The tgas below will be replaced with their associated values in the .htaccess template:</p>
								<ul>
									<li><strong><code>{ee:template_groups}</code></strong> will be replaced with a pipe delimited list of this sites template groups</li>
									<li><strong><code>{ee:pages}</code></strong> will be replaced with a pipe delimited list of this sites page urls</li>
									<li><strong><code>{ee:404}</code></strong> will be replaced with the sites 404 path ie: site/404</li>
								</ul>
								<p>Read more about the "include method" of removing your sites index.php on the <a rel="external" target="_blank" href="http://expressionengine.com/index.php?affiliate=leevi&amp;page=/wiki/Remove_index.php_From_URLs/#Include_List_Method">ExpressionEngine Wiki</a>.</p>
								<p>If you are using the Pages or Structure module make sure you add: <code>{ee:pages}</code> into the include string after <code>{ee:template_groups}</code>. If you are not using the Pages or Structure module remove this tag.</p>',

'htaccess_path_label'		=> '.htaccess server path',
'htaccess_path_info'		=> 'Must be writable by the server',
'htaccess_template_label'	=> ".htaccess template",

'htaccess_generated'		=> 'Your .htaccess file has been generated successfully.',

'htaccess_not_writable'		=> 'Your .htaccess could not be found or is not writable. Please check the .htaccess file exists and has the correct permissions.',
'dir_doesnt_exist'			=> 'Your .htaccess directory does not exist.',
'htaccess_generated_error'	=> 'There was an error generating your .htaccess file.',

// END
''=>''
);