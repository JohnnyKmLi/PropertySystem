# Property System Trial Task
This project was developed by using localhost server XAMPP.

# Installing Xampp local server
1. Navigate to https://www.apachefriends.org/index.html.
2. Install the latest window version.
3. Execute the installer and keep a record of details and where you have installed to.

# Starting Xampp
1. Navigate to the Xampp directory which you should have a note of (default: C:\xampp).
2. Execute `xampp-control.exe`.
3. When window starts up you should see a list of modules Apache, MySQL, FileZilla, etc (we will only be using Apache and MySQL).
4. Click the start actions for the module "Apache" and "MySQL".

# Adding database
1. Use a browser on your computer (I'm using google chrome).
2. In the browser type the url `http://localhost/phpmyadmin` (this will take you the phpmyadmin application on the xampp server, this will handle the database).
3. Import the `trial.sql` database that can be found in this repo, in the database folder. If this does not work use the script `seed_database.sql` and execute it in phpmyadmin to create the tables, this should create the tables.

# Adding Vuejs webpage and PHP api
1. Navigate to the xampp directory and locate directory `htdocs`.
2. Remove all files and folder currently there.
3. Clone the repo and move all the files into the `htdocs` directory.

# Using the webiste
1. Start up your broswer and enter the url `http://localhost/view/PropertyOverview.html`.
2. This should take you to a simple webpage with the title `Properties`, buttons `Refresh Properties` and `Show Properties`.
3. Click on `Refresh Properties` this will insert or update any properties missing from the database from the property system API.
4. Click on `Show Properties` this will display the property details in the database (currently just a container of information).
