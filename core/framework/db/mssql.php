<?php
/**
 * mysqli驱动
 *
 *
 * @package    db
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @author       ShopNC Team
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');


class Db
{

    private static $link = array();

    private static $iftransacte = true;


    private function __construct()
    {
        if (!extension_loaded('PDO')) {
            throw_exception("Db Error: sqlserver is not install");
        }
    }

    private static function connect($host = 'sqlserver')
    {
        $host = 'sqlserver';
        $conf = C('db.' . $host);
        if (is_object(self::$link[$host])) return;
        try {
            self::$link[$host] = @new PDO('sqlsrv:Server=' . $conf['dbhost'] . ',' . $conf['dbport'] . ';Database=' . $conf['dbname'], $conf['dbuser'], $conf['dbpwd']);
            self::$link[$host]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Db Error: database connect failed');
        }
    }

    /**
     * 执行查询
     *
     * @param string $sql
     * @return mixed
     */
    public static function query($sql, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (C('debug')) addUpTime('queryStartTime');
        $lowstr = strtolower($sql);
        //处理 limit
        $limitindex = strpos($lowstr, ' limit ');
        if ($limitindex > 0) {
            $limitstr = strtolower(trim(substr($sql, $limitindex)));
            $limits = explode(' ', $limitstr);
            $limitnum = explode(',', $limits[1]);

            //得到主键用来orderby
            $orderindex = strpos($lowstr, ' order ');
            $fromindex = strpos($lowstr, ' from ');
            $whereindex = strpos($lowstr, ' left join ',$fromindex);
            if($whereindex<=0){
                $whereindex = strpos($lowstr, ' where ');
            }
            if($whereindex<=0){
                $whereindex = strpos($lowstr, ' order ',$fromindex);
            }
            if($whereindex<=0){
                $whereindex = strpos($lowstr, ' group ',$fromindex);
            }
            if($whereindex<=0){
                $whereindex = $limitindex;
            }
            $tableastr = substr($sql, $fromindex + 6, $whereindex - $fromindex - 6);
            $tmp_table = explode(',', $tableastr);
            for ($i = 0; $i < count($tmp_table); $i++) {
                $tmp_strs = explode('.', str_replace('`', '', $tmp_table[$i]));
                if (count($tmp_strs) >= 2) {
                    $tmp_table[$i] = $tmp_strs[1];
                } else {
                    $tmp_table[$i] = $tmp_strs[0];
                }
            }
            if ($orderindex && $orderindex > 0) {
                $byindex = strpos($lowstr, ' by ', $orderindex);
                $orderby = substr($sql, $byindex + 4, $limitindex - $byindex - 4);
            } else {
                if(strpos($tmp_table[0],MS_DBPRE)<=0){
                    $tmp_table[0] = MS_DBPRE.$tmp_table[0];
                }
                $result = self::$link[$host]->query('exec sp_pkeys ' .  explode(' ', $tmp_table[0])[0] );
                $keys = [];
                while ($tmp = $result->fetch(PDO::FETCH_OBJ)) {
                    $keys[] = $tmp->COLUMN_NAME;
                }
                $orderby = implode(',', $keys);
            }
            $selectindex = strpos($lowstr, 'select ');

            if($whereindex<=0){
                $sql = substr($sql,0,$fromindex+6) . implode(',',$tmp_table);
            }else{
                $sql = substr($sql,0,$fromindex+6) . implode(',',$tmp_table).substr($sql,$whereindex);
            }
            $limitindex = strpos(strtolower($sql), ' limit ');
            $sql = substr($sql, $selectindex + 7, $limitindex - $selectindex - 7);

            if (count($limitnum) > 1) {
                $start = intval($limitnum[0]);
                $len = intval($limitnum[1]);
                $end = $start+$len;
                $sql = 'select * from ( SELECT TOP ' . $end . ' row_number() OVER (order by ' . $orderby . ') rownum,  ' . $sql . ')tb WHERE   tb.rownum  > ' . $start . ' ORDER BY tb.rownum ASC  ';
            } else {
                $start = 0;
                $end = intval($limitnum[0]);
                $sql = ' SELECT TOP ' . $end . '    ' . $sql . ' ';
            }

        }
        //处理 concat
        $sql = str_replace('FROM_UNIXTIME(','(',$sql);
        $sql = str_replace('HOUR(','datepart(hour,',$sql);
        $sql = str_replace('!(','not(',$sql);

        $query = self::$link[$host]->query($sql);
        if (C('debug')) addUpTime('queryEndTime');
        if ($query === false) {
            $error = 'Db Error: ' . self::$link[$host]->errorInfo();
            if (C('debug')) {
                throw_exception($error . '<br/>' . $sql);
            } else {
                Log::record($error . "\r\n" . $sql, Log::ERR);
                return false;
            }
        } else {
            Log::record($sql . " [ RunTime:" . addUpTime('queryStartTime', 'queryEndTime', 6) . "s ]", Log::SQL);
            return $query;
        }
    }
    /**
     * 取得数组
     *
     * @param string $sql
     * @return bool/null/array
     */
    public static function getAll($sql, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $result = self::query($sql, $host);
        if ($result === false) return array();
        $array = array();
        while ($tmp = $result->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $tmp;
        }
        return !empty($array) ? $array : null;
    }

    /**
     * SELECT查询
     *
     * @param array $param 参数
     * @param object $obj_page 分类对象
     * @return array
     */
    public static function select($param, $obj_page = '', $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        static $_cache = array();

        if (empty($param)){
            throw_exception('Db Error: select param is empty!');
        }
        if (empty($param['field'])){
            $param['field'] = '*';
        }
        if (empty($param['count'])){
            $param['count'] = 'count(*)';
        }
        if (isset($param['index'])){
            $param['index'] = 'USE INDEX ('.$param['index'].')';
        }
        if (trim($param['where']) != ''){
            if (strtoupper(substr(trim($param['where']),0,5)) != 'WHERE'){
                if (strtoupper(substr(trim($param['where']),0,3)) == 'AND'){
                    $param['where'] = substr(trim($param['where']),3);
                }
                $param['where'] = 'WHERE '.$param['where'];
            }
        }
        $param['where_group'] = '';
        if (!empty($param['group'])){
            $param['where_group'] .= ' group by '.$param['group'];
        }
        $param['where_order'] = '';
        if (!empty($param['order'])){
            $param['where_order'] .= ' order by '.$param['order'];
        }
        //判断是否是联表查询
        $tmp_table = explode(',',$param['table']);
        if (!empty($tmp_table) && count($tmp_table) > 1){
            if ((count($tmp_table)-1) != count($param['join_on'])){
                throw_exception('Db Error: join number is wrong!');
            }
            foreach($tmp_table as $key=>$val){
                $tmp_table[$key] = trim($val) ;
            }
            //拼join on 语句
            for ($i=1;$i<count($tmp_table);$i++){
                $tmp_sql .= $param['join_type'].' ['.MS_DBPRE.$tmp_table[$i].'] ['.$tmp_table[$i].'] ON '.$param['join_on'][$i-1].' ';
            }
            $sql = 'SELECT '.$param['field'].' FROM ['.MS_DBPRE.$tmp_table[0].'] ['.$tmp_table[0].'] '.$tmp_sql.' '.$param['where'].$param['where_group'].$param['where_order'];

            //如果有分页，计算信息总数
            $count_sql = 'SELECT '.$param['count'].' as count FROM ['.MS_DBPRE.$tmp_table[0].'] ['.$tmp_table[0].'] '.$tmp_sql.' '.$param['where'].$param['where_group'];
        }else {
            $sql = 'SELECT '.$param['field'].' FROM ['.MS_DBPRE.$param['table'].'] as ['.$param['table'].'] '.$param['index'].' '.$param['where'].$param['where_group'].$param['where_order'];
            $count_sql = 'SELECT '.$param['count'].' as count FROM ['.MS_DBPRE.$param['table'].'] as ['.$param['table'].'] '.$param['index'].' '.$param['where'].$param['where_group'];
        }

        //limit ，如果有分页对象的话，那么优先分页对象
        if ($obj_page instanceof Page ){
            $count_query = self::query($count_sql);
            $count_fetch = $count_query->fetchAll(PDO::FETCH_ASSOC);
            $obj_page->setTotalNum($count_fetch['count']);
            $param['limit'] = $obj_page->getLimitStart().",".$obj_page->getEachNum();
        }
        if ($param['limit'] != ''){
            $sql .= ' limit '.$param['limit'];
        }
        if ($param['cache'] !== false){
            $key =  is_string($param['cache_key'])?$param['cache_key']:md5($sql);
            if (isset($_cache[$key])) return $_cache[$key];
        }
        echo $sql;
        $result = self::query($sql,$host);
        while ($tmp=$result->fetch(PDO::FETCH_ASSOC)){
            $array[] = $tmp;
        }
        if ($param['cache'] !== false && !isset($_cache[$key])){
            $_cache[$key] = $array;
        }

        return $array;
    }

    /**
     * 插入操作
     *
     * @param string $table_name 表名
     * @param array $insert_array 待插入数据
     * @return mixed
     */
    public static function insert($table_name, $insert_array = array(), $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (!is_array($insert_array)) return false;
        $fields = array();
        $value = array();
        foreach ($insert_array as $key => $val) {
            $fields[] = self::parseKey($key);
            $value[] = self::parseValue($val);
        }
        $sql = 'INSERT INTO [' . MS_DBPRE . $table_name . '] (' . implode(',', $fields) . ') VALUES(' . implode(',', $value) . ')';

        //当数据库没有自增ID的情况下，返回 是否成功
        $result = self::query($sql, $host);
        $insert_id = self::getLastId($host);
        return $insert_id ? $insert_id : $result;
    }

    /**
     * 批量插入
     *
     * @param string $table_name 表名
     * @param array $insert_array 待插入数据
     * @return mixed
     */
    public static function insertAll($table_name, $insert_array = array(), $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (!is_array($insert_array[0])) return false;
        $fields = array_keys($insert_array[0]);
        array_walk($fields, array(self, 'parseKey'));
        $values = array();
        foreach ($insert_array as $data) {
            $value = array();
            foreach ($data as $key => $val) {
                $val = self::parseValue($val);
                if (is_scalar($val)) {
                    $value[] = $val;
                }
            }
            $values[] = '(' . implode(',', $value) . ')';
        }
        $sql = 'INSERT INTO [' . MS_DBPRE . $table_name . '] (' . implode(',', $fields) . ') VALUES ' . implode(',', $values);
        $result = self::query($sql, $host);
        $insert_id = self::getLastId($host);
        return $insert_id ? $insert_id : $result;
    }

    /**
     * 更新操作
     *
     * @param string $table_name 表名
     * @param array $update_array 待更新数据
     * @param string $where 执行条件
     * @return bool
     */
    public static function update($table_name, $update_array = array(), $where = '', $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (!is_array($update_array)) return false;
        $string_value = '';
        foreach ($update_array as $k => $v) {
            if (is_array($v)) {
                switch ($v['sign']) {
                    case 'increase':
                        $string_value .= " $k = $k + " . $v['value'] . ",";
                        break;
                    case 'decrease':
                        $string_value .= " $k = $k - " . $v['value'] . ",";
                        break;
                    case 'calc':
                        $string_value .= " $k = " . $v['value'] . ",";
                        break;
                    default:
                        $string_value .= " $k = " . self::parseValue($v['value']) . ",";
                }
            } else {
                $string_value .= " $k = " . self::parseValue($v) . ",";
            }
        }

        $string_value = trim(trim($string_value), ',');
        if (trim($where) != '') {
            if (strtoupper(substr(trim($where), 0, 5)) != 'WHERE') {
                if (strtoupper(substr(trim($where), 0, 3)) == 'AND') {
                    $where = substr(trim($where), 3);
                }
                $where = ' WHERE ' . $where;
            }
        }
        $sql = 'UPDATE [' . MS_DBPRE . $table_name . ']  SET ' . $string_value . ' ' . $where;
        $result = self::query($sql, $host);
        return $result;
    }

    /**
     * 删除操作
     *
     * @param string $table_name 表名
     * @param string $where 执行条件
     * @return bool
     */
    public static function delete($table_name, $where = '', $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (trim($where) != '') {
            if (strtoupper(substr(trim($where), 0, 5)) != 'WHERE') {
                if (strtoupper(substr(trim($where), 0, 3)) == 'AND') {
                    $where = substr(trim($where), 3);
                }
                $where = ' WHERE ' . $where;
            }
            $sql = 'DELETE FROM [' . MS_DBPRE . $table_name . '] ' . $where;
            return self::query($sql, $host);
        } else {
            throw_exception('Db Error: the condition of delete is empty!');
        }
    }

    /**
     * 取得上一步插入产生的ID
     *
     * @return int
     */
    public static function getLastId($host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $id = self::query('SELECT @@IDENTITY as id', $host)->fetchall(PDO::FETCH_NUM);
        return $id;
    }

    /**
     * 取得一行信息
     *
     * @param array $param
     * @param string $fields
     * @return array
     */
    public static function getRow($param, $fields = '*', $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $table = $param['table'];
        $wfield = $param['field'];
        $value = $param['value'];

        if (is_array($wfield)) {
            $where = array();
            foreach ($wfield as $k => $v) {
                if(is_numeric($value[$k])){
                    $where[] = $v . "=" . $value[$k] . "";
                }else{
                    $where[] = $v . "='" . $value[$k] . "'";
                }
            }
            $where = implode(' and ', $where);
        } else {
            if(is_numeric($value)){
                $where = $wfield . "=" . $value . "";
            }else{
                $where = $wfield . "='" . $value . "'";
            }
        }

        $sql = "SELECT " . $fields . " FROM [" . MS_DBPRE . $table . "] WHERE " . $where;
        $result = self::query($sql, $host)->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * 执行REPLACE操作
     *
     * @param string $table_name 表名
     * @param array $replace_array 待更新的数据
     * @return bool
     */
    public static function replace($table_name, $replace_array = array(), $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        if (!empty($replace_array)) {
            foreach ($replace_array as $k => $v) {
                $string_field .= " $k ,";
                $string_value .= " '" . $v . "',";
            }
            $sql = ' insert INTO [' . MS_DBPRE . $table_name . '] (' . trim($string_field, ', ') . ') VALUES(' . trim($string_value, ', ') . ')';
            return self::query($sql, $host);
        } else {
            return false;
        }
    }

    /**
     * 返回单表查询记录数量
     *
     * @param string $table 表名
     * @param $condition mixed 查询条件，可以为空，也可以为数组或字符串
     * @return int
     */
    public static function getCount($table, $condition = null, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);

        if (!empty($condition) && is_array($condition)) {
            $where = '';
            foreach ($condition as $key => $val) {
                self::parseKey($key);
                $val = self::parseValue($val);
                $where .= ' AND ' . $key . '=' . $val;
            }
            $where = ' WHERE ' . substr($where, 4);
        } elseif (is_string($condition)) {
            if (strtoupper(substr(trim($condition), 0, 3)) == 'AND') {
                $where = ' WHERE ' . substr(trim($condition), 4);
            } else {
                $where = ' WHERE ' . $condition;
            }
        }
        $sql = 'SELECT COUNT(*) as [count] FROM [' . MS_DBPRE . $table . '] [' . $table . '] ' . (isset($where) ? $where : '');
        $result = self::query($sql, $host)->fetchall(PDO::FETCH_NUM);
        return $result['count'];
    }

    /**
     * 执行SQL语句
     *
     * @param string $sql 待执行的SQL
     * @return
     */
    public static function execute($sql, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $result = self::query($sql, $host);
        return $result;
    }

    /**
     * 列出所有表
     *
     * @return array
     */
    public static function showTables($host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $sql = ' select name from Sysobjects where xtype=\'U\' order by name';
        $result = self::query($sql, $host);
        $array = array();
        while ($tmp = $result->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $tmp;
        }
        return $array;
    }

    /**
     * 显示建表语句
     *
     * @param string $table
     * @return string
     */
    public static function showCreateTable($table, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $sql = 'SHOW CREATE TABLE ' . $GLOBALS['config']['tablepre'] . $table;
        $result = self::query($sql, $host);
        $result = $result->fetchall(PDO::FETCH_ASSOC);
        return $result['Create Table'];
    }

    /**
     * 显示表结构信息
     *
     * @param string $table
     * @return array
     */
    public static function showColumns($table, $host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
        $sql = " Select a.name 'name' , b.name 'type',
             isnullable 'null' ,
            sm.TEXT 'default',
             case when i.id is null then 0 else 1 end 'primary' ,
            columnproperty(a.id, a.name, 'IsIdentity') 'autoinc'
            from syscolumns  a
            LEFT JOIN syscomments sm ON a.cdefault = sm.id
            left join sysindexkeys k  on k.id = a.id
            left join sysindexes i inner join sysobjects o on  o.name = i.name and o.xtype='PK'
              on i.id=a.id and k.colid = a.colid
             ,systypes b
            Where a.ID=OBJECT_ID('" . $GLOBALS['config']['tablepre'] . $table . "') and a.xtype = b.xtype
            and  b.name <>'sysname'";
        $result = self::query($sql, $host);
        $array = array();
        while ($tmp = $result->fetch(PDO::FETCH_ASSOC)) {
            $array[$tmp['Field']] = array(
                'name' => $tmp['name'],
                'type' => $tmp['type'],
                'null' => $tmp['null'],
                'default' => $tmp['default'],
                'primary' => $tmp['primary'],
                'autoinc' => $tmp['autoinc'],
            );
        }
        return $array;
    }

    /**
     * 取得服务器信息
     *
     * @return string
     */
    public static function getServerInfo($host = 'sqlserver')
    {
        $host = 'sqlserver';
        self::connect($host);
//        $result = mysqli_get_server_info(self::$link[$host]);
        $result= null;
        return $result;
    }

    /**
     * 格式化字段
     *
     * @param string $key 字段名
     * @return string
     */
    public static function parseKey(&$key)
    {
        $key = trim($key);
        if (!preg_match('/[,\'\"\*\(\)`.\s]/', $key)) {
            $key = '[' . $key . ']';
        }
        return $key;
    }

    /**
     * 格式化值
     *
     * @param mixed $value
     * @return mixed
     */
    public static function parseValue($value)
    {
        $value = addslashes(stripslashes($value)); //重新加斜线，防止从数据库直接读取出错
        return "'" . $value . "'";
    }

    public static function beginTransaction($host = 'sqlserver')
    {
        self::connect($host);
        if (self::$iftransacte) {
            self::$link[$host]->autocommit(false); //关闭自动提交
        }
        self::$iftransacte = false;
    }

    public static function commit($host = 'sqlserver')
    {
        if (!self::$iftransacte) {
            $result = self::$link[$host]->commit();
            self::$link[$host]->autocommit(true); //开启自动提交
            self::$iftransacte = true;
            if (!$result) throw_exception("Db Error: " . self::$link[$host]->errorInfo());
        }
    }

    public static function rollback($host = 'sqlserver')
    {
        if (!self::$iftransacte) {
            $result = self::$link[$host]->rollback();
            self::$link[$host]->autocommit(true);
            self::$iftransacte = true;
            if (!$result) throw_exception("Db Error: " . self::$link[$host]->errorInfo());
        }
    }
}
