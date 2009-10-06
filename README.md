LG .htaccess Generator - _Easily remove index.php from your ExpressionEngine URLs_
================================================================================

**This addon is for testing purposes only and is considered a public beta and should be used for testing purposes only!!!**.

**This ExpressionEngine addon requires Morphine (the painless ExpressionEngine framework) _for CP styles)_. Grab the latest version of Morphine from [http://github.com/newism/nsm.morphine.ee_addon](http://github.com/newism/nsm.morphine.ee_addon) and follow the readme instructions to install.**

LG .htaccess Generator Multi-Site Manager compatible ExpressionEngine extension that automatically generates and updates your sites .htaccess file every time an entry, template group or template is created or modified.

Requirements
------------

* **Morphine**: Morphine (the painless ExpressionEngine framework) is required for CP styles. Grab the latest version of Morphine from [http://github.com/newism/nsm.morphine.ee_addon](http://github.com/newism/nsm.morphine.ee_addon) and follow the readme instructions to install.
* **ExpressionEngine**: NSM Quarantine requires ExpressionEngine 1.6.8+. New version update notifications will only be displayed if LG Addon Updater is installed.
* **Server**: Your server must be running PHP5+ or greater on a Linux flavoured OS.

Installation
------------

* Install and activate Morphine (the painless ExpressionEngine framework) available from: [http://github.com/newism/nsm.morphine.ee_addon](http://github.com/newism/nsm.morphine.ee_addon)
* Copy all the downloaded folders into your EE install. Note: you may need to change the <code>system</code> folder to match your EE installation
* Activate the LG. htaccess extension.
* Check the .htaccess template and adjust as necessary

Updating from 1.0.0
-------------------

Automatic update is currently disabled for this version. To update:

* Copy your .htaccess template from the existing extension settings
* Delete all references to Lg\_htaccess\_generator\_ext from the exp_extensions db table by running the following sql:  
<code>DELETE FROM `exp_extensions` WHERE `class` = 'Lg\_htaccess\_generator\_ext'</code>
* Remove:
	* <code>system/extensions/ext.lg\_htaccess\_generator.ext</code>
	* <code>system/languages/english/lang.lg\_htaccess\_generator.ext</code>
* Install as normal

.htaccess Template variables
----------------------------

If you are using the Pages or Structure module make sure you add: {ee:pages} into the include string after {ee:template_groups}.

The .htaccess rules will be added to your .htaccess file after the following special tags are replaced:

* <code>{ee:template_groups}</code> is replaced with a pipe delimited list of this sites template groups with a pipe "|" appended.
* <code>{ee:pages}</code> is replaced with a pipe delimited list of this sites page urls with a pipe "|" appended.
* <code>{ee:404}</code> is replaced with the sites 404 path ie: site/404.

Read more about the "Include List Method" of removing your sites index.php on the [ExpressionEngine Wiki](http://expressionengine.com/index.php?affiliate=newism&page=wiki/Remove_index.php_From_URLs/#Include_List_Method).

TODO
----

* Remove Morphine dependency