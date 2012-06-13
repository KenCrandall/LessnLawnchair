LessnLawnchair
==============

URL shortener with stats and custom urls, a fork of Shaun Inman’s Lessn but with many changes

## Features

*	The ability to use custom short URLs (slugs), unlike Lessn
*	A bookmarklet that even supports custom short URLs
*	An [API][] that supports the same commands as the web interface
*	Different auto-shorten modes (optional mixed case),
*	The ability to avoid lookalike characters, and 
*	An optional "banned word list" to prevent auto-generating offensive URLs.
*	Support for more shortened URLs than Lessn
*	The ability to add multiple slugs that point to the same long URL, unlike Lessn
*	Trims punctuation from the right of the slug, per [best practices][bestp]
*	Uses Lawnchair.php so no need to set up MySQL databases.

Legal
-----

Lessn is offered as-is, sans support and without warranty.
Copyright © 2009-10 Shaun Inman and contributors.
Offered under a BSD-like license; see license.txt.

Installation
------------

Installation instructions are different depending on if you are upgrading or doing a fresh install.

### Fresh Install ###

**ONLY** follow these instructions if you are not upgrading!

0. Copy or rename /-/config-example.php to /-/config.php.

1. Open /-/config.php in a plaintext editor and
	create a Lessn username and password.
	You may also choose other settings such as
	authentication salts, a default home page, and your current time zone.

2. For the shortest URLs possible, upload the contents of this
	directory to your domain's root public folder.

3. Make sure the http://doma.in/x/-/data/ folder is writeable (777).

4. Visit http://doma.in/-/ to log in & start using Lessn More!
	Be sure to grab the bookmarklets. 
	
**NOTE:** If your Lessn'd urls aren't working you probably didn't
upload the .htaccess file. Enable "Show invisible files" 
in your FTP application. It's also possible that your host doesn't like
the `<IfModule>` directives; try removed them and just leaving the 
`Rewrite*` lines that were wrapped by the `<IfModule>`. 
(This seems to happen on 1and1).

EXPORTING FROM BITLY
----------------------

1.	Open /lessn/-/bitly_export.php in an editor

2.	Follow the instructions to set up your bit.ly app

3.	Open /lessn/-/bitly_export.php in your browser.

4.	Click the link..

5.	Authorize the app and return via button on bitly (if it shows.. sometimes it's automatic)..

6.	Let it run.. it will populate the Lawnchair DB with the bitly links you previously saved.

API
---

You can find [API documentation here][API].
It's super simple.

Issues
-------

To report an issue or check known issues, visit [the Lessn More issue tracker on GitHub][issues].

[#7]: https://github.com/freekrai/lessnLawnChair/issues/7
