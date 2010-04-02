<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Behavior_Basic_sample');

    public function execute()
    {
        $this->set(
            'data',
            $this->Docs_Tutorial_Behavior_Basic_sample->getData()
        );

    }
}
