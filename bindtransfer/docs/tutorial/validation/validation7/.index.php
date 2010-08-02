<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Validation_Validation7_sample');

    public function execute()
    {
        if (isset($this->post->type)) {

            $validError = $this->Docs_Tutorial_Validation_Validation7_sample->validation($this->post);

            if (isset($validError->data1)) {
                $this->set('errData1', $validError->data1->messages);
            }

            if (isset($validError->data2)) {
                $this->set('errData2', $validError->data2->messages);
            }
        }

    }
}
