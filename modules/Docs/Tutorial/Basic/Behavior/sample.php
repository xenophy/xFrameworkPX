<?php

class Docs_Tutorial_Basic_Behavior_sample extends xFrameworkPX_Model
{
    public $behaviors = array('Docs_Tutorial_Basic_Behavior_Behavior');

    public function getData()
    {
        return $this->getTestData();
    }
}

