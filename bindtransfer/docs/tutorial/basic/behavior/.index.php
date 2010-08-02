<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Basic_Behavior_sample');

    public function execute()
    {
        $this->set(
            'data',
            $this->Docs_Tutorial_Basic_Behavior_sample->getData()
        );

    }
}
