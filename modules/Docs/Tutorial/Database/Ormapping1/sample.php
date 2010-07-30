<?php

class Docs_Tutorial_Database_Ormapping1_sample extends xFrameworkPX_Model
{
    public function getDataAll()
    {
        return $this->get('all');
    }

    public function getDataAll2()
    {
        // Order By句の指定
        return $this->get(
            'all',
            array(
                'order' => array(
                    'id DESC'
                )
            )
        );
    }

    public function getDataAll3()
    {
        // ページ単位での取得
        return $this->get(
            'all',
            array(
                'limit' => 2,
                'page' => 1
            )
        );
    }

    public function getDataAll4()
    {
        // 条件指定
        return $this->get(
            'all',
            array(
                'conditions' => array(
                    array(
                        'tbl_sample.id' => '>= 4',
                    ),
                    array(
                        'tbl_sample.id' => '<= 20',
                    )
                )
            )
        );
    }

}

