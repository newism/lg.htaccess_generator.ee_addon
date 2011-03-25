<?php

/**
 * LG .htaccess Generator extension file
 * 
 * This file must be placed in the
 * /system/extensions/ folder in your ExpressionEngine installation.
 *
 * @package LGHtaccessGenerator
 * @version 1.1.0
 * @author Leevi Graham <http://leevigraham.com>
 * @see http://leevigraham.com/cms-customisation/expressionengine/addon/lg-htaccess-generator/
 * @copyright Copyright (c) 2007-2008 Leevi Graham
 * @license {@link http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported} All source code commenting and attribution must not be removed. This is a condition of the attribution clause of the license.
 */

if ( ! defined('EXT')) exit('Invalid file request');

/**
 * This extension adds a new tab to the CP publish / edit pages. It also adds a 'quick tweet' bar to the footer of the CP.
 *
 * @package LGHtaccessGenerator
 * @version 1.1.0
 * @author Leevi Graham <http://leevigraham.com>
 * @see http://leevigraham.com/cms-customisation/expressionengine/addon/lg-htaccess-generator/
 * @copyright Copyright (c) 2007-2008 Leevi Graham
 * @license {@link http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported} All source code commenting and attribution must not be removed. This is a condition of the attribution clause of the license.
 *
 */
class Lg_htaccess_generator_ext{

	/**
	 * Extension name
	 * 
	 * @var		string
	 * @since	Version 1.0.0
	 */
	var $name = 'LG .htaccess Generator';

	/**
	 * Extension version
	 * 
	 * @var		string
	 * @since	Version 1.0.0
	 */
	var $version = '1.1.0';

	/**
	 * Extension description
	 * 
	 * @var		string
	 * @since	Version 1.0.0
	 */
	var $description = 'Generates .htacess files to remove index.php from your urls';

	/**
	 * If $settings_exist = 'y' then a settings page will be shown in the ExpressionEngine admin
	 * 
	 * @since  	Version 1.0.0
	 * @var 	string
	 */
	var $settings_exist = 'y';
	
	/**
	 * Link to extension documentation
	 * 
	 * @since  	Version 1.0.0
	 * @var 	string
	 */
	var $docs_url = "http://leevigraham.com/cms-customisation/expressionengine/lg-htaccess-generator/";

	/**
	 * Default settings
	 * 
	 * @var 	array
	 * @since	Version 1.1.0
	 */
	var $default_settings = array(
		'enabled' => TRUE,
		'check_for_updates' => TRUE,
		'htaccess_path'		=> '',
		'htaccess_template'	=> '# secure .htaccess file
<Files .htaccess>
 order allow,deny
 deny from all
</Files>

# EE 404 page for missing pages
ErrorDocument 404 /index.php?/{ee:404}

# Simple 404 for missing files
<FilesMatch "(\.jpe?g|gif|png|bmp|css|js|flv)$">
  ErrorDocument 404 "File Not Found"
</FilesMatch>

RewriteEngine On
RewriteBase /

# remove the www
RewriteCond %{HTTP_HOST} ^(www\.$) [NC]
RewriteRule ^ http://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Add a trailing slash to paths without an extension
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !(\.[a-zA-Z0-9]{1,5}|/)$
RewriteRule ^(.*)$ $1/ [L,R=301]

# Remove index.php
# Uses the "include method"
# http://expressionengine.com/wiki/Remove_index.php_From_URLs/#Include_List_Method
RewriteCond %{QUERY_STRING} !^(ACT=.*)$ [NC]
RewriteCond %{REQUEST_URI} !(\.[a-zA-Z0-9]{1,5})$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} ^/({ee:template_groups}{ee:pages}members|P[0-9]{2,8}) [NC]
RewriteRule (.*) /index.php?$1&%{QUERY_STRING} [L]');

