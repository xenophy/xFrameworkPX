<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Rapid_user'
    );

    public $rapid = array(
        'mode' => 'list',
        'count' => 10,
        'module' => 'Docs_Tutorial_Rapid_user',
        'search_field' => array(
            'name' => array(
                'field_type' => 'text',
                'options' => array(
                    'id' => 'name',
                    'prelabel' => '氏名'
                ),
                'cond' => 'LIKE',
                'target' => array('name')
            ),
            'age' => array(
                'field_type' => 'text',
                'options' => array(
                    'id' => 'age',
                    'prelabel' => ' 年齢',
                    'size' => '4'
                ),
                'target' => array('age')
            ),
            'sex' => array(
                'field_type' => 'select',
                'options' => array(
                    'id' => 'sex',
                    'prelabel' => '性別',
                    'options' => array(
                        array(
                            'value' => '',
                            'intext' => '---',
                        ),
                        array(
                            'value' =>  '1',
                            'intext' => '男性'
                        ),
                        array(
                            'value' => '2',
                            'intext' => '女性'
                        ),
                    ),
                ),
                'target' => array('sex')
            ),
            'btn_submit' => array(
                'field_type' => 'submit',
                'options' => array(
                    'value' => '検索'
                )
            )
        ),
        'search' => array(
            'del' => '0'
        ),
        'order_field' => array('id'),
        'field_filter' => array(
            'tbl_user' => array('del')
        ),
        'init_search' => true,
        'no_item_message' => '検索結果なし'
    );
}
