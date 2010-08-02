<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Database_Db5_sample');

    public function execute()
    {
        if (isset($this->post['delId']) && $this->post['delId'] !== '') {
            $this->Docs_Tutorial_Database_Db5_sample->deleteData($this->post['delId']);
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Database_Db5_sample->getDataAll()
        );
    }
}