	/**
	 * Extension hooks
	 * 
	 * @var 	array
	 * @since	Version 1.1.0
	 */
	var $hooks = array(
			'submit_new_entry_end',
			'publish_form_start',
			'update_template_end',
			'show_full_control_panel_end',
			'lg_addon_update_register_addon',
			'lg_addon_update_register_source'
	);

	/**
	 * Paypal details for donate button
	 * 
	 * @var 	array
	 * @since	Version 1.1.0
	 */
	var $paypal 			=  array(
		"account"				=> "sales@newism.com.au",
		"donations_accepted"	=> TRUE,
		"donation_amount"		=> "20.00",
		"currency_code"			=> "USD",
		"return_url"			=> "http://leevigraham.com/donate/thanks/",
		"cancel_url"			=> "http://leevigraham.com/donate/cancel/"
	);

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array|string $settings Extension settings associative array or an empty string
	 * @since	Version 1.0.0
	 */
	public function __construct($settings='')
	{
		global $IN, $SESS;
		if(isset($SESS->cache['lg_htaccess_generator']) === FALSE){ $SESS->cache['lg_htaccess_generator'] = array();}
		if(isset($SESS->cache['Morphine']) === FALSE) $SESS->cache['Morphine'] = array();
		$this->settings = ($settings == FALSE) ? $this->get_settings() : $this->save_settings_to_session($settings);
	}

	/**
	 * Activate the extension
	 * 
	 * @access 	public
	 * @see 	http://expressionengine.com/docs/development/extensions.html#enable
	 * @since	Version 1.0.0
	 */
	public function activate_extension()
	{
		$this->create_hooks();
	}

	/**
	 * Update the extension
	 *
	 * @access 	public
	 * @see		http://expressionengine.com/docs/development/extensions.html#enable
	 * @since	Version 1.0.0
	 * @param	string $current The current installed version
	 */
	public function update_extension($current = '')
	{
		$this->update_hooks();
	}

	/**
	 * Disable the extension
	 * 
	 * @access 	public
	 * @since	Version 1.0.0
	 * @see		http://expressionengine.com/docs/development/extensions.html#disable
	 */
	public function disable_extension(){}

	/**
	 * Render the settings form
	 * 
	 * @access 	public
	 * @param	string $current_settings The current settings
	 * @see		http://expressionengine.com/docs/development/extensions.html#settings
	 * @since	Version 1.0.0
	 */
	public function settings_form($current_settings)
	{

		global $DB, $DSP, $LANG, $PREFS, $REGX, $SESS;

		$site_id = $PREFS->ini("site_id");
		$settings = $this->settings[$site_id];
		$addon_name = $this->name;

		$lgau_query = $DB->query("SELECT class FROM exp_extensions WHERE class = 'Lg_addon_updater_ext' AND enabled = 'y' LIMIT 1");
		$lgau_enabled = $lgau_query->num_rows ? TRUE : FALSE;

		$DSP->title = $this->name . " " . $this->version . " | " . $LANG->line('extension_settings');
		$DSP->crumbline = TRUE;
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'area=utilities', $LANG->line('utilities'));
		$DSP->crumb .= $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=extensions_manager', $LANG->line('extensions_manager')));
		$DSP->crumb .= $DSP->crumb_item($this->name . " " . $this->version);

