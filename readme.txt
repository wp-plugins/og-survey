=== Plugin Name ===
Contributors: ogmaconceptions
Tags: WooCommerce
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OG Survey is a beautiful WooCommerce extension which creates a simple Survey Management to your WooCommerce site.

== Description ==

Add a Survey Management to your WordPress WooCommerce website. This is simple enough.

Site Admin can add one or more Questions & Sub-Questions. Sub-Questions can be linked to Questions, which represents the Survey as one or more Questions and for an answer there maybe be a Sub-Question. Behind each & every answer of Questions or Sub Questions there are different types of recommendations. You can recommend one or more WooCommerce products of your site or you can also recommend something else like YouTube videos, any other thing via shortcode, etc.

== Installation ==

= Minimum Requirements =

* WordPress 3.0.1 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

1. Download the `.zip` file and extract it to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Features ==

* Two Custom Post Types: question, sub-question
* Two Shortcodes: [questions ids="question-ids-comma-separated"], [og-survey-result]
* WordPress Settings API for the Settings page
* Custom CSS, JS for styling the Survey Content pages and Survey Resultant page
* Webshim Library

== How To Use ==

1. After activating the plugin you can see two admin menu entry called "Questions" and "Sub Questions".
2. Add some Sub-Questions and then add some Questions.
3. Now create pages: one or more for creating multiple surveys, one for the survey result.
4. Now go to Settings page and go to Multiple Surveys tab and add one or more surveys by checking the questions and select the page where to display the survey.
5. Now select the Resultant page from the Main tab and optionally you can add css, js for the corresponding pages.
6. Select a page for the "Terms & Conditions" as because when the survey page visited, at first the end user has to accept the terms and conditions for using the survey.
7. Now copy the shortcodes which are generating just below the question checking section(on the Multiple Survey tab) and on the Main tab Resultant Page Shortcode and paste them to corresponding survey pages and resultant page.
8. You are done. Now link the survey pages to your site using any custom menu or anything else.
9. By visiting those pages, end user can see those questions.

== Changelog ==

= 1.0.0 =
* First stable release.

= 1.0.1 =
* Fixed some minor bugs.
