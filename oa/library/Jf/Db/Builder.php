<?php

namespace Jf\Db;

class Builder {

    const DISTINCT = 'distinct';
    const COLUMNS = 'columns';
    const SELECT = 'select';
    const FROM = 'from';
    const JOIN = 'join';
    const UNION = 'union';
    const WHERE = 'where';
    const GROUP = 'group';
    const HAVING = 'having';
    const ORDER = 'order';
    const LIMIT = 'limit';
    const LIMIT_COUNT = 'limitcount';
    const LIMIT_OFFSET = 'limitoffset';
    const FOR_UPDATE = 'forupdate';
    const INNER_JOIN = 'inner join';
    const LEFT_JOIN = 'left join';
    const RIGHT_JOIN = 'right join';
    const FULL_JOIN = 'full join';
    const CROSS_JOIN = 'cross join';
    const NATURAL_JOIN = 'natural join';
    const SQL_WILDCARD = '*';
    const SQL_SELECT = 'SELECT';
    const SQL_UNION = 'UNION';
    const SQL_UNION_ALL = 'UNION ALL';
    const SQL_UPDATE = 'UPDATE';
    const SQL_DELETE = 'DELETE';
    const SQL_FROM = 'FROM';
    const SQL_WHERE = 'WHERE';
    const SQL_DISTINCT = 'DISTINCT';
    const SQL_GROUP_BY = 'GROUP BY';
    const SQL_ORDER_BY = 'ORDER BY';
    const SQL_HAVING = 'HAVING';
    const SQL_FOR_UPDATE = 'FOR UPDATE';
    const SQL_AND = 'AND';
    const SQL_AS = 'AS';
    const SQL_OR = 'OR';
    const SQL_ON = 'ON';
    const SQL_ASC = 'ASC';
    const SQL_DESC = 'DESC';
    const SQL_LIMIT = 'LIMIT';
    const SQL_OFFSET = 'OFFSET';
    const SQL_INNER_JOIN = 'INNER JOIN';
    const SQL_LEFT_JOIN = 'LEFT JOIN';
    const SQL_RIGHT_JOIN = 'RIGHT JOIN';
    const SQL_CROSS_JOIN = 'CROSS JOIN';
    const SQL_VALUES = 'VALUES';

    protected $_parts = array(
        self::UNION => array(),
        self::INSERT => array(),
        self::DELETE => array(),
        self::UPDATE => array(),
        self::SELECT => array(),
        self::JOIN => array(),
        self::WHERE => array(),
        self::BETWEEN => null,
        self::GROUP => array(),
        self::HAVING => array(),
        self::ORDER => array(),
        self::LIMIT => null,
    );

    public function reset($partKey = null) {
        if (!$partKey) {
            $this->_parts = array(
                self::UNION => array(),
                self::INSERT => array(),
                self::DELETE => array(),
                self::UPDATE => array(),
                self::SELECT => array(),
                self::JOIN => array(),
                self::WHERE => array(),
                self::BETWEEN => null,
                self::GROUP => array(),
                self::HAVING => array(),
                self::ORDER => array(),
                self::LIMIT => null,
            );
        } elseif ($partKey == self::COLUMNS) {
           $this->_parts[self::SELECT][self::COLUMNS] = array();
        } else {
            $this->_parts[$partKey] = array();
        }

        return $this;
    }

    const INSERT = 'insert';
    const UPDATE = 'update';
    const INSERT_TEMPLATE = "INSERT INTO %s (%s) VALUES %s";
    const INSERT_ON_DUPLICATE_TEMPLATE = "%s ON DUPLICATE KEY UPDATE %s";
    const INSERT_ON_DUPLICATE_UPDATE_VALUES_TEMPLATE = "%s = VALUES(%s)";

    public function insert($name, array $row, $updateCols = array()) {
        $this->reset();
        $vals = array();
        $cols = array();
        $flag = true;
        foreach ($row as $col => $val) {
            if ($flag && !is_string($col)) {
                $cols = array_map('self::quoteIdentifier', array_values($row));
                break;
            } else {
                $vals[] = self::quote($val);
                $cols[] = self::quoteIdentifier($col);
                $flag = false;
            }
        }
        $this->_parts[self::INSERT] = array(
            'name' => self::quoteIdentifier($name),
            'cols' => implode(', ', $cols),
            'vals' => $vals ? sprintf('(%s)', implode(', ', $vals)) : null,
            'updateCols' => $updateCols,
        );
        return $this;
    }

