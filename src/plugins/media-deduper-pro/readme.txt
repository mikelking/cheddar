=== Media Deduper Pro ===
Contributors: drywallbmb, kenjigarland
Tags: media, attachments, admin, upload
Requires at least: 4.3
Tested up to: 5.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save disk space and bring some order to the chaos of your media library by removing and preventing duplicate files.

== Description ==
Media Deduper was built to help you find and eliminate duplicate images and attachments from your WordPress media library. After installing, you'll have a new "Manage Duplicates" option in your Media section.

Before Media Deduper can identify duplicate assets, it first must build an index of all the files in your media library, which can take some time. Once that's done, however, Media Deduper automatically adds new uploads to its index, so you shouldn't have to generate the index again.

Once up and running, Media Deduper provides two key tools:

1. A page listing all of your duplicate media files. The list makes it easy to see and delete duplicate files: delete one and its twin will disappear from the list because it's then no longer a duplicate. Easy! By default, the list is sorted by file size, so you can focus on deleting the files that will free up the most space.
2. A scan of media files as they're uploaded via the admin to prevent a duplicate from being added to your Media Library. Prevents new duplicates from being introduced, automagically!

Media Deduper comes with a "Smart Delete" option that prevents a post's Featured Image from being deleted, even if that image is found to be a duplicate elsewhere on the site. If a post has a featured image that's a duplicate file, Media Deduper will re-assign that post's image to an already-in-use copy of the image before deleting the duplicate so that the post's appearance is unaffected. At this time, this feature only tracks Featured Images, and not images used in galleries, post bodies, shortcodes, meta fields, or anywhere else.

Note that duplicate identification is based on the data of the files themselves, not any titles, captions or other metadata you may have provided in the WordPress admin.

Media Deduper can differentiate between media items that are duplicates because the media files they link to have the same data and those that actually point to the same data file, which can happen if a plugin like WP Job Manager or Duplicate Post.

As with any plugin that can perform destructive operations on your database and/or files, using Media Deduper can result in permanent data loss if you're not careful. **We strongly recommend backing up your entire WordPress site before deleting duplicate media.**

== Installation ==
1. Upload the `media-deduper-pro` directory to your plugins directory (typically wp-content/plugins)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit Media > Manage Duplicates to generate the duplicate index and see your duplicated files

== Frequently Asked Questions ==
= How are duplicates computed? =

Media Deduper Pro looks at the original file uploaded to each attachment post and computes a unique hash (using md5) for that file. Those hashes are stored as postmeta information. Once a file's hash is computed it can be compared to other files' hashes to see if their data is an exact match.

= Why does the list of duplicates include all the copies of a duplicated file and not just the extra ones? =

Because there's no way of knowing which of the duplicates is the "real" or "best" one with the preferred metadata, etc.

= Should I just select all duplicates and bulk delete permanently? =

NO! Because the list includes every copy of your duplicates, you'll likely always want to save one version, so using *Delete Permanently* to delete all of them would be very, very bad. Don't do that. You've been warned.

Instead, we recommend using the *Smart Delete* action (which is also found in the Bulk Actions menu). Smart Delete will delete the selected items one by one, and refuse to delete an item if it has no remaining duplicates. For example, if you have three copies of an image, and you select all three and choose Smart Delete, two copies will be deleted and the third will be skipped.

Again, we strongly recommend backing up your data before performing any bulk delete operations, including Smart Delete.

= Does Media Deduper Pro prevent duplicates from all methods of import? =

At this time, Media Deduper Pro only identifies and blocks duplicate media files manually uploaded via the admin dashboard -- it does not block duplicates that are imported via WP-CLI or the WordPress Importer plugin.

= I have another question! =

Check out our Media Deduper knowledge base at https://cornershop-creative.groovehq.com/knowledge_base/categories/media-deduper. If you can't find your answer there, please email us at support@cornershopcreative.com.

