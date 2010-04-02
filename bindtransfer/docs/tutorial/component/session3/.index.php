<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (isset($this->post->exec)) {
            $this->Session->remove('test');
        }
    }
}
