<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Docs_tree Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    Docs_tree
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: tree.php 1223 2010-01-08 11:47:38Z kotsutsumi $
 */

// {{{ Docs_tree

/**
 * Docs_tree Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0.0
 */
class Docs_tree extends xFrameworkPX_Model
{
    // {{{ props

    /**
     * 使用テーブル設定
     *
     * @var string
     */
    public $usetable = false;

    // }}}
    // {{{ getNodes

    /**
     * ノード取得メソッド
     *
     * @param string $id ID
     * @return array ノード配列
     */
    public function getNodes($id)
    {
        $ret = array();
        $basepath = normalize_path('../bindtransfer/docs/wiki/');

        if ($id === 'root') {
            $ret = $this->_getRootNode();
        } else if($id === 'tutorial') {

            $path = $basepath . str_replace('_', DS, $id);
            foreach (glob($path . "/*") as $filename) {
                if (is_dir($filename)) {
                    $leaf = false;

                    if (mb_detect_encoding($filename, array('ascii', 'utf-8', 'sjis'), true) == 'SJIS') {
                        $temp = mb_convert_encoding_deep($filename, 'utf-8', 'sjis');
                    } else {
                        $temp = $filename;
                    }

                    $idTemp = $id . '_' . end(explode('/', $temp));
                    $text = end(explode(
                        '/', preg_replace('/[0-9]+\./', '', $temp)
                    ));
                } else {
                    $leaf = true;
                    $idTemp = $id . '_' . get_filename($filename);
                    $handle = @fopen($filename, "r");

                    if ($handle) {
                        $text = fgets($handle, 4096);
                        fclose($handle);
                    }

                    preg_match('/\* (.*)/', $text, $match);

                    if(isset($match[1])) {
                        $text = $match[1];
                    } else {
                        $text = '';
                    }

                }

                $ret[] = array(
                    'id' => $idTemp,
                    'text' => $text,
                    'leaf' => $leaf
                );
            }

        } else {
            $path = $basepath . str_replace('_', DS, $id);

            if ($this->_checkOS('win')) {
                $files = get_filelist(mb_convert_encoding_deep($path, 'sjis', 'utf-8'));
            } else {
                $files = get_filelist($path);
            }

            sort($files);

            foreach ($files as $file) {

                if(matchesIn($file, '.svn')) {
                    continue;
                }

                $text = '';
                $leaf = true;
                $handle = @fopen($file, "r");
                if ($handle) {
                    $text = fgets($handle, 4096);
                    fclose($handle);
                }

                preg_match('/\* (.*)/', $text, $match);
                if(isset($match[1])) {
                    $text = $match[1];
                }

                $ret[] = array(
                    'id' => $id . '_' . get_filename($file),
                    'text' => $text,
                    'leaf' => $leaf
                );
            }
        }

        return $ret;
    }

    // }}}
    // {{{ _getRootNode

    /**
     * ルートノード取得メソッド
     *
     * @return array ノード配列
     */
    private function _getRootNode()
    {
        return array(
            array(
                'id' => 'about',
                'text' => 'xFrameworkPXについて',
            ),
            array(
                'id' => 'controller',
                'text' => 'コントローラー',
            ),
            array(
                'id' => 'module',
                'text' => 'モジュール(モデル)',
            ),
            array(
                'id' => 'view',
                'text' => 'ビュー',
            ),
            array(
                'id' => 'util',
                'text' => 'ユーティリティー',
            ),
            array(
                'id' => 'config',
                'text' => '設定'
            ),
            array(
                'id' => 'tutorial',
                'text' => 'チュートリアル',
            )
            /*
            array(
                'id' => 'api',
                'text' => 'クラスリファレンス',
            )
            */
        );
    }

    // }}}
    // {{{ checkOS

    private function _checkOS($osName)
    {
        $ret = false;

        if (preg_match(sprintf('/%s/i', $osName), PHP_OS)) {
            $ret = true;
        }

        return $ret;
    }

    // }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
