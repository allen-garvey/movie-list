# Movie List

Web application to keep track of when the movies you want to watch are released. Displays recommendations based on pre-ratings and movie release date and keeps track of ratings for watched movies.

## Dependencies

* PHP >= 7.0
* PostgreSQL >= 9.4.0
* jQuery 2.2.4
* jQuery UI 1.11.4 (datepicker only)
* Bootstrap
* node.js >= 4.4.4
* npm >= 2.15.1

## Getting Started

* `cd` into downloaded project directory
* Type `npm install` to install dependencies
* If you do not have Gulp installed type `npm install gulp -g` or `sudo npm install gulp -g` to install gulp globally to enable the `gulp` command
* Type `gulp build` to compile scss files
* Type `gulp watch` to watch for changes in scss files and build as necessary during development
* Type `npm run setup` to initialize `./inc/db.php` which you should edit with database connection settings (requires bash and POSIX compatible operating system)

## License

Movie List is released under the MIT License. See license.txt for more details.