<?php

class Docs_Tutorial_Database_Db6_sample extends xFrameworkPX_Model
{
    public function insertData($flag)
    {
        $ret = '成功！';

        if($flag) {

            try {
                // トランザクション開始
                $this->beginTrans();

                $this->insert(
                    array(
                        'field' => array(
                            'id',
                            'title',
                            'note',
                        ),
                        'value' => array(
                            ':id',
                            ':title',
                            ':note',
                        ),
                        'bind' => array(
                            'id' => 20,
                            'title' => 'タイトル20',
                            'note' => '内容20'
                        )
                    )
                );

                $this->insert(
                    array(
                        'field' => array(
                            'id',
                            'title',
                            'note',
                        ),
                        'value' => array(
                            ':id',
                            ':title',
                            ':note',
                        ),
                        'bind' => array(
                            'id' => 21,
                            'title' => 'タイトル21',
                            'note' => '内容21'
                        )
                    )
                );

                $this->commit();

            } catch (PDOException $e) {

                $this->rollback();
                $ret = 'ロールバックしました。';

            }

        } else {

            try {
                // トランザクション開始
                $this->beginTrans();

                $this->insert(
                    array(
                        'field' => array(
                            'id',
                            'title',
                            'note',
                        ),
                        'value' => array(
                            ':id',
                            ':title',
                            ':note',
                        ),
                        'bind' => array(
                            'id' => 22,
                            'title' => 'タイトル22',
                            'note' => '内容22'
                        )
                    )
                );

                $this->insert(
                    array(
                        'field' => array(
                            'id',
                            'title',
                            'note',
                        ),
                        'value' => array(
                            ':id',
                            ':title',
                            ':note',
                        ),
                        'bind' => array(
                            'id' => 22,
                            'title' => 'タイトル22',
                            'note' => '内容22'
                        )
                    )
                );

                $this->commit();

            } catch (PDOException $e) {

                $this->rollback();
                $ret = 'ロールバックしました。';

            }
        }

        return $ret;
    }

    public function getDataAll()
    {
        return $this->rowAll(
            array(
                'query' => 'SELECT * FROM ' . $this->getTableName()
            )
        );
    }
}

