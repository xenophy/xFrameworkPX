<?php

class Docs_Tutorial_Association_hasOne7_uriage extends xFrameworkPX_Model
{
    public $hasOne = 'tbl_customer';

    public function test()
    {
        return $this->get('all',array(
            'fields' => array(
                'tbl_uriage.id as id',
                'tbl_uriage.date as date',
                'tbl_uriage.customer_id as customer_id',
                'tbl_customer.id as customer_id_org',
                'tbl_customer.name as customer_name',
            ),
            'conditions' => array(
                array(
                    'tbl_customer.id' => '>= 103',
                    array(
                        array(
                            'tbl_customer.name' => '福岡商事'
                        ),
                        'OR',
                        array(
                            'tbl_customer.name' => '姫路商事'
                        )
                    )
                ),
                'OR',
                array(
                    'tbl_customer.name' => '二島商店'
                )
            ),
            'order' => array(
                'tbl_customer.id'
            )
        ));
    }
}

