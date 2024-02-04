# This assessment is to test your Laravel development skills

The purpose of this Laravel project is to be able to create a user admin system, where you can create/edit a user and assign a role to a user. Each role has permissions assigned to it. The system also sends an email when a user logs in with a new IP or device/browser.

## Please follow the below process to get set up.

- Create a new Laravel project via Composer with the name `plus-assessment` and complete the full installation with your database set up.
- Update/create Migrations/Seeders/Models
    - Update the user migration to have a first_name and last_name and any other columns you find neccessary.
    - Create a roles table.
        - The roles should include, `Admin`, `Content Manager`, `User`.
    - Create a permissions table.
        - The permissions should include, `View Admin Dashboard`, `Administer Users`.
        - All permissions should be assigned to the `Admin` role.
        - `View Admin Dashboard` should be assigned to the `Content Manager` role.
    - Create the pivot tables for the above migrations.
    - Create another pivot table that keeps track of a users IP, IP Location (integrating with https://ip-api.com/ free version), login_at time and browser user agent when they log in.
    - Create a user seeder and populate the database with 100 users, making one of them an admin user.
- Add the Laravel Breeze Authentication [starter kit](https://laravel.com/docs/10.x/starter-kits#laravel-breeze).
- Add an `admin` button to the top header.
- Add the neccessary admin routes to view a list of users, create users and edit a user.
- When creating/editing a user, the admin should be able to assign multiple roles to a user.
- If a user that does not have the role `admin` logs in, they should not be able to access the admin backend.
- When a user logs in, the system should check if their IP and User Agent match what is in the database. If it isn't the first entry or a new user, an email ([https://laravel.com/docs/10.x/mail#markdown-mailables](https://laravel.com/docs/8.x/mail#markdown-mailables) or [https://laravel.com/docs/8.x/notifications#mail-notifications](https://laravel.com/docs/8.x/notifications#mail-notifications)) needs to be sent out to the user informing there is a login from a new device/browser.
- When storing/updating a user, use [Form Requests](https://laravel.com/docs/10.x/validation#form-request-validation) to validate and sanitise the request.
- View the design in [Figma](https://www.figma.com/file/B3YLATw9Mw0I7O1zhB63VW/Plusnarrative-Admin-Dashboard?node-id=42%3A1090)
- The frontend is up to you to build and we'd like you to please use [Tailwind CSS](https://tailwindcss.com/)
- Where possible use [components](https://laravel.com/docs/10.x/blade#components) for building out the frontend (anonymous components can be used).

### NOTE: The code contained herein is property of PlusNarrative. You may not use the code in its entirety or a portion thereof without written consent from PlusNarrative, outside of the purposes of completing this assessment. By downloading/manipulating any of these files, you agree to be bound hereby.