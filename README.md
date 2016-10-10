Demo Webshop in Symfony
========

This is my Symfony demo Webshop project.

Install guide step-by-step:

1. Download the zip file or clone the repo:
    ```
    git clone https://github.com/szvitek/demo-webshop.git
    ```

2. Go to the downloaded demo-webshop directory and run:
    ```
    composer install
    ```

3. During the composer install you can configure parameters for your database (or you can do it later manually in the app/config/parameters.yml) 
    ```
    - database_host
    - database_port
    - database_name 
    - database_user 
    - database_password
    ```

4. Create the database:
    ```
    php bin/console doctrine:database:create
    ```

5. Create the schema:
    ```
    php bin/console doctrine:schema:create
    ```

6. Load fixtures:
    ```
    php bin/console doctrine:fixtures:load
    ```

6. Run the built in web server
    ```
    php bin/console server:start
    ```

Now the demo application is ready to use at your localhost you can see more details on the apllication's info page