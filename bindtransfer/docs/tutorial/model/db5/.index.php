<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db5_sample');

    public function execute()
    {
        if (isset($this->post->submit)) {
            $this->Docs_Tutorial_Model_Db5_sample->deleteData();
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db5_sample->getDataAll()
        );
    }
}
