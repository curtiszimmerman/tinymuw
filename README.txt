-=( tinyMuw v0.1.0 )=-

TABLE OF CONTENTS:
I. General
   1. Dedication
   2. Copyright
   3. Requirements
   4. Setup and Installation
   5. Upgrade
II. Administrative
   1. Security Issues
   2. Future Plans
III. Miscellaneous
   1. Known Bugs
   2. Version Tracker
   3. Development Information
   4. Contact Information
IV. Troubleshooting
V. FAQ


I. GENERAL:

   1. Dedication:

      This whole thing is dedicated to my wife Kirby, for her (somewhat forced) acceptance
      of my desire to make this happen. Without her there wouldn't be a life worth living,
      let alone a tinyMuw for you to use. This is also dedicated to the future of energy
      research, that we (the West) won't anymore rely on oil from countries I hate. I'm also 
      putting a moment of silence here for one of our boys, SGT Jesse Davila, who was struck 
      and killed on 20 February 2006 by an IED.

      ...

      Thanks.

   2. Copyright:

      This utility and its associated code and programming are copyright(c) Curtis Zimmerman
      and L0j1k.com. It is subject to the Creative Commons Licence (CCL), all rights
      reserved. The CCL can be found at the following address:
      http://creativecommons.org/licenses/by-nc-sa/2.0/
	  
      You may modify, cut'n'paste, implement, chop up, skewer, change, re-implement any and
      all of this code for NONCOMMERCIAL purposes ONLY by crediting the original author 
      Curtis Zimmerman and L0j1k.com. This means that you can't sell the code, and if you 
      were Microsoft you couldn't -- without special written permission by the original 
      author -- USE the utility and its associated code and programming on your commercial 
      site. If you have questions about using commercially, selling, repackaging, licensing 
      or leasing this software, please contact the original author at L0j1k@L0j1k.com. By
      using this utility and its associated code and programming, you also agree that the 
      original author, Curtis Zimmerman and L0j1k.com, cannot be held liable for any loss 
      of data or service that you might experience because of this code or your use of it. 
      Basically, you can't come after me or my website if this stuff screws your stuff up.

   3. Requirements:

      This software and its associated code and programming require the following:
      
         - Website with appropriate web server (Apache, IIS, etc.). I recommend Apache, in
	   the spirit of free, open-source software. You can get it here:
              http://httpd.apache.org/

         - PHP 5.0, the latest of which can be obtained here:
	      http://www.php.net/

         - MySQL 5.0, a free, open-source SQL database, which can be obtained here:
	      http://www.mysql.com/

      You can probably get away with slightly older versions of the above stuff, but I
      highly recommend getting these. They're free, after all.

   4. Setup and Installation:

         - Unzip php_tinyMuw_v0-x-x.zip to your website's root documents directory. All 
	   of tinyMuw is CASE-SENSITIVE. That means that CaPiTaLiZaTiOn is different than 
	   capitalization, which is also different than CAPITALIZATION. Usernames however 
	   are NOT case-sensitive. ;)

	   NOTE: BE MINDFUL that tinyMuw will not work unless you have the right files in
	   the right places! How they are in the zip file is how they should be extracted to
	   your root documents directory. So all the files in the /tinyMuw directory in the 
	   zip file should go in a /tinyMuw folder in your root documents directory. All the 
	   files in NO folder, but just in the zip file itself should go in the root docs
	   directory. It sounds simple but I guarantee that *somebody* will fuck it up. :)

         - Fire up your browser and point it at install.php, which should be placed (along
	   with installProc.php and installSession.php) in the root documents folder of your
	   website. The /tinyMuw directory containing all tinyMuw script files should also
	   be in the root directory. Again, follow the filetree found in the archive!

         - Input the CSS data from tinyStyle.css into your page CSS document. Just cut and
	   paste and add it to the end.

         - Cut and Paste the code from Header.inc into every page you want any portion of
	   tinyMuw BEFORE any of the code there. This is required for page refreshes, 
	   session and cookie stuff to work. And by BEFORE any code, I mean even before the
	   <html> tag starts!

	 - Cut and Paste the code fom adminHeader.inc into every page you want accessible
	   to admin users ONLY. By default the only page that requires this is adminPage.php,
	   unless you WANT everyone to abuse your awesome site.

	 - The user account registration page uses javascript for a simple popup to display
	   the terms and conditions of your website. THEREFORE you need to add the following
	   code BETWEEN the <head> tag and the </head> tag of that page. An example can be
	   found in the samplePages directory in the archive.

	   <script>
	   var termsPop;
	   function pop(url) {
	      termsPop=window.open(url,'terms','height=300,width=500,scrollbars=yes');
	      if (window.focus) {termsPop.focus()}
	   }</script>

         - Change the MySQL server information in config.php to your site-specific MySQL
	   information. The lines containing the information you need to change are lines 
	   15, 16 and 17.

	 - While you're at it, change EVERYTHING in config.php to something unique. If you
	   actually READ the comments, it tells you exactly what needs to be changed and
	   why you should change it. This will prevent anyone from just using the information 
	   found on the tinyMuw site to break in and change all your awesome content.   

         - To actually implement the utility, insert the following code into index.php (or
	   whatever pages you have set up) where you want to see the login/entry system 
	   (login.php), and the actual display of your entries (tinyMuw.php):

	   <?php include('tinyMuw/login.php');?>
	   <?php include('tinyMuw/tinyMuw.php');?>

	   To implement the Quickchat portion of tinyMuw (so that users will have something
	   to actually DO on your site), put the following code wherever you want to see the
	   last few user comments:

	   <?php include('tinyMuw/quickchat.php';?>

	   To implement the video portion of tinyMuw, insert the following code into the
	   page you have created for displaying video:

	   <?php include('tinyMuw/video.php');?>

	   To place the user-tracking code, put the following code where you want to 
	   display the registered users logged in and the total number of people (both
	   active and guest) viewing the site:

	   <?php include('tinyMuw/users.php');?>

	 NOTE: I know this sounds like a lot of work, and it is. But what else do you
	 expect from a free, low-numbered release, especially one written in a combat
	 theater? ;)

   5. Upgrade:

      This is like, the easiest thing a human could do, despite how much text you see down
      below. If you have installed a previous version of tinyMuw, it's very hard to kill the
      whole thing. Even if you have a major screw-up, you can just start the installation
      over and there shouldn't be a problem.

      If you are upgrading tinyMuw, you first need to delete everything. EXCEPT for your
      MySQL data. That can stay EXACTLY how it is. You may need to update the columns and
      add a couple new tables for the NEW version, but old data is ALWAYS useful. :) So
      delete all of your old tinyMuw files, and then simply follow the instructions for
      installing the new version of tinyMuw.

      IMPORTANT: Actually installing the newest version of tinyMuw directly over your old
      installation won't hurt anything AT ALL. You can safely do this no problem. The only
      issue would be in case a new version doesn't use a file that the old version does.
      This could lead to excess garbage files hanging out in the tinyMuw folder, which won't
      really hurt anything, but just isn't cool.

      ALSO IMPORTANT: If you already have tinyMuw installed, you may have to MANUALLY input
      the tables and such into MySQL, as it may fart errors and die() on you if you try to
      add shit to tables that already exist without using the proper commands. I want to
      implement a patching system soon, so that might help upgraders.

II. ADMINISTRATIVE:

   1. Security Issues:

      In this release, there are a few things included in the code to prevent such easy
      break-in techniques as basic SQL injection and hypertext format traversal (I don't
      know if that's the formal term for it but the term works for me). There is the minor
      problem of using cookies to maintain session information, as well as the problem of
      forms submission injection in the PHP url itself. These problems aren't easily
      circumvented, but the latter of which can be reduced significantly by renaming a few
      of the variables within the code itself. This creates Security Through Obscurity,
      but in my experience, 95% of websites using PHP/MySQL solutions use this technique
      when the same types of functions/operations are performed. Until I research or a
      random developer drops me a line with a better idea, this will have to work. I do
      this for free anyways.

      As of v0.1.0, there exists in the script a way for the system to verify that functions
      are called by the script itself and not a hacker. This is known as indirect function
      calling and is a very obscure and difficult practice. I'm not entirely sure if it's
      even *possible* with current versions of PHP, but the code is there anyways. This adds
      to the amount of coding actually in the script. If you are using tinyMuw on a system
      which handles hundreds or thousands of visitors an hour, I can't guarantee the upper
      threshold of it's operating capacity.

   2. Future Plans:

      None. What I mean is that I'll get to the future when I get to the future. Although I
      would REALLY like to see myself (et al) develop this into a full-blown Content
      Management System (CMS) like RedCMS and others. I would also like to see people
      learning from this as much (or more!) about PHP and MySQL as I did. However, I would
      like to start inventing code to do some of the following:

      - Add user registration code, which will also allow users to retrieve (actually reset)
        lost passwords. Visitors will be required to verify the email used during 
        registration by inputting a random password generated during this process. The
	number of times a user has been emailed (along with the timestamp of the last email
	from the system) is kept with other user information to prevent abuse.

      - I would like to keep security at the forefront of this CMS.

III. MISCELLANEOUS:

   1. Known Bugs:

      - The Archive function in tinyMuw.php only supports the display of 50 old entries. They
	are retained in the database, so this isn't a huge issue currently.

      - This isn't really a BUG per se, so much as a mildly annoying disadvantage: The blatant
	overuse of buttons and html forms to instigate processing of functions. While this is
	the accepted standard pretty much for actual forms, it would be nice to incorporate
	some javascript support of hyperlinks that do the same thing. Clean interface, you
	know. Currently you can change the CSS of the buttons to look better, less like
	buttons and more fluid with your design, but still.

   2. Version Tracker:

      - v0.1.0: (CURRENT STABLE RELEASE):

	 - HUGE CHANGES including converting database.php into an object, adding session.php
	   and process.php, and converting the userbase into a database-driven affair (as
	   opposed to the flatfile format it was in). Sessions are now used.

	 - Security advanced. This includes converting cookie names and some other variables
	   into constants which can be modified in config.php. Also added functionality to
	   prevent indirect function calls. Script also monitors aggressive user activity,
	   and will proactively suspend users and/or ban IP addresses depending on number 
	   and frequency of critical security-related errors.

	 - Site visitors are tracked as either guest users or active users and the results 
           are displayed on the main page.

	 - Includes basic user-registration functions and forgotten password functions.

	 - Introduces Admin Functions page, which supports addition of users, modification
	   of individual user permission groups, suspension of user accounts, banning of
	   individual IP addresses, as well as banning of entire IP ranges. This also has
	   a built-in log viewer.

	 - Added SMTP notification support for excessive number of minor errors by a single
	   IP address, or huge, obvious hacking errors, sorted by IP address and/or user.

	 - Support for the Quick Chat function for users to converse quickly on a page has
	   been added, with support for comments on news items and other things coming soon.

      - v0.0.4 (also known as v0.4.0GV):

         - Added video.php and the videoPage.php functions, which allow Google Video to
           easily be implanted into the news. The purpose of having videos launch in a
           separate page is multifaceted. It gives your page more exposure space (read:
           more adspace if you so desire) as well as keeping your mainpage streamlined.

         - Tightened database function calls a little more, referring to database.php in
           places where it wasn't before. This increases security and efficiency.

      - v0.0.3:

         - Added database.php, a central file from which all database calls are made, to
           which function calls from all database-connection-requesting files are made.

      - v0.0.2 (first public release):
         
         - Added Archive function, which allows the display of the last 50 entries.

         - Added the ability to delete entries.

         - Made possible the insertion of basic html formatting tags into entries.

      - v0.0.1:

         - Added Edit feature.

         - Added security to script with string handlers.

   3. Development Information:

      The forums at http://forums.L0j1k.com have a helpful forum dedicated just for tinyMuw
      and the development train and community. If you'd like to join the group (as formal
      as this sounds) just register at the forums and start posting. All comments and
      questions and criticisms are welcome. There are many ways to get involved in the
      development. Just grab a copy and start running it, chopping it up, serving it, 
      modifying it and throwing comments and suggestions and code snippets or chunks back
      onto the forums. The important thing is to LEARN from it and have fun making it work
      for your site.

   4. Contact Information:

      You can contact the original author of this software at L0j1k@L0j1k.com. My name is
      Curtis Zimmerman and I like hearing about how people use the software, any comments
      or suggestions you have concerning the software, or what kind of fish is your
      favorite to fish for. I will also answer limited questions concerning installation
      and setup but please no questions like "taht not wrokig, plz hlep me". I speak only
      English and limited amounts of German, so please don't ask me things in Russian or
      Vietnamese.

