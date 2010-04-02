<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db3_sample');

    public function execute()
    {

        if(
            isset($this->post->id) &&
            is_numeric($this->post->id) &&
            isset($this->post->title) &&
            is_string($this->post->title) &&
            isset($this->post->note) &&
            is_string($this->post->note)
        ) {

            $this->Docs_Tutorial_Model_Db3_sample->insertData(
                $this->post->id,
                $this->post->title,
                $this->post->note
            );

        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db3_sample->getDataAll()
        );

    }
}
