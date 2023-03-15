## Setup Backend Code

The instructions will be divided into parts.

### Inital

- Go to Src/symfony_docker/app
- Open .env and .env.test
- You Can get  the following params ( MYSQL_ROOT_PASSWORD & MYSQL_USER & MYSQL_PASSWORD & MYSQL_DATABASE)

## Start Project
From the base directory run these steps:

1. In terminal execute this command( make start-project )
2. In terminal execute this command .(make composer-install)
3. In new terminal execute this command (make database-inital) .
4. Connect to mysql and create DB
5. Put the name of db inside .env to parameter (MYSQL_DATABASE)
6. In terminal execute this command (make database-update)
8. Your application will be run on (http://localhost:3333/) if you don't use kong
9.  Your application will be run on (http://localhost:8000/) if you use kong
10. Run rabbitmq you can execute ( make rabbitmq-execute)




## Notes
1- I made 3 queues ( messages, notification_creation , notification_dispatch )

2- I didn't write more unit tests because i don't have enough time

## Entities

1. if you create a new entity you can update schema with this command ( make database-update)

## Tests
1. To run Unit Test execute this command (make phpunit)


## Other Notes
1. I pushed .env and .env.test to see you what I did it in these files 