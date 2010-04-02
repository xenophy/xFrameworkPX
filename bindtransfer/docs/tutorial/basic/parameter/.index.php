<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        // GET値の取得
        $get1 = 'なし';
        if (isset($this->get->get1)) {
            $get1 = $this->get->get1;
        }

        $this->set('get1', $get1);


        // POST値の取得
        $post1 = 'なし';
        if (isset($this->post->post1)) {
            $post1 = $this->post->post1;
        }

        $this->set('post1', $post1);


        if (isset($this->get->setcookie)) {

            // Cookie値設定
            setcookie('cookie1', $this->get->setcookie);

        } else if(isset($this->get->cookieclear)) {

            // Cookie値設定
            setcookie('cookie1', '', time()-1);

        }

        $cookie1 = 'なし';
        if (isset($this->cookie->cookie1)) {
            $cookie1 = $this->cookie->cookie1;
        }

        $this->set('cookie1', $cookie1);


        if (isset($this->get->setsession)) {
            $this->Session->write('session1', $this->get->setsession);
        } else if(isset($this->get->sessionclear)) {
            $this->Session->destroy();
            // または
            // $this->Session->remove('session1');
        }

        $session1 = 'なし';
        if ($this->Session->read('session1')) {
            $session1 = $this->Session->read('session1');
        }

        $this->set('session1', $session1);


    }
}
