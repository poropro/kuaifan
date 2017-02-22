<?php
 /*
 * cms 数据库连接文件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
class mysql {
	var $linkid=null;
	var $ExecuteArr = array(); 
	var $db_name = '';
	var $db_charset = '';

    function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'UTF8', $connect = 1) {
    	$this -> connect($dbhost, $dbuser, $dbpw, $dbname, $dbcharset, $connect);
    }

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'UTF8', $connect=1){
    	$this->db_name = $dbname;
    	$this->db_charset = $dbcharset;
    	$func = empty($connect) ? 'mysql_pconnect' : 'mysql_connect';
    	if(!$this->linkid = @$func($dbhost, $dbuser, $dbpw, true)){
    		$this->dbshow('Can not connect to Mysql!');
    	} else {
    		if($this->dbversion() > '4.1'){
    			mysql_query( "SET NAMES UTF8");
    			if($this->dbversion() > '5.0.1'){
    				mysql_query("SET sql_mode = ''",$this->linkid);
					mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->linkid);
    			}
    		}
    	}
    	if($dbname){
    		if(mysql_select_db($dbname, $this->linkid)===false){
    			$this->dbshow("Can't select MySQL database($dbname)!");
    		}
    	}
    }

    function select_db($dbname){
    	return mysql_select_db($dbname, $this->linkid);
    }

    function query($sql){
    	$this->ExecuteArr[] = $sql;
    	if(!$query=@mysql_query($sql, $this->linkid)){
    		$this->dbshow("Query <br/>error: $sql");
    	}else{
    		return $query;
    	}
    }

    function getall($sql, $type=MYSQL_ASSOC){
    	$query = $this->query($sql);
		$rows = array();
    	while($row = mysql_fetch_array($query,$type)){
    		$rows[] = $row;
    	}
    	return $rows;
    }

    function getone($sql, $type=MYSQL_ASSOC){
    	$query = $this->query($sql,$this->linkid);
    	$row = mysql_fetch_array($query, $type);
    	return $row;
    }


	function get_total($sql)
	{
		$row = $this->getall($sql);
		$v=0;
		if (!empty($row) && is_array($row))
		{			
			foreach($row as $n)
			{
			$v=$v+$n['num'];
			}			
		}
		return $v;
 	}
    function getfirst($sql, $type=MYSQL_NUM) {
    	$query = $this->query($sql, $this->linkid);
    	$row = mysql_fetch_array($query, $type);
    	return $row[0];
    }
	function fetch_array($result,$type = MYSQL_ASSOC){
		return mysql_fetch_array($result,$type);
	}

    function affected_rows(){
    	return mysql_affected_rows($this->linkid);
    }

    function num_rows($result = null){
    	if (empty($result)) return mysql_num_rows($this->linkid);
    	return mysql_num_rows($result);
    }

    function num_fields($result){
    	return mysql_num_fields($result);
    }

    function insert_id(){
    	return mysql_insert_id($this->linkid);
    }

    function free_result(){
    	return mysql_free_result($this->linkid);
    }
	
	function escape_string($string)
    {
        if (PHP_VERSION >= '4.3')
        {
            return mysql_real_escape_string($string, $this->linkid);
        }
        else
        {
            return mysql_escape_string($string, $this->linkid);
        }
    }
    function error(){
    	return mysql_error($this->linkid);
    }

    function errno(){
    	return mysql_errno($this->linkid);
    }

    function close(){
    	return mysql_close($this->linkid);
    }

    function dbversion(){
    	return mysql_get_server_info($this->linkid);
    }

    function dbshow($err)
	{
    	if($err){
    		$info = "Error: ".$err;			
    	}else{
    		$info = "Errno: ".$this->errno()."<br/> Error: ".$this->error();
    	}
    	showmsg("Errno: Query error", $info);
    	//exit($info);
    }
    
	/**
	 * 执行添加记录操作
	 * @param $data 		要增加的数据，参数为数组。数组key为字段值，数组值为数据取值
	 * @param $table 		数据表
	 * @return boolean
	 */
	function insert($data, $table, $return_insert_id = false, $replace = false, $silent=0) {
		if(!is_array( $data ) || $table == '' || count($data) == 0) {
			return false;
		}
		
		$fielddata = array_keys($data);
		$valuedata = array_values($data);
		array_walk($fielddata, array($this, 'add_special_char'));
		array_walk($valuedata, array($this, '_escape_string'));
		
		$field = implode (',', $fielddata);
		$value = implode (',', $valuedata);

        $method = $replace?'REPLACE':'INSERT';
        $state = $this->query($method." INTO $table ($field) VALUES ($value)", $silent?'SILENT':'');
        if($return_insert_id && !$replace) {
            return $this->insert_id();
        }else {
            return $state;
        } 
	}
	
    
	/**
	 * 执行更新记录操作
	 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
	 * 						为数组时数组key为字段值，数组值为数据取值
	 * 						为字符串时[例：`name`='kuaifan',`hits`=`hits`+1]。
	 *						为数组时[例: array('name'=>'kuaifan','password'=>'123456')]
	 *						数组可使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
	 * @param $table 		数据表
	 * @param $where 		更新数据时的条件
	 * @return boolean
	 */
	function update($data, $table, $where = '', $silent=0) {
		if($table == '' or $where == '') {
			return false;
		}
		//
		$_where = $where;
		if(empty($where)) {
			$_where = '1';
		} elseif(is_array($where)) {
			$_where = "";
			$comma = "";
			foreach ($where as $key => $value) {
				$_where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
				$comma = ' AND ';
			}
		}
		$where = ' WHERE '.$_where;
		//
		$field = '';
		if(is_string($data) && $data != '') {
			$field = $data;
		} elseif (is_array($data) && count($data) > 0) {
			$fields = array();
			foreach($data as $k=>$v) {
				switch (substr($v, 0, 2)) {
					case '+=':
						$v = substr($v,2);
						if (is_numeric($v)) {
							$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'+'.$this->_escape_string($v, '', false);
						} else {
							continue;
						}
						
						break;
					case '-=':
						$v = substr($v,2);
						if (is_numeric($v)) {
							$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'-'.$this->_escape_string($v, '', false);
						} else {
							continue;
						}
						break;
					default:
						$fields[] = $this->add_special_char($k).'='.$this->_escape_string($v);
				}
			}
			$field = implode(',', $fields);
		} else {
			return false;
		}
		
        return $this->query("UPDATE ".($table)." SET ".$field.$where, $silent?"SILENT":"");
	}
    
	/**
	 * 对字段两边加反引号，以保证数据库安全
	 * @param $value 数组值
	 */
	function add_special_char(&$value) {
		if('*' == $value || false !== strpos($value, '(') || false !== strpos($value, '.') || false !== strpos ( $value, '`')) {
			//不处理包含* 或者 使用了sql方法。
		} else {
			$value = '`'.trim($value).'`';
		}
		if (preg_match("/\b(select|insert|update|delete)\b/i", $value)) {
			$value = preg_replace("/\b(select|insert|update|delete)\b/i", '', $value);
		}
		return $value;
	}
	
	/**
	 * 对字段值两边加引号，以保证数据库安全
	 * @param $value 数组值
	 * @param $key 数组key
	 * @param $quotation 
	 */
	function _escape_string(&$value, $key='', $quotation = 1) {
		$value = rtrim($value, '/\\');
		if ($quotation) {
			$q = '\'';
		} else {
			$q = '';
		}
		$value = $q.$value.$q;
		return $value;
	}


	function run($sql) {
		global $pre;
		if(!isset($sql) || empty($sql)) return;

		$sql = str_replace("\r", "\n", str_replace(array(' kf_', ' $kuaifan$'), ' '.$pre, $sql));
		$sql = str_replace("\r", "\n", str_replace(array(' `kf_', ' `$kuaifan$'), ' `'.$pre, $sql));
		$ret = array();
		$num = 0;
		$sql = preg_replace("/\;[ \f\t\v]+/", ';', $sql);
		foreach(explode(";\n", trim($sql)) as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			foreach($queries as $query_) {
				$ret[$num] .= (isset($query_[0]) && $query_[0] == '#') || (isset($query_[1]) && isset($query_[1]) && $query_[0].$query_[1] == '--') ? '' : $query_;
			}
			$num++;
		}
		unset($sql);
		foreach($ret as $query) {
			$query = trim($query);
			if($query) {
				$this->query($query);
			}
		}
	}

    /** *****************************************************/
    /** ********************新的查询命令***********************/
    /** *****************************************************/

    /**
     * @param $sql
     * @param array $wherearr
     * @param string $ordersql
     * @param $type
     * @return array
     */
    function getone_($sql, $wherearr = array(), $ordersql = '', $type=MYSQL_ASSOC){
        if (strpos(strtolower($sql),'select') === false) {
            $sql = "SELECT * FROM  ".$sql;
        }
        $sql.= $this->where_clause($wherearr);
        if ($ordersql){
            $sql.= " ORDER BY ".$ordersql;
        }
        $query = $this->query($sql);
        return mysql_fetch_array($query, $type);
    }

    /**
     * @param $sql
     * @param array $wherearr
     * @param string $ordersql
     * @param $type
     * @return array
     */
    function getall_($sql, $wherearr = array(), $ordersql = '', $type=MYSQL_ASSOC){
        if (strpos(strtolower($sql),'select') === false) {
            $sql = "SELECT * FROM  ".$sql;
        }
        $sql.= $this->where_clause($wherearr);
        if ($ordersql){
            $sql.= " ORDER BY ".$ordersql;
        }
        $query = $this->query($sql);
        $rows = array();
        while($row = mysql_fetch_array($query,$type)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @param $sql
     * @param array $wherearr
     * @return int
     */
    function get_total_($sql, $wherearr = array()){
        if (strpos(strtolower($sql),'select') === false) {
            $sql = "SELECT * FROM  ".$sql;
        }
        $sql.= $this->where_clause($wherearr);
        $row = $this->getall_($sql);
        $v=0;
        if (!empty($row) && is_array($row)){
            foreach($row as $n){
                $v=$v+$n['num'];
            }
        }
        return $v;
    }

    /**
     * 获取分页列表
     * @param $table 表名称
     * @param string $where 查询条件，默认空
     * @param string $order 排序方式，默认空
     * @param int $row 每页显示，默认10
     * @param int $page 当前页，默认1
     * @param string $field 读取字段名称
     * @return array (total=>总数量,perpage=>每页显示,nowpage=>当前页,totalpage=>总页数,list=>数据列表)
     */
    function getlist_($table, $where='', $order='', $row=10, $page=1, $field='*'){
        if (empty($table)) return array();
        if (!empty($order)) $order = " ORDER BY ".$order;
        $where = $this->where_clause($where);
        $total_sql="SELECT COUNT(*) AS num FROM ".$table." ".$where;
        $total_count = $this->get_total_($total_sql);
        $totalpage = $total_count / $row;
        $totalpage = ($totalpage > intval($totalpage))?intval($totalpage+1):intval($totalpage);
        if ($page > $totalpage) $page = $totalpage;
        if ($page < 1) $page = 1;
        $pagearr = array(
            'total'=>$total_count, //总数
            'perpage'=>$row, //每页显示
            'nowpage'=>$page, //当前页
            'totalpage'=>$totalpage, //总页数
        );
        kf_class::run_sys_class('page','',0);
        $pagelist = new page(array('total'=>$pagearr['total'],'perpage'=>$pagearr['perpage'],'getarray'=>$_GET,'page_name'=>'page'));
        $pagearr['pagehtml'] = $pagelist->show('wap');
        //
        $start = ($page-1)*$row;
        $limit=" LIMIT ".abs($start).','.$row;
        $sql="SELECT ".$field." FROM ".$table." ".$where." ".$order." ".$limit;
        $query = $this->query($sql);
        $list= array();
        $__n= 1;
        while($rows = mysql_fetch_array($query, MYSQL_ASSOC)){
            $rows['_n']=$__n+($page*$row)-$row;
            $__n ++;
            $list[] = $rows;
        }
        $pagearr['list'] = $list;
        return $pagearr;
    }

    /**
     * @param $tablename
     * @param $insertsqlarr
     * @param int $returnid
     * @param bool $replace
     * @param int $silent
     * @return bool
     */
    function insert_($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
        return $this->insert($insertsqlarr, $tablename, $returnid, $replace, $silent);
    }

    /**
     * @param $tablename
     * @param $setsqlarr
     * @param $wheresqlarr
     * @param int $silent
     * @return bool|resource
     */
    function update_($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
        $newdate = $this->data_preg($setsqlarr);
        if ($newdate) {
            return $this->query("UPDATE `".$tablename."` SET ".implode(',', $newdate).$this->where_clause($wheresqlarr));
        }else{
            return $this->update($setsqlarr, $tablename, $wheresqlarr, $silent);
        }
    }

    /**
     * @param $table
     * @param array $where
     * @return resource
     */
    function delete_($table, $where = array()){
        return $this->query('DELETE FROM `'.$table.'` '.$this->where_clause($where));
    }

    protected function data_preg($data) {
        if (empty($data)) return $data;
        $fields = array();
        $isfields = false;
        foreach ($data as $key => $value) {
            preg_match('/([\w]+)(\[(\+|\-|\*|\/)\])?/i', $key, $match);
            if (isset($match[3])) {
                if (is_numeric($value)) {
                    $fields[] = $this->column_quote($match[1]) . ' = ' . $this->column_quote($match[1]) . ' ' . $match[3] . ' ' . $value;
                    $isfields = true;
                }
            }else{
                $column = $this->column_quote($key);
                switch (gettype($value)){
                    case 'NULL':
                        $fields[] = $column . ' = NULL';
                        break;
                    case 'array':
                        preg_match("/\(JSON\)\s*([\w]+)/i", $key, $column_match);
                        if (isset($column_match[0])) {
                            $fields[] = $this->column_quote($column_match[1]) . ' = ' . json_encode($value);
                        }else{
                            $fields[] = $column . ' = ' . serialize($value);
                        }
                        break;
                    case 'boolean':
                        $fields[] = $column . ' = ' . ($value ? '1' : '0');
                        break;
                    case 'integer':
                    case 'double':
                    case 'string':
                        $fields[] = $column . ' = ' . $value;
                        break;
                }
            }
        }
        return $isfields?$fields:array();
    }

    protected function column_quote($string) {
        return '`' . str_replace('.', '"."', preg_replace('/(^#|\(JSON\))/', '', $string)) . '`';
    }

    protected function where_clause($where)
    {
        if (is_array($where)) {
            $where_clause = '1';
            foreach($where AS $key=>$val) {
                if (substr($key,0,4)=='[OR]') {
                    $where_clause.= " OR `".substr($key,4)."`='{$val}' ";
                }elseif (substr($key,0,5)=='[AND]') {
                    $where_clause.= " AND `".substr($key,5)."`='{$val}' ";
                }else{
                    $where_clause.= " AND `{$key}`='{$val}' ";
                }
            }
            if ($where_clause == "1") {
                $where_clause = "";
            }else{
                $where_clause = " WHERE ".$where_clause;
            }
        }else{
            $where_clause = " WHERE ".$where;
        }
        return $where_clause;
    }
}
?>