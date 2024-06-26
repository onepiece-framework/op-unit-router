<?php
/** op-unit-router:/include/CalcRoute2018.php
 *
 * @created    2024-05-27 Copy from ROUTER_2018.trait.php
 * @version    1.0
 * @package    op-unit-router
 * @author     Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace OP\UNIT\ROUTER;

/** use
 *
 */
use \OP\Env;
use function \OP\RootPath;

/* @var string $request_uri */
if( empty($request_uri) ){
	$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
}

//	...
$_route = [];
$_route[self::_ARGS_] = [];
$_route[self::_END_POINT_] = null;

//	...
if(!$app_root = RootPath('app') ){
	throw new \Exception('app:/ was not set.');
}

//	Generate real full path.
if( Env::isHttp() or Env::isCI() ){

	//	Separate of URL Query.
	if( $pos   = strpos($request_uri, '?') ){
		$uri   = substr($request_uri, 0, $pos);
	}else{
		$uri   = $request_uri;
	};

	//	HTTP
	$full_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/').$uri;

}else{
	//	Shell
	$full_path = $app_root . ($_SERVER['argv'][1] ?? '');
};

//	Remove duplicate slash.
$full_path = str_replace('//', '/', $full_path);

//	Check "asset" path.
if( $pos = strpos($full_path, RootPath('app').'asset/') === 0 ){
	$full_path = RootPath('app').'404';
}

//	HTML pass through.
if( is_dir($full_path) ){
	//	Directory
}else
if( file_exists($full_path) ){
	//	Get extension.
	$extension = substr($full_path, strrpos($full_path, '.')+1);

	//	...
	if(!$mime = OP()->GetMimeFromExtension($extension) ){
		OP()->Notice("This extension's MIME is not define. ({$extension})");
	}

	//	HTML pass-through possible file extensions.
	if( strpos('html, css, js, txt', $extension) !== false ){
		//	...
		Env::MIME($mime);

		//	...
		$_route[self::_END_POINT_] = $full_path;

		//	...
		return $_route;
	};
};

//	Remove application root: /www/htdocs/api/foo/bar/ --> api/foo/bar/
$uri = str_replace($app_root, '', $full_path);

//	Remove slash from tail: api/foo/bar/ --> api/foo/bar
$uri  = rtrim($uri, '/');

//	/foo/bar --> ['foo','bar']
$dirs = explode('/', $uri);

//	...
$dir = null;

//	...
do{
	//	['foo','bar'] --> foo/bar//index.php --> foo/bar/index.php
	$path = trim(join(DIRECTORY_SEPARATOR, $dirs).DIRECTORY_SEPARATOR.'index.php', DIRECTORY_SEPARATOR);

	//	...
	if( isset($dir) ){
		array_unshift($_route[self::_ARGS_], \OP\Encode($dir));
	}

	//	...
	$full_path = $app_root.$path;

	//	...
	if( file_exists($full_path) ){
		$_route[self::_END_POINT_] = $full_path;
		break;
	}

	//	...
}while( false !== $dir = array_pop($dirs) );

//	...
return $_route;
