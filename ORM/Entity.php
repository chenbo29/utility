<?php
/**
 * PHP Version 5.4
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 3.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Cake\ORM;

use Cake\Datasource\EntityTrait;

/**
 * An entity represents a single result row from a repository. It exposes the
 * methods for retrieving and storing properties associated in this row.
 */
class Entity implements \ArrayAccess, \JsonSerializable {

	use EntityTrait;

/**
 * Initializes the internal properties of this entity out of the
 * keys in an array
 *
 * ### Example:
 *
 * ``$entity = new Entity(['id' => 1, 'name' => 'Andrew'])``
 *
 * @param array $properties hash of properties to set in this entity
 * @param array $options list of options to use when creating this entity
 * the following list of options can be used:
 *
 * - useSetters: whether use internal setters for properties or not
 * - markClean: whether to mark all properties as clean after setting them
 * - markNew: whether this instance has not yet been persisted
 * - guard: whether to prevent inaccessible properties from being set (default: false)
 */
	public function __construct(array $properties = [], array $options = []) {
		$options += [
			'useSetters' => true,
			'markClean' => false,
			'markNew' => null,
			'guard' => false
		];
		$this->_className = get_class($this);
		$this->set($properties, [
			'setter' => $options['useSetters'],
			'guard' => $options['guard']
		]);

		if ($options['markClean']) {
			$this->clean();
		}

		if ($options['markNew'] !== null) {
			$this->isNew($options['markNew']);
		}
	}

}
