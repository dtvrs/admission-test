# EYESO Admission Test
Hey, welcome to our little coding challenge.

## Instructions
To run this application you'll need Docker, Composer and NPM installed on your computer.

### Docker

On the eyeso-test-docker folder, copy the `.env.dist` file to `.env`.

Run the command `docker-compose up -d`

### Dependencies

Install all project related dependencies for backend and frontend.

### Backend

1. The parameters.yml file should look like 
```
    parameters:
        database_host: db
        database_port: null
        database_name: eyeso_test
        database_user: user
        database_password: userpass
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        secret: ThisTokenIsNotSoSecretChangeIt
```
2. With all dockers running, access the php container with the command `docker exec -it eyeso_test_php bash`
3. To create the schema run the command `php bin/console doctrine:schema:create`
4. To run the data seeder, use the command `php bin/console doctrine:fixtures:load`

### Frontend

To build the application, run `ng build`


Access [http://localhost:8080](http://localhost:8080) and go to the Exercise page.

When you are done, check in your code to a github project of yours, write a short release-document and email it back to us with the link to your project. Do not amend after you have delivered it.

Please note that we do not exclusively rate how many of the points you have covered but also approach, documentation and quality.

Good Luck!

The EYESO Team
