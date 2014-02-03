<?php
/* This first setting is the NAME OF YOUR SITE! */
define(siteName, 'L0j1k.com');
/* This next setting is the administrator email, we use this */
/* in a couple spots, specifically the place where you want */
/* severe security warnings to be sent. */
define(adminEmail, 'L0j1k@L0j1k.com');
/* This next one is obvious. You can change it to something */
/* completely arbitrary if you don't want to put this out on */
/* the web for all to see. */
define(versionNum, 'v0.1.1');
/* You need to change this for any of the site to work. It's */
/* the root folder of your website. ALMOST ALL LINKS IN TINYMUW */
/* USE THIS CONSTANT! That's how badly you need to change it. ;) */
/* Please do not forget to add a forward-slash at the end! */
define(webRoot, 'http://www.L0j1k.com/LoFiPages/');
/* Hereafter lies the grand ole Database Info, that you need to */
/* change to fit the credentials for your specific server! */
define(dbServer, 'localhost');
define(dbUsername, 'l0j1k02_tinyMuw');
define(dbPassword, 'cc483top');
/* This next one you shouldn't need to change, as it is automagically */
/* set by the tinyMuw installer. BUT if you're feeling tinkery, go */
/* ahead and change away, friend! :) */
define(dbDatabase, 'l0j1k02_tinyMuw');
/* Below are cookie names that need to be changed. I don't */
/* think you'd be happy if you just downloaded tinyMuw and */
/* started using it, only to have someone else come in and */
/* change all the content on your pages because you didn't */
/* do your homework and change all the simple things here! */
define(verifiedCookie, 'youarein');
define(usernameCookie, 'luser');
define(lastUsername, 'loggedusername');
/* Below is the time (in seconds -- use your fucking calculator, */
/* math whiz) it takes for our login cookies and our data from */
/* the Active and Guest User tables to expire, and hence how long */
/* a user can idle between page loads before they have to verify */
/* that they are who they say they are, as well as the maximum */
/* time a user (active or guest) is listed in the active and guest */
/* user tables. Before you go setting this to six weeks or eight */
/* years, be advised that users will NOT be removed from the */
/* active and guest tables UNTIL AFTER this amount of time has */
/* passed. So essentially if someone visits your page, then runs */
/* away because of the porn, it will show a guest user viewing the */
/* site for expireTime seconds after they loaded their last page. */
/* Please also consider not leaving cookies on your grandmother's */
/* poor computer that don't expire for the next 40 years. */
/* The default here is 10 minutes (600 seconds). */
define(expireTime, '1200');
/* CHANGE THE FOLLOWING VARIABLES! Starfleet Class One Imperative! */
/* Change them to "muck" and "fiddle" or "leftnut" and "rightnut". */
/* I don't care what you change them to, as long as you change them! */
/* The first is the name of the variable in a few functions which */
/* test to see if the function was called internally (i.e. by the */
/* program) or by some other means (i.e. random hacker). It's */
/* essentially a password for function calls! The second is the name */
/* of the session variable that is set to true when you are found to */
/* be an admin user. Don't use spaces or weird characters, just the */
/* normal ones. */
define(internalCall, 'boobFlingPip');
define(adminVariable, 'chosen');
/* The following two variables reflect how many times (emailCount) */
/* the system will allow an account to be emailed in a certain amount */
/* of time (emailTime). This is to prevent email abuse. The default */
/* here is tinyMuw will allow 3 emails to be sent to a user in one */
/* day (this time is measured in seconds, so use your math!). */
define(emailTime, 86400);
define(emailNum, 3);
/* This next variable is the max number of entries from the QuickChat */
/* database to display at one time, unless the user wants more. */
define(quickchatNum, 5);
/* The following variables need to be changed to reflect the structure */
/* of your website. There are some defaults here, but they won't work */
/* unless you have EXACTLY what is listed here, which is unlikely. :) */
/* This first one is the name of the page you have created where users */
/* are redirected when they wish to register as a user for your site. */
/* The second one is the name of the page you wish to direct users to */
/* once they successfully register and log in for the first time. */
define(registerPage, 'registerPage.php');
define(successPage, 'successPage.php');
/* This next one is the name of the page you have created where users */
/* are directed to when they have forgotten their password and wish to */
/* have it reset. */
define(forgotPage, 'forgotPage.php');
/* Below is the name of the page you have selected as your index page. */
/* By index page I mean the page that you want to have your news on. */
/* By default this should be index.php, as there really isn't any reason */
/* to change this from standard web development practices. If however */
/* MUST be different, specify the name here, homo. */
define(indexPage, 'mainPage.php');
/* This is the simple page where the terms and conditions of using your */
/* site are kept. This checkbox is required of users when they attempt */
/* to register as a user on your site. This is a good thing. */
define(termsPage, 'termsPage.htm');
/* This is the name of the page that you will include('admin.php') on, */
/* where it will show administrator options to handle your users. */
define(adminPage, 'adminPage.php');
/* The next value sets the page where you want to redirect users who */
/* are attempting to login or register from a banned IP address. */
define(bannedPage, 'bannedPage.php');
/* This defines where we want to send users (including admin) who want */
/* to change their preferences, like password, email, etc. */
define(userPage, 'userPage.php');
/* The next value is how often you want to check the tinyMuw server for */
/* updates on admin user login. This value shouldn't be set too low. The */
/* default value here is at least every two weeks. This value is, like */
/* any other value in here, expressed in seconds. Use Math! */
define(updateSystem, 1209600);
?>