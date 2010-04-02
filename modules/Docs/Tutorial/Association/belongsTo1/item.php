<?php

class Docs_Tutorial_Association_belongsTo1_item extends xFrameworkPX_Model
{
    public $belongsTo = 'tbl_meisai';

    public function test()
    {
        return $this->get('all',array(
            'fields' => array(
                'tbl_item.name as name',
                'tbl_item.price as price',
                'tbl_meisai.count as count',
                'tbl_meisai.id as id',
                'tbl_meisai.seq as seq',
            ),
            'order' => array(
                'tbl_meisai.id',
                'tbl_meisai.seq',
                'tbl_item.price DESC'
            )
        ));
    }

}

