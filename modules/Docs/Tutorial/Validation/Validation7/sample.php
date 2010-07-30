<?php

class Docs_Tutorial_Validation_Validation7_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data1' => array(
            array(
                'rule' => 'validateTest',
                'message' => 'abcを入力してください。',
            ),
        ),
        'data2' => array(
            array(
                'rule' => 'validateTestOpt',
                'message' => 'abcで始まる文字列を入力してください。',
                'option' => 'abc'
            )
        )
    );

    public function validateTest($target)
    {
        return ($target === 'abc');
    }

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
