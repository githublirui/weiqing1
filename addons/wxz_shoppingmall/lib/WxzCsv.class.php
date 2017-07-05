<?php

/**
 * CSV相关功能
 * lirui @author lirui <lirui@51talk.com> 
 */
class WxzCsv {

    private $_data = '';
    private $_char;

    function __construct() {
        $this->_char = 'UTF-8';
    }

    /**
     * 写一行
     */
    public function createRow($row) {
        if (is_array($row)) {
            foreach ($row as $k => $v) {
                //$row[$k] = str_replace(array("\t", "\n", '\t'), '', $v);
                $row[$k] = "\"$v\"";
            }
            $data = implode(",", $row);
        } else {
            $data = $row;
        }

        if ($this->_char != 'GBK') {
            $data = @iconv($this->_char, 'GBK//IGNORE', $data);
        }
        $this->_data .= "{$data}\r\n";
    }

    /**
     * 设置字符编码
     */
    public function setChar($char) {
        $this->_char = strtoupper($char);
    }

    /**
     * 创建一个空行
     */
    public function createBlank() {
        $this->_data .= "\r\n";
    }

    /**
     * 下载
     */
    public function download($fileName = '') {
        $fileName = $fileName ? $fileName : date('ymd-Hi');
        header("Content-type:application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition:attachment;filename={$fileName}.csv");
        echo $this->_data;
        exit;
    }

    /**
     * 返回数据
     */
    public function getData() {
        return $this->_data;
    }

}
