<?php

class Docs_Tutorial_Validation_Validation4_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'Hankaku',
                'message' => '半角で入力してください。',
            ),
        ),

        'data2' => array(
            array(
                'rule' => 'HankakuKana',
                'message' => '半角カナで入力してください。',
            ),
        ),

        'data3' => array(
            array(
                'rule' => 'Zenkaku',
                'message' => '全角で入力してください。',
            ),
        ),

        'data4' => array(
            array(
                'rule' => 'ZenkakuHira',
                'message' => '全角ひらがなを入力してください。',
            ),
        ),

        'data5' => array(
            array(
                'rule' => 'ZenkakuKana',
                'message' => '全角カナを入力してください。',
            ),
        ),

        'data6' => array(
            array(
                'rule' => 'ZenkakuNum',
                'message' => '全角数値を入力してください。',
            ),
        ),
    );

}

