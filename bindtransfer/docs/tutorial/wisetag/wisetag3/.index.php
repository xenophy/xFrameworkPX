<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (isset($this->post['delField'])) {

            switch ($this->post['delField']) {

                case 'txt':
                    $this->Tag->remove(array(
                        'type' => 'text',
                        'name' => $this->post['delField']
                    ));
                    break;

                case 'pass':
                    $this->Tag->remove(array(
                        'type' => 'password',
                        'name' => $this->post['delField']
                    ));
                    break;

                case 'radio':
                    $this->Tag->remove(array(
                        'type' => 'radio',
                        'name' => $this->post['delField']
                    ));
                    break;

                case 'check':
                    $this->Tag->remove(array(
                        'type' => 'checkbox',
                        'name' => $this->post['delField'] . '[]'
                    ));
                    break;

                case 'sel':
                    $this->Tag->remove(array(
                        'type' => 'select',
                        'name' => $this->post['delField']
                    ));
                    break;

                case 'txtarea':
                    $this->Tag->remove(array(
                        'type' => 'textarea',
                        'name' => $this->post['delField']
                    ));
                    break;
            }

        } else {

            // 設定消去
            $this->Tag->clear();

            // 設定追加
            $this->Tag->add($this->_getInitConfig());
        }

        //タグ生成
        $this->Tag->gen();
    }

    /**
     * 初期設定取得メソッド
     */
    private function _getInitConfig()
    {
        return array(
            array(
                'type' => 'form',
                'action' => './',
                'method' => 'post'
            ),
            array(
                'type' => 'text',
                'name' => 'txt',
                'id' => 'txt',
                'value' => '',
                'size' => '20',
                'maxsize' => '10',
                'prelabel' => 'テキストボックス'
            ),
            array(
                'type' => 'password',
                'name' => 'pass',
                'id' => 'pass',
                'value' => '',
                'size' => '20',
                'maxsize' => '15',
                'prelabel' => 'パスワード'
            ),
            array(
                'type' => 'radio',
                'name' => 'radio',
                'id' => 'radio1',
                'value' => 'a',
                'label' => 'A',
                'checked' => 'checked'
            ),
            array(
                'type' => 'radio',
                'name' => 'radio',
                'id' => 'radio2',
                'value' => 'b',
                'label' => 'B'
            ),
            array(
                'type' => 'checkbox',
                'name' => 'check[]',
                'id' => 'check1',
                'value' => '1',
                'label' => '1'
            ),
            array(
                'type' => 'checkbox',
                'name' => 'check[]',
                'id' => 'check2',
                'value' => '2',
                'label' => '2'
            ),
            array(
                'type' => 'checkbox',
                'name' => 'check[]',
                'id' => 'check3',
                'value' => '3',
                'label' => '3'
            ),
            array(
                'type' => 'select',
                'name' => 'sel',
                'id' => 'sel',
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
            ),
            array(
                'type' => 'textarea',
                'name' => 'txtarea',
                'id' => 'txtarea',
                'cols' => '50',
                'rows' => '5',
                'intext' => '',
                'prelabel' => 'テキストエリア',
                'style' => 'vertical-align: top;'
            ),
            array(
                'type' => 'select',
                'name' => 'delField',
                'options' => array(
                    array(
                        'value' => '',
                        'intext' => '選択してください',
                        'selected' => 'selected'
                    ),
                    array(
                        'value' => 'txt',
                        'intext' => 'テキストボックス'
                    ),
                    array(
                        'value' => 'pass',
                        'intext' => 'パスワード'
                    ),
                    array(
                        'value' => 'radio',
                        'intext' => 'ラジオボタン'
                    ),
                    array(
                        'value' => 'check',
                        'intext' => 'チェックボックス'
                    ),
                    array(
                        'value' => 'sel',
                        'intext' => 'セレクトボックス'
                    ),
                    array(
                        'value' => 'txtarea',
                        'intext' => 'テキストエリア'
                    )
                )
            ),
            array(
                'type' => 'submit',
                'name' => 'del',
                'value' => '削除'
            )
        );
    }
}