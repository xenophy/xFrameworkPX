<?php

class Docs_Tutorial_Rapid_Valid_user extends xFrameworkPX_Model_RapidDrive
{
    public $validators = array(
        'name' => array(
            array(
                'rule'    => 'NotEmpty',
                'message' => '氏名を入力してください。'
            ),
        ),
        'age' => array(
            array(
                'rule'    => 'NotEmpty',
                'message' => '年齢を入力してください。'
            ),
            array(
                'rule'    => 'validateInt',
                'message' => '年齢は半角数字で入力してください。'
            ),
        ),
        'sex' => array(
            array(
                'rule'    => 'NotEmpty',
                'message' => '性別を選択してください。'
            ),
        )
    );

    public function validateInt($target)
    {

        if (!preg_match('/^[0-9]+$/', $target)) {
            return false;
        }

        if (strlen($target) > 1 && preg_match('/^0/', $target)) {
            return false;
        }

        return true;

    }

}
