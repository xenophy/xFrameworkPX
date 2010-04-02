<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (isset($this->post->test)) {
            $this->Session->write('test', $this->post->test);
        }
    }
}
