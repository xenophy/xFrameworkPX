<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Database_Ormapping3_sample');

    public function execute()
    {
        if (isset($this->post->id)) {
            $this->Docs_Tutorial_Database_Ormapping3_sample->removeData($this->post->id);
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Database_Ormapping3_sample->getDataAll()
        );
    }
}