    public function batchInsert($name, array $data, $updateCols = array()) {
        if (empty($data)) {
            return $this;
        }
        $this->reset();
        $vals = array();
        foreach ($data as $row) {
            if (!is_array($row)) {
                continue;
            }
            $rowVals = array_map('self::quote', array_values($row));
            $vals[] = sprintf('(%s)', implode(', ', $rowVals));
        }
        $cols = array_map('self::quoteIdentifier', array_keys(current($data)));
        $this->_parts[self::INSERT] = array(
            'name' => self::quoteIdentifier($name),
            'cols' => implode(', ', $cols),
            'vals' => implode(', ', $vals),
            'updateCols' => $updateCols,
        );
        return $this;
    }

    protected function prepareInsert() {
        list($name, $cols, $vals, $updateCols) = array_values($this->_parts[self::INSERT]);
        $sql = sprintf(self::INSERT_TEMPLATE, $name, $cols, $vals);
        if (!$vals) {
            return str_replace(self::SQL_VALUES, '', $sql);
        } elseif (empty($updateCols)) {
            return $sql;
        }
        $valuesTemplate = self::INSERT_ON_DUPLICATE_UPDATE_VALUES_TEMPLATE;
        foreach (array_map('self::quoteIdentifier', $updateCols) as $col) {
            $updateData .= sprintf($valuesTemplate, $col, $col);
            $updateData .= ', ';
        }
        $updateData = rtrim($updateData, ', ');
        return sprintf(self::INSERT_ON_DUPLICATE_TEMPLATE, $sql, $updateData);
    }

    const UPDATE_TEMPLATE = "UPDATE %s SET %s";

    public function update($name, $data) {
        $this->reset();
        $this->_parts[self::UPDATE] = array(
            'name' => (array) $name,
            'data' => $data
        );
        return $this;
    }

    public function prepareUpdate() {
        list($name, $data) = array_values($this->_parts[self::UPDATE]);
        $updateString = self::quoteIdentifier(current($name), key($name));
        foreach ($data as $col => $val) {
            $set[] = self::quoteIdentifier($col) . ' = ' . self::quote($val);
        }
        if ($this->_parts[self::JOIN]) {
            $updateString .= $this->prepareJoin();
            $this->reset(self::JOIN);
        }
        $sql = sprintf(self::UPDATE_TEMPLATE, $updateString, implode(', ', $set));
        return $sql;
    }

    const DELETE = 'delete';

    public function delete($name, $where = null) {
        $this->reset();
        $this->_parts[self::DELETE] = (array) $name;
        $this->where($where);
        return $this;
    }

    const DELETE_TEMPLATE = 'DELETE FROM %s';

    public function prepareDelete() {
        if (!$this->_parts[self::WHERE]) {
            $this->where(1);
        }
        $name = $this->_parts[self::DELETE];
        $nameString = self::quoteIdentifier(current($name), key($name));
        $sql = sprintf(self::DELETE_TEMPLATE, $nameString);
        return $sql;
    }

    public function select($name, $columns = null) {
        $this->_parts[self::SELECT][self::FROM] = (array) $name;
        if ($columns) {
            $this->_parts[self::SELECT][self::COLUMNS] = (array) $columns;
        }
        return $this;
    }

    const SELECT_TEMPLATE = "SELECT %s FROM %s ";

    protected function prepareSelect() {
        $cols = array();
        list($name, $columns) = array_values($this->_parts[self::SELECT]);
        foreach ($columns as $colAlias => $column) {
            if (preg_match('/^[\w]*\(.*\)$/', $column)
                    || $column == self::SQL_WILDCARD 
                    || $column == $colAlias . '.' . self::SQL_WILDCARD) {
                $column = new Expr($column);
            }
            $cols[] = self::quoteIdentifier($column, $colAlias);
        }
        $colsString = ' ' . implode(', ', $cols);
        $nameString = self::quoteIdentifier(current($name), key($name));
        $sql = sprintf(self::SELECT_TEMPLATE, $colsString, $nameString);
        return $sql;
    }

    public function columns($cols = '*') {
        if (!empty($this->_parts[self::SELECT][self::COLUMNS])) {
            $hasCols = $this->_parts[self::SELECT][self::COLUMNS];
            $cols = array_merge($hasCols, (array) $cols);
        }
        $this->_parts[self::SELECT][self::COLUMNS] = (array) $cols;
        return $this;
    }


    protected $_joinTemplates = array(
        self::INNER_JOIN => 'INNER JOIN %s  ON %s = %s',
        self::LEFT_JOIN =>  'LEFT JOIN %s  ON %s = %s',
        self::RIGHT_JOIN =>  'RIGHT JOIN %s  ON %s = %s',
        self::CROSS_JOIN => 'CROSS JOIN %s  ON %s = %s',
    );

    /*
     * sprintf("INNER JOIN %s  ON '%s' = '%s'", $name,  $condKey, $condValue);
     */
    /*
         $builder->select(array('t1' => 'table1'))
            ->columns(array('a1', 'a2', 'a3'))
            ->leftJoin(array('t2'=> 'table2'), array('t1.a1' => 't2.a2'))
            ->assemble();
     SELECT `a1`, `a2`, `a3` FROM `table1` AS `t1` 
     LEFT JOIN `table2` AS `t2` ON `t1`.`a1` = `t2`.`a2`
     */
    public function innerJoin(array $name, $condition, $columns = null) {
        $this->join(self::INNER_JOIN, $name, $condition, $columns);
        return $this;
    }

    public function leftJoin(array $name, $condition, $columns = null) {
        $this->join(self::LEFT_JOIN, $name, $condition, $columns);
        return $this;
    }

    public function rightJoin(array $name, $condition, $columns = null) {
        $this->join(self::RIGHT_JOIN, $name, $condition, $columns);
        return $this;
    }

    public function crossJoin(array $name, $condition, $columns = null) {
        $this->join(self::CROSS_JOIN, $name, $condition, $columns);
        return $this;
    }

    public function join($joinType, $name, $conditon, $columns) {
        array_push($this->_parts[self::JOIN], array(
            'joinType' => $joinType,
            'name' => self::quoteIdentifier(current($name), key($name)),
            'condKey' => self::quoteIdentifier(key($conditon)),
            'condValue' => self::quoteIdentifier(current($conditon)),
        ));
        if ($this->_parts[self::SELECT]) {
            $this->columns($columns);
        }
        return $this;
    }

    public function prepareJoin() {
        $sql = '';
        foreach ($this->_parts[self::JOIN] as $join) {
            list($joinType, $name, $condKey, $condValue) = array_values($join);
            $sql .= sprintf($this->_joinTemplates[$joinType], $name, $condKey, $condValue);
        }
        return $sql;
    }

    public function where($cond, $value = null) {
        if (!$cond) {
            return $this;
        }
        if ($value == null && is_array($cond)) {
            foreach ($cond as $key => $val) {
                if (is_int($key)) {
                    $this->where($val);
                } else {
                    $this->where($key, $val);
                }
            }
        } else {
            $this->_parts[self::WHERE][] = $this->processWhere($cond, $value, true);
        }
        return $this;
    }

    public function orWhere($cond, $value = null) {
        $this->_parts[self::WHERE][] = $this->processWhere($cond, $value, false);
        return $this;
    }

    const WHERE_TEMPLATE = 'WHERE %s';

    public function prepareWhere() {
        return sprintf(self::WHERE_TEMPLATE, implode(' ', $this->_parts[self::WHERE]));
    }

    protected function processWhere($condition, $value = null, $bool = true) {
        if (count($this->_parts[self::UNION])) {
            throw new Exception("Invalid use of where clause with " . self::SQL_UNION);
        }

        if (isset($value)) {
            $condition = self::quoteInto($condition, $value);
        }

        $cond = "";
        if ($this->_parts[self::WHERE]) {
            if ($bool === true) {
                $cond = self::SQL_AND . ' ';
            } else {
                $cond = self::SQL_OR . ' ';
            }
        }
        return $cond . "($condition)";
    }

    public function having($cond, $value = null, $type = null) {
        if ($value !== null) {
            $cond = self::quoteInto($cond, $value, $type);
        }

        if ($this->_parts[self::HAVING]) {
            $this->_parts[self::HAVING][] = self::SQL_AND . " ($cond)";
        } else {
            $this->_parts[self::HAVING][] = "($cond)";
        }
        return $this;
    }

    public function orHaving($cond, $value = null, $type = null) {
        if ($value !== null) {
            $cond = self::quoteInto($cond, $value, $type);
        }

        if ($this->_parts[self::HAVING]) {
            $this->_parts[self::HAVING][] = self::SQL_OR . " ($cond)";
        } else {
            $this->_parts[self::HAVING][] = "($cond)";
        }
        return $this;
    }

    const HAVING_TEMPLATE = "HAVING %s";

    protected function prepareHaving() {
        if (!$this->_parts[self::SELECT]) {
            return;
        }
        return sprintf(self::HAVING_TEMPLATE, implode(' ', $this->_parts[self::HAVING]));
    }

    const BETWEEN = 'between';
    const BETWEEN_TEMPLATE = "%s BETWEEN '%s' AND '%s'";

    public function between($field, $start, $end) {
        if (!$this->_parts[self::WHERE]) {
            $this->where(1);
        }
        $this->_parts[self::BETWEEN] = array('field' => $field, 'start' => $start, 'end' => $end);
        return $this;
    }

    protected function prepareBetween() {
        list($field, $start, $end) = array_values($this->_parts[self::BETWEEN]);
        $condition = sprintf(self::BETWEEN_TEMPLATE, self::quoteIdentifier($field), $start, $end);
        return $this->processWhere($condition);
    }

    public function order($field, $val = null, $flag = false) {
        if (!$field) {
            return $this;
        }
        if ($flag === false && is_array($field)) {
            foreach ($field as $v) {
                $this->order($v, null, true);
            }
            return $this;
        } else {
            $field = trim($field);
        }
        
        if (!$val && strpos($field, ' ')) {
            list($field, $val) = explode(' ', $field);
        }
        $orderVal = $val ? strtoupper($val) : self::SQL_ASC;
        if ($orderVal != self::SQL_DESC && $orderVal != self::SQL_ASC) {
            throw new Exception(sprintf('order value must: %s, %s', self::SQL_DESC, self::SQL_ASC));
        }
        if (preg_match('/^[\w]*\(.*\)$/', $field)) {
            $field = new Expr($field);
        }
        $order = self::quoteIdentifier($field) . ' ' . $orderVal;
        $this->_parts[self::ORDER][] = $order;
        return $this;
    }

    const ORDER_BY_TEMPLATE = 'ORDER BY %s';

    protected function prepareOrder() {
        return sprintf(self::ORDER_BY_TEMPLATE, implode(', ', $this->_parts[self::ORDER]));
    }

    public function group($group, $flag = false) {
        if (!$group) {
            return $this;
        }
        if ($flag === false && is_array($group)) {
            foreach ($group as $val) {
                $this->group($val, true);
            }
        }
        if (preg_match('/^[\w]*\(.*\)$/', $group)) {
            $group = new Expr($group);
        } else {
            $group = self::quoteIdentifier($group);
        }
        $this->_parts[self::GROUP][] = $group;
        return $this;
    }

    const GROUP_BY_TEMPLATE = "GROUP BY %s";

    protected function prepareGroup() {
        if (!$this->_parts[self::SELECT]) {
            return;
        }
        return sprintf(self::GROUP_BY_TEMPLATE, implode(', ', $this->_parts[self::GROUP]));
    }

    public function limit($count = null, $offset = null) {
        $limit[self::LIMIT_COUNT] = (int) $count;
        $limit[self::LIMIT_OFFSET] = (int) $offset;
        $this->_parts[self::LIMIT] = $limit;
        return $this;
    }

    public function limitPage($page, $rowCount) {
        $count = (int) $rowCount;
        $offset = (int) $rowCount * ($page - 1);
        $this->limit($count, $offset);
        return $this;
    }

    protected function prepareLimit() {
        $limit = $this->_parts[self::LIMIT];
        $count = intval($limit[self::LIMIT_COUNT]);
        $offset = intval($limit[self::LIMIT_OFFSET]);
        $sql = self::SQL_LIMIT . ' ' . $count;
        if ($offset > 0) {
            $sql .= ' ';
            $sql .= self::SQL_OFFSET . ' ' . $offset;
        }
        return $sql;
    }

    public function assemble() {
        $sql = '';
        foreach (array_keys($this->_parts) as $part) {
            if (empty($this->_parts[$part])) {
                continue;
            }
            $prepare = 'prepare' . ucfirst($part);
            $sql .= ' ';
            $sql .= $this->$prepare();
        }
        return $sql;
    }

    /**
     *
     * quoteInto("WHERE date < ?", "2005-01-02");
     * "WHERE date < '2005-01-02'"
     *
     *  quoteInto('id IN(?)', array(1, 2, 3));
     *  'id IN('1', '2', '3')
     */
    public function quoteInto($text, $value, $count = null) {
        if ($count === null) {
            return str_replace('?', self::quote($value), $text);
        } else {
            while ($count > 0) {
                if (strpos($text, '?') !== false) {
                    $text = substr_replace($text, self::quote($value), strpos($text, '?'), 1);
                }
                --$count;
            }
            return $text;
        }
    }

    public static function quote($value) {
        if ($value instanceof Expr) {
            return $value->__toString();
        }

        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = self::quote($val);
            }
            return implode(', ', $value);
        }

        if (!is_int($value) && !is_float($value)) {
            $value = addcslashes($value, "\000\n\r\\'\"\032");
        }
        return sprintf("'" . '%s' . "'", $value);
    }

    const IDENTIFIER_AS_TEMPLATE = " %s AS `%s` ";

    public static function quoteIdentifier($value, $alias = null) {
        if ($value instanceof Expr) {
            $value =  $value->__toString();
        } elseif (strpos($value, '.')) {
            $value = preg_replace('/(\w+).(\w+)$/', "`\${1}`.`\${2}`", $value);
        } else {
            $value = "`" . $value . "`";
        }
        if ($alias && !is_numeric($alias)) {
            $value = sprintf(self::IDENTIFIER_AS_TEMPLATE, $value, $alias);
        }
        return $value;
    }

}