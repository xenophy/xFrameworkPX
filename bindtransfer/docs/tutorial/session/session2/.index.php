<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {

        if (isset($this->post->test)) {

            // セッションデータの書き込み
            $this->Session->write('test', $this->post->test);
        }

        if (isset($this->post->exec)) {

            // セッションデータの削除
            $this->Session->remove('test');
        }

        $this->set(
            'test',

            // セッションデータの読み込み
            $this->Session->read('test')
        );
    }
}
