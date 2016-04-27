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
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\ORM\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Association;
use Cake\Validation\Validation;
use RuntimeException;

/**
 * Validates the count of associated records.
 */
class ValidCount
{

    /**
     * The list of fields to check
     *
     * @var array
     */
    protected $_field;

    /**
     * Constructor.
     *
     * @param string|array $fields The field to check the count on.
     */
    public function __construct($field)
    {
        $this->_field = $field;
    }

    /**
     * Performs the count check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity from where to extract the fields
     * @param array $options Options passed to the check.
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        $value = $entity->{$this->_field};
        if (!is_array($value)) {
            return false;
        }

        if (isset($value['_ids'])) {
            if (!is_array($value['_ids'])) {
                return false;
            }
            $count = count($value['_ids']);
        } else {
            $count = count($value);
        }

        return Validation::comparison($count, $options['operator'], $options['count']);
    }
}
