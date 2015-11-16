<?php

/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.benchmark.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
class Driver_mysql {

    var $db_connect_id; //数据库连接操作符
    var $query_result; //查询操作符号
    var $row = array(); //记录数组
    var $num_queries = 0; //查询数量
    var $in_transaction = 0; //事务变量（查询批量）
    var $query = "";
    var $dbname = "";
    var $mysql_field = array();

    public function __construct($config) {

        $this->connect($config);

    }

    // Constructor
    function connect($config) {

        $this->db_connect_id = mysql_connect($config ['dbhost'], $config ['dbuser'], $config['dbpw']);
        mysql_query("SET character_set_connection=" . $config ['dbcharset'] . ", character_set_results=" . $config['dbcharset'] . ", character_set_client=" . $config ['dbcharset'] . "", $this->db_connect_id);

        if ($this->db_connect_id) {

            $dbselect = mysql_select_db($config ['dbname'], $this->db_connect_id); //选择数据库返回数?
            if (!$dbselect) {

                mysql_close($this->db_connect_id);
                die('database not exits');
            }
            return $this->db_connect_id; //返回数据库连接字?
        } else {
            return false;
        }
    }


    // Other base methods
    function close() { //关闭数据
        if ($this->db_connect_id) { // Commit any remaining transactions
            return mysql_close($this->db_connect_id);
        } else {
            return false;
        }
    }


    // Base query method
    function query($querystring) {
        unset($this->query_result);
        $this->query_result = mysql_query($querystring, $this->db_connect_id); //＊＊返回查询句柄
        if ($this->query_result) { //
            unset($this->row [$this->query_result]);
            return $this->query_result;
        } else { //查询失败
            $this->halt('error');
            return false;
        }
       // $this->sql_freeresult($this->query_result);
    }


    //显示所有数据库
    function listdb() {

        unset($this->query_result);
        $this->query_result = mysql_list_tables($this->dbname); //＊＊返回查询句柄
        if ($this->query_result) {
            unset($this->row [$this->query_result]);

            return $this->query_result;
        } else {//查询失败
            return false;
        }
    }


    // Other query methods
    function numrows($query_id = 0) { //返回查询记录数量

        return ($query_id) ? mysql_num_rows($query_id) : false;
    }


	//返回查询记录影响
    function affectedrows() {
        return ($this->db_connect_id) ? mysql_affected_rows($this->db_connect_id) : false;
    }


    //返回查询记录中的字段数目
    function numfields($query_id = 0) {
        return ($query_id) ? mysql_num_fields($query_id) : false;
    }

	//返回指定offset偏移动的字段名称?
    function fieldname($offset, $query_id = 0) {
        return ($query_id) ? mysql_field_name($query_id, $offset) : false;
    }


    //返回指定offset偏移动的字段类型?
    function fieldtype($offset, $query_id = 0) {
        return ($query_id) ? mysql_field_type($query_id, $offset) : false;
    }

    //＊获取一行查询记?
    function fetchrow($query_id = 0) {
        if ($query_id) {
            $this->row = mysql_fetch_array($query_id, MYSQL_ASSOC);
            return $this->row;
        } else {
            return false;
        }
    }

    //＊获取总记录数
    function total($query_id = 0) {
        if ($query_id) {
            $this->row = mysql_fetch_row($query_id);
            return $this->row [0];
        } else {
            return false;
        }
    }

    //数据偏移
    function rowseek($rownum, $query_id = 0) { //数据移动查询
        return ($query_id) ? mysql_data_seek($query_id, $rownum) : false;
    }

   //获取最新入库ID
    function nextid() {
        return ($this->db_connect_id) ? mysql_insert_id($this->db_connect_id) : false;
    }

   //释放资源
    function freeresult($query_id = 0) { //释放?查询操作
        if ($query_id) {
            unset($this->row [$query_id]);
            mysql_free_result($query_id);

            return true;
        } else {
            return false;
        }
    }

    //错误行返
    function error() {
        $result_error ['message'] = mysql_error($this->db_connect_id);
        $result_error ['code'] = mysql_errno($this->db_connect_id);
        return $result_error;
    }

    //输出表中所有的字段
    function listfield($from, $tablename = '') { //

        if ($this->db_connect_id) {
            $fields = mysql_list_fields($this->dbname, $tablename, $this->db_connect_id);
            $columns = mysql_num_fields($fields);

            for ($i = $from; $i < $columns; $i++) {
                $this->mysql_field = mysql_field_name($fields, $i);
                $ay [] = $this->mysql_field;
            }
            return $ay;
        } else {
            return false;
        }
    }

    //创建数据库
    function createDataBase($db = '') {

        if (empty($db)) {

            $this->halt('DB Name Empty');
        } else {

            $this->query("CREATE DATABASE `{$db}`");
        }
    }

    // 列出数据库中的所有表
     function listTables($db = '') {

        $list = array();

        if (!empty($db)) {

            $db = ' FROM `' . $db . '`';
        }

        $query_id = $this->sql_query('SHOW TABLES' . $db);

        while ($row = $this->fetchrow($query_id)) {

            $list[] = $row[0];
        }

        return $list;
    }

    //拷贝表
     function copyTable($dstTable, $srcTable, $condition = '') {

        return $this->query("SELECT * INTO `{$dstTable}` FROM `{$srcTable}` {$condition}");
    }

     //警示信息
     function halt($msg) {

        global $technicalemail, $debug;

        $message = "<html>\n<head>\n";
        $message .= "<meta content=\"text/html; charset=utf8\" http-equiv=\"Content-Type\">\n";
        $message .= "<STYLE TYPE=\"text/css\">\n";
        $message .= "<!--\n";
        $message .= "body,td,p,pre {\n";
        $message .= "font-family : Verdana, Arial, Helvetica, sans-serif;font-size : 12px;\n";
        $message .= "}\n";
        $message .= "</STYLE>\n";
        $message .= "</head>\n";
        $message .= "<body bgcolor=\"#ffffff\" text=\"#000000\" >\n";
        $message .= "<font size=4><b> Monitor </b></font>\n<hr NOSHADE SIZE=1>\n";

        $Script = "http://" . $_SERVER ['HTTP_HOST'] . getenv("REQUEST_URI") . "";
        $content = "<p>Database Error:</p><pre><b>" . htmlspecialchars($msg) . "</b></pre>\n";
        $content .= "<b>Mysql error description</b>: " . mysql_error () . "\n<br><br />";
        $content .= "<b>Mysql error number</b>: " . mysql_errno () . "\n<br><br />";
        $content .= "<b>Date</b>: " . date("Y-m-d @ H:i") . "\n<br><br />";
        $content .= "<b>Script</b>: " . $Script . "\n<br><br />";
        $content .= "<b>Referer</b>: " . getenv("HTTP_REFERER") . "\n<br><br><br />";
        $message .= $content;
        // }
        $message .= "</body>\n</html>";
        echo $message;
        //echo "<br>";
        //echo $sqlerror;
        exit ();
    }

    function __destruct() {
        $this->close();
    }

}


?>
