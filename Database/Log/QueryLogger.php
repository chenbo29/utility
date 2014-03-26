<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Cake\Database\Log;

use Cake\Database\Type;
use Cake\Log\Log;
use Cake\Utility\String;

/**
 * This class is a bridge used to write LoggedQuery objects into a real log.
 * by default this class use the built-in CakePHP Log class to accomplish this
 *
 */
class QueryLogger {

/**
 * Writes a LoggedQuery into a log
 *
 * @param LoggedQuery $query to be written in log
 * @return void
 */
	public function log(LoggedQuery $query) {
		if (!empty($query->params)) {
			$query->query = $this->_interpolate($query);
		}
		$this->_log($query);
	}

/**
 * Wrapper function for the logger object, useful for unit testing
 * or for overriding in subclasses.
 *
 * @param LoggedQuery $query to be written in log
 * @return void
 */
	protected function _log($query) {
		Log::write('debug', $query, ['queriesLog']);
	}

/**
 * Helper function used to replace query placeholders by the real
 * params used to execute the query
 *
 * @param LoggedQuery $query
 * @return string
 */
	protected function _interpolate($query) {
		$params = array_map(function($p) {
			if (is_null($p)) {
				return 'NULL';
			}
			return is_string($p) ? "'$p'" : $p;
		}, $query->params);

		return String::insert($query->query, $params);
	}

}
