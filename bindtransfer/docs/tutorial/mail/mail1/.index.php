<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (isset($this->post->addr)) {

            $this->Mail->send(
                array(
                    'to' => $this->post->addr,
                    'from' => 'info@xframeworkpx.com',
                    'subject' => 'テストメール',
                    'body' => 'テストメールです。',
                )
            );

        }
    }
}
