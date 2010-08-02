<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (
            isset($this->post->name) &&
            isset($this->post->addr)
        ) {

            // テンプレート作成
            $this->view->smarty->assign('name', $this->post->name);
            $body = $this->view->smarty->fetch(
                dirname(__FILE__) . DS . 'mail.tpl'
            );

            // メール送信
            $this->Mail->send(
                array(
                    'to' => $this->post->name . ' <'. $this->post->addr . '>',
                    'from' => 'info@xframeworkpx.com',
                    'subject' => 'テストメール',
                    'body' => $body,
                )
            );

        }
    }
}
