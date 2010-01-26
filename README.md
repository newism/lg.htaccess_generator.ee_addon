LG .htaccess Generator - Easily remove index.php from your ExpressionEngine URLs
================================================================================

LG .htaccess Generator Multi-Site Manager compatible ExpressionEngine extension that automatically generates and updates your sites .htaccess file everytime an entry, template group or template is created or modified.

Using special `{ee:}` tags LG .htaccess Generator allows you to easily remove your sites index.php file using the ["Include List Method"][ee_include_method].

### Requirements

LG .htaccess Generator requires ExpressionEngine 1.6+ but is not available for EE 2.0+ yet. Addon update notifications require [LG Addon Updater][lg_addon_updater].

Technical requirements include:

* PHP5
* A modern browser: [Firefox][firefox], [Safari][safari], [Google Chrome][chrome] or IE8+
* A writeable .htaccess file

Other requirements:

LG .htaccess Generator requires the 'Morphine' default CP theme addon. [Download the addon from Github][gh_morphine_theme].

### Installation

1. Download the latest version of LG .htaccess Generator
2. Extract the .zip file to your desktop
3. Copy `/system/extensions/ext.lg_htaccess_generator_ext.php` to `/system/extensions/`
3. Copy `/system/language/english/lang.lg_htaccess_generator_ext.php` to `/system/language/english/`
3. Copy `/system/lib/lg_htaccess_generator` to `/system/lib/`
4. Download and install the [Morphine theme addon][gh_morphine_theme]

### Activation

1. Open the [Extension Manager][ee_extensions_manager]
2. Enable Extensions if not already enabled
3. Enable the extension
4. Configure the extension settings

### Configuration

LG .htaccess Generator has the following extension settings which need to be entered separately for each Multi-Site Manager site.

Note: All configuration options are site specific. When a new site is created be sure to save the extension settings for the new site to avoid errors.

#### Enable extension

##### Enable LG .htaccess Generator for this site?

Enabling LG .htaccess Generator will copy the .htaccess template into the .htaccess file whenever a template is saved via the CP or a new page is created using the Pages module.

#### .htaccess configuration

LG .htaccess Generator requires that an existing .htaccess file exists and is writable. Non LG .htacess rules these will not be modified by this extension.

##### .htaccess server path

    /Users/admin/Sites/Internal/local.expressionengine/www/

The path where your `.htaccess` file is located. Must be writable by the server.

##### .htaccess template

    # secure .htaccess file
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
    RewriteCond %{QUERY_STRING} !^(ACT=.*)$ [NC] # EE Actions
    RewriteCond %{REQUEST_URI} !(\.[a-zA-Z0-9]{1,5})$ # files
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_URI} ^/({ee:template_groups}{ee:pages}members|P[0-9]{2,8}) [NC]
    RewriteRule (.*) /index.php?$1&%{QUERY_STRING} [L]

The tgas below will be replaced with their associated values in the .htaccess template:

* `{ee:template_groups}` will be replaced with a pipe delimited list of this sites template groups
* `{ee:pages}` will be replaced with a pipe delimited list of this sites page urls
* `{ee:404}` will be replaced with the sites 404 path ie: site/404

Read more about the "include method" of removing your sites index.php on the [ExpressionEngine Wiki][ee_include_method].

If you are using the Pages or Structure module make sure you add: `{ee:pages}` into the include string after `{ee:template_groups}`. If you are not using the Pages or Structure module remove this tag.

For more information about the default .htaccess rules visit my [official launch post][newism_launch_post] on [newism.com.au][newism].


#### Check for updates

LG .htaccess Generator can call home, check for recent updates and display them on your CP homepage? This feature requires [LG Addon Updater][lg_addon_updater] to be installed and activated.

##### Would you like this extension to check for updates?

Enable / disable update notifications.

Release Notes
------------

### Upgrading?

* Before upgrading back up your database and site first, you can never be too careful.
* Never upgrade a live site, you're asking for trouble regardless of the addon.
* After an upgrade if you are experiencing errors re-save the extension settings for each site in your ExpressionEngine install.

#### Upgrading from 1.0.0

Automatic update is currently disabled for this version. To update:

* Copy your .htaccess template from the existing extension settings
* Delete all references to `Lg_htaccess_generator_ext` from the `exp_extensions` db table by running the following sql:
    * `DELETE FROM exp_extensions WHERE class = 'Lg_htaccess_generator_ext'`
* Remove:
	* `system/extensions/ext.lg_htaccess_generator_ext.php`
	* `system/languages/english/lang.lg_htaccess_generator_ext.php`
* Install as normal

### Change log

#### 1.1.0

* Rewrote documentation
* Added Morphine CP theme addon styles
* Rewrote parts of the extension
* Added `lg_htaccess_filename` configuration variable for non .htacess files.

#### 1.0.0

* Initial Release w/ documentation

Support
-------

Technical support is available primarily through the [ExpressionEngine forums][ee_forums]. [Leevi Graham][lg] and [Newism][newism] do not provide direct phone support. No representations or guarantees are made regarding the response time in which support questions are answered.

Although we are actively developing this addon, [Leevi Graham][lg] and [Newism][newism] makes no guarantees that it will be upgraded within any specific timeframe.

License
------

Ownership of this software always remains property of the author.

You may:

* Modify the software for your own projects
* Use the software on personal and commercial projects

You may not:

* Resell or redistribute the software in any form or manner without permission of the author
* Remove the license / copyright / author credits

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

[lg]: http://leevigraham.com
[newism]: http://newism.com.au
[newism_launch_post]: http://newism.com.au/blog/post/39/lg-htaccess-generator-removing-indexphp-from-your-expressionengine-site/

[ee]: http://expressionengine.com/index.php?affiliate=newism
[ee_forums]: http://expressionengine.com/index.php?affiliate=newism&page=forums
[ee_extensions_manager]: http://expressionengine.com/index.php?affiliate=newism&page=docs/cp/admin/utilities/extension_manager.html
[ee_include_method]: http://expressionengine.com/index.php?affiliate=newism&amp;page=/wiki/Remove_index.php_From_URLs/#Include_List_Method

[firefox]: http://firefox.com
[safari]: http://www.apple.com/safari/download/
[chrome]: http://www.google.com/chrome/

[lg_addon_updater]: http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
[gh_morphine_theme]: http://github.com/newism/nsm.morphine.theme