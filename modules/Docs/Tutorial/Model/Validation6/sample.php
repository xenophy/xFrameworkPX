<?php

class Docs_Tutorial_Model_Validation6_sample extends xFrameworkPX_Model
{
    public $behaviors = array('Docs_Tutorial_Model_Validation6_validators');

    public $validators = array(
        'data' => array(
            array(
                'rule' => 'validateTest',
                'message' => 'efgを入力してください。',
            ),
        ),
    );

}

