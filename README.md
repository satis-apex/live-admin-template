# Live-Admin

![LIVE Admin Template](https://iili.io/HqHBmej.jpg)

Live-Admin is an admin template created by integrating Laravel, Vue3 and Inertia.js with the UI Toolkit Element Plus and css utility Tailwind. The work is inspired by vue-element-admin with the latest versions of the stack integrated with vite bundler for a rapid development experience. With the powerful Laravel framework as the backend, Vue3 as the high performance on the front end integrated with Inertia js, vite as a bundler, Live-admin appears to be a full-stack solution for an enterprise application level.

## Features:

-   Dynamic menu management with the recorder.
-   Role Permission integrated with the menu
-   User Impersonate option for admin only.
-   Option to generate Controller, Model and Service class (all about personal preferences and coding standard)
-   Laravel Module Implemented with database activator
-   Ability to create fully functional Demo Crud file from add Manage Menu UI dynamically
-   Dark mode Compatible
-   Notification bell integrated with laravel notification (database,broadcast)
-   Event Announcement integrated with Full calender library
-   Broadcast Event Announcement using Laravel-websockets

## Requirement

Prior running application make sure your machine meets all the defaults requirements from [laravel documentation](https://laravel.com/docs/9.x/deployment#server-requirements) and also include the following

-   [node](https://nodejs.org/en/download/) (version: [16.13.1](https://nodejs.org/en/download/))
-   [composer](https://getcomposer.org/download/)
-   php-gd2 library extension
-   php-exif extension

## Installation

Use the [composer](https://getcomposer.org/download/) to install php dependencies.

```bash
composer install
```

Use the [npm](https://www.w3schools.com/whatis/whatis_npm.asp) to install js dependencies.

```bash
npm install

#development server
npm run dev

#production server
npm run build
```

### Don’t forget .env.example

form your root application folder rename [.env.example](https://github.com/laravel/laravel/blob/9.x/.env.example)
to .env and update the corrersponding value to your need.

Inside .env MODULE_ACTIVATOR must be set to "file" initially before table migration else an error occurs. (library-specific issue). After successful migration you can change it manually, or "php artisan live:migrate" will do this automatically for you )

    MODULE_ACTIVATOR=file

```bash
#generates key for session encryption
php artisan key:generate

#create symlink in public folder
php artisan storage:link

#configure env file for laravel-websocket and migrate database and seed data
php artisan live:initialize
```

### Enabling laravel Schelule

laravel websocket package provides the artisan command "websockets:clean" to clean up old statistics from the websocket log in the database table, but needs to run the command manually. I have created a laravel schedule to run this command daily.You also need to add a cron entry to your server's crontab file to run the Laravel schedule every minute:

```bash
#linux/mac users only
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

#window/linux/mac user -manually runs untile server shutdowns
php artisan schedule:work
```

## Usage

```bash
#start local php server
php artisan serve
or
php artisan serve --port=80
#start local websocket server
php artisan websockets:serve
```

## Keeping the socket server running in production

Please follow the official documentation [link](https://beyondco.de/docs/laravel-websockets/basic-usage/starting#keeping-the-socket-server-running-with-supervisord)

## Admin Login Infos / Demo Account

-   Email: _admin@admin.com_
-   Password: _password_

### Docker

[WIP]

## Built with

-   [laravel](https://laravel.com)-Laravel is a PHP web application framework with expressive, elegant syntax.
-   [Inertia.js](https://inertiajs.com)- A glue that connects your existing server-side or client-side frameworks.
-   [laravel Breeze](https://github.com/laravel/breeze)- Breeze provides a minimal and simple starting point for building a Laravel application with authentication. Styled with Tailwind
-   [spatie/laravel-permission](https://github.com/spatie/laravel-permission)-Associate users with permissions and roles.
-   [vue3](https://vuejs.org) - An approachable, performant and versatile framework for building web user interfaces.
-   [Element Plus](https://element-plus.org/en-US/) - A Vue 3 based component library for designers and developers.
-   [Laravel Modules](https://github.com/nWidart/laravel-modules) - A package to manage your large Laravel app using modules.
-   [Laravel Websockets](https://github.com/beyondcode/laravel-websockets) - A package to Bring the power of WebSockets to your Laravel application (Pusher replacement).
-   [Full Calender](https://fullcalendar.io) - Highly customizable Full-sized event calendar.
-   [landing Page](https://github.com/tailwindtoolbox/Landing-Page) - A generic landing page template for Tailwind CSS

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## 🚀 About Me

I'm a full stack developer...

# Authors

-   **Satish Maharjan** - _full development_ - [satis-apex](https://github.com/satis-apex)

# Acknowledgements

-   [Laravue-core](https://github.com/tuandm/laravue-core).
-   [Quick Admin Panel](https://quickadminpanel.com)

## Roadmap

-   ✔ Integrate laravel Notification
-   ✔ Intregrate [laravel Modules](https://github.com/nWidart/laravel-modules)
-   ✔ Intregrate [laravel Websocket](https://github.com/beyondcode/laravel-websockets)
-   ✔ Intregrate [Full Calender](https://github.com/fullcalendar/fullcalendar)
-   Intregrate [Laravel webpush](https://github.com/laravel-notification-channels/webpush)
-   Multi tenent implementation (i will implement if this feature is requested)
-   Add more integrations

## License

[MIT](https://choosealicense.com/licenses/mit/)
