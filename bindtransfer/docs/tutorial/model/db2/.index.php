<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db2_sample');

    public function execute()
    {
        $this->set(
            'count',
            $this->Docs_Tutorial_Model_Db2_sample->getCount()
        );

    }
}
