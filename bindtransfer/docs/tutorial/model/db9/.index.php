<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Db9_sample');

    public function execute()
    {

        if (
            !isset($this->post->insert) &&
            isset($this->post->id) &&
            isset($this->post->title)) {

            $this->Docs_Tutorial_Model_Db9_sample->setData(
                $this->post->id,
                $this->post->title
            );
        } else if (
            isset($this->post->insert) &&
            isset($this->post->title)
        ) {
            $this->Docs_Tutorial_Model_Db9_sample->insertData(
                $this->post->title
            );
        }

        $this->set(
            'alldata',
            $this->Docs_Tutorial_Model_Db9_sample->getDataAll()
        );

    }
}
