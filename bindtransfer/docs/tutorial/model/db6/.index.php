<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db6_sample');

    public function execute()
    {
        $ret = '';
        if (isset($this->post->success)) {
            $ret = $this->Docs_Tutorial_Model_Db6_sample->insertData(true);
        } else if (isset($this->post->failure)) {
            $ret = $this->Docs_Tutorial_Model_Db6_sample->insertData(false);
        }

        $this->set('result', $ret);

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db6_sample->getDataAll()
        );
    }
}
