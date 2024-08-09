<?php
class Db {
    public static $tablename;
    public static $where = null;
    public static $pdo;
    public static $stme;
    public static $executeData;

    public function __construct() {
        self::connect();
        self::setAttr();
    }

    public static function connect() {
        $DS = DIRECTORY_SEPARATOR;
        $config = require dirname(__DIR__).$DS."config".$DS."database.php";
        $dbms = $config['dbms'];
        $host = $config['host'];
        $dbname = $config['dbName'];
        $user = $config['user'];
        $password = $config['password'];
        $port = $config['port'];

        $dsn = "$dbms:host=$host;dbname=$dbname;port=$port;charset=utf8mb4";
        try {
            self::$pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            throw new Exception("数据库链接错误：" . $e->getMessage());
        }
    }

    private static function setAttr() {
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    // 链式操作
    public static function table($tablename) {
        self::$tablename = $tablename;
        return new self();
    }

    function whereOr(Array $condition) {
        return $this->where($condition, 'OR');
    }
    
    function whereNot(Array $condition) {
        return $this->where($condition, 'NOT');
    }
    
    function whereOrNot(Array $condition) {
        return $this->where($condition, 'ORNOT');
    }
    
    public function whereNull($name) {
        $where = "$name is null";
        return $this->buildWhere($where);
    }
    
    public function whereNonNull($name) {
        $where = "$name is not null";
        return $this->buildWhere($where);
    }
    
    // where条件
    public function where(Array $condition, $andOrNot = 'AND') {
        $where = "";
        if (!empty($condition)) {
            $whereArray = [];
            $executeData = [];
            foreach ($condition as $key => $value) {
                if ($value[1] == 'between' || $value[1] == 'BETWEEN') {
                    $whereArray[] = "$value[0] $value[1] ? AND ?";
                    $executeData[] = $value[2][0];
                    $executeData[] = $value[2][1];
                } else if ($value[1] == 'in' || $value[1] == 'IN') {
                    $str = rtrim(str_repeat('?,', count($value[2])), ',');
                    $whereArray[] = "$value[0] $value[1] ($str)";
                    foreach ($value[2] as $v) {
                        $executeData[] = $v;
                    }
                } else {
                    $whereArray[] = "$value[0] $value[1] ?";
                    $executeData[] = $value[2];
                }
            }
            
            if (!in_array($andOrNot, ['NOT', 'ORNOT'])) 
            {
                $where = implode(" $andOrNot ", $whereArray);
            } else {
                if ($andOrNot == 'ORNOT') {
                    $where = implode(" OR ", $whereArray);
                } 
                else {
                    $where = implode(' AND ', $whereArray);
                }
                    $where = 'NOT (' . $where . ')';
            }


            // 将数组元素组合为字符串
            if (isset(self::$executeData)) {
                self::$executeData = array_merge(self::$executeData, $executeData);
            } else {
                self::$executeData = $executeData;
            }
        }
        $this->buildWhere($where, $andOrNot);
        return $this;
    }

    public function buildWhere($where, $andOrNot = 'AND') {
        $oldwhere = self::$where;
        if ($where !== '') {
            if (is_null($oldwhere)) {
                // 如果 oldwhere 是 null，直接添加 WHERE
                $where = ' WHERE ' . $where;
            } else {
                // 如果 oldwhere 不为空，检查是否包含 WHERE
                if (strpos($oldwhere, 'WHERE') === false) {
                    $where = ' WHERE ' . $oldwhere . ' ' . $andOrNot . ' ' . $where;
                } else {
                    $where = $oldwhere . ' ' . $andOrNot . ' ' . $where;
                }
            }
            self::$where = $where;
        }
    }

    // 查询
    public function select() {
        $sql = "SELECT * FROM " . self::$tablename . self::$where;
        // 预处理
        // echo $sql;
        $stmt = self::$pdo->prepare($sql);
        if (isset(self::$executeData)) {
            $stmt->execute(self::$executeData);
        } else {
            $stmt->execute();
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    //插入
    public function insert(Array $data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $str = rtrim(str_repeat('?,', count($keys)), ',');
        $sql = "INSERT INTO " . self::$tablename . " (" . implode(',', $keys) . ") VALUES ($str)";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($values);
        $stmt->closeCursor();
        return self::$pdo->lastInsertId();
    }
    //根据username查询
}

// 使用示例
 //$db = new Db();
 //$data = Db::table('users')->whereNot([["id", "in", [1, 2, 3]]])->whereOr([["createtime", "=", "55"]])->select();
 //$data = Db::table('users')->insert(['username' => 'test1111', 'password' => '123456']);
 //echo json_encode($data);
//  $username = 'j22123hy';
//  $db = new Db();
//  $user = $db->table('users')->where([['username','=', "$username"]])->select();
// // 检查查询结果
// if (empty($user)) {
//     echo "没有找到匹配的用户。\n";
// } else {
//     echo "查询成功，用户数据: \n";
//     // 使用 print_r 打印用户数据
//     print_r($user);
// }

?>