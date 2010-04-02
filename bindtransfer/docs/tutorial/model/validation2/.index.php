<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Validation2_sample');

    public function execute()
    {
        if (isset($this->post->type)) {

            $validError = $this->Docs_Tutorial_Model_Validation2_sample->isValid($this->post);

            if (isset($validError->data)) {
                $this->set('errData', $validError->data->messages);
            }

            if (isset($validError->data2)) {
                $this->set('errData2', $validError->data2->messages);
            }

            if (isset($validError->data3)) {
                $this->set('errData3', $validError->data3->messages);
            }

        }

    }
}
