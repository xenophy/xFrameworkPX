<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (
            isset($this->post->name) &&
            isset($this->post->value)
        ) {

            $this->Session->write(
                $this->post->name,
                $this->post->value
            );

        } else if(isset($this->post->exec)) {

            $this->Session->clear();

        }
    }
}
