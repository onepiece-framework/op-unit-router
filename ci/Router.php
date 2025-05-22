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

/* @var $ci \OP\UNIT\CI\CI_Config */
$ci = OP::Unit('CI')::Config();

//	EndPoint
$end_point = OP::MetaPath('app:/').'index.php';
$args   = null;
//	In case of git:/index.php is not exists.
$result = file_exists($end_point) ? $end_point: '';
$ci->Set('EndPoint', $result, $args);

//	Args
$args   = null;
$result = [];
$ci->Set('Args', $result, $args);

//	Table
$args   =  null;
$result = [
	'args'      => [],
	'end-point' => file_exists($end_point) ? $end_point: null,
];
$ci->Set('Table', $result, $args);

//	...
$method = 'Calculate';
$args   = '/foo/bar/?foo=bar';
$result = [
	'args'      => [
		'foo',
		'bar',
	],
	'end-point' => file_exists($end_point) ? $end_point: null,
];
$ci->Set($method, $result, $args);

/*
//	...
$app_root = RootPath('app');

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

//	Good case.
$method = 'Calculate';
$args   = '/img/';
$result = [
	'args'      => [],
	'end-point' => $app_root . 'img/index.php',
];
$ci->Set($method, $result, $args);

//	Exists png file.
$method = 'Calculate';
$args   = '/img/404.png';
$result = [
	'args'      => [
	//	basename($args),
	],
	'end-point' => $app_root . 'img/404.png',
];
$ci->Set($method, $result, $args);

//	Exists ico file.
$method = 'Calculate';
$args   = '/img/favicon.png';
$result = [
	'args'      => [
		basename($args),
	],
	'end-point' => $app_root . 'img/index.php',
];
$ci->Set($method, $result, $args);

//	Not exists png file.
$method = 'Calculate';
$args   = '/img/_not_found_.png';
$result = [
	'args'      => [
		basename($args),
	],
	'end-point' => $app_root . 'img/index.php',
];
$ci->Set($method, $result, $args);
*/

//	Template
$path   = '../';
$args   = [$path];
$result = 'Exception: Deny upper directory specification.';
$ci->Set('Template', $result, $args);

//	...
return $ci->Get();
