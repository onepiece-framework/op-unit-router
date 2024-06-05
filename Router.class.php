<?php
/** op-unit-router:/Router.class.php
 *
 * @created   2019-02-23 Separate from NewWorld.
 * @version   1.0
 * @package   op-unit-router
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2018-04-13  OP\UNIT\NEWWORLD
 * @updated   2019-02-23  OP\UNIT
 */
namespace OP\UNIT;

/** Use
 *
 */
use OP\OP_CI;
use OP\OP_CORE;
use OP\IF_UNIT;

/** Router
 *
 * @genesis   2008 OnePiece
 * @created   2009 NewWrold was re:born in Kozhikode.
 * @created   2015-01-30  Born at NewWorld.
 * @updated   2016-11-26  Separate to op-unit-newworld from op-core class.
 * @updated   2019-02-23  Separate to op-unit-router from op-unit-newworld.
 * @version   1.0
 * @package   op-unit-router
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Router implements IF_UNIT
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_CI;

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
		$this->_route = self::Calculate();
	}

	/** Return end-point
	 *
	 * @created  2019-02-23
	 * @return   string
	 */
	function EndPoint()
	{
		return $this->_route[self::_END_POINT_];
	}

	/** Return args
	 *
	 * @created  2019-02-23
	 * @return   array
	 */
	function Args()
	{
		return $this->_route[self::_ARGS_];
	}

	/** Return route table.
	 *
	 * @created  2022-10-05
	 * @return   array
	 */
	function Table()
	{
		return $this->_route;
	}

	/** Calculate route table.
	 *
	 * @created    2024-06-05
	 * @param      string     $request_uri
	 * @return     array      $route_table
	 */
	static function Calculate(string $request_uri='') : array
	{
		return include(__DIR__.'/include/CalcRoute2018.php');
	}
}
