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
class Comp extends \Mnix\ActiveRecordSub
{
    protected $_table = 'comp';
    protected $_belongsTo = array(
        'person' => array(
            'class' => 'Mnix\ActiveRecordSub\Person',
            'field' => 'person_id'
    ));
}