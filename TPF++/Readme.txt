

Changes From Changelog:
*************************************************************************************************

30th May 2009
OH 2006-2009(No Updates Since){Iam Not Ralph[The original creator]}
My Name is Avinash D'Silva (IN)[19 years after three days from 30/05/2009] 
When i Downloaded this forum.It was using a hell lot of resources
* Even when the index.php used to load ,it had to go through almost each and every directory
and file
* It did not look slow, but when the forum reached 100 topics, you could feel the difference
* There was no pagination 
* data was stored in multiple flat files.It was very hardware expensive(think about the no of disk seeks)
* so i thought of doing a little cleaning!

You'll see the views at the bottom of the webpage
thats the number of times i viewed it while changing from TPF to TPF++

------HOPEFULLY RALPH DOESN'T MIND 

***Here are the changes:
--This Forum now works only with PHP 5.0 or higher
--Used a new Data Engine(CODEXQL) to store Data into FLAT FILES
--SQL like Syntax to insert, update and retrieve data
--Added Pagination Feature For Topics
--Changed The Resource intensive Search Feature
--Removed The Split Feature(will be added in the next version(1.0) as a MOD)
--Renamed TPF 3.61 to TPF++ vBeta (LOL since,the whole internal working of the forum has changed)
--Cleaned Some Old Bugs
--TPF++ is now being tested under "E_STRICT|E_NOTICE|E_WARNING|E_PARSE" Conditions
--Uploaded images will have unique names(so no more rewriting of older images)
--Most of the outer structure hasn't been touched like skins,lang,hits,sid etc.
--It will Still require the "forums" and the "users" folder to store hits and onlines
--I was a little curious about the ICO file,so i changed it(since users will have their own ICO anyway)
--Most of the older code is just commented out 

***Changes Thought for the next version (TPF++ v1.0)
-to completely use sessions and remove cookies
-add pagination for posts
-change search from strpos to more powerfull REGEX
-add module capabilities as in PHPBB or SMF
-add WYSIWYG editor
-add a real templating/skin mechanism (Probably use Smarty)

##inspite of the heavy changes in the next version we will maintain total backward compatibility
  you only have to replace the "DATA" folder with your older one and you'll have a new version	

************************************************************************************************


1. Installation

Extract the zip file contents into a clean directory.

Open the file 'config.php' in a text editor.

Change these lines at the start of the file:


	$siteName = "TPF++ Forum";		// Used in page title
	$siteURL = "http://ralpharama.co.uk/tpf"; // Link used for main logo
	$title = $siteName;			// Displayed in headings, etc
	$skin = "tpf";				// Skin to use
	$email = "stuff@ralpharama.co.uk";
	$version = "Beta";
	$openForum = TRUE;			// Guests can view this forum?
	$lang = "en";				// Default language code
	$hitcounter = TRUE;			// Index hit counter?
	$cookietm = 10800;			// Time for cookie expiry
	$topics_per_page=10;


The site name will be displayed in numerous locations, and the url 
used for navigation when someone clicks the image at the top of the
forum.

Now copy the entire directory contents, including subfolders, into 
your webspace, in the correct location.

Changing Permissions on some Folders:

You must allow write access to certain folders on your server to avoid
errors when users post, upload images, create profiles etc.

Set permissions to '777' on these folders (and all files and folders within them):

forums
users
image/avatars
DATA

Now open the page 'index.php' in your web browser and take a look, if 
you have problems at this point, refer to the troubleshooting section.


2. Customisation

General gfx:

Take a look in the 'image/skin/' folder.  Each folder contains a skin theme, 
and images in each of them can be changed for your own gfx.  
The size of each image is relatively unimportant, but
you must save your work as gif files with identical filenames.

Avatars:

Users can upload their own avatars, but you can give them a gallery to 
choose from too.  Any images you place in 'image/avatars' will be
shown as avatar options for users
But the images that you upload for avatars should not start from a number
numbered images are only meant for profile uploads
.  All gfx file types are useable here.

Emoticons:

