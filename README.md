# CEAS Tribunal TV Submission
This form will allow organizations to submit advertisements to be displayed on TVs in Baldwin Hall and Engineering Research Center. 

## Use Case
### General Flow
A user will enter their information such as their name and email along with the image they to display on the TVs. An email will be sent to the TV admin with the image to approve their TV submission. 

# Getting Started
## Initial Set Up
Prerequisites:
- [Mamp](https://www.mamp.info/en/) (or something equivalent)
- [VS Code](https://code.visualstudio.com) (or something equivalent)
- [Node.js for NPM](https://nodejs.org/en/)

1. `cd` into the MAMP folder.
2. Rename your current htdocs folder to something else using `mv htdocs <new directory name>`.
3. `git clone https://github.com/mihi-r/ceas-tribunal-tv-submission.git` and click enter to clone repo (alternatively, you can use the SSH clone if you have that set up).
4. `mv ceas-tribunal-tv-submission htdocs` to rename the cloned folder to “htdocs”.
5. `cd` into htdocs.
6. Run `npm install` to install all of the needed modules for the project.
7. Run `npm install gulp-cli -g` to install Gulp globally.
8. Next, run `gulp watch`. Gulp watch will compile the TypeScript file and CSS into a minified cross-browser compatible code.
9. Start the MAMP Server and click on "Open WebStart page". 
10. On the newly opened MAMP webpage, go to Tools -> phpMyAdmin.
11. Create a database called "tribunal" by clicking on "new" from the left-hand-side panel. Then, add in the name "tribunal" for the database name and select "utf8_general_ci" as the collation, and click "create".
12. Click on the newly created tribunal database from the left-hand-side panel and click on import from the top toolbar. Import all the files from the schema folder (htdocs/schema). This will create the necessary tables for you.
13. Now create a PHP file which will allow you to connect to the MAMP database. `cd` into api/includes and create a mysqli.php file. For development, the contents of the PHP file can like look like this:
```
<?php
//mysqli database connection

// Development
DEFINE('DB_USERNAME_DEV', 'root');
DEFINE('DB_PASSWORD_DEV', 'root');
DEFINE('DB_HOST_DEV', 'localhost');
DEFINE('DB_DATABASE_DEV', 'tribunal');

// Production
DEFINE('DB_USERNAME_PROD', '');
DEFINE('DB_PASSWORD_PROD', '');
DEFINE('DB_HOST_PROD', '');
DEFINE('DB_DATABASE_PROD', '');

$mysqli = new mysqli(DB_HOST_DEV, DB_USERNAME_DEV, DB_PASSWORD_DEV, DB_DATABASE_DEV);

if (mysqli_connect_error()) {
    die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
}
?>
```
For production, you will need to provide the missing constants and update the database connection to use those.

14. To set up linting, open VS Code and install the TSLint extension. Reload VS Code afterwards.
15. Open up the htdocs directory in VS Code to start developing.
16. To see the webpage, click on "Open WebStart page" from the MAMP Server and click on "My Website".

## Making changes
1. Run `gulp watch`.
2. Make your desired change.
3. Visit the webpage and clear browser cache (<kbd>⌘</kbd>+<kbd>Shift</kbd>+<kbd>r</kbd> or <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>r</kbd>).
