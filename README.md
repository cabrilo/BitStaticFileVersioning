BitStaticFileVersioning
=======================

Zend Framework 2 module for automatically appending version number to a static file in order to force browsers to reload it.

The Problem
-----------

Whenever a CSS, JavaScript or image file is included in a HTML document, it will be cached by browser, which will load the same static file on subsequent page loads.
When you change your CSS or JavaScript file, you want the browser to discard the cached file and use a new one.

The Solution
------------

Always append a version string to the end of the file name. E.g.

	<link href="/css/style.css" media="screen" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/js/script.js"></script>

would become:

	<link href="/css/style.css?v=1" media="screen" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/js/script.js?last_modification=January2013"></script>
	
The query string will instruct browser to reload the asset without using the old file. I.e. browsers will consider "/css/style.css?v=1" and "/css/style.css?v=2" to be two separate files.
In order to "clear" the cache of the browser, you would need to append a different query string to the end of the file from the previous version, e.g. ?v=15 or ?v=20

*BitStaticFileVersioning* does this for you automatically.

Installation
------------

### By cloning

Clone into `./vendor/`.

### Post intallation

1. Enable the module in your `application.config.php` file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'BitStaticFileVersioning',
        ),
        // ...
    );
    ```

Usage
-----

In your view, include files with `staticVersion` view helper:

```php
<?php $this->staticVersion($relativePathUrl); ?>
```
	
For example, like this:

```php
<?php echo $this->headLink(array('rel' => 'shortcut icon', 'type' => 'image/gif', 'href' => $this->staticVersion($this->basePath() . '/favicon.gif')))
	->appendStylesheet($this->staticVersion($this->basePath() . '/css/mycssfile.css'));
?>
<?php echo $this->headScript()->appendFile($this->staticVersion($this->basePath() . '/js/scripts.js')); ?>
```

Defaults
--------

By default, *BitStaticFileVersioning* will append `?v=LAST_FILE_MODIFIED_TIME` to the file. 
For this to be possible,  *BitStaticFileVersioning* has to be able to map your URL to a filesystem path of the file. By default, it will look into:

	getcwd() / public / YOUR / URL / FILE.CSS
	getcwd() / htdocs / YOUR / URL / FILE.CSS

So, if you call the view helper with:

```php
$this->staticVersion('/css/mycssfile.css');
$this->staticVersion('/assets/js/script.css');
```

and your application has the standard skeleton organization in e.g.:

	/var/www/myproject/public/index.php
	/var/www/myproject/modules
	/var/www/myproject/vendor
	...
	
the script will look for files in:

	/var/www/myproject/public/css/mycssfile.css
	/var/www/myproject/public/assets/js/script.css

Configuration
-------------

You can override this default behaviour by adding a custom callable to your config:

```php
'static_file_versioning' => array(
	'resolvers' => array(
		/**
		 * @var string $input	This is the URL from the view (e.g. /css/mycssfile.css)
		 * @var \Zend\ServiceManager\ServiceManager $serviceLocator
		 */
		'default' => function($input, $serviceLocator) {
				// Code to resolve a file to filesystem path, e.g.:
				$file = '/var/www/storage/' . $input;
				
				// Code to determine version:
				$version = filemtime($file);
				// or: 
				$version = md5_file($file);

				return $version;        
			}
	),
),
```

Advanced configuration & usage
------------------------------

View helper has several parameters:

```php
<?php $this->staticVersion(RELATIVE_PATH_URL, RESOLVER, PREFIX, SUFFIX); ?>
```
	
If `RESOLVER` is specified, you can use a different resolver than the 'default' one (you can have as many as you want defined).
PREFIX is added before the version number, by default: `?v=`
SUFFIX is added after the version number, by default it is blank.

You can set default prefix and suffix in your module configuration, like so:

```php
'static_file_versioning' => array(
	'prefix' => 'my prefix',
	'suffix' => 'my suffix'
),
```