In the 'emoticons' folder you will find 6 different images that can 
be changed for your own.  These are inserted to replace the usual
emoticon text codes, such as :) ;) :( :D etc.
(At some point we may make this easily skinnable)

Icon:

Some browsers allow you to show your own page icon.  Change the file
called 'favicon.ico' to your own icon if you desire.

Css:

The layout and look generally of the forum is controlled by the css
sheet 'eim.css'.  You can change this as you wish, but may need some
css knowledge to do so.  Even a novice can alter colours though.

Open it with a text editor:

html:                General settings for the pages/
body:                General background, fonts, colour.
img:                 By default, no border for images is selected.
input.search:        The text box for searching on the right hand side menu.
input.username:      The text box for username on the right hand side menu.
input.password:      The text box for password on the right hand side menu.
a,hover,link,visited:Hyperlinks look.
.topicLink:          Main heading links on listing pages
a.topicLink:link:    "
a.topicLink:visited: "
a.topicLink:hover:   "
.barreLien:          The bar under the logo with navigation
a.barreLien:link:    The links on said bar
a.barreLien:visited: The links on said bar
a.barreLien:hover:   The links on said bar
.barre:              The bar under the logo with navigation
.tp:                 Used to size the fist column of the table used to hold 
                     the page in place.
.date:               Used to display dates and some other text.
.filler:             The bar used to separate entries in the forum.
.big:                Used for various large headings.
.foruml:             For displaying the poster in topic listings, and member 
                     statements.
.col1tp:             Column heading 1
.col2tp:             Column heading 2
.col3tp:             Column heading 3
.col4tp:             Column heading 4
.col1bt:             Column entry 1
.col2bt:             Column entry 2
.col3bt:             Column entry 3
.col4bt:             Column entry 4
.coltxt:             Text entry in tables
.mainlogo:           If you wish to apply background behind the main logo, do 
                     it here.
.menu:               Right hand side menu column
.menupic:            What goes around the main menu listings
.menutop:            What goes above the menu listings at the top right.
.little:             Small text, e.g time of last post on topic list screen
.quote:              Used for highlighting text, and when user uses [q] [/q] in 
                     posts
.altquote:           Another quote used for member statements on posts (on left) etc
.editionLight:       One of the stripes seen on profile pages etc
.editionDark:        The other of the stripes seen on profile pages etc
.courant:            Used for description text on page listings
.footer:             The bottom of the page - 'Powered by' text

Create an admin account:

First, create your own account using the [new user] option.  Then, log out.

There is a default account with admin rights, login with it:

username: admin
password: password

Now, go to memberlist and select your new account.  Check the box marked
'admin' and save the changes.  Now your new account has admin rights.

Important:
Now, change the password on the 'admin' account to something secure, 
If you get errors trying to do this, check permissions on the * files - 
they should be set to 777.


Things To Remember:
While Editing Forums an ID is asked for input,well it is the ORDER in which your forums will appear

WARNING:do not edit any of the.cxt or .cxf files or any files inside the DATA folder,these are binary files !!
Editing these files may cause data corruption








3. Skins
You'll find links on the website to download skins.  These should be unzipped
into your 'image/skin' folder.

Each skin will contain a folder with several gfx files and a stylesheet inside.
Check the readme.txt file in the folder for more information.

The name of the skin is the same as the folder.

Now change your config.php file's line:


	$skin = "tpf";			        // Skin to use


To reflect the change.



3. Language

You'll find links on the website to download different languages.  These 
should be unzipped into your 'lang' folder.

The name of the language is the same as the code after the 'lang_'

Eg. 
'lang_en' is English, and the name of the language is 'en'
'lang_fr' is French, and the name of the language is 'fr'
...etc...

Now change your config.php file's line:


	$lang = "en";			        // Skin to use


To reflect the change.



3. Troubleshooting

q) I get an error something like:

Warning: move_uploaded_file(image/avatars/avatar.png): failed to 
open stream: Permission denied in /home/ralph/public_html/tpf/updatepf.php 
on line 54

ans) Check the permissions are set to '777' on the following folders:

forums
users
image/avatars
DATA


q) I get some other permission error when trying to edit, post, reply, etc:

ans) If you created users, forums, etc when running the forum locally, 
then copied it all up to your webserver, you may have permission errors
trying to modify anything that was copied over.  

Example: You copy the example forums and topics over to your webserver
and then try and edit them.  You will get an error.  You should create
your forums, topics etc, whilst running your forum on your webserver.

ans) Change the permission of any user files, forum folders, topic
folders, post entries to '777' and then try again.


q) It doesn't work - The browser just says that it doesn't now the format 
of the file index.php or it displays something like:

0) echo "
"; // We're posting or editing? if ($action=="edit") { newPost(); } // No, just 
viewing... else { // Display a particular topic? if ($f0 & $t0) { showPosts($f, $t); 
} // Else, show list of topics in this forum else if ($f0) { showTopics($f); } // 
Show list of forums (main page) else { showForums(); } } ?

Or it says that it is a binary executable.

ans) I'm guessing that you unzipping the forum onto your computer and trying 
to run it from there in a browser?

You need to upload the forum onto some webspace that can handle PHP files, 
or install a server on your computer that you can run, which will interpret 
PHP for you locally.

A good idea is Apache, which is Open Source, and works well:

http://www.apache.org/

Or, I found this package easy to install and use Xampp:

http://www.apachefriends.org/en/xampp.html 

There is a Linux version there.

If your webserver handles PHP though, it can be easier to just upload it 
there, but of course, it's best to be able to debug locally.






