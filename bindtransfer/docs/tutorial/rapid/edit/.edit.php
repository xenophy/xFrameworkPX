<?php

class edit extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Rapid_Valid_user'
    );

    public $rapid = array(
        'mode' => 'edit',
        'module' => 'Docs_Tutorial_Rapid_Valid_user',
        'field_filter' => array('id', 'del'),
        'prevAction' => 'index',
        'nextAction' => 'verify',
        'input_field' => array(

            'name' => array(
                'field_type' => 'text',
                'options' => array(
                    'id' => 'name',
                    'maxlength' => '50',
                )
            ),
            'age' => array(
                'field_type' => 'text',
                'options' => array(
                    'id' => 'age',
                    'maxlength' => '3',
                    'size' => '4',
                ),
            ),
            'sex' => array(
                'field_type' => 'radio',
                'options' => array(
                    array(
                        'id' => 'male',
                        'value' => '1',
                        'label' => '男性',
                    ),
                    array(
                        'id' => 'female',
                        'value' => '2',
                        'label' => '女性',
                    ),
                ),
            ),

            'btn_submit' => array(
                'field_type' => 'submit',
                'options' => array(
                    'value' => '確認',
                    'name' => 'btnSubmit',
                )
            )
        )
    );
}
