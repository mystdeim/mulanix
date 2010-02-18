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
class Person extends \Mnix\ActiveRecordSub
{
    protected $_table = 'person';
    protected $_hasOne = array(
        'car' => array(
            'class' => 'Mnix\ActiveRecordSub\Car',
            'field' => 'person_id'
    ));
    protected $_hasMany = array(
        'comps' => array(
            'class' => 'Mnix\ActiveRecordSub\Comp',
            'field' => 'person_id'
    ));
}