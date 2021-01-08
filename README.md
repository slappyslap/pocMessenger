#POC Symfony messenger
#prerequisites
- Symfony 5.*
- RabbitMQ or another messenger
##Step to run
- git clone https://github.com/slappyslap/pocMessenger
- composer install
- Set env variables
    - DATABASE_URL
    - SEND_TO
    - MAILER_DSN
    - MESSENGER_TRANSPORT_DSN
- Launch app and test
- Run php bin/console messenger:consume async

