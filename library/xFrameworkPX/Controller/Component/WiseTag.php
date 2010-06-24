<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Controller_Component_WiseTag Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: WiseTag.php 1435 2010-01-20 15:28:50Z kotsutsumi $
 */

// {{{ xFrameworkPX_Controller_Component_WiseTag

/**
 * xFrameworkPX_Controller_Component_WiseTag Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Controller_Component
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 3.5.0
 * @link       http://www.xframeworkpx.com/api/?class=xFrameworkPX_Controller_Component_WiseTag
 */
class xFrameworkPX_Controller_Component_WiseTag
extends xFrameworkPX_Controller_Component
{

    /**
     * フィールド種別定数（form）
     */
    const FIELD_FORM = 'form';

    /**
     * フィールド種別定数（text）
     */
    const FIELD_TEXT = 'text';

    /**
     * フィールド種別定数（password）
     */
    const FIELD_PASSWORD = 'password';

    /**
     * フィールド種別定数（checkbox）
     */
    const FIELD_CHECKBOX = 'checkbox';

    /**
     * フィールド種別定数（radio）
     */
    const FIELD_RADIO = 'radio';

    /**
     * フィールド種別定数（file）
     */
    const FIELD_FILE = 'file';

    /**
     * フィールド種別定数（hidden）
     */
    const FIELD_HIDDEN = 'hidden';

    /**
     * フィールド種別定数（submit）
     */
    const FIELD_SUBMIT = 'submit';

    /**
     * フィールド種別定数（reset）
     */
    const FIELD_RESET = 'reset';

    /**
     * フィールド種別定数（image）
     */
    const FIELD_IMAGE = 'image';

    /**
     * フィールド種別定数（button）
     */
    const FIELD_BUTTON = 'button';

    /**
     * フィールド種別定数（textarea）
     */
    const FIELD_TEXTAREA = 'textarea';

    /**
     * フィールド種別定数（select）
     */
    const FIELD_SELECT = 'select';

    /**
     * アサイン変数名
     *
     * @var string
     */
    private $_assignName = null;

    /**
     * セッション名
     *
     * @var string
     */
    private $_sessionName = null;

    /**
     * セッションオブジェクト
     *
     * @var object
     */
    private $_session = null;

    /**
     * 出力タグ設定
     *
     * @var string
     */
    private $_fields = null;

    /**
     * コンストラクタ
     *
     * @param  array $conf
     * @return void
     */
    public function __construct($conf)
    {

        if (isset($conf['assign_name']) && $conf['assign_name'] !== '') {
            $this->_assignName = $conf['assign_name'];
        } else {
            $this->_assignName = 'wt';
        }

        if (isset($conf['session_name']) && $conf['session_name'] !== '') {
            $this->_sessionName = $conf['session_name'];
        } else {
            $this->_sessionName = 'WiseTagConfig';
        }

        $this->_fields = array();
    }

    /**
     * セッションオブジェクト設定メソッド
     *
     * @param object $sess
     * @return void
     */
    public function setSession($sess)
    {
        $this->_session = $sess;
    }

    /**
     * init
     *  動的フィールド初期化メソッド
     *
     * @param string $sessName
     * @return void
     */
    public function init($sessName = null)
    {
        $sessionName = ($sessName) ? $sessName : $this->_sessionName;

        $this->_fields = $this->_session->read($sessionName);

        if (is_null($this->_fields)) {
            $this->_fields = array();
        }

    }

    /**
     * gen
     *  動的フィールドタグ出力メソッド
     *
     * @param string $sessName
     * @return void
     */
    public function gen($sessName = null)
    {
        $sessionName = ($sessName) ? $sessName : $this->_sessionName;

        if ($this->_fields) {
            $temp = array();

            foreach ($this->_fields as $type => $fields) {

                foreach ($fields as $name => $field) {
                    $src = array();

                    foreach ($field as $key => $value) {

                        if (is_numeric($key)) {

                            if (preg_match(
                                '/\[([^\[\]]*?)\]/', $value['name'], $matches
                            )) {
                                $srcKey = ($matches[1] !== '')
                                        ? $matches[1] : $key;
                            } else {
                                $srcKey = $key;
                            }

                            $tag = $this->_convertTag($value, $type);

                            if (isset($src[$srcKey])) {

                                if (is_array($src[$srcKey])) {
                                    $src[$srcKey][] = $tag;
                                } else {
                                    $first = $src[$srcKey];
                                    $src[$srcKey] = array($first, $tag);
                                }

                            } else {
                                $src[$srcKey] = $tag;
                            }

                        } else {

                            if (
                                $type != 'form' &&
                                preg_match(
                                    '/\[([^\[\]]*?)\]/',
                                    $field['name'],
                                    $matches
                                )
                            ) {
                                $srcKey = ($matches[1] !== '')
                                        ? $matches[1] : '';
                            } else {
                                $srcKey = false;
                            }
                            
                            if ($srcKey) {
                                $src[$srcKey] = $this->_convertTag(
                                    $field, $type
                                );
                            } else if ($srcKey === '') {
                                $src[] = $this->_convertTag($field, $type);
                            } else {
                                $src = $this->_convertTag($field, $type);
                            }

                            break;
                        }

                    }

                    if (isset($temp[$name])) {
                        $temp[$name] = array_merge((array)$temp[$name], (array)$src);
                    } else {
                        $temp[$name] = $src;
                    }

                }

            }

            $view = xFrameworkPX_View::getInstance()->setUserData(
                $this->_assignName, $temp
            );

            $this->_session->write($sessionName, $this->_fields);
        }

    }

    /**
     * add
     *   動的フィールド追加メソッド
     *
     * @param array  $fields
     * @param bool $override
     * @return void
     */
    public function add($fields, $override = false)
    {

        if (is_array($fields)) {

            foreach ($fields as $key => $value) {

                if (is_numeric($key)) {
                    $this->add($value, $override);
                } else {
                    $tag = $this->_createTag($fields);

                    if (is_array($tag)) {
                        $type = $tag['type'];
                        $name = $tag['name'];
                        $config = $tag['config'];
                    } else {
                        break;
                    }

                    if (array_key_exists($type, $this->_fields)) {

                        if (
                            array_key_exists($name, $this->_fields[$type]) &&
                            !$override
                        ) {

                            // 同名のフィールドがあった場合の配列化処理
                            reset($this->_fields[$type][$name]);
                            $key = key($this->_fields[$type][$name]);

                            if (is_numeric($key)) {
                                $this->_fields[$type][$name][] = $config;
                            } else {
                                $temp = $this->_fields[$type][$name];
                                $this->_fields[$type][$name] = array(
                                    $temp, $config
                                );
                            }

                        } else {
                            $this->_fields[$type][$name] = $config;
                        }

                    } else {
                        $this->_fields[$type] = array(
                            $name => $config
                        );
                    }

                    break;
                }

            }

        }

    }

    /**
     * edit
     *  動的フィールド設定変更メソッド
     *
     * @param array  $config
     * @param string $conditions
     * @return void
     */
    public function edit($config, $conditions = array())
    {

        if ($config) {

            if (is_array($conditions)) {
                $type = null;
                $count = null;
                $other = array();

                foreach ($conditions as $key => $value) {

                    switch ($key) {

                        case 'type':
                            $type = ($value) ? strtolower($value) : null;
                            break;

                        case 'count':
                            $count = null;

                            if (is_numeric($value)) {
                                $count = (int)$value;

                                if ($count > 0) {
                                    $count -= 1;
                                } else {
                                    $count = null;
                                }

                            }

                            break;

                        default:
                            $other[$key] = $value;
                            break;
                    }

                }

            }

            // 変更対象抽出
            if ($type) {

                if (isset($this->_fields[$type])) {

                    foreach ($this->_fields[$type] as $name => $field) {

                        foreach ($field as $key => $value) {

                            if (is_numeric($key)) {

                                if (!is_null($count)) {

                                    if (isset($field[$count])) {

                                        // 設定マージ
                                        $this->_fields[$type][$name][$count] =
                                            $this->_configMerge(
                                                $this->_fields[$type][$name][$count],
                                                $config
                                            );
                                    }

                                    break;
                                } else if (
                                    $this->_isMatchField($other, $value)
                                ) {

                                    // 設定マージ
                                    $this->_fields[$type][$name][$key] =
                                        $this->_configMerge(
                                            $value, $config
                                        );
                                }

                            } else {

                                if ($this->_isMatchField($other, $field)) {

                                    // 設定マージ
                                    $this->_fields[$type][$name] =
                                        $this->_configMerge(
                                            $field, $config
                                        );
                                }

                                break;
                            }

                        }

                    }

                }

            } else if ($other) {

                foreach ($this->_fields as $type => $fields) {

                    foreach ($fields as $name => $field) {

                        foreach ($field as $key => $value) {

                            if (is_numeric($key)) {

                                if (!is_null($count)) {

                                    if (isset($field[$count])) {

                                        // 設定マージ
                                        $this->_fields[$type][$name][$count] =
                                            $this->_configMerge(
                                                $this->_fields[$type][$name][$count],
                                                $config
                                            );
                                    }

                                    break;
                                } else if (
                                    $this->_isMatchField($other, $value)
                                ) {

                                    // 設定マージ
                                    $this->_fields[$type][$name][$key] =
                                        $this->_configMerge(
                                            $value, $config
                                        );
                                }

                            } else {

                                if ($this->_isMatchField($other, $field)) {

                                    // 設定マージ
                                    $this->_fields[$type][$name] =
                                        $this->_configMerge(
                                            $value, $config
                                        );
                                }

                                break;
                            }

                        }

                    }

                }

            }

        }

    }

    /**
     * remove
     *  フィールド削除メソッド
     *
     * @param array $conditions
     * @return void
     */
    public function remove($conditions)
    {

        if (is_array($conditions) && $conditions) {
            $type = null;
            $count = null;
            $other = array();

            foreach ($conditions as $key => $value) {

                switch ($key) {

                    case 'type':
                        $type = ($value) ? strtolower($value) : null;
                        break;

                    case 'count':
                        $count = null;

                        if (is_numeric($value)) {
                            $count = (int)$value;

                            if ($count > 0) {
                                $count -= 1;
                            } else {
                                $count = null;
                            }

                        }

                        break;

                    default:
                        $other[$key] = $value;
                        break;
                }

            }

            if ($type) {

                if (isset($this->_fields[$type])) {

                    foreach ($this->_fields as $name => $field) {

                        foreach ($fields as $key => $value) {

                            if (is_numeric($key)) {

                                if (!is_null($count)) {

                                    if (isset($field[$count])) {

                                        // 設定削除
                                        unset($this->_fields[$type][$name][$count]);
                                    }

                                    break;
                                } else if (
                                    $this->_isMatchField($other, $value)
                                ) {

                                    // 設定削除
                                    unset($this->_fields[$type][$name][$key]);
                                }

                            } else {

                                if ($this->_isMatchField($other, $field)) {

                                    // 設定マージ
                                    unset($this->_fields[$type][$name]);
                                }

                                break;
                            }

                        }

                    }

                }

            } else if ($other) {

                foreach ($this->_fields as $type => $fields) {

                    foreach ($fields as $name => $field) {

                        foreach ($field as $key => $value) {

                            if (is_numeric($key)) {

                                if (!is_null($count)) {

                                    if (isset($field[$count])) {

                                        // 設定削除
                                        unset($this->_fields[$type][$name][$count]);
                                    }

                                    break;
                                } else if (
                                    $this->_isMatchField($other, $value)
                                ) {

                                    // 設定削除
                                    unset($this->_fields[$type][$name][$key]);
                                }

                            } else {

                                if ($this->_isMatchField($other, $field)) {

                                    // 設定削除
                                    unset($this->_fields[$type][$name]);
                                }

                                break;
                            }

                        }

                    }

                }

            }

        }

    }

    /**
     * clear
     *  動的フィールドクリアメソッド
     *
     * @param array $tags
     * @return void
     */
    public function clear($sessName = null)
    {
        $sessionName = ($sessName) ? $sessName : $this->_sessionName;
        $this->_fields = array();
        $this->_session->remove($sessionName);
    }

    /**
     * _isMatchField
     *  対象のフィールドが指定した条件に一致するかどうかをチェックする
     *
     * @return bool
     */
    private function _isMatchField($condition, $subject)
    {
        $ret = true;

        if (is_array($condition) && $condition) {

            foreach ($condition as $key => $value) {

                if (isset($subject[$key])) {
                    $ret = $subject[$key] === $value;

                    if (!$ret) {
                        break;
                    }

                }

            }

        } else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * _configMerge
     *  タグ設定マージメソッド
     *
     * @return array
     */
    private function _configMerge($src, $merge) {
        $ret = $src;

        foreach ($merge as $key => $value) {
            $ret[$key] = $value;
        }

        return $ret;
    }

    /**
     * _createTag
     *  タグ設定生成メソッド
     *
     * @param array $config
     * @return array
     */
    private function _createTag($config)
    {
        $ret = null;

        if (is_array($config)) {
            $ret = array();

            if (isset($config['type']) && $config['type'] !== '') {
                $ret['type'] = strtolower($config['type']);
                unset($config['type']);

                if (
                    $ret['type'] != self::FIELD_FORM &&
                    $ret['type'] != self::FIELD_TEXT &&
                    $ret['type'] != self::FIELD_PASSWORD &&
                    $ret['type'] != self::FIELD_CHECKBOX &&
                    $ret['type'] != self::FIELD_RADIO &&
                    $ret['type'] != self::FIELD_FILE &&
                    $ret['type'] != self::FIELD_HIDDEN &&
                    $ret['type'] != self::FIELD_SUBMIT &&
                    $ret['type'] != self::FIELD_RESET &&
                    $ret['type'] != self::FIELD_IMAGE &&
                    $ret['type'] != self::FIELD_BUTTON &&
                    $ret['type'] != self::FIELD_TEXTAREA &&
                    $ret['type'] != self::FIELD_SELECT
                ) {

                    // TODO エラーメッセージ追加
                    throw new xFrameworkPX_Controller_Component_Exception(
                        '指定されたフィールドタイプは定義されていません。'
                    );
                }

            } else {

                // typeを指定しなかった場合、デフォルトで'text'タイプを設定
                $ret['type'] = 'text';
            }

            if (isset($config['name']) && $config['name'] !== '') {
                $ret['name'] = preg_replace('/\[.*\]/', '', $config['name']);
            } else if ($ret['type'] == 'form') {
                $ret['name'] = 'form';
            } else {

                // TODO エラーメッセージ追加
                throw new xFrameworkPX_Controller_Component_Exception(
                    'name属性の設定がありません。'
                );
            }

            $ret['config'] = $config;
        }

        return $ret;
    }

    /**
     * _convertTag
     *  タグ生成メソッド
     *
     * @param array  $config
     * @param string $type
     * @return mixed $ret 
     */
    private function _convertTag($config, $type)
    {
        $ret = false;

        if ($type == self::FIELD_FORM) {

            foreach ($config as $name => $value) {

                if (is_string($name)) {
                    $value = htmlspecialchars($value);
                    $ret .= sprintf(' %s="%s"', $name, $value);
                }

            }

        } else {
            $pretext = '';
            $prelabel = '';
            $tag = '';
            $attr = '';
            $label = '';
            $text = '';
            $intext = '';
            $options = '';

            // pretext取得
            if (isset($config['pretext'])) {
                $pretext = $config['pretext'];
                unset($config['pretext']);
            }

            // text取得
            if (isset($config['text'])) {
                $text = $config['text'];
                unset($config['text']);
            }

            // prelabel取得
            if (isset($config['prelabel'])) {

                if (isset($config['id']) && $config['id'] !== '') {
                    $prelabel = sprintf(
                        '<label for="%s">%s</label>',
                        htmlspecialchars($config['id']),
                        htmlspecialchars($config['prelabel'])
                    );
                }

                unset($config['prelabel']);
            }

            // label取得
            if (isset($config['label'])) {

                if (isset($config['id']) && $config['id'] !== '') {
                    $label = sprintf(
                        '<label for="%s">%s</label>',
                        htmlspecialchars($config['id']),
                        htmlspecialchars($config['label'])
                    );
                }

                unset($config['label']);
            }

            if ($type == self::FIELD_TEXTAREA) {

                if (isset($config['intext'])) {
                    $intext = htmlspecialchars($config['intext']);
                    unset($config['intext']);
                }

            } else if ($type == self::FIELD_SELECT) {

                if (isset($config['options'])) {

                    if (is_array($config['options'])) {

                        foreach ($config['options'] as $option) {
                            $optAttr = '';

                            if ($option['intext']) {
                                $intext = htmlspecialchars($option['intext']);
                                unset($option['intext']);
                            }

                            // 属性生成
                            foreach ($option as $name => $value) {

                                if (!is_null($value)) {
                                    $value = htmlspecialchars($value);
                                    $optAttr .= sprintf(
                                        ' %s="%s"', $name, $value
                                    );
                                }

                            }

                            // optionタグ生成
                            $options .= sprintf(
                                '<option%s>%s</option>', $optAttr, $intext
                            );
                        }

                    }

                    unset($config['options']);
                }

            }

            // その他属性取得
            foreach ($config as $name => $value) {

                if (!is_null($value)) {
                    $value = htmlspecialchars($value);
                    $attr .= sprintf(' %s="%s"', $name, $value);
                }

            }

            // タグ生成
            switch ($type) {

                // inputタグ
                case self::FIELD_TEXT:
                case self::FIELD_PASSWORD:
                case self::FIELD_FILE:
                case self::FIELD_HIDDEN:
                case self::FIELD_SUBMIT:
                case self::FIELD_RESET:
                case self::FIELD_IMAGE:
                case self::FIELD_BUTTON:
                case self::FIELD_CHECKBOX:
                case self::FIELD_RADIO:
                    $tag = sprintf(
                        '<input type="%s"%s />',
                        $type,
                        $attr
                    );
                    break;

                case self::FIELD_TEXTAREA:
                    $tag = sprintf('<textarea%s>%s</textarea>', $attr, $intext);
                    break;

                case self::FIELD_SELECT:
                    $tag = sprintf('<select%s>%s</select>', $attr, $options);
                    break;

                default:
                    return $ret;
                    break;
            }

            $ret = sprintf(
                '%s%s%s%s%s',
                $pretext, $prelabel, $tag, $label, $text
            );
        }

        return $ret;
    }

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
