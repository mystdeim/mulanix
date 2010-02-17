<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecordSub;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Car extends \Mnix\ActiveRecordSub
{
    protected $_table = 'car';
    protected $_has_one = array(
        'person' => array(
            'class' => 'Mnix\ActiveRecordSub\Person',
            'fk'    => 'car_id'
    ));
}