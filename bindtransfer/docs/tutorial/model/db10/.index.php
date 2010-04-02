<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db10_sample');

    public function execute()
    {
        if (isset($this->post->id)) {
            $this->Docs_Tutorial_Model_Db10_sample->removeData($this->post->id);
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db10_sample->getDataAll()
        );
    }
}
