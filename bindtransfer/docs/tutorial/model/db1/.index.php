<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db1_sample');

    public function execute()
    {
        $this->set(
            'rowdata',
            $this->Docs_Tutorial_Model_Db1_sample->getData()
        );

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db1_sample->getDataAll()
        );

    }
}
