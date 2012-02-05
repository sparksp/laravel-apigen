# ApiGen Bundle, by Phill Sparks

A Laravel [ApiGen](http://apigen.org/) bundle, installable via the Artisan CLI:

    php artisan bundle:install apigen

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

You can add apigen.neon files to any of the above directories and they will get mixed in with our defaults.  We will ignore any destination you try to override.

You can also configure the default apigen options in **config/default.php**.