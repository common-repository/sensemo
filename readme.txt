=== Sensemo ===
Contributors: vtricity
Tags: sensemo, emotions, feedback, poll, feelings
Donate link: http://vtricity.com/sensemo-wordpress-plugin/
Author URI: http://vtricity.com/about
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily include Sensemo emotion polls in your blogs or pages via shortcodes. Be among the first to see what your audience feels - not thinks. Enjoy.

== Description ==
We know that emotions drive decisions - particularly including buying decisions.

But how do you know what your audience **feels** (not thinks) when they interact with your company, product, service, website? People are longing to express their emotions as we can see in the massive use of emoticons and emoji. After all, emotions are a core part of our very nature.

But how can we measure and compare emotions that larger audiences experience - and do this live?

Now you can with the world’s first emotion feedback cloud service: Sensemo <https://sensemo.vtricity.com> - and this Sensemo shortcode plugin makes it particularly easy to gather emotion feedback with WordPress:

* Include live Sensemo emotion polls in your blogs or pages with simple shortcodes.
* Track which emotions your audience experience right from within your WordPress blogs and pages.
* Be amazed.

== Installation ==
1. Upload "sensemo-plugin.php" to the "/wp-content/plugins/" directory.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. Set your Sensemo Master Poll ID and token.
1. Place [ sensemo ] shortcodes in your posts and pages.

== Frequently Asked Questions ==
= How does a proper Sensemo shortcode look like? =
1. [ sensemo ] (will use your Master Poll ID by default)
1. [ sensemo id="EADX75KK" ] (will use the given Sensemo Poll ID

= What is the difference between a Sensemo Poll ID and a Sensemo Master Poll ID? =
1. Each Sensemo Poll has a unique ID. You can use any Sensemo Poll ID as your WordPress Master Poll ID. If you do, simply include [ sensemo ] in WordPress posts and pages and the respective Sensemo Poll question, icon and link will appear for your visitors to use. 
1. Alternatively, you can point the Sensemo shortcode to a particular Sensemo Poll by including its ID like so: [ sensemo id="EADX75KK" ]. 
1. The Sensemo WordPress Plugin will automatically use the Sensemo Master Poll ID if no particular Poll ID is specified. 

= Where can I find my Sensemo Master Poll ID? =
1. Sign in at https://sensemo.vtricity.com
1. Create a new emotion poll or select one from "My Polls" - you will be taken in this emotion poll’s “Live Emotion Report” view
1. Click or tap on “Share” at the top of the "Live Emotion Report" view - a popup window with various sharing options will appear that includes a WordPress shortcode (e.g. [sensemo id="FWGHGLT4”])
1. From this shortcode, copy the id parameter if you would like to use it as your Master Poll ID - here: FWGHGLT4
1. Paste the Master Poll ID into the Master Poll ID field in the Sensemo plugin page
1. Save

= Where can I find my Sensemo Master API Token? =
1. Sign in at https://sensemo.vtricity.com
1. Click or tap on the settings icon at the top right
1. Open the API Token dropdown area at the bottom - you will see a text box with your Master API Token
1. Copy and paste the Master API Token into the respective Sensemo WordPress plugin field
1. Save


== Screenshots ==
1. Sensemo Plugin Master Settings
2. Copying a shortcode from a Sensemo Emotion Poll
3. Including a Sensemo shortcode in a page
4. Admin view of a page that includes the Sensemo Poll Link (report icon is only visible to page admins)
5. Audience view upon clicking the Sensemo Poll Link
6. Admin report view of the Sensemo Emotion Poll as emotion feedback streams in

== Changelog ==
= 0.1 =
* Initial release.

== Upgrade Notice ==
= 0.1 =
Initial release