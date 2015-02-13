<?php

class Rule extends CFormModel {

    static $list = array();

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function Rule() {
        self::$list["next_month"] = "Next Month (@Params: day)";
    }

    public function rules() {
        return array(
        );
    }

    public static function getList() {
        self::$list["0"] = "Select";
        self::$list["next_month"] = "Next Month (@Params: day)";
        return self::$list;
    }

    public static function getRule($rule, $params = array()) {
        switch ($rule) {
            case "next_month":
                return self::nextMonth($params);
                break;
            default:
                return false;
        }
    }
    public static function nextMonth($params) {
        $time = strtotime($params["date"]);
        $day  = $params["day"];
        $date = date("Y-m-d", mktime(0, 0, 0, date("m", $time)+1, $day, date("Y", $time)));   
        return $date;
    }

}

?>
