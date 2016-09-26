job_posts
=========

A Symfony project created on September 23, 2016, 2:28 pm.

------------------------------------------------------------------------------------------
Installation process:

- pull project files on web server

- execute command: "composer install"
 
- set database parameters in file: "app/config/parameters.yml"

- execute command: "php app/console doctrine:migrations:migrate", inside project folder.
  This will create tables in database based on Doctrine migrations
-------------------------------------------------------------------------------------------


- Application default route is "/"
- On the right side of menu "Login" link is used for entering Back-end part,
  Password: "manager"/"moderator", Username: anything, not empty
- Password is hardcoded for testing purpose
