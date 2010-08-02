<?php

class cookie extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (isset($this->get->set)) {

            // Cookie値設定
            setcookie('cookie1', $this->get->set);

        } else if(isset($this->get->clear)) {

            // Cookie値設定
            setcookie('cookie1', '', time()-1);

        }

        $this->redirect('./');
    }
}
