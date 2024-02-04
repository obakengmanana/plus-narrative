# On terminal, navigate into plus-narrative\plus-assessment folder.
cd .\plus-assessment\  

## install all dependencies
npm install

## publish all schemas to the database
php artisan migrate

## run all seeders below
php artisan db:seed --class=PermissionsTableSeeder
php artisan db:seed --class=RolesTableSeeder    
php artisan db:seed --class=UsersTableSeeder

## start project
php artisan serve 
use link provided e.g (Server running on [http://127.0.0.1:8000])

## An admin has been loaded via seeder by default, credentials are as follows:

email: obaman@gmail.com
password: 1234

## Other 100 users, with different data were populated.
## To get their login emails you can login via admin and choose any User email to use, then logout and use it.

Password for all other ordinary users is : 5678.

## for email whenever a user log in the system based on conditions provided.
I made use of mailtrap dummy mailbox, to view all mails, below are the credentials:

Domain/URL: https://mailtrap.io/signin
email: obeekaymanana@gmail.com
password: q@9xXdAVKmuzbp9

Once logged in, click on "Email Testing" then "My inbox", 
all emails should appear in the inbox if their IP and User Agent match what is in the database. 
If it isn't the first entry or a new user, 
an email will be sent out to the user informing there is a login from a new device/browser.

## The link below is for a video doing a short demostration of how the application works.
