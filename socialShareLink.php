<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Twitter - http://twitter.com/intent/tweet?status=[TITLE]+[URL]
Pinterest - http://pinterest.com/pin/create/bookmarklet/?media=[MEDIA]&url=[URL]&is_video=false&description=[TITLE]
Facebook - http://www.facebook.com/share.php?u=[URL]&title=[TITLE]
According to Frédéric, supposedly the URL syntax above for Facebook has been deprecated, even though so far it seems to be still working for me, but just in case here is the new one for future reference.
http://www.facebook.com/sharer/sharer.php?u=[URL]&title=[TITLE]

Google+ - https://plus.google.com/share?url=[URL]

Reddit - http://www.reddit.com/submit?url=[URL]&title=[TITLE]
Delicious - http://del.icio.us/post?url=[URL]&title=[TITLE]]&notes=[DESCRIPTION]
Tapiture - http://tapiture.com/bookmarklet/image?img_src=[IMAGE]&page_url=[URL]&page_title=[TITLE]&img_title=[TITLE]&img_width=[IMG WIDTH]img_height=[IMG HEIGHT]
StumbleUpon - http://www.stumbleupon.com/submit?url=[URL]&title=[TITLE]
Linkedin - http://www.linkedin.com/shareArticle?mini=true&url=[URL]&title=[TITLE]&source=[SOURCE/DOMAIN]
Slashdot - http://slashdot.org/bookmark.pl?url=[URL]&title=[TITLE]
Technorati - http://technorati.com/faves?add=[URL]&title=[TITLE]
Posterous - http://posterous.com/share?linkto=[URL]
Tumblr - http://www.tumblr.com/share?v=3&u=[URL]&t=[TITLE]
Google Bookmarks - http://www.google.com/bookmarks/mark?op=edit&bkmk=[URL]&title=[title]&annotation=[DESCRIPTION]
Newsvine - http://www.newsvine.com/_tools/seed&save?u=[URL]&h=[TITLE]
Ping.fm - http://ping.fm/ref/?link=[URL]&title=[TITLE]&body=[DESCRIPTION]
Evernote - http://www.evernote.com/clip.action?url=[URL]&title=[TITLE]
Friendfeed - http://www.friendfeed.com/share?url=[URL]&title=[TITLE]
    
endfor;
This a great list listing most popular social services but I have struggled finding what the syntax for an email button should be like. If you are a developer, you are most likely familiar with syntax for an email link. What you need to accomplish the desired outcome, your markup should looks something along these lines:

<a href="mailto:?subject=[TITLE]&body=Check out this site I came across [URL]">[EMAIL]</a>
Follow Buttons

I’ve been just recently asked if I knew about a way to create a “follow” buttons without any plugin. So far I’ve only looked into Twitter’s syntax for a follow button and this is what I got:

<a href="https://twitter.com/intent/follow?original_referer=[URL]/&region=follow_link&screen_name=[YOUR TWITTER HANDLE]&tw_p=followbutton" onclick="_gaq.push(['_trackEvent', 'outbound-article', 'https://twitter.com/intent/follow?original_referer=[URL]/&region=follow_link&screen_name=[YOUR TWITTER HANDLE]&tw_p=followbutton', 'Follow @[YOUR TWITTER HANDLE]']);">Follow @[YOUR TWITTER HANDLE]</a>
Having this site in WordPress, I wanted to test it of course. Using WordPress codex functions, all I had to do was to substitute the [TITLE] with:

<?php print(urlencode(the_title())); ?>
…and [URL] with:

<?php print(urlencode(get_permalink())); ?>
