LIGOZZA — FIX FOR GITHUB PAGES + PHP REGISTRATION

WHY THE 405 HAPPENED
GitHub Pages only hosts static files. It cannot execute register.php or process a PHP POST request.

THIS ZIP HAS TWO SEPARATE PARTS:
1) site/        -> upload the CONTENTS of this folder to your GitHub Pages repository.
2) php-backend/ -> upload these PHP files to a separate hosting account that supports PHP + MySQL.

IMPORTANT: Do NOT upload php-backend/ to the public GitHub Pages repository. It contains the PHP backend and config file. Do not publish database credentials.

SETUP STEPS
A. GITHUB PAGES WEBSITE
1. Open the site/ folder in this ZIP.
2. Upload all files and the assets/ folder inside site/ to the root of your GitHub Pages repository (keep the folder structure).
3. In every HTML page, the form now points to:
   https://YOUR-PHP-HOSTING-DOMAIN/register.php
   Replace YOUR-PHP-HOSTING-DOMAIN with the real domain/subdomain provided by your PHP hosting company.
   You can use Find/Replace across the HTML files, or edit the action URL in each page.

B. PHP BACKEND HOSTING
1. Get PHP + MySQL hosting (GitHub Pages cannot do this part).
2. Create a MySQL database and database user in its control panel.
3. Import php-backend/database.sql using phpMyAdmin.
4. Upload php-backend/register.php and php-backend/config.php into the PHP hosting web root (often public_html/).
5. Edit config.php with the real db_host, db_name, db_user, db_pass and from_email values.
6. In config.php, set site_url to your real GitHub Pages homepage URL, including the trailing slash.
7. Confirm PHP PDO MySQL is enabled. Configure SMTP/mail with your hosting provider if PHP mail() is unavailable.
8. Use HTTPS for the PHP backend and set the form action URLs to its HTTPS register.php URL.

REGISTRATION BEHAVIOUR
- One registration per email address (MySQL unique constraint).
- IDs begin at #2006001 and increment automatically.
- Admin notification goes to hansindupulitha403@gmail.com.
- Customer confirmation email is attempted.

TESTING
- First submit a new email address and confirm the success page shows a registration ID.
- Submit the same email again; it should show Email already registered.
- Check the MySQL table ligozza_registrations to verify the record.

NOTE
I cannot activate the live backend or fill in hosting credentials from this ZIP alone. You must provide/configure a PHP + MySQL host. The GitHub Pages website itself can remain on GitHub Pages.
