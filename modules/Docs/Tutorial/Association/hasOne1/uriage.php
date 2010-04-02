<?php

class Docs_Tutorial_Association_hasOne1_uriage extends xFrameworkPX_Model
{
    public $hasOne = 'Docs_Tutorial_Association_hasOne1_customer';

    public function test()
    {
        return $this->get('all',array(
            'fields' => array(
                'tbl_uriage.id as id',
                'tbl_uriage.date as date',
                'tbl_uriage.customer_id as customer_id',
                'tbl_customer.id as customer_id_org',
                'tbl_customer.name as customer_name',
            )
        ));
    }

}

