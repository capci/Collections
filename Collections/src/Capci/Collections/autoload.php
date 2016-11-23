<?php
/**
 * @package Capci\Collections
 * @version 0.9
 * @author capci https://github.com/capci
 * @link https://github.com/capci/Collections Capci\Collections
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

declare (strict_types = 1);

namespace Capci\Collections;

\call_user_func(function(string $packageName) {
	$len = \strlen($packageName);
	\spl_autoload_register(function(string $className) use($packageName, $len) {
		if(\strncmp($packageName, $className, $len) === 0) {
			require __DIR__ . '/' . \str_replace('\\', '/', \substr($className, $len)) . '.php';
		}
	});
}, 'Capci\\Collections\\');