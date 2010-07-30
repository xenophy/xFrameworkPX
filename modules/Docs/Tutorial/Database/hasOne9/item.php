<?php

class Docs_Tutorial_Database_hasOne9_item extends xFrameworkPX_Model
{
    public $hasOne = array(
        'Docs_Tutorial_Database_hasOne9_uriage' => array(
            'foreignKey' => 'id'
        ),
    );

    public function test()
    {
        return $this->get('all',array(
            'fields' => array(
                'tbl_uriage.date as date',
                'tbl_item.name as name',
                'tbl_item.price as price',
                'tbl_meisai.count as count',
            ),
            'order' => array(
                'tbl_item.price DESC',
                'tbl_meisai.count DESC'
            )
        ));
    }

}

