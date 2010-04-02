<?php

class Docs_Tutorial_Association_hasOne4_uriage extends xFrameworkPX_Model
{
    public $hasOne = array(
        'tbl_customer',
        'tbl_meisai' => array(
            'foreignKey' => 'id'
        )
    );

    public function test()
    {
        return $this->get('all',array(
            'fields' => array(
                'tbl_uriage.id as id',
                'tbl_uriage.date as date',
                'tbl_uriage.customer_id as customer_id',
                'tbl_customer.id as customer_id_org',
                'tbl_customer.name as customer_name',
                'tbl_meisai.id as meisai_id_org',
                'tbl_meisai.seq as meisai_seq',
                'tbl_meisai.item_id as item_id',
                'tbl_meisai.count as count',
            )
        ));
    }
}

