<?php

class Docs_Tutorial_Validation_Validation5_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'validateTest',
                'message' => 'abcを入力してください。',
            ),

            array(
                'rule' => 'validateTestOpt',
                'message' => 'abcで始まる文字列ではありません。',
                'option' => 'abc'
            )
        )
    );

    public function validateTest($target)
    {
        return ($target === 'abc');
    }

    // オプションを設定するタイプのユーザーバリデーション
    public function validateTestOpt($target, $option)
    {
        $ret = true;

        $regx = sprintf('/^%s/', $option);

        if ($target !== '' && !preg_match($regx, $target)) {
            $ret = false;
        }

        return $ret;
    }
}