		$DSP->body .= "<div class='mor settings-form'>";
		// PAYPAL
		if(isset($this->paypal["donations_accepted"]) === TRUE)
		{
			$DSP->body .= "<p class='donate paypal'>
								<a rel='external'"
									. "href='https://www.paypal.com/cgi-bin/webscr?"
										. "cmd=_donations&amp;"
										. "business=".rawurlencode($this->paypal["account"])."&amp;"
										. "item_name=".rawurlencode($this->name . " Development: Donation")."&amp;"
										. "amount=".rawurlencode($this->paypal["donation_amount"])."&amp;"
										. "no_shipping=1&amp;return=".rawurlencode($this->paypal["return_url"])."&amp;"
										. "cancel_return=".rawurlencode($this->paypal["cancel_url"])."&amp;"
										. "no_note=1&amp;"
										. "tax=0&amp;"
										. "currency_code=".$this->paypal["currency_code"]."&amp;"
										. "lc=US&amp;"
										. "bn=PP%2dDonationsBF&amp;"
										. "charset=UTF%2d8'"
									."class='button'
									target='_blank'>
									Support this addon by donating via PayPal.
								</a>
							</p>";
		}
		$DSP->body .= $DSP->heading("{$this->name} <small>{$this->version}</small>");
		$DSP->body .= $DSP->form_open(
								array('action' => 'C=admin'.AMP.'M=utilities'.AMP.'P=save_extension_settings'),
								array('name' => strtolower(get_class($this))
							));
		ob_start(); include(PATH_LIB.'lg_htaccess_generator/views/Lg_htaccess_generator_ext/form_settings.php'); $DSP->body .= ob_get_clean();
		$DSP->body .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit("Save extension settings"));
		$DSP->body .= $DSP->form_c();
		$DSP->body .= "</div>";
	}

	/**
	 * Save the settings
	 * 
	 * @access 	public
	 * @since	Version 1.0.0
	 * @see http://expressionengine.com/docs/development/extensions.html#settings
	 */
	public function save_settings()
	{
		global $IN, $PREFS;
		$new_settings = $IN->GBL("Lg_htaccess_generator_ext", "POST");
		if(substr($new_settings['htaccess_path'], -1) != "/")
		{
			$new_settings['htaccess_path'] .= "/";
		}
		$this->settings[$PREFS->ini("site_id")] = $new_settings;
		$this->save_settings_to_db($this->settings);
	}

	/**
	 * Saves the objects hooks to the DB
	 * 
	 * @access 	private
	 * @since	Version 1.1.0
	 */
	private function create_hooks()
	{
		global $DB;

		$hook_template = array(
			'class'    => get_class($this),
			'settings' => FALSE,
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		);

		foreach($this->hooks as $key => $value)
		{
			if(is_array($value))
			{
				$hook["hook"] = $key;
				$hook["method"] = (isset($value["method"]) === TRUE) ? $value["method"] : $key;
				$hook = array_merge($hook, $value);
			}
			else
			{
				$hook["hook"] = $hook["method"] = $value;
			}
			$hook = array_merge($hook_template, $hook);
			$DB->query($DB->insert_string('exp_extensions', $hook));
		}
	}

	/**
	 * Updates the objects hooks in the DB
	 * 
	 * Delete the current hooks, recreate them from scratch
	 * 
	 * @access 	private
	 * @since	Version 1.1.0
	 */
	private function update_hooks()
	{
		$this->delete_hooks();
		$this->create_hooks();
	}

	/**
	 * Delete the objects hooks from the DB
	 * 
	 * @access 	private
	 * @since	Version 1.1.0
	 */
	private function delete_hooks()
	{
		global $DB;
		$DB->query("DELETE FROM `exp_extensions` WHERE `class` = '".get_class($this)."'");
		return $DB->affected_rows;
	}

	/**
	 * Method for the update_template_end undocumented hook
	 * 
	 * Called when a template is saved
	 * 
	 * @access 	public
	 * @param	int $template_id The saved templates id
	 * @param	string $message Any wanrings, error or success messages
	 * @since	Version 1.1.0
	 */
	public function update_template_end($template_id, $message)
	{
		global $IN;
		if (session_id() == "") session_start(); // if no active session we start a new one
		$_SESSION['lg_htaccess_generator']['response'] = $this->generate_htaccess();
	}

	/**
	 * Method for the publish_form_start hook
	 *
	 * - Runs before any data is processed
	 *
	 * @access	public
	 * @param	string $which The current action (new, preview, edit, or save)
	 * @param	string $submission_error A submission error if any
	 * @param	string $entry_id The current entries id
	 * @since	Version 1.1.0
	 * @see		http://expressionengine.com/developers/extension_hooks/publish_form_start/
	 */
	public function publish_form_start( $which, $submission_error, $entry_id, $hidden )
	{
		global $IN;
		if(empty($entry_id)) $entry_id = $IN->GBL("entry_id");
		if($which == "save" && !empty($entry_id))
		{
			$this->submit_new_entry_end($entry_id);
		}
	}

	/**
	 * Method for the submit_new_entry_end hook
	 * 
	 * - Runs after a new entry has been validated and created in the database
	 * - Manipulates data from the posted custom field ready for DB insert
	 * - Checks to see if the record was created properly
	 *
	 * @access	public
	 * @param	int 	$entry_id 		The saved entry id
	 * @param	array 	$data 			Array of data about entry (title, url_title)
	 * @param	string 	$ping_message	Error message if trackbacks or pings have failed to be sent
	 * @since	Version 1.0.0
	 * @see		http://expressionengine.com/developers/extension_hooks/submit_new_entry_end/
	 */
	public function submit_new_entry_end($entry_id)
	{
		global $IN, $LANG, $OUT, $PREFS;
		$site_id = $PREFS->ini("site_id");
		if($this->settings[$site_id]['enabled'] == TRUE)
		{
			if (
				$IN->GBL('pages_uri', 'POST') !== FALSE
				&& $IN->GBL('pages_uri', 'POST') != ''
				&& $IN->GBL('pages_uri', 'POST') != '/example/pages/uri/'
				&& is_numeric($IN->GBL('pages_template_id', 'POST'))
			)
			{

				$pages = $PREFS->ini('site_pages');
				if($PREFS->ini("app_version") > 168) 
					$pages = $pages[$PREFS->ini("site_id")];

				$pages['uris'][$entry_id] = $IN->GBL('pages_uri', 'POST');
				$pages['templates'][$entry_id] = $IN->GBL('pages_template_id', 'POST');
				if (session_id() == "") session_start(); // if no active session we start a new one
				$_SESSION['lg_htaccess_generator']['response'] = $this->generate_htaccess();
			}
		}
		
	}

	/**
	 * Takes the control panel html before it is sent to the browser
	 *
	 * This method does two main things:
	 * - Changes the custom field name in the {@link http://expressionengine.com/docs/cp/admin/weblog_administration/custom_fields_edit.html Add/Edit Custom Fields page}
	 * - Adds custom scripts and styles to the publish / edit form when creating editing polls.
	 *
	 * @access	public
	 * @param	string $out The control panel html
	 * @return	string The modified control panel html
	 * @since 	Version 1.0.0
	 * @see		http://expressionengine.com/developers/extension_hooks/show_full_control_panel_end/
	 */
	public function show_full_control_panel_end($out)
	{
		global $IN, $PREFS, $SESS;
		$this->get_last_call($out);

		if(isset($SESS->cache['Morphine']['cp_styles_included']) === FALSE)
		{
			$css = "\n<link rel='stylesheet' type='text/css' media='screen' href='" . $PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/Morphine/css/MOR_screen.css' />";
			$out = str_replace("</head>", $css . "</head>", $out);
			$SESS->cache['Morphine']['cp_styles_included'] = TRUE;
		}


		if (session_id() == "") session_start();
		if(isset($_SESSION['lg_htaccess_generator']['response']))
		{
			$response = $_SESSION['lg_htaccess_generator']['response'];
			unset($_SESSION['lg_htaccess_generator']['response']);
			$msg_style = ($response['success'] === TRUE) ? 'success' : 'error';
			$notice = "<div class='mor-alert {$msg_style}'>{$response['msg']}</div>";
			$out = str_replace("id='content'>", "id='content'>{$notice}", $out);
			$out = str_replace("id='contentNB'>", "id='contentNB'>{$notice}", $out);
		}
		return $out;
	}

	/**
	 * Register a new Addon Source
	 *
	 * @access	public
	 * @param	array $sources The existing sources
	 * @return	array The new source list
	 * @see 	http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
	 * @since	Version 1.0.0
	 */
	public function lg_addon_update_register_source($sources)
	{
		global $EXT, $PREFS;
		$this->get_last_call($sources);

		// add a new source
		// must be in the following format:
		/*
		<versions>
			<addon id='LG Addon Updater' version='2.0.0' last_updated="1218852797" docs_url="http://leevigraham.com/" />
		</versions>
		*/
		if($this->settings[$PREFS->ini('site_id')]['check_for_updates'] == 'y')
		{
			$sources[] = 'http://leevigraham.com/version-check/versions.xml';
		}

		return $sources;

	}

	/**
	 * Register a new Addon
	 *
	 * @access	public
	 * @param	array $addons The existing sources
	 * @return	array The new addon list
	 * @see		http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
	 * @since	Version 1.0.0
	 */
	public function lg_addon_update_register_addon($addons)
	{
		global $EXT, $PREFS;
		// -- Check if we're not the only one using this hook
		$this->get_last_call($addons);

		// add a new addon
		// the key must match the id attribute in the source xml
		// the value must be the addons current version
		if($this->settings[$PREFS->ini('site_id')]['check_for_updates'] == 'y')
		{
			$addons["LG .htaccess generator"] = $this->version;
		}

		return $addons;
	}

	/**
	 * Get the extension settings from the $SESS or database
	 *
	 * @access	private
	 * @param	array $addons The existing sources
	 * @return	array The new addon list
	 * @since	Version 1.0.0
	 */
	private function get_settings($refresh = FALSE)
	{
		global $DB, $PREFS, $REGX;
		$settings = FALSE;
		$site_id = $PREFS->ini("site_id");
		if(isset($SESS->cache['lg_htaccess_generator'][__CLASS__]['settings']) === FALSE || $refresh === TRUE)
		{
			$settings_query = $DB->query("SELECT `settings` FROM `exp_extensions` WHERE `enabled` = 'y' AND `class` = '".__CLASS__."' LIMIT 1");
			// if there is a row and the row has settings
			if ($settings_query->num_rows > 0 && $settings_query->row['settings'] != '')
			{
				// save them to the cache
				$settings = $REGX->array_stripslashes(unserialize($settings_query->row['settings']));
			}
		}
		else
		{
			$settings = $SESS->cache['lg_htaccess_generator'][__CLASS__]['settings'];
		}
		if(isset($settings[$site_id]) == FALSE)
		{
			$settings[$site_id] = $this->build_default_settings();
			$this->save_settings_to_db($settings);
		}
		$this->save_settings_to_session($settings);
		return $settings;
	}

	/**
	 * Save the extension settings to the current $SESS
	 *
	 * @access	private
	 * @param	array $settings The existing sources
	 * @since	Version 1.1.0
	 */
	private function save_settings_to_session($settings = FALSE)
	{
		$SESS->cache['lg_htaccess_generator'][__CLASS__]['settings'] = $settings;
		return $settings;
	}

	/**
	 * Save the extension settings to the database
	 *
	 * @access	private
	 * @param	array $settings The existing sources
	 * @since	Version 1.1.0
	 */
	private function save_settings_to_db($settings)
	{
		global $DB;
		$DB->query($DB->update_string("exp_extensions", array("settings" => $this->serialize($settings)), array("class" => __CLASS__)));
	}

	/**
	 * Build the default settings array
	 *
	 * @access	private
	 * @param	array $settings The existing sources
	 * @since	Version 1.1.0
	 */
	private function build_default_settings()
	{
		$site_settings = $this->default_settings;
		$site_settings['htaccess_path'] = (isset($_SERVER['DOCUMENT_ROOT']) === TRUE) ? $_SERVER['DOCUMENT_ROOT'] : "";
		return $site_settings;
	}

	/**
	 * Generate the .htaccess file and save it
	 *
	 * @access	private
	 * @param	array $settings The existing sources
	 * @return	array Response status and message array("success" => TRUE|FALSE, "message" => "...")
	 * @since	Version 1.1.0
	 */
	private function generate_htaccess()
	{
 		global $DB, $FNS, $LANG, $PREFS, $OUT;
		
		$LANG->fetch_language_file("lg_htaccess_generator_ext");

		$settings = $this->settings[$PREFS->ini("site_id")];
		$new_htaccess = "";

		$dir =  $settings['htaccess_path'];

		if(substr($dir, -1) != "/")
			$dir .= "/";

		$response['success'] = FALSE;

		if(is_dir($dir) === FALSE)
		{
			$response['msg'] = $LANG->line("dir_doesnt_exist");
			return $response;
		}

		$file = $dir . (($PREFS->ini("lg_htaccess_filename")) ? $PREFS->ini("lg_htaccess_filename") : ".htaccess");

		if(($fp = @fopen($file, 'a+')) === FALSE)
		{
			$response['msg'] = $LANG->line("htaccess_not_writable");
			return $response;
		}

		$hash = "*lg:" . $FNS->hash(time()) . "*";
		$tmpl = str_replace('$', $hash, $settings['htaccess_template']);
		$old_htaccess = (($filesize = filesize($file)) !== 0) ? trim(fread($fp, $filesize)) . "\n\n" : "";
		$new_htaccess = "# -- LG .htaccess Generator Start --".
						"\n\n# .htaccess generated by LG .htaccess Generator v{$this->version}".
						"\n# {$this->docs_url}".
						"\n\n{$tmpl}".
						"\n\n# -- LG .htaccess Generator End --";
		// replace template groups
		if(strpos($new_htaccess, "{ee:template_groups}") !== FALSE)
		{
			$template_groups = array();
			$query = $DB->query("SELECT group_name FROM `exp_template_groups` WHERE site_id = " . $PREFS->ini('site_id'));
			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$template_groups[] = $row['group_name'];
				}
			}
			$new_htaccess = str_replace("{ee:template_groups}", implode("|", $template_groups) . "|", $new_htaccess);
		}

		// replace pages
		if(strpos($new_htaccess, "{ee:pages}") !== FALSE)
		{

			$pages = $PREFS->ini('site_pages');

			if($PREFS->ini("app_version") > 168) 
				$pages = $pages[$PREFS->ini("site_id")];

			if($pages !== FALSE)
			{
				$page_roots = array();
				
				foreach ($pages['uris'] as $page_id => $page_url)
				{
					if(substr($page_url, 0, 1) == "/")
					{
						$page_url = substr(trim($page_url), 1);
					}
					$parts = explode("/", $page_url);
					if(isset($parts[0]) === TRUE)
					{
						$page_roots[] = $parts[0];
					}
				}
				$new_htaccess = str_replace("{ee:pages}", implode("|", array_unique($page_roots)) . "|", $new_htaccess);
			}
			else
			{
				$new_htaccess = str_replace("{ee:pages}", "", $new_htaccess);
			}
		}

		$new_htaccess = str_replace("{ee:404}", $PREFS->ini("site_404"), $new_htaccess);

		$htaccess = (strpos($old_htaccess, "# -- LG .htaccess Generator Start --") !== FALSE)
						? preg_replace("/# -- LG \.htaccess Generator Start --.*?# -- LG \.htaccess Generator End --/s", $new_htaccess, $old_htaccess)
						: $old_htaccess . $new_htaccess;

		// open the file again for reading and writing
		$fp = fopen($file, 'w+b');
		flock($fp, LOCK_EX);
		if(fwrite($fp, str_replace($hash, '$', $htaccess)))
		{
			$response['success'] = TRUE;
			$response['msg'] = $LANG->line('htaccess_generated');
		}
		else
		{
			$response['msg'] = $LANG->line('htaccess_generated_error');
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		return $response;
	}

	/**
	 * Creates a select box
	 *
	 * @access	private
	 * @param	mixed $selected The selected value
	 * @param	array $options The select box options in a multi-dimensional array. Array keys are used as the option value, array values are used as the option label
	 * @param	string $input_name The name of the input eg: Lg_polls_ext[log_ip]
	 * @param	string $input_id A unique ID for this select. If no id is given the id will be created from the $input_name
	 * @param	boolean $use_lanng Pass the option label through the $LANG->line() method or display in a raw state
	 * @param 	array $attributes Any other attributes for the select box such as class, multiple, size etc
	 * @return	string Select box html
	 * @since	Version 1.1.0
	 */
	private function select_box($selected, $options, $input_name, $input_id = FALSE, $use_lang = TRUE, $key_is_value = TRUE, $attributes = array())
	{
		global $LANG;

		$input_id = ($input_id === FALSE) ? str_replace(array("[", "]"), array("_", ""), $input_name) : $input_id;

		$attributes = array_merge(array(
			"name" => $input_name,
			"id" => strtolower($input_id)
		), $attributes);

		$attributes_str = "";
		foreach ($attributes as $key => $value)
		{
			$attributes_str .= " {$key}='{$value}' ";
		}

		$ret = "<select{$attributes_str}>";

		foreach($options as $option_value => $option_label)
		{
			if (!is_int($option_value))
				$option_value = $option_value;
			else
				$option_value = ($key_is_value === TRUE) ? $option_value : $option_label;

			$option_label = ($use_lang === TRUE) ? $LANG->line($option_label) : $option_label;
			$checked = ($selected == $option_value) ? " selected='selected' " : "";
			$ret .= "<option value='{$option_value}'{$checked}>{$option_label}</option>";
		}

		$ret .= "</select>";
		return $ret;
	}

	/**
	 * Serialise the array
	 * 
	 * @access	private
	 * @param	array The array to serialise
	 * @return	array The serialised array
	 */ 
	private function serialize($vals)
	{
		global $PREFS;

		if ($PREFS->ini('auto_convert_high_ascii') == 'y')
		{
			$vals = $this->array_ascii_to_entities($vals);
		}

	 	return addslashes(serialize($vals));
	}

	/**
	 * Unerialise the array
	 * 
	 * @access	private
	 * @param	array $vals The array to unserialise
	 * @param	boolean $convert convert the entities to ascii
	 * @return	array The serialised array
	 */ 
	private function unserialize($vals, $convert=TRUE)
	{
		global $REGX, $PREFS;

		if (($tmp_vals = @unserialize($vals)) !== FALSE)
		{
			$vals = $REGX->array_stripslashes($tmp_vals);

			if ($convert AND $PREFS->ini('auto_convert_high_ascii') == 'y')
			{
				$vals = $this->array_entities_to_ascii($vals);
			}
		}

	 	return $vals;
	}

	/**
	 * Get the last call from a previous hook
	 * 
	 * @access	private
	 * @param	mixed $param The variable we are going to fill with the last call
	 * @param	mixed $default The value to use if no last call is available
	 */ 
	private function get_last_call(&$param, $default = NULL){
		global $EXT;

		if ($EXT->last_call !== FALSE)
			$param = $EXT->last_call;
		else if ($param !== NULL && $default !== NULL)
			$param = $default;
	}
	
	public function array_ascii_to_entities($vals)
	{
		if (is_array($vals))
		{
			foreach ($vals as &$val)
			{
				$val = $this->array_ascii_to_entities($val);
			}
		}
		else
		{
			global $REGX;
			$vals = $REGX->ascii_to_entities($vals);
		}

		return $vals;
	}

	public function array_entities_to_ascii($vals)
	{
		if (is_array($vals))
		{
			foreach ($vals as &$val)
			{
				$val = $this->array_entities_to_ascii($val);
			}
		}
		else
		{
			global $REGX;
			$vals = $REGX->entities_to_ascii($vals);
		}

		return $vals;
	}
}
?>