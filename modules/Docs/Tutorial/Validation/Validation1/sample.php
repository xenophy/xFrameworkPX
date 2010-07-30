<?php

class Docs_Tutorial_Validation_Validation1_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'NotEmpty',
                'message' => 'タイトルを入力してください。',
            ),
        ),

        'data2' => array(
            array(
                'rule' => 'Alpha',
                'message' => '半角英字で入力してください。',
            ),
        ),

        'data3' => array(
            array(
                'rule' => 'AlphaNumeric',
                'message' => '半角英数で入力してください。',
            ),
        ),

        'data4' => array(
            array(
                'rule' => 'NotEmpty',
                'message' => 'タイトルを入力してください。',
            ),
            array(
                'rule' => 'AlphaNumeric',
                'message' => '半角英数で入力してください。',
            ),
        ),
    );

}

