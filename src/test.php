<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 11/28/2016
 * Time: 21:32
 */
namespace foo\bar;

use gears\accounts\Employee;
trait ClassName {
    public static function getName() {
        echo '------------' . PHP_EOL;
        echo __CLASS__ . PHP_EOL;
        // echo substr(__CLASS__, strrpos(__CLASS__, '\\') + 1).PHP_EOL;
        echo basename(str_replace('\\', '/', __CLASS__)) . PHP_EOL;
        echo basename(str_replace('\\', '/', get_called_class())) . PHP_EOL;
    }


    public static function getShortName() {
        echo '------------' . PHP_EOL;
        echo __CLASS__ . PHP_EOL;
        // echo substr(__CLASS__, strrpos(__CLASS__, '\\') + 1).PHP_EOL;
        $path = explode('\\', __CLASS__);
        echo array_pop($path) . PHP_EOL;
    }
}

class Test {
    use ClassName;
}

class ChildTest extends Test {
    public static function getColumns() {
        $allCols = ['phone_number', 'first_name', 'last_name', 'is_manager', 'state'];
        $cols = '';
        $table = 'employee';
        foreach ($allCols as $col) {
            $cols = "$cols $col = ?,";
        }
        $cols = rtrim($cols);
        $sql = "UPDATE $table SET $cols WHERE emp_id = ?";
        echo $sql;
    }

    public static function genString() {
        $cols = ['phone_number', 'first_name', 'last_name', 'is_manager', 'state'];
        $c = count($cols);
        $s = '';
        for ($id = 0; $id < $c; $id++) {
            $s = "$s ?,";
        }
        $s = rtrim($s, ',');
        echo $s;
    }

    public static function getValues() {
        $cols = ['phone_number'=>1, 'first_name'=>2, 'last_name'=>5, 'is_manager'=>0, 'state'=>3];
        $v = array_values($cols);
        foreach($v as $s) {
            echo $s.PHP_EOL;
        }
        echo '......................'.PHP_EOL;
        $c = count($v);
        for ($i = 0; $i < $c; $i++) {
            echo "{$v[$i]}".PHP_EOL;
        }
    }
}

echo '------------------------------------' . PHP_EOL;
ChildTest::genString();
ChildTest::getValues();
