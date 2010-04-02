<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db7_sample');

    public function execute()
    {
        $this->set(
            'schema',
            $this->Docs_Tutorial_Model_Db7_sample->getSchema()
        );
    }
}
