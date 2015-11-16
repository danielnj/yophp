<?php

/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name db_active.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

/**
 * ORM程序
 */
class DB_ORM extends Model
{


    /**
	 * 构造函数
	 */
	public function __construct() {


	}
	/**
	 * 保证对象不被clone
	 */
	private function __clone() {}

	/**
	 * 对特殊字符进行过滤
	 * @param value  值
	 */
	public function val_escape($value) {
		if(is_null($value))return 'NULL';
		if(is_bool($value))return $value ? 1 : 0;
		if(is_int($value))return (int)$value;
		if(is_float($value))return (float)$value;
		if(@get_magic_quotes_gpc())$value = stripslashes($value);
		return '\''.mysql_real_escape_string($value).'\'';
	}

     /**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit)
	{
		return $sql. " LIMIT {$limit}";
	}

	/**
	 * 从数据表中查找记录
	 *
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要自行使用escape来对输入值进行过滤
	 * @param sort    排序，等同于“ORDER BY ”
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 * @param limit    返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
	 * 如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function find($conditions = null,$tablename = null, $sort = null, $fields = null, $limit = null)
	{
		$where = "";
		$fields = empty($fields) ? "*" : $fields;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->val_escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		if(null != $sort){
			$sort = "ORDER BY {$sort}";
		}else{
			$sort = "ORDER BY {$this->pk}";
		}
		$sql = "SELECT {$fields} FROM {$tablename} {$where} {$sort}";
		if(null != $limit)$sql = $this->setlimit($sql, $limit);
		return $sql;
	}


  //删除数据
public function del_table($tablename, $wheresqlarr, $silent = 0) {

	$where = $comma = '';
	if (empty ( $wheresqlarr )) {
		$where = '1';
	} elseif (is_array ( $wheresqlarr )) {
		foreach ( $wheresqlarr as $key => $value ) {
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	return $where;
}

//添加数据
public function insert_table($tablename, $insertsqlarr, $returnid = 0, $replace = false, $silent = 0) {

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ( $insertsqlarr as $insert_key => $insert_value ) {
		$insertkeysql .= $comma . '`' . $insert_key . '`';
		$insertvaluesql .= $comma . '\'' . $insert_value . '\'';
		$comma = ', ';
	}
	$method = $replace ? 'REPLACE' : 'INSERT';
	$query = $method . " INTO " .  $tablename  . " (" . $insertkeysql . ") VALUES (" . $insertvaluesql . ")";
	return $query;

}

//更新数据
public function update_table($tablename, $setsqlarr, $wheresqlarr, $silent = 0) {

	$setsql = $comma = '';
	foreach ( $setsqlarr as $set_key => $set_value ) {
		$setsql .= $comma . '`' . $set_key . '`' . '=\'' . $set_value . '\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if (empty ( $wheresqlarr )) {
		$where = '1';
	} elseif (is_array ( $wheresqlarr )) {
		foreach ( $wheresqlarr as $key => $value ) {
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$query = 'UPDATE ' .  $tablename . ' SET ' . $setsql . ' WHERE ' . $where;
	return $query;

}

//查询记录总数
public function query_total($tablename, $wheresqlarr) {

	$where = $comma = '';
	if (empty ( $wheresqlarr )) {
		$where = '1';
	} elseif (is_array ( $wheresqlarr )) {
		foreach ( $wheresqlarr as $key => $value ) {
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$query_str = "SELECT count(*) FROM " .  $tablename . " where " . $where . "";
	return $query_str;

}


/**
	 * 为设定的字段值增加
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param field    字符串，需要增加的字段名称，该字段务必是数值类型
	 * @param optval    增加的值
	 */
	public function add_field_val($conditions,$tablename, $field, $optval = 1)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->val_escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		$values = "{$field} = {$field} + {$optval}";
		$sql = "UPDATE {$tablename} SET {$values} {$where}";
		return $sql;
	}

/**
	 * 联合查询
	 * @param conditions    数组形式，查找条件，
	 * @param field    字符串，需要增加的字段名称，该字段务必是数值类型
	 * @param optval    增加的值
	 */
	public function unit_find($conditions,$tablename, $field, $optval = 1)
	{

	}


}