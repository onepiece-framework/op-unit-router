<?php
/**
 * unit-router:/router.class.php
 *
 * @created   2019-02-23 Separate from NewWorld.
 * @version   1.0
 * @package   unit-router
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2018-04-13  OP\UNIT\NEWWORLD
 * @updated   2019-02-23  OP\UNIT
 */
namespace OP\UNIT;

/** Used class.
 *
 */
use OP\OP_CORE;
use OP\OP_UNIT;
use OP\IF_UNIT;
use OP\Env;
use OP\Cookie;
use function OP\RootPath;
use function OP\ConvertPath;

/** Router
 *
 * @created   2015-01-30  Born at NewWorld.
 * @update    2016-11-26  Separate to unit.
 * @update    2019-02-23  Separate from NewWorld.
 * @update    2019-11-21  Separate to UNIT_ROURER trait.
 * @version   1.0
 * @package   unit-router
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Router implements IF_UNIT
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_UNIT;

	/** g11n is Globalization.
	 *
	 *  <pre>
	 *  Globalization is not Multilingalization.
	 *  World Wide Web is connecting of world wide people.
	 *  People from all over the world visit your site.
	 *
	 *  Internationalization is not Multilingalization.
	 *  Multilingualization is one manifestation of that policy.
	 *
	 *  Localization is local area unique settings.
	 *  For example currency, tax, holiday.
	 *  </pre>
	 *
	 * @created  2019-03-19
	 * @return   array
	 */
	function G11n()
	{
		return $this->_route['g11n'] ?? null;
	}

	/** Use for route table's associative array key name.
	 *
	 * @var string
	 */
	const _ARGS_ = 'args';

	/** Use for route table's associative array key name.
	 *
	 * @var string
	 */
	const _END_POINT_ = 'end-point';

	/** Route table.
	 *
	 * @var array
	 */
	private $_route;

	/** Routing G11n.
	 *
	 */
	private function _G11n(&$dirs)
	{
		//	...
		if( strpos($dirs[0], ':') ){
			//	...
			$this->_route['locale'] = $locale = array_shift($dirs);

			//	...
			Cookie::Set('locale', $locale);
		};
	}

	/** Init route table.
	 *
	 * <pre>
	 * 1. Search end-point by request uri.
	 * 2. Generate smart-url's arguments by request uri.
	 *
	 * Structure:
	 * {
	 *   "args" : [],
	 *   "end-point" : "/foo/bar/index.php"
	 * }
	 * </pre>
	 */
	function __construct()
	{
		//	...
		$config = Env::Get('router');

		//	...
		$this->_route = [];
		$this->_route[self::_ARGS_] = [];
		$this->_route[self::_END_POINT_] = null;

		//	...
		$app_root = RootPath()['app'];

		//	Generate real full path.
		if( Env::isHttp() ){
			//	HTTP
			$full_path = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'];
		}else{
			//	Shell
			$path = $_SERVER['argv'][1] ?? './index.php';
			$full_path = $app_root . $path;
		};

		//	HTML pass through.
		if( file_exists($full_path) ){

			//	Get extension.
			$extension = substr($full_path, strrpos($full_path, '.')+1);

			//	HTML pass through.
			switch( $extension ){
				case 'html':
					$io = $config['html-pass-through'] ?? true;
					break;

				case 'js':
					$io = true;
					Env::Mime('text/javascript');
					break;

				case 'css':
					$io = true;
					Env::Mime('text/css');
					break;
				default:
			};

			//	...
			if( $io ?? null ){
				$this->_route[self::_END_POINT_] = $full_path;
				return;
			};
		};

		//	Remove application root: /www/htdocs/api/foo/bar/ --> api/foo/bar/
		$uri = str_replace($app_root, '', $full_path);

		//	Remove slash from tail: api/foo/bar/ --> api/foo/bar
		$uri  = rtrim($uri, '/');

		//	/foo/bar --> ['foo','bar']
		$dirs = explode('/', $uri);

		//	Globalization.
		if( $config['g11n'] ?? null ){
			$this->_G11n($dirs);
		};

		//	...
		$dir = null;

		//	...
		do{
			//	['foo','bar'] --> foo/bar//index.php --> foo/bar/index.php
			$path = trim(join(DIRECTORY_SEPARATOR, $dirs).DIRECTORY_SEPARATOR.'index.php', DIRECTORY_SEPARATOR);

			//	...
			if( isset($dir) ){
				array_unshift($this->_route[self::_ARGS_], \OP\Encode($dir));
			}

			//	...
			$full_path = $app_root.$path;

			//	...
			if( file_exists($full_path) ){
				$this->_route[self::_END_POINT_] = $full_path;
				break;
			}

			//	...
		}while( false !== $dir = array_pop($dirs) );
	}

	/** EndPoint
	 *
	 * @return   string
	 */
	function EndPoint()
	{
		return $this->_route[self::_END_POINT_];
	}

	/** Args
	 *
	 * @created  2019-03-19
	 * @return   array
	 */
	function Args()
	{
		return $this->_route[self::_ARGS_];
	}

	/** Locale
	 *
	 * @return   string      $locale
	 */
	function Locale()
	{
		return $this->_route['locale'] ?? null;
	}
}
