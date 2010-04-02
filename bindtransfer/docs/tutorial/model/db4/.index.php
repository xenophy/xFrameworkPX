<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db4_sample');

    public function execute()
    {
        if(
            isset($this->post->title) &&
            is_string($this->post->title) &&
            isset($this->post->note) &&
            is_string($this->post->note)
        ) {
            $this->Docs_Tutorial_Model_Db4_sample->updateData(
                $this->post->title,
                $this->post->note
            );
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db4_sample->getDataAll()
        );
    }
}
