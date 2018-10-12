=== CF Zoho ===
Contributors: feedmymedia, matttrustmytravel
Tags: Caldera Forms, zoho
Requires at least: 4.3
Tested up to: 4.5.1
Requires PHP: 5.3
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to add processors for Zoho leads, contacts and tasks to the Caldera Forms plugin.

== Description ==
Caldera Forms is one of a kind WordPress form builder. With its user friendly drag and drop interface, it’s simple to create forms for your WordPress site that look awesome on any device. Caldera also comes with a range of add-ons, like integration with the Zoho CRM platform, which allows users to automate their day-to-day business activities allowing them to focus on selling without having to worry about digging through data. Use the extension to track your sales activities and gain complete understanding of your sales cycle.

== Installation ==

1. Ensure you have the Caldera Forms plugin installed 
2. Upload the CF Zoho plugin to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go the the plugin options page `/wp-admin/options-general.php?page=cfzoho` and follow instructions on configuring your Zoho CRM to allow access.
5. Create a Caldera Form and click the `Processors` tab to select the relevant Zoho processor(s) you wish to apply to the form

== Frequently Asked Questions ==
 
= How do I allow the plugin access to my Zoho CRM? =
 
Go to `https://accounts.zoho.eu/developerconsole` or `https://accounts.zoho.com/developerconsole` (depending on the region your Zoho CRM is registered in), and add a client ID with the settings shown on the CF Zoho Options page.

= I have added a new field to my Zoho CRM and its not showing up the Caldera Forms processor, how do I resolve this?

In order for this plugin to work at efficient speeds, Zoho CRM fields and users data are cached. If you have added new users or fields to your Zoho CRM, go to the CF Zoho Options page, check the "Flush Cache" checkbox and click the "Save Settings" button. This will flush all cached settings. Next time you edit a processor, new fields and/or users will be displayed.

== Upgrade Notice ==

If you are upgrading from version 1.1.2 or earlier, you will need to give the plugin access to Zoho as per the CF Zoho Options page. All other settings should be retained from the previous version of the plugin.

== Changelog ==

= 2.0.0 =
* Changed all calls to old Zoho API to new REST API.
* Renamed 'cf_zoho_create_entry' hook to 'process_zoho_submission' as the old hook passes a different $object array which will probably break any code hooking this.
* Added in the Travis Integration
* Removed the force text fields

= 1.1.2 =
* Dev - Added in a filter to replace any checkbox values with a set "true" or "false"
* Dev - Removed the restricted fields from the configuration options allowing you to map a magic tag to the field.
* Dev - Removed the unused options for the Lead and the Contact processors.
* Dev - Adding in a setting field to allow the user to define their API url
* Fix - Added in 3 options to fix the duplicate checks on Contact and Lead Submission

= 1.1.1 =
* Added A Filter 'cf_zoho_create_entry' to allow manipulation of the request object
* Added An Action 'cf_zoho_create_entry_complete' to allow tasks for after creation
* Added a new config page "Field Setup"
* Fixed a typo in the get config call which prevented certain tasks from running
* Added in a setup page to allow you to force "select" dropdowns via WP
* Fixed the list configs

= 1.1.0 =
* Added in a fallback connection for the wp_remote_request in the CF_Zoho_CRM() class
* Fixed the filtering of the XML data sent to Zoho

= 1.0.0 =
* Initial release
