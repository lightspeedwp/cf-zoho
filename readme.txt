=== LSX Zoho CRM Addon for Caldera Forms ===
Contributors: feedmymedia, lightspeedwp, eleshar, krugazul, jacquesvdh, ignusvermaak
Donate link: https://lsdev.biz/lsx/donate/
Tags: Caldera Forms, Zoho, CRM, Zoho CRM, forms
Requires at least: 5.0
Tested up to: 5.5.1
Requires PHP: 7.0
Stable tag: 2.0.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

The LSX Zoho CRM addon for Caldera Forms allows you capturing leads via form  on your WordPress website, then it posts that data to Zoho CRM.

== Description ==

[Caldera Forms](https://wordpress.org/plugins/caldera-forms/) is a different kind of WordPress form builder. Developed to be responsive, intuitive and meet the needs of the modern WordPress site builder. Caldera Forms is the free WordPress form builder plugin that does more.

The LSX Zoho CRM Addon for Caldera Forms allows users to automate their day-to-day business activities by allowing them to focus on selling without having to worry about digging through data. Use this free extension to track your sales activities and gain complete understanding of your sales cycle. Easily create or update a lead, contact or any other object in Zoho CRM when an entry is created in Caldera Forms.

= Documentation = 

We have extensive documentation on all our plugins and theme functionality. This allows you to take control of your website setup and design to your needs: 

* [Getting Starting with LSX Zoho CRM Addon for Caldera Form](https://www.lsdev.biz/lsx/documentation/lsx-extensions/lsx-zoho-crm-addon-for-caldera-forms/)

= Support =

Contact the [LightSpeed](https://lsdev.biz/) for assistance via the [LSX support form](https://www.lsdev.biz/lsx/support/).

If you are experiencing issues with the LSX Zoho CRM Addon for Caldera Form plugin, please log any bug issues you are having on the [LSX Zoho CRM Addon for Caldera Form Issues](https://github.com/lightspeeddevelopment/cf-zoho/issues) page.

= Contributing =

Extensible, adaptable, and open source — LSX Zoho CRM Addon for Caldera Form is created with theme and plugin developers in mind. If you’re interested to jump in the project, there are opportunities for developers at all levels to get involved. 

If you're a developer who's spotted a bug issue and have a fix, or simply have the functionality you think would extend our core theme, we are always happy to accept your contribution! Visit the [LSX Zoho CRM Addon for Caldera Form on Github](https://github.com/lightspeeddevelopment/cf-zoho/) and submit a Pull Request with your updates.

== Installation ==

1. Ensure you have the Caldera Forms plugin installed 
2. Upload the LSX Zoho CRM Caldera Forms Add-on to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go the the plugin options page `/wp-admin/options-general.php?page=lsx_cf_zoho` and follow instructions on configuring your Zoho CRM to allow access.
5. Create a Caldera Form and click the `Processors` tab to select the relevant Zoho processor(s) you wish to apply to the form

== Frequently Asked Questions ==

= How do I allow the plugin access to my Zoho CRM? =
 
Go to `https://accounts.zoho.eu/developerconsole` or `https://accounts.zoho.com/developerconsole` (depending on the region your Zoho CRM is registered in), and add a client ID with the settings shown on the LSX CF Zoho Options page.

= I have added a new field to my Zoho CRM and its not showing up the Caldera Forms processor, how do I resolve this?

In order for this plugin to work at efficient speeds, Zoho CRM fields and users data are cached. If you have added new users or fields to your Zoho CRM, go to the options page for the LSX Zoho CRM Caldera Forms addon, check the "Flush Cache" checkbox and click the "Save Settings" button. This will flush all cached settings. Next time you edit a processor, new fields and/or users will be displayed.

= What field formatting is supported? =

* Date fields need to be set in the format yyyy-mm-dd otherwise they will fail.
* The date magic tag can be used in the following format "{date:Y-m-d}"

= How to configure the processor? =

* When setting a "Layout" you will need to include the name and the ID separated by a | symbol.  The name goes first, and then the ID. e.g "Direct|11111111111"

= Where can I report bugs or contribute to the project? =
Bugs can be reported either in our support forum or preferably on the [LSX Zoho CRM Addon for Caldera Forms GitHub repository](https://github.com/lightspeeddevelopment/cf-zoho/issues).

= The LSX Zoho CRM Addon for Caldera Forms plugin is awesome! Can I contribute? =
Yes you can! Join in on our [GitHub repository](https://github.com/lightspeeddevelopment/cf-zoho/) :)

Take a look at all our [Frequently Asked Questions](https://www.lsdev.biz/lsx/documentation/lsx-theme/lsx-theme-faqs/), we are sure you'll find what you're looking for. 

== Screenshots ==
1. Form processors
2. Zoho CRM Leads Caldera processor
3. Zoho CRM Deals Caldera processor


== Changelog == 

[Latest changelogs can be found on GitHub](https://github.com/lightspeeddevelopment/cf-zoho/blob/master/changelog.txt). 
