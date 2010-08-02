<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        // WiseTag設定クリア
        $this->Tag->clear();

        if (isset($this->post['gen'])) {
            $config = array();

            $config[] = array(
                'type' => 'form',
                'action' => './',
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            );

            foreach ($this->post['gen'] as $field) {

                switch ($field) {

                    case 'text':
                        $config[] = array(
                            'type' => 'text',
                            'name' => 'txtbox',
                            'id' => 'txtbox',
                            'value' => 'blank',
                            'size' => '20',
                            'maxsize' => '10',
                            'prelabel' => 'テキストボックス'
                        );
                        break;

                    case 'password':
                        $config[] = array(
                            'type' => 'password',
                            'name' => 'pass',
                            'id' => 'pass',
                            'value' => '',
                            'size' => '20',
                            'maxsize' => '15',
                            'prelabel' => 'パスワード'
                        );
                        break;

                    case 'radio':
                        $config[] = array(
                            'type' => 'radio',
                            'name' => 'radio',
                            'id' => 'radio1',
                            'value' => 'a',
                            'label' => 'A',
                            'checked' => 'checked'
                        );
                        $config[] = array(
                            'type' => 'radio',
                            'name' => 'radio',
                            'id' => 'radio2',
                            'value' => 'b',
                            'label' => 'B'
                        );
                        break;

                    case 'check':
                        $config[] = array(
                            'type' => 'checkbox',
                            'name' => 'check',
                            'id' => 'check1',
                            'value' => '1',
                            'label' => '1'
                        );
                        $config[] = array(
                            'type' => 'checkbox',
                            'name' => 'check',
                            'id' => 'check2',
                            'value' => '2',
                            'label' => '2'
                        );
                        $config[] = array(
                            'type' => 'checkbox',
                            'name' => 'check',
                            'id' => 'check3',
                            'value' => '3',
                            'label' => '3'
                        );
                        break;

                    case 'select':
                        $config[] = array(
                            'type' => 'select',
                            'name' => 'select',
                            'id' => 'select',
                            'prelabel' => 'セレクトボックス',
                            'options' => array(
                                array(
                                    'value' => '',
                                    'intext' => '選択してください',
                                    'selected' => 'selected'
                                ),
                                array(
                                    'value' => '1-1',
                                    'intext' => '1-1'
                                ),
                                array(
                                    'value' => '1-2',
                                    'intext' => '1-2'
                                ),
                                array(
                                    'value' => '1-3',
                                    'intext' => '1-3'
                                )
                            )
                        );
                        break;

                    case 'textarea':
                        $config[] = array(
                            'type' => 'textarea',
                            'name' => 'txtarea',
                            'id' => 'txtarea',
                            'cols' => '50',
                            'rows' => '5',
                            'intext' => 'blank',
                            'prelabel' => 'テキストエリア',
                            'style' => 'vertical-align: top;'
                        );
                        break;

                    case 'file':
                        $config[] = array(
                            'type' => 'file',
                            'name' => 'file',
                            'id' => 'file',
                            'size' => '50',
                            'prelabel' => 'ファイル送信'
                        );
                        break;

                    case 'hidden':
                        $config[] = array(
                            'type' => 'hidden',
                            'name' => 'hidden',
                            'value' => 'hidden value',
                            'pretext' => '隠しパラメータ -&gt;'
                        );
                        break;

                    case 'submit':
                        $config[] = array(
                            'type' => 'submit',
                            'name' => 'submit',
                            'value' => '実行'
                        );
                        break;

                    case 'reset':
                        $config[] = array(
                            'type' => 'reset',
                            'name' => 'clear',
                            'value' => 'クリア'
                        );
                        break;

                    case 'button':
                        $config[] = array(
                            'type' => 'button',
                            'name' => 'btn',
                            'value' => '汎用ボタン'
                        );
                        break;

                    case 'image':
                        $config[] = array(
                            'type' => 'image',
                            'name' => 'img',
                            'id' => 'img',
                            'src' => './logo.png',
                            'alt' => 'xFrameworkPX'
                        );
                        break;
                }

            }

            if (count($this->post['gen']) > 0) {

                // 設定追加
                $this->Tag->add($config);

                // フォーム生成
                $this->Tag->gen();
            }

        }
    }
}
