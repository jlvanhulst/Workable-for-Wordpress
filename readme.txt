=== Workable Api ===
Contributors: katwhite, miriamgoldman, jlvanhulst
Tags: workable, job listing, job board, careers, jobs
Requires at least: 5.0
Tested up to: 5.8
Stable tag: 1.1.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A quick overview of all your company's listings on Workable, harnessing their API, with the ability to toggle which appear on your website.

== Description ==

The Workable API plugin harnesses the Workable API, allowing WordPress administrators a quick view of all their jobs listed on Workable. They can then choose which plugins they wish to have featured on their website.

== Installation ==

= Traditional Method =
1. Place workable-api.zip to the wp-content/plugins/ directory
2. Extract plugin
3. Navigate to "Installed Plugins" and click "Activate"
4. To view, go to Settings -> Workable API


== Frequently Asked Questions ==

= Where do I find my Workable info? =

Follow the <a href="https://help.workable.com/hc/en-us/articles/115015785428-How-do-I-generate-an-API-key-access-token-">instructions that Workable provides</a>. You must be an admin level user on the Workable platform. 

= How do I show the listings on my website? =

1) Add the shortcode [workabole_jobs]   - it will put up to 100 active jobs on your page.
The following parameters are supported, default value shown:

[workable_jobs state='published' department='' apply='Apply Now' nojobs='No Current Job Openings' ]

department='Sales' will only show published jobs with department 'Sales' This will also suppress the line that shows 'Deparmentt' in the shoplisting

apply= controls the message shown as the third (or second, if no department is shown) line. 'Apply now' is the default. If set to EMPTU
(apply='') no line is shown and the job title is instead made clickable to the full description of the job on Workable

nojobs=  controls the message to be shown when no jobs are found. The default message is 'No Current Job Openings'

Note: at this point the plugin does not support pagination so if you have more than 100 jobs results from your filter it will only show 100



2) (old)

Create or open the template you wish to use for your careers page. Call the `workable_api_get_featured_jobs()` function, and loop through it. Assign variables as you see fit.


== Screenshots ==
1. View of admin screen

== Changelog ==
1.1.0 : New 'generic shortcode' [workabble_jobs]
        bug fixes
1.0.3 : Squashing bugs, fixing errors.
1.0.2 : Lots of fun enhancements to the admin screen! AJAX and monkeys! *(there may or may not be monkeys present in this plugin)*

== Upgrade Notice ==
1.0.3: Error fixes
1.0.2: Admin enhancements










