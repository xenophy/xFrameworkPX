<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Basic_Model_sample');

    public function execute()
    {
        $this->set('module_data', $this->Docs_Tutorial_Basic_Model_sample->testMethod());
    }
}
