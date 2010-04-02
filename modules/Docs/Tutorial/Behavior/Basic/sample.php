<?php

class Docs_Tutorial_Behavior_Basic_sample extends xFrameworkPX_Model
{
    public $behaviors = array('Docs_Tutorial_Behavior_Basic_behavior');

    public function getData()
    {
        return $this->getTestData();
    }
}

