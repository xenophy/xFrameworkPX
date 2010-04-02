<?php

class Docs_Tutorial_Model_Validation2_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'BgColor',
                'message' => '#xxxxxx形式で入力してください。',
            ),
        ),

        'data2' => array(
            array(
                'rule' => 'Date',
                'message' => '日付を入力してください。',
            ),
        ),

        'data3' => array(
            array(
                'rule' => 'Email',
                'message' => 'メールアドレスが不正です。',
            ),
        ),
    );

}