IV. TROUBLESHOOTING:

   1. Problem (P): "Teh skr1pt not wr0king!!!11!~twentyone"

      Possible Solution (PS): Have an adult assist you in the installation of tinyMuw. 
         Another possible solution would be to learn the English Language, especially
	 considering its widespread use on the Internet. Seriously.

   2. P: "I get weird errors that give me filenames and line numbers."

      PS: This could mean a thousand things. Your best bet is to download and install
         Firefox, then fire it up and go to Google.com. Now cut and paste the error you are
         getting into the search box. Press Search. This should help you a lot with finding
	 out how to fix some errors. You can find the quick and easy Firefox installation
	 package here (free of charge!):
	 http://www.firefox.com

   3. P: "I get the error 'Unexpected T-VARIABLE' at some line number."

      PS: This is a common error when you have unintentionally done something minor to your
	 script like add a semicolon (;) or accidentally deleted a semicolon (;) or 
	 end-bracket (}). Unless you can track it down to the specific line number, it's
	 going to be real big trouble to find it, you might as well just reinstall. Lucky
	 for you, that's very easy.

V. FAQ:

   1. Question (Q): "Do I need to have register_globals on in Apache for tinyMuw to work?"

      Answer (A): No, you don't! Actually anything that's properly written for the current
	 standards of web development shouldn't give you ANY reason to have that turned on.
	 It's bad, bad, bad! Your site can be hacked in a second.

   2. Q: "What's the maximum number of users that tinyMuw can handle?"

      A: Currently I don't know, although it probably wouldn't be too happy on a site like
	 Microsoft.com or CNN.com with tens of thousands of users per hour. That's just a
	 guess though. Try it and see, and then email me about your experience. ;)

   3. Q: "Was tinyMuw *really* written in a combat theater, or was that a neat lie?"

      A: Yes, this script was completely developed in a combat theater. Specifically in the
         city of Baghdad, Iraq, near BIAP (Baghdad International Airport). I will probably
	 continue to fine-tune tinyMuw after I return, but as of v0.1.0 I still have almost
	 half a year left in country and plan on this being mostly (if not totally) complete
	 by my return.
	 
   4. Q: "Why does tinyMuw contact its home server during installation?"
   
      A: Simple. I want to have a fairly decent idea of how many copies of tinyMuw are
	 not just being downloaded, but actually installed and used on people's websites. The
	 tinyMuw script does NOT collect any information that could POSSIBLY identify you or
	 your machine personally. A 12-bit unique, randomly-generated key is all that is sent,
	 as this is the best way I can think of to differentiate between any copies of the
	 script itself. I chose to use socket programming and send raw HTTP headers because I
	 didn't know anything about it before. And while the government DOES spy on you, 
	 tinyMuw doesn't. There is no Illuminati, Bavarian or otherwise.
	 
   5. Q: "Why does tinyMuw contact its home server after installation? Is there any way
         to turn this off so that it *doesn't* do this?"
	 
      A: Again, the answer to this is simple. If there is a new version of tinyMuw out,
	 especially one that might contain security fixes and/or updates, you would want to
	 know about it, right? I want to ensure that you find out about newer versions of the
	 script with as little effort as possible. YES, you can turn this off! Manually adjust
	 the setting for 'tog' or 'toggle' in the MySQL database in the table 'tinymuwsys'.

   6. Q: "I don't like the copyrights that link to your site. Can I remove them?"

      A: No. I spent probably hundreds of working hours on this. I deserve a little credit.
	 I am not an unreasonable man, however, so I tell you what: If you pay me 10 bucks and
	 agree to give the tinyMuw website address to anyone who asks, I wouldn't have a huge 
	 problem with you removing the PUBLICLY-VIEWABLE credit for my work (i.e. you have to
	 keep all comments in the source code -- including copyright and credit). This doesn't 
	 mean that tinyMuw is stripped of its license agreement. I am being completely and 
	 totally honest when I say that I make next to nothing for this utility, aside from the 
	 sum of knowledge about MySQL and PHP, and the trickle that comes from Google's Adsense 
	 on the tinyMuw site (which none of you ever click on after you download tinyMuw like 
	 you were asked). And when I say trickle, I mean *trickle*. But Adsense is a very good
	 thing for independent developers. Find out more at:
	 http://www.google.com/adsense

   7. Q: "Will you help me with my website?"

      A: No, I will not. But if you're a blind orphan from rural China living on welfare in
	 South-Central Los Angeles in desparate need of a website for your local Boys and 
	 Girls Club, well.. let's just say I'm not inhumane.


Copyright (C) 2005-2006 Curtis Zimmerman and L0j1k.com.