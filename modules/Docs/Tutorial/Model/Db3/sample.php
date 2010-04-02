<?php

class Docs_Tutorial_Model_Db3_sample extends xFrameworkPX_Model
{
    public function insertData($id, $title, $note)
    {
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
                    'id' => $id,
                    'title' => $title,
                    'note' => $note,
                )
            )
        );
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

