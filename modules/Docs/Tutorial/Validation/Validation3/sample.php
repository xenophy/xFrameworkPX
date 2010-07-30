<?php

class Docs_Tutorial_Validation_Validation3_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'Number',
                'message' => '数値を入力してください。',
            ),
        ),

        'data2' => array(
            array(
                'rule' => 'Phone',
                'message' => '電話番号で入力してください。',
            ),
        ),

        'data3' => array(
            array(
                'rule' => 'TextLength',
                'message' => '5文字以内で入力してください。',
                'option' => array(
                    'maxlength' => 5
                )
            ),
        ),

        'data4' => array(
            array(
                'rule' => 'Url',
                'message' => 'URLを入力してください。',
            ),
        ),
    );

}

