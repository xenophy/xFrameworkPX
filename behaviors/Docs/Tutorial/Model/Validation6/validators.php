<?php

class Docs_Tutorial_Model_Validation6_validators extends xFrameworkPX_Model_Behavior
{
    public function bindValidateTest($target)
    {
        return ($target === 'efg');
    }

}

