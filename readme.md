# ApiGen Bundle, by Phill Sparks

A Laravel [ApiGen](http://apigen.org/) bundle, installable via the Artisan CLI:

    php artisan bundle:install apigen

**Tip:** You must have 'apigen' installed and in your path for the bundle to work.

## Generate

Generate API Documentation for the application:

	php artisan apigen::make

Generate API Documentation for Laravel:

	php artisan apigen::make:core

Generate API Documentation for a bundle:

	php artisan apigen::make:bundle {name}

Generate API Documentation for all bundles:

	php artisan apigen::make:bundle

Generate API Documentation for everything:

	php artisan apigen::make:all

##Configure

You can add apigen.neon files to any of the above directories and they will get mixed in with our defaults.  However, the destination will always be **api**.

You can also configure the default apigen options in **config/default.php**.