<?php

class Docs_Tutorial_Database_Ormapping2_sample extends xFrameworkPX_Model
{
    public function setData($id, $title)
    {
        $this->set(
            array(
                'id' => $id,
                'title' => $title
            )
        );
    }
    
    public function insertData($title)
    {
        // プライマリキーを設定しない場合、INSERTが実行されます。
        // プライマリキーはMAX+1が設定されます。
        $this->set(
            array(
                'title' => $title
            )
        );
    }

    public function getDataAll()
    {
        return $this->get('all');
    }

}

