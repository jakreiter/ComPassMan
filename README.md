compassman - Company's Password Manager
=======================================

Compassman is an application that allows sharing of passwords by many users.
All passwords entries to external resources are encrypted with a master key.

The master key is saved in an encrypted form.
For each user, 
there is an entry with a master key encrypted with user's key
(which is determined mainly based on the user's password).
The user's password is not saved on the server.
Only hash from password is saved on the server.
 
This is server-side web application.

There is no feature that encrypts transmission between server and browser.
To ensure the confidentiality of transmitted passwords,
additional tools should be used - for example HTTPS protocol and SSL certificate.


Requirements
------------
Web server with PHP support.

See composer.json for details.

Installation
------------
  * Clone repository
  * Make public directory accessible by web server
  * Prepare empty MySQL database
  * Basing on .env.dist create .env file with configuration
  * On linux machines run `chmod 744 *.sh`
  * Run install.sh
  
  

