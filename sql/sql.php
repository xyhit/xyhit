<?php
//数据库的操作CURD接口

//数据库连接配置
$DBMS = 'mysql';
$HOST = 'localhost';
$DBNAME = 'xyhit';
$USER = 'root';
$PASSWORD = '123456';
$DSN = "$DBMS:host=$HOST;dbname=$DBNAME";

//连接数据库
function connect(){
    global $DSN;
    global $USER;
    global $PASSWORD;
    try{
        //echo $DSN;
        $dbh = new PDO($DSN, $USER, $PASSWORD);
        return $dbh;
    } catch(PDOException $e){
        //echo "Error!: ".$e->getMessage()."<br/>";
        die();
    }
}

function insert($tableName, $values){
    //返回值:成功返回0，否则-1
    //array(0, '')
    //构造SQL插入语句
    //$insert_text = "insert into $tableName";
    $cols = "(";
    $value = "(";
    $map = array();
    foreach($values as $k=>$v){
        $cols .= "$k,";
        $value .= ":$k,";
        $map[":$k"] = $v;
    }
    $cols = substr($cols, 0, -1);
    $cols .= ")";
    $value = substr($value, 0, -1);
    $value .= ")";
    $text = "INSERT INTO $tableName $cols VALUES $value";
    //echo $text;
    //print_r($map);
    $dbh = connect();
    try{
        $sth = $dbh->prepare($text);
        $ret = $sth->execute($map);
        $dbh = null;
        return array(0, $ret);
    }catch (Exception $e){
        $dbh->rollback();
        //echo "Failed: ".$e->getMessage();
        $dbh = null;
        return array(-1, $e->getMessage()
        );
    }
}
/*insert('think_message',array(
    'id'=>'12',
    'datetime_t'=>'',
    'name'=>'zjp',
    'content'=>'php insert'
));*/
function query($tableName, $select_t, $condition){
    //构造SQL查询语句
    //用法： select $select_t from $tableName where $condition
    /*
    query(
        'think_user', 
        array('openid', 'name', 'city'),
        array(
            'openid'=>'012345'
        )
    )
    */
    //返回值：
    //  成功返回array(0, results)
    //  失败返回array(-1, error_info)
    $select = "";
    foreach($select_t as $col){
        $select .= "$col,";
    }
    $select = substr($select, 0, -1);

    $where = "1=1";
    $map = array();
    foreach($condition as $k=>$v){
        $where .= " and $k=:$k";
        $map[":$k"] = $v;
    }
    if(strlen($where)  <= 3){
        $where = '1!=1';
    }
    $text = "SELECT $select FROM $tableName WHERE $where";
    //echo $where.'<br/>';
    //print_r($map);
    //echo '<br/>';
    //连接数据库并查询
    $dbh = connect();
    try{
        $sth = $dbh->prepare($text, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($map);
        $results = $sth->fetchAll();
        $dbh = null;
        //print_r($results);
        return array(0, $results);
    }catch (Exception $e){
        $dbh = null;
        return array(-1, $e->getMessage());
    }
}
/*
query('think_user', 
    array('openid', 'name', 'city'),
    array(
        'openid'=>'012345'
    )
    )
    */
function update($tableName, $set_value, $condition){
    //函数UPDATE用法:
    //UPDATE $tableName SET $set_value WHERE $condition
    /*
    update(
        'think_user',
        array(
            'name'=>'new_ZJP'
        ), 
        array(
            'openid'=>'012345'
        )
    );
    */
    //返回值：
    //  成功返回array(0, count) //count是影响的记录数
    //  失败返回array(-1, error_info)
    $where = '1=1';
    $map = array();
    foreach($condition as $k=>$v){
        $where .= " and $k=:$k";
        $map[":$k"] = $v;
    }
    if(strlen($where)  <= 3){
        $where = '1!=1';
    }

    $set = '';
    foreach($set_value as $k=>$v){
        $set .= "$k=:new_$k";
        $map[":new_$k"] = $v;
    }

    //print_r($where);echo '<br/>';
    //print_r($set);echo '<br/>';
    //print_r($map);echo '<br/>';

    $text = "UPDATE $tableName SET $set WHERE $where";
    //print_r($text);
    $dbh = connect();
    try{
        $sth = $dbh->prepare($text);
        $ret = $sth->execute($map);
        $dbh = null;
        //print_r($results);
        return array(0, $ret);
    }catch (Exception $e){
        $dbh = null;
        return array(-1, $e->getMessage());
    }
}
/*
$result = update(
    'think_user',
    array(
        'name'=>'new_ZJP'
    ), 
    array(
        'openid'=>'012345'
    )
);
print_r($result);
*/
function delete($tableName, $condition){
    //用法：DELETE FROM $tableName WHERE $condition
    /*
    delete('think_user',
        array(
            'openid'=>'012345'
        )
    )
    */
    //返回值：
    //  成功返回array(0, count) //count是影响的记录数
    //  失败返回array(-1, error_info)
    $where = '1=1';
    $map = array();
    foreach($condition as $k=>$v){
        $where .= " and $k=:$k";
        $map[":$k"] = $v;
    }
    if(strlen($where)  <= 3){
        $where = '1!=1';
    }
    //print_r($where);echo '<br/>';
    //print_r($map);echo '<br/>';

    $text = "DELETE FROM $tableName WHERE $where";
    //print_r($text);
    $dbh = connect();
    try{
        $sth = $dbh->prepare($text);
        $ret = $sth->execute($map);
        $dbh = null;
        //print_r($ret);
        return array(0, $ret);
    }catch (Exception $e){
        $dbh = null;
        return array(-1, $e->getMessage());
    }
}
/*
delete('think_user',
    array(
        'openid'=>'012345'
    )
)
*/
//插入
$ret = insert('think_user',
    array(
        'openid'=>'012345',
        'name'=>'ZJP',
        'nickname'=>'ZJP0',
        'gender'=>'M',
        'country'=>'China',
        'province'=>'Anhui',
        'city'=>'Fuyang',
        'registertime'=>'2016-09-29',
        'degree'=>'B',
        'company'=>'Google',
        'position'=>'CTO'
    )
);
echo '<br/>insert return: ';
print_r($ret);

//更新
$ret = update(
    'think_user', 
    array(
        'name'=>'newZJP'
    ),
    array(
        'openid'=>'012345'
    )
);
echo '<br/>update return: ';
print_r($ret);

//查询
$ret = query('think_user', 
    array('openid', 'name', 'city'),
    array(
        'openid'=>'012345'
    )
);
echo '<br/>query return: ';
print_r($ret);

//删除
$ret = delete('think_user',
    array(
        'openid'=>'012345'
    )
);
echo '<br/>delete return: ';
print_r($ret);

?>










