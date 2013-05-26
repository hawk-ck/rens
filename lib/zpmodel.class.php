<?php

namespace Rens;

use \mysqli;

class ZPModel {
    private $_dbHandle;
    private $_result;
    private $_query;

    protected $_table;  
    protected $_limit;
    protected $_page;
	
	function __construct($model){
		$this->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$this->setTable($model);
	}

	public function connect($host, $user, $passwd, $name){
		$this->_dbHandle = new mysqli($host, $user, $passwd, $name);
		if ($this->_dbHandle->connect_errno){
			throw new RensException('An error occcured connecting to the database.');
		}
		if (!$this->_dbHandle->set_charset("utf8")){
			throw new RensException('Error loading character set utf8');
		}
	}
	
	public function disconnect(){
		$this->_dbHandle->close();
	}
	
	/* functions to set protected values: _table, _limit, _page */
	
	/**
	 *
	 * 设置table
	 *
	 * @param unknown $table
	 * @return string
	 *
	 * $table规则：
	 * 		arr: 改写成多表名形式
	 * 		str: 直接作为单表名
	 */
	public function setTable($table){
		$mkTable = null;
		if (is_array($table)){
			foreach($table as $t){
				$mkTable .= $this->_Value($t) . ', ';
			}
			$this->_table = substr($mkTable, 0, -2);
		} elseif(is_string($table)){
			$this->_table = $this->_Value($table);
		} else {
			//error: table error
			return 'null';
		}
	}
	
	public function setPage($page){
		if (is_numeric($page)){
			$this->_page = $page;
		}
	}
	 
	/**
	 * 直接输入sql语句处理
	 */
	public function common($query){
		$this->_query = $query;
		return $this->_getData($this->_query);
	}
	
	/**
	 * 总页数查询语句
	 *
	 * @return number
	 *
	 * 规则：需要设置_limit和_query，因为基于_query修改查询语句
	 */
	public function totalPages(){
		if (isset($this->_query, $this->_limit)){
			$pattern = '/SELECT (.*?) FROM (.*)LIMIT(.*)/i';
			$replacement = 'SELECT COUNT(*) FROM $2';
			$totalPagesQuery = preg_replace($pattern, $replacement, $this->_query);
	
			$result = $this->_getData($totalPagesQuery);
			$totalPages = ceil($result[0]['COUNT(*)']/$this->_limit);
			return $totalPages;
		} else {
			return -1;
		}
	}
	
	/**
	 * 查询语句
	 *
	 * @param mixed $where
	 * @param mixed $order
	 * @param mixed $limit
	 * @param int $offset
	 * @return array
	 */
	public function search($where = null, $order = null, $limit = PAGINATE_LIMIT, $offset = null){
		$this->_query = 'SELECT * FROM ' . $this->_table .
		$this->_Where($where) . $this->_Order($order) . $this->_Limit($limit) . $this->_Offset($offset);
	
		return $this->_getData($this->_query);
	}
	
	/**
	 * where子句
	 *
	 * @param mixed $where
	 * @return string|NULL
	 *
	 * $where规则：
	 *     str：任何超出预定执行能力的查询可通过string直接传递，注意安全性。目前只提供in和=，通过and连接的规则
	 *     arr：
	 *         key为str：
	 *             var为arr：key为条件，var为IN规则
	 *             var为str：key为条件，var为=规则
	 */
	protected function _Where($where){
		$sql = ' WHERE ';
		if (is_string($where)){
			return $sql . $where;
		} elseif (is_array($where)){
			foreach ($where as $key => $var){
				if (is_string($key)){
					if (is_array($var)){
						foreach ($var as $inKey => $inVar){
							$var[$inKey] = $this->_Value($inVar);
						}
						$sql .= $key . ' IN (' . implode(', ', $var) . ')';
					} else {
						$sql .= $key . ' = ' . $this->_Value($var);
					}
				} else {
					continue;
				}
				$sql .= ' AND ';
			}
			$sql = substr($sql, 0, -5);
			return $sql;
		} elseif (!isset($where)){
			return '';
		}
		return null;
	}
	
	/**like条件设置，where子句深入**/
	protected function like($field, $value){
		$this->_extraConditions .= '`'.$this->_table.'`.`'.$field.'` LIKE \'%'.$this->_Value($value).'%\' AND ';
	}
	
	/**
	 * order子句
	 *
	 * @param mixed $order
	 * @return string|NULL
	 *
	 * $order规则：
	 *     str：一个条件，正序
	 *     arr：
	 *         key为num：var为条件（str），正序
	 *         key为str：key为条件；var为规则（bool），Ture为正序，False为逆序
	 */
	protected function _Order($order){
		$sql = ' ORDER BY ';
		if (is_string($order)){
			return $sql . $order;
		} elseif (is_array($order)){
			foreach ($order as $key => $var){
				if (is_numeric($key) && is_string($var)){
					$sql .= $var;
				} elseif (is_string($key) && is_bool($var)){
					$sql .= $key;
					if (!$var){
						$sql .= ' DESC';
					}
				} else {
					continue;
				}
				$sql .= ', ';
			}
			$sql = substr($sql, 0, -2);
			return $sql;
		}
		return null;
	}
	
	/**
	 * limit子句
	 *
	 * @param mixed $limit
	 * @return string|NULL
	 *
	 * $limit规则：
	 *     int：则直接作为条件
	 *     arr：作为(offset,limit)使用
	 */
	protected function _Limit($limit){
		if (is_numeric($limit)){
			$this->_limit = $limit;
			return ' LIMIT ' . $this->_limit;
		} elseif (is_array($limit)){
			if (count($limit)==2 && is_numeric($limit[0]) && is_numeric($limit[1])){
				return ' LIMIT ' . $limit[0] . ', ' . $limit[1];
			}
		}
		return null;
	}
	
	/**
	 * offset子句
	 *
	 * @param int $offset
	 * @return string|NULL
	 *
	 * 规则：
	 *     1.设置了_limit和_page，则计算得到offset
	 *     2.否则直接使用offset参数
	 */
	protected function _Offset($offset){
		if (isset($this->_page, $this->_limit)){
			$c_offset = ($this->_page - 1) * $this->_limit;
			return ' OFFSET ' . $c_offset;
		} elseif (is_numeric($offset)){
			return ' OFFSET ' . $offset;
		}
		return null;
	}
	
	/**
	 * SQL参数过滤
	 *
	 * @param mixed $value
	 * @return string
	 */
	protected function _Value($value){
		if (is_numeric($value)){
			return (string) $value;
		} elseif (is_string($value)){
			return '`' . $this->_dbHandle->real_escape_string($value) . '`';
		} else {
			return 'null';
		}
	}
	
	protected function _getData($query, $mapData = TRUE){
		$this->_result = $this->_dbHandle->query($query);
		if ($this->_result === false || $this->_dbHandle->errno != 0){
			throw new RensException('An error occurred when execute query');
		}
	
		if ($mapData){
			$result = array();
			$table = array();
			$field = array();
	
			if ($this->_result->num_rows){
				while ($row = $this->_result->fetch_assoc()){
					$result[] = $row;
				}
			}
	
			$this->_result->free();
			return $result;
		} else {
			return 1;
		}
	}
	
	function __destruct(){
		$this->disconnect();
	}
}