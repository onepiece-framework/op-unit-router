<?php
/** op-unit-router:/ci/Router.php
 *
 * @created    2023-02-11
 * @version    1.0
 * @package    op-unit-router
 * @author     Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/** Declare strict
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP;

//	...
$ci = OP::Unit('CI')::Config();

//	EndPoint
$end_point = OP::MetaRoot('app').'index.php';
$args   = true;
$result = $end_point;
$ci->Set('EndPoint', $result, $args);

//	Args
$args   = null;
$result = [];
$ci->Set('Args', $result, $args);

//	Table
$args   =  null;
$result = [
	'args'      => [],
	'end-point' => $end_point,
];
$ci->Set('Table', $result, $args);

//	...
$app_root = RootPath('app');

//	...
$method = 'Calculate';
$args   = '/foo/bar/?foo=bar';
$result = [
	'args'      => [
		'foo',
		'bar',
	],
	'end-point' => $app_root . 'index.php',
];
$ci->Set($method, $result, $args);

//	...
$method = 'Calculate';
$args   = '/js/index.js';
$result = [
	'args'      => [
		basename($args),
	],
	'end-point' => $app_root . 'js/index.php',
];
$ci->Set($method, $result, $args);

//	...
$method = 'Calculate';
$args   = '/css/index.css';
$result = [
	'args'      => [
		basename($args),
	],
	'end-point' => $app_root . 'css/index.php',
];
$ci->Set($method, $result, $args);

//	...
$method = 'Calculate';
$args   = '/img/';
$result = [
	'args'      => [],
	'end-point' => $app_root . 'img/index.php',
];
$ci->Set($method, $result, $args);

//	...
$method = 'Calculate';
$args   = '/img/404.png';
$result = [
	'args'      => [
		basename($args),
	],
	'end-point' => $app_root . 'img/index.php',
];
$ci->Set($method, $result, $args);

//	Template
$path   = '../';
$args   = [$path];
$result = 'Exception: Deny upper directory specification.';
$ci->Set('Template', $result, $args);

//	...
return $ci->Get();
