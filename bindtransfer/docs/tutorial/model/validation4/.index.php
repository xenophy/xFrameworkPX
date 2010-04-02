<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Model_Validation4_sample');

    public function execute()
    {
        if (isset($this->post->type)) {

            $validError = $this->Docs_Tutorial_Model_Validation4_sample->isValid($this->post);

            if (isset($validError->data)) {
                $this->set('errData', $validError->data->messages);
            }

            if (isset($validError->data2)) {
                $this->set('errData2', $validError->data2->messages);
            }

            if (isset($validError->data3)) {
                $this->set('errData3', $validError->data3->messages);
            }

            if (isset($validError->data4)) {
                $this->set('errData4', $validError->data4->messages);
            }

            if (isset($validError->data5)) {
                $this->set('errData5', $validError->data5->messages);
            }

            if (isset($validError->data6)) {
                $this->set('errData6', $validError->data6->messages);
            }

        }

    }
}
