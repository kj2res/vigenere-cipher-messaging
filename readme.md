# vigenere-cipher-messaging
Messaging demo using Vigenère Cipher Encryption

## Fresh install

    git clone https://github.com/trs-king/vigenere-cipher-messaging.git
    cd vigenere-cipher-messaging
    composer install
    cp .env.local .env
    php artisan key:generate
    # Now update .env file placeholders with custom values for your local machine
    php artisan migrate --seed

## Quick Run

    php artisan serve

    and go to your browser see http://localhost:8000