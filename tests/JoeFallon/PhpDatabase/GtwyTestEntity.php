<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\PhpDatabase\AbstractEntity;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
class GtwyTestEntity extends AbstractEntity
{
    public $_name;
    public $_nullable_val;


    public function isValid()
    {
        if(strlen($this->_name) == 0)
        {
            return false;
        }

        return true;
    }
}
