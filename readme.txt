=== Gravity Forms Iframe Add-on ===
Contributors: blazersix, bradyvercher
Tags: gravity forms, iframe, embed
Requires at least: 3.7.1
Tested up to: 3.9
Stable tag: trunk
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed a Gravity Form on any site using an iframe.

== Description ==

Requires [Gravity Forms](http://www.gravityforms.com/).

The typical process to embed a Gravity Form on a site where the plugin isn't installed requires:

1. Developing a custom page template with necessary code to output form scripts and styles.
2. Creating a new page in WordPress.
3. Inserting the form shortcode in the new page.
4. Manually writing an iframe tag with the page permalink and giving it a static height.

With the Gravity Forms Iframe add-on, just enable a setting to allow the form to be embedded and copy the provided code snippet. That's it. As a bonus, the iframe automatically resizes whenever the form height changes -- for instance, when fields are shown or hidden due to conditional logic.

= Features =

* Selectively enable embedding for individual forms.
* Auto-resizing iframes.
* Override embed templates in a theme or child theme.
* Override settings via the embed src query string.
* Extends the Graviy Forms add-on API to seamless integrate with the WordPress and Gravity Forms interface.
* Protocol-relative URLs for embedding on secure sites (both sites need SSL).

= Additional Resources =

* [Write a review](http://wordpress.org/support/view/plugin-reviews/gravity-forms-iframe#postform)
* [Have a question?](http://wordpress.org/support/plugin/gravity-forms-iframe)
* [Contribute on GitHub](https://github.com/blazersix/gravity-forms-iframe)
* [Follow @bradyvercher](https://twitter.com/bradyvercher)
* [Hire Blazer Six](http://www.blazersix.com/)

== Installation ==

Installing the Gravity Forms Iframe Add-on is just like installing most other plugins. [Check out the codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins) if you have any questions.

== Screenshots ==

1. The form settings panel.

== Notes ==

### Settings

The form title and description can be hidden independent of regular form display by toggling a checkbox after enabling embedding.

#### Overrides for Individual Iframes

If the title and description settings need to be changed per embed, they can be modified in the iframe `src` query string.

* **`dt`:** Set to `1` to display the form title; `0` to hide.
* **`dd`:** Set to `1` to display the form description; `0` to hide.

_**Example:** gfembed/?f=1&dt=0&dd=0_

#### Auto-resizing Script

If the auto-resizing functionality isn't needed for a particular form, adjust the iframe's height attribute to accommodate the form and don't include the `<script>` tag when copying the embed code. Leaving off the script tag will save an HTTP request.

### Template Hierarchy

Templates can be defined in a theme or child theme to override the plugin's template using the following template names:

* gravity-forms-iframe-{$form_id}.php
* gravity-forms-iframe.php

== Changelog ==

= 1.0.3 =
* Defined 'gravityforms_iframe' capability in the Add-on class to integrate with the Members plugin.

= 1.0.2 =
* Fixed a long-standing bug that may have prevented scripts from loading in the iframe template.
* Fixed deprecated notices in Gravity Forms 1.9+.

= 1.0.1 =
* Deprecated the .php extension in the embed rewrite rule to prevent conflicts with WordPress Multisite.
* Disabled the WordPress toolbar in iframes when forms are embedded on the host domain.
* Added a POT file for translators.

= 1.0.0 =
* Initial release.
