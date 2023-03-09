# testfruitsapp

## Name
 testfruitsapp

## Description
This is a Symfony project that performs the following tasks:

1. Fetches fruits data from an external API (https://fruityvice.com/).
2. Saves the fruits data into the database.
3. Sends an email notification whenever a new fruit is added.
4. Implements a page to display all fruits with pagination.
5. Adds a filter form to search fruits by name and family.
6. Allows users to add fruits to favorites (up to 10).
7. Implements a separate page to display favorite fruits.
8. Calculates and displays the sum of nutrition facts of all favorite fruits on the favorite fruits page.


## Installation
1. Clone the repository
  `git clone'
2. Install dependencies
   `composer install`
3. Configure the database in the .env file and Mailer DSN
   `DATABASE_URL="mysql://user:password@127.0.0.1:3307/dbname?serverVersion=mariadb-10.6.5&charset=utf8mb4"`
   `MAILER_DSN=smtp://localhost:port`
4. Create the database schema
  `symfony console doctrine:database:create
   symfony console doctrine:migrations:migrate`
6. create the command to fech all fruits from the link fruits
  `symfony console app:get-fetch-fruits`
7. Run the local server 
  `symfony serve -d`
## Usage
 1. Access on the home page by the backed link 
 2. On the homepage, you can see a list of all the fruits with pagination. You can also filter the fruits by name and family using the filter form.
 3. Click on the "Add to favorites" button to add a fruit to your favorites list.
 4. Click on the "All favorited Fruits" link in the navigation menu to view your favorite fruits. The nutrition facts of all the favorite fruits are also displayed on this page.

## Support
1. debugging tools 
2. Symfony website
3. Twig website