== Changelog ==
= 1.0.7 =
* Improve user experience around licensing: when a user deactivates a licensed copy of Media Deduper Pro, their license key will now be automatically deactivated on the current site, so that if they reactivate the plugin on another site, they won't be told their license key is already in use; and when a user's license key expires, they'll be shown an alert on the WordPress admin dashboard, whereas previously they would simply be unable to successfully update the plugin.
* Fix an issue that could cause a performance hit or MySQL bottleneck on sites with large numbers of posts during initial activation, or when upgrading from 1.0.5 or earlier to 1.0.6

= 1.0.6 =
* Add Gutenberg support: Smart Delete now detects and replaces references to duplicate attachments in the Image, Cover Image, and Gallery block types. Make sure to rebuild the index!
* Add a plugin option to only hash the first 5 MB of each file (this speeds up the indexing process on sites whose media library contains lots of large files; values other than 5 MB can be set using the `mdd_pro_file_hash_maxlength` filter)
* Add a plugin option to _allow_ users to upload duplicates of files that are already in the media library (the plugin prevents duplicate uploads by default)
* Add a table column to the Manage Duplicates screen indicating where each attachment is referenced
* Add a screen containing useful information for diagnosing any issues with the plugin
* Fix a bug that could caused unnecessary storage of large amounts of data in the `options` database table
* Fix a bug in the license key validation functionality that could cause plugin updates to fail on a site whose URL had recently changed

= 1.0.5 =
* In cases where multiple attachments share the same file on the web server, Smart Deleting one or more of those attachments will no longer delete that shared file unless ALL attachments that use that file are Smart Deleted.

= 1.0.4 =
* Fix a bug that could cause PHP errors during post deletion in very specific edge cases
* Start checking the brand-new mediadeduper.com website for plugin updates, instead of cornershopcreative.com

= 1.0.3 =
* Media Deduper Pro now fully supports the media trash feature (a.k.a. the MEDIA_TRASH constant; see [this post](https://philkurth.com.au/wordpress/enabling-wordpress-media-trash-feature/) for more information). As of Media Deduper Pro 1.0.3, if the media trash feature is enabled:
	* The Manage Duplicates screen will list (and allow the user to delete or Smart Delete) both trashed and non-trashed attachment posts. Previously, only non-trashed attachments were listed on this screen, which caused confusing behavior if, for instance, a site's media library contained two copies of an image, but one was in the Trash.
	* If a user attempts to upload a media file that already exists in the media library, but the existing copy of the file is in the Trash, the user will *no longer* be prevented from uploading the new copy.
* Bugfix: The "Attach" or "Detach" links in the list table on the Manage Duplicates screen are now working again, after they stopped working in a previous version of the plugin.
* Bugfix: There was a goofy typo in the instructions for the beta opt-in feature, which has been fixed.

= 1.0.2 =
* Add a button to the Index tab that allows users to stop the indexer if it's running
* Add a setting to the License Key tab that allows users to opt in to receive beta updates
* Fix a bug that could cause the indexer to display progress incorrectly in some edge cases

= 1.0.1 =
* Fix a bug that caused the count of indexed/un-indexed items to be calculated incorrectly on some WP installs
* Calculate count of indexed/un-indexed items more frequently, to reduce the chance of inaccurate counts being displayed
* Prevent the index of post content from going out of sync if a user deactivates the plugin for a period of time and then reactivates it
* Improve behavior/language when there are no items (posts or attachments) to index at all
* Improve notices displayed to users when the index needs to be regenerated (only display to admins/privileged users, link directly to the Index tab)
* Remind users to enter license keys, so they don't miss out on updates

= 1.0.0 =
Initial public release of Media Deduper Pro. Changes compared to the free version of Media Deduper:
* Replace references in post properties and certain post meta fields (featured image, Yoast FB/Twitter images, WooCommerce product gallery)
* Perform indexing in the background, so the user doesn't have to stay on the indexer page while the process completes
* Implement license key system to allow one-click/automatic plugin updates
