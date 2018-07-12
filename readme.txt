=== CF Zoho ===
Tags: Caldera Forms, zoho
Requires at least: 4.3
Tested up to: 4.5.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= 2.0.0 =
* Changed all calls to old Zoho API to new REST API.
* Switched to using Caldera pre_processor rather than processor in caldera_forms_get_form_processors to allow for feedback to end user
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
