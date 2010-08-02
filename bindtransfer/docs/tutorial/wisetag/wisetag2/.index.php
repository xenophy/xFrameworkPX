<?php

class index extends xFrameworkPX_Controller_Action
{
    public function execute()
    {
        if (is_null($this->Session->read('WiseTagConfig'))) {
            $this->Tag->add($this->_getInitConfig());
        } else {

            if (isset($this->post['txt'])) {
                $this->Tag->edit(
                    array('value' => $this->post['txt']),
                    array(
                        'type' => 'text',
                        'name' => 'txt'
                    )
                );
            } else {
                $this->Tag->edit(
                    array('value' => ''),
                    array(
                        'type' => 'text',
                        'name' => 'txt'
                    )
                );
            }

            if (isset($this->post['pass'])) {
                $this->Tag->edit(
                    array('value' => $this->post['pass']),
                    array(
                        'type' => 'password',
                        'name' => 'pass'
                    )
                );
            } else {
                $this->Tag->edit(
                    array('value' => ''),
                    array(
                        'type' => 'password',
                        'name' => 'pass'
                    )
                );
            }

            if (isset($this->post['radio'])) {
                $this->Tag->edit(
                    array('checked' => null),
                    array(
                        'type' => 'radio',
                        'name' => 'radio'
                    )
                );

                $this->Tag->edit(
                    array('checked' => 'checked'),
                    array(
                        'type' => 'radio',
                        'name' => 'radio',
                        'value' => $this->post['radio']
                    )
                );
            } else {
                $this->Tag->edit(
                    array('checked' => null),
                    array(
                        'type' => 'radio',
                        'name' => 'radio'
                    )
                );

                $this->Tag->edit(
                    array('checked' => 'checked'),
                    array(
                        'type' => 'radio',
                        'name' => 'radio',
                        'count' => '1'
                    )
                );
            }

            if (isset($this->post['check'])) {
                $this->Tag->edit(
                    array('checked' => null),
                    array(
                        'type' => 'checkbox',
                        'name' => 'check[]'
                    )
                );

                foreach ($this->post['check'] as $item) {
                    $this->Tag->edit(
                        array('checked' => 'check'),
                        array(
                            'type' => 'checkbox',
                            'name' => 'check[]',
                            'value' => $item
                        )
                    );
                }
            } else {
                $this->Tag->edit(
                    array('checked' => null),
                    array(
                        'type' => 'checkbox',
                        'name' => 'check[]'
                    )
                );
            }

            if (isset($this->post['sel'])) {
                $initOpt = array(
                    array(
                        'value' => '',
                        'intext' => '選択してください'
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
                );

                foreach ($initOpt as $key => $conf) {

                    if ($conf['value'] == $this->post['sel']) {
                        $conf['selected'] = 'selected';
                        $initOpt[$key] = $conf;
                    }

                }

                $this->Tag->edit(
                    array('options' => $initOpt),
                    array('type' => 'select', 'name' => 'sel')
                );
            } else {
                $this->Tag->edit(
                    array('options' => array(
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
                    )),
                    array('type' => 'select', 'name' => 'sel')
                );
            }

            if (isset($this->post['txtarea'])) {
                $this->Tag->edit(
                    array('intext' => $this->post['txtarea']),
                    array('type' => 'textarea', 'name' => 'txtarea')
                );
            } else {
                $this->Tag->edit(
                    array('intext' => ''),
                    array('type' => 'textarea', 'name' => 'txtarea')
                );
            }

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
                'type' => 'submit',
                'name' => 'save',
                'value' => '保存'
            )
        );
    }
}