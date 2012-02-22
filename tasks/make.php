<?php

if ( ! class_exists('Neon'))
{
	require dirname(__DIR__).DS.'libraries'.DS.'Neon.php';
}

use Laravel\Bundle;
use Laravel\CLI\Tasks\Task;

/**
 * A Laravel Task to generate API documentation.
 * 
 * <code>
 *     php artisan apigen::make[:command] [options]
 * </code>
 * 
 * @category    Bundle
 * @package     ApiGen
 * @author      Phill Sparks <me@phills.me.uk>
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 * @copyright   2012 Phill Sparks
 * 
 * @see  https://github.com/sparksp/laravel-apigen
 */
class Apigen_Make_Task extends Task {

	/**
	 * Generate documentation for the application.
	 * 
	 * @return void
	 */
	public function run()
	{
		$options = $this->config(path('app'));

		$this->apigen($options);
	}

	/**
	 * Generate documentation for the Laravel framework.
	 * 
	 * @return void
	 */
	public function core()
	{
		$options = $this->config(path('sys'));
		$options['title'] = 'Laravel';
		$options['main']  = 'Laravel';

		$this->apigen($options);
	}

	/**
	 * Generate documentation for everything.
	 * 
	 * @return void
	 */
	public function all()
	{
		// Gather all the bundle paths, app path and sys path
		$paths = array_map(array('Bundle', 'path'), Bundle::names());
		$paths[] = path('app');
		$paths[] = path('sys');

		// Generate config for the paths
		$options = $this->config($paths);

		// Run ApiGen
		$this->apigen($options);
	}

	/**
	 * Generate documentation for a given bundles.  If no bundles are provided
	 * documentation will be generated for all registered bundles.
	 * 
	 * @param  array  $bundles
	 * @return void
	 */
	public function bundle(array $bundles = array())
	{
		// If no bundles are provided documentation will be generated for all
		// registered bundles.
		if (count($bundles) === 0)
		{
			$bundles = Bundle::names();
		}

		// Remove any bundles that have not been registered, and give a
		// warning for each one we come across.
		$bundles = array_filter($bundles, function($name)
		{
			if ( ! Bundle::exists($name))
			{
				if ($name == DEFAULT_BUNDLE) return true;

				echo "Bundle [$name] is not registered.", PHP_EOL;
				return false;
			}
			return true;
		});

		// If there are no registered bundles then exit with a message
		if (count($bundles) === 0)
		{
			echo PHP_EOL, "Please register your bundles and try again.", PHP_EOL;
			return;
		}

		// Get the options
		$options = $this->config(array_map(array('Bundle', 'path'), $bundles));

		// Run ApiGen
		$this->apigen($options);
	}

	/**
	 * For each source check for a config file and mix in the options.
	 * 
	 * @internal
	 * @param  array|string  $sources
	 * @return array
	 */
	protected function config($sources)
	{
		$sources = (array) $sources;
		$options = Config::get('apigen::default');
		$options['source'] = array();

		// Walk through each source and mix in any ApiGen options found.  If
		// no options are found then we just add the source to the list.
		foreach ($sources as $path)
		{
			if (is_file($path.'apigen.neon'))
			{
				$config = Neon::decode(file_get_contents($path.'apigen.neon'));

				// Do not allow the user to override the destination, this
				// should be done using the bundle config file instead.
				unset($config['destination']);
			}
			else
			{
				$config = array();
			}
			
			// If apigen.neon did not specify a source then we shall do it
			// ourselves.  Note: source needs to be absolute or relative to
			// the base path, not the bundle path.
			if ( ! isset($config['source']))
			{
				$config['source'] = $path;
			}

			// Recursively merge in the config so we get all the excludes.
			$options = array_merge_recursive($options, $config);
		}

		// Only allow a title when there's once source
		if (count($sources) > 1 and isset($options['title']))
		{
			unset($options['title']);
		}

		return $options;
	}

	/**
	 * Run ApiGen with the provided options.
	 *
	 * @internal
	 * @param  array  $options
	 * @return void
	 */
	protected function apigen(array $options)
	{
		// We assume that you've got 'apigen' in your path, if you install
		// ApiGen using PEAR then this should be the case.
		passthru('apigen'.$this->options($options));
	}

	/**
	 * Generate an options string ready for the shell.
	 * 
	 * @internal
	 * @param  array  $options
	 * @return string
	 */
	protected function options(array $options)
	{
		$output = '';
		foreach ($options as $name => $values)
		{
			$name = ' '.escapeshellarg("--$name").'=';

			$values = (array) $values;
			foreach ($values as $value)
			{
				$output .= $name.escapeshellarg($value);	
			}
		}
		return $output;
	}

}
