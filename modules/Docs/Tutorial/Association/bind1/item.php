<?php

class Docs_Tutorial_Association_bind1_item extends xFrameworkPX_Model
{
    // プロパティでのアソシエーションは行わない

    public function test()
    {

        // 動的アソシエーション設定
        $this->bind(
            array(
                'hasMany' => array(
                    'tbl_meisai' => array(
                        'order' => array(
                            'tbl_meisai.id'
                        )
                    )
                )
            )
        );

        $ret = $this->get(
            'all',
            array(
                'order' => array(
                    'tbl_item.id'
                )
            )
        );

        // 動的アソシエーション解除
        $this->unbind(
            array(
                'hasMany' => array('tbl_meisai')
            )
        );

        return $ret;
    }

}

