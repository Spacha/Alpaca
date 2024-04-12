<?php

/*---------------------------------------------------------
 * Alpaca helpers
 *---------------------------------------------------------
 *
 * This file contains general helper function used by Alpaca.
 * You are free to use them as well but if you wish to create
 * your own helper functions, add new one to app folder.
 */

/**
 * @todo Not permanent! Is this even ok cuz we load this every time from drive??
 * @todo config('app.locale'), config('paths.templates') etc...
 * @param string $config Config file's name matching one in app/config
 * @return array Config file as array if found
 */
function config(string $config = '', bool $anywhere = false) : array
{
    $configPath = $anywhere
        ? $config
        : PATH_ROOT . '/config/' . $config . '.php';

    $configFile = file_exists($configPath)
        ? require($configPath)
        : [];

    return $configFile;
}

/**
 * Get the database configuration if found.
 * @return array
 **/
function dbConfig(string $config = 'dbConfig')
{
    return config(PATH_ROOT . "/{$config}.php", true);
}

/**
 * Get the environment configuration if found.
 * @return array
 **/
function envConfig()
{
    return config(PATH_ROOT . '/envConfig.php', true);
}

/**
 * Laravel Mix helper to make cache busting easy
 * @link https://laravel-mix.com/docs/6.0/versioning
 *
 * @param  string $file Path to the asset file (use leading '/')
 * @return string       Versioned file of the asset (if versioning used)
 */
function mix(string $file)
{
    static $manifests;
    $manifestPath = public_root('mix-manifest.json');

    if (!is_file($manifestPath))
        return $file;

    $manifests = json_decode(file_get_contents($manifestPath), true);

    if (!isset($manifests[$file]))
        return $file;

    return $manifests[$file];
}

/**
 * Redirect the user to new location.
 *
 * @param  string $to The path to redirect to
 * @return void
 */
function redirect(string $to)
{
    header("Location: ". $to);
    die();
}

/**
 * Get the current timestamp in specified format.
 * @return string
 */
function now()
{
    return date(config('app')['date_format']);
}

/*---------------------------------------------------------
 *      PATH HELPERS
 *---------------------------------------------------------
 *
 */

/**
 * Return given path related to app root
 *
 * @param string $path
 * @return string
 */
function app_root(string $path)
{
    return PATH_ROOT.'/'.$path;
}

/**
 * Get a path from the path config file by the key.
 *
 * @param string $name  The path key
 * @param string $after Path coming after the base path
 * @return string       The corresponding path if found, or the key if path is not found
 */
function app_path(string $name, string $after = '')
{
    $paths = config('paths');
    if (array_key_exists($name, $paths))
        return PATH_ROOT.'/'.$paths[$name].'/'.$after;
    else
        return $name.'/'.$after;
}

/**
 * Return given path related to public root
 *
 * @todo Cannot use config like this!
 * @param string $path
 * @return string
 */
function public_root(string $path)
{
    $paths = config('paths');
    return dirname(PATH_ROOT).'/'.$paths['public'].'/'.$path;
}

/*---------------------------------------------------------
 *      VARIABLE HELPERS
 *---------------------------------------------------------
 *
 */

/**
 * Get data value from array. Checks if stuff exists for you.
 *
 * @param array $params parameter array which Router passed to the method
 * @return * Return the value from the array or null if not found
 */
function data($params = [], $key = '')
{
    return $params[$key] ?? null;
}

/**
 * Prevents unwanted errors from happening. Return $var if exist,
 * otherwise return default value which is null as default
 *
 * @param $var The variable
 * @param $default If $var is undefined, use this, null as default
 * @return * Return the value or default value
 */
function opt($var = null, $default = null)
{
    return $var ?? $default;
}

/**
 * Unset element from array and return reindexed array
 *
 * @param array $array 
 * @param integer $index Which element to remove
 * @return array
 */
function array_unset(array &$array, $index = 0) : array
{
    unset($array[$index]);
    return array_values($array);
}


/*---------------------------------------------------------
 *      DEVELOPMENT HELPERS
 *---------------------------------------------------------
 *
 */

/**
 * Dumps given variable and kills the program.
 * 
 * @param $var Variable you want to get dumped
 **/
function dd($var = null)
{
    echo "<pre>";
    
    die(var_dump($var));

    echo "</pre>";
}

/**
 * Dumps all given variables without killing the program.
 * Also separates all of them nicely
 * 
 * @param ...$vars All the variables you want to be dumped
 * @return void
 **/
function bb(...$vars)
{
    print_r('_______________________________________________________');

    foreach ($vars as $var) {
        print_r(PHP_EOL);
        print_r($var);
        print_r(PHP_EOL);
    }

    print_r('_______________________________________________________'.PHP_EOL);
}
