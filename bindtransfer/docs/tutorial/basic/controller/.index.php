<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        $this->set('test', 'テストメッセージ');
    }
}
