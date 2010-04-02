<?php

class Docs_Tutorial_Association_hasOne9_uriage extends xFrameworkPX_Model
{
    public $usetable = 'tbl_uriage';
    public $primaryKey = 'tbl_meisai.item_id';

    public $hasOne = array(
        'tbl_meisai' => array(
            'type' => 'INNER',
            'foreignKey' => 'id'
        ),
    );
}

