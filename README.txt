-----------------------------------------------------------
# Lessn / Lawnchair is an extremely simple, personal url shortener 
  written in PHP with LawnChair.php and mod_rewrite.
-----------------------------------------------------------

Installation

1. Open /lessn/-/config.php in a plaintext editor and
   create a Lessn username and password.

2. Upload the entire /lessn/ directory to your server. 
   For the shortest urls, place it at the root of your 
   site and rename to a single character. 
   Example: http://doma.in/x/

3. Make sure the http://doma.in/x/-/data/ folder is writeable (777).

4. Visit http://doma.in/x/-/ to Lessn a new url and grab
   the bookmarklets.

EXPORTING FROM BITLY
----------------------

1.	Open /lessn/-/bitly_export.php in an editor

2.	Follow the instructions to set up your bit.ly app

3.	Open /lessn/-/bitly_export.php in your browser.

4.	Click the link..

5.	Authorize the app and return via button on bitly (if it shows.. sometimes it's automatic)..

6.	Let it run.. it will populate the Lawnchair DB with the bitly links you previously saved.


NOTE:

   If your Lessn'd urls aren't working you probably didn't
   upload the .htaccess file. Enable "Show invisible files" 
   in your FTP application.