# Gravity Forms Iframe Add-on

Embed a Gravity Form in an iframe on any site.

__Contributors:__ [Brady Vercher](https://github.com/bradyvercher)  
__License:__ [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)

The typical process to embed a Gravity Form on a site where the plugin isn't installed requires:

1. Developing a custom page template with necessary code to output form scripts and styles.
2. Creating a new page in WordPress.
3. Inserting the form shortcode in the new page.
4. Manually writing an iframe tag with the page permalink and giving it a static height.

With the _Gravity Forms Iframe_ add-on, just enable a setting to allow the form to be embedded and copy the code snippet. That's it. As a bonus, the iframe automatically resizes whenever the form height changes -- for instance, when fields are shown or hidden due to conditional logic.

## Features

* Selectively enable embedding for individual forms.
* Auto-resizing iframes.
* Override embed templates in a theme or child theme.
* Override settings via the embed src query string.
* Extends the Gravity Forms add-on API to seamlessly integrate with the WordPress and Gravity Forms interface.
* Protocol-relative URLs for embedding on secure sites (both sites need SSL).

## Settings

The form title and description can be hidden independent of regular form display by toggling a checkbox after enabling the embedding setting.

![Form Iframe Settings Screenshot](https://raw.github.com/bradyvercher/gravity-forms-iframe/master/screenshot-1.png)
_The form's iframe settings panel._

### Overrides for Individual Iframes

If the title and description settings need to be changed per embed, they can be modified in the iframe `src` query string.

* **`dt`:** Set to `1` to display the form title; `0` to hide.
* **`dd`:** Set to `1` to display the form description; `0` to hide.

_**Example:** gfembed/?f=1&dt=0&dd=0_

### Auto-resizing Script

If the auto-resizing functionality isn't needed for a particular form, adjust the iframe's height attribute to accomodate the form and don't include the `<script>` tag when copying the embed code. Leaving off the script tag will save an HTTP request.

### Confirmation Screen

If you redirect users to another page upon submission and would like the iframe to automatically resize, include the following in the confirmation page:
`add_filter( 'gfiframe_print_resize_ping_script', '__return_true' );`

## Template Hierarchy

Templates can be added in a theme or child theme to override the plugin's template. Use the following template names:

* gravity-forms-iframe-{$form_id}.php
* gravity-forms-iframe.php

## Installation

### Upload

1. Download the [latest release](https://github.com/bradyvercher/gravity-forms-iframe/archive/master.zip) from GitHub.
2. Go to the __Plugins &rarr; Add New__ screen in your WordPress admin panel and click the __Upload__ tab at the top.
3. Upload the zipped archive.
4. Click the __Activate Plugin__ link after installation completes.

### Manual

1. Download the [latest release](https://github.com/bradyvercher/gravity-forms-iframe/archive/master.zip) from GitHub.
2. Unzip the archive.
3. Copy the folder to `/wp-content/plugins/`.
4. Go to the __Plugins__ screen in your WordPress admin panel and click the __Activate__ link under _Gravity Forms Iframe_.

Read the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Clone this repository in `/wp-content/plugins/`:

`git clone git@github.com:bradyvercher/gravity-forms-iframe.git`

Then go to the __Plugins__ screen in your WordPress admin panel and click the __Activate__ link under _Gravity Forms Iframe_.

## Changelog

### 2.0.4

* Required composer/installers 1.0 or 2.0 to prevent conflicts when installed with Composer.

### 2.0.3

* Declared a variable in the `Plugin` class to prevent dynamic proprety warnings in PHP 8.2+.
* Handled Gravity Forms config collections in the head section of the iframe template. This allows scripts to be "localized" and prevents JavaScript errors.

### 2.0.2

* Updated embed code field in the admin panel to work with Gravity Forms 2.5+.
* Passed an object instead of a string in the auto-resize message.
* Changed the method for hiding the admin bar to improve reliability.
* Added GitHub Update compatibility.

### 2.0.1

* Made GravityFormsIframe_Addon::form_settings_fields() compatible with the parent class.

### 2.0.0

* Moved to the Cedaro GitHub account.
* Refactored the plugin structure.
* Introduced a 'gfiframe_head' action in the template header.

### 1.0.3

* Defined 'gravityforms_iframe' capability in the Add-on class to integrate with the Members plugin.

### 1.0.2

* Fixed a long-standing bug that may have prevented scripts from loading in the iframe template.
* Fixed deprecated notices in Gravity Forms 1.9+.

### 1.0.1

* Deprecated the .php extension in the embed rewrite rule to prevent conflicts with WordPress Multisite.
* Disabled the WordPress toolbar in iframes when forms are embedded on the host domain.
* Added a POT file for translators.

### 1.0.0

* Initial release.
