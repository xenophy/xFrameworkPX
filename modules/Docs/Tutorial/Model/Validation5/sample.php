<?php

class Docs_Tutorial_Model_Validation5_sample extends xFrameworkPX_Model
{
    public $validators = array(
        'data' => array(
            array(
                'rule' => 'validateTest',
                'message' => 'abcを入力してください。',
            ),
        ),
    );

    public function validateTest($target)
    {
        return ($target === 'abc');
    }

}

