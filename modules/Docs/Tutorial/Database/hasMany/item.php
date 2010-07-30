<?php

class Docs_Tutorial_Database_hasMany_item extends xFrameworkPX_Model
{
    public $hasMany = array(
        'tbl_meisai' => array(
            'order' => array(
                'tbl_meisai.id'
            )
        )
    );

    public function test()
    {
        return $this->get(
            'all',
            array(
                'order' => array(
                    'tbl_item.id'
                )
            )
        );
    }

}

