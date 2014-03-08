<?php

class b8_storage_pdo extends b8_storage_base
{
    public $config = array(
        'database'   => 'b8_wordlist',
        'table_name' => 'b8_wordlist',
    );
    
    private $_deletes = array();
    private $_puts    = array();
    private $_updates = array();
    
    /**
     * Constructs the backend.
     *
     * @access public
     * @param string $config
     */
    
    function __construct($config, &$degenerator)
    {
        # Pass the degenerator instance to this class
        $this->degenerator = $degenerator;
        
        foreach($config as $name => $value) {
            switch($name) {
                case 'table_name':
                case 'database':
                    $this->config[$name] = (string) $value;
                    break;
                default:
                    throw new Exception("b8_storage_mysql: Unknown configuration key: \"$name\"");
            }
        }
                
        # Let's see if this is a b8 database and the version is okay
        $this->check_database();
    }
    
    /**
     * Closes the database connection.
     *
     * @access public
     * @return void
     */
    
    function __destruct()
    {
        # Commit any changes before closing
        $this->_commit();
    }
    
    /**
     * Does the actual interaction with the database when fetching data.
     *
     * @access protected
     * @param array $tokens
     * @return mixed Returns an array of the returned data in the format array(token => data) or an empty array if there was no data.
     */
    
    protected function _get_query($tokens)
    {
        # Construct the query ...
        
        if(count($tokens) > 1) {
        
            # We have more than 1 token
            
            $where = array();
            foreach ($tokens as $token) {
                array_push($where, $token);
            }
            
            $where = "token IN ('" . implode("', '", $where) . "')";
        }
        
        elseif(count($tokens) == 1) {
            # We have exactly one token
            $token = $tokens[0];
            $where = "token = '" . $token . "'";
        }
        
        elseif(count($tokens) == 0) {
            # We have no tokens
            # This can happen when we do a degenerates lookup and we don't have any degenerates.
            return array();
        }
        
        # ... and fetch the data
                
        $db = \Dang\Quick::mysql($this->config['database'], "master");
        $sql = 'SELECT token, count_ham, count_spam FROM `' . $this->config['table_name'] . '` WHERE ' . $where . '';
        $all = $db->getAll($sql);
        
        $data = array();
        for($i=0;$i<count($all);$i++)
        {
            $row = $all[$i];
            $data[$row['token']] = array(
                'count_ham'  => $row['count_ham'],
                'count_spam' => $row['count_spam']
            );
        }

        return $data;
        
    }
    
    /**
     * Store a token to the database.
     *
     * @access protected
     * @param string $token
     * @param string $count
     * @return void
     */
    
    protected function _put($token, $count)
    {
        $count_ham = $count['count_ham'];
        $count_spam = $count['count_spam'];
        
        array_push($this->_puts, "('$token', '{$count['count_ham']}', '{$count['count_spam']}')");
        
    }
    
    /**
     * Update an existing token.
     *
     * @access protected
     * @param string $token
     * @param string $count
     * @return void
     */
    
    protected function _update($token, $count)
    {
    
        $token = $token;
        
        $count_ham = $count['count_ham'];
        $count_spam = $count['count_spam'];
        
        array_push($this->_updates, "('$token', '{$count['count_ham']}', '{$count['count_spam']}')");
        
    }
    
    /**
     * Remove a token from the database.
     *
     * @access protected
     * @param string $token
     * @return void
     */
    
    protected function _del($token)
    {
    
        $token = $token;
        
        array_push($this->_deletes, $token);
        
    }
    
    /**
     * Commits any modification queries.
     *
     * @access protected
     * @return void
     */
    
    protected function _commit()
    {
        if(count($this->_deletes) > 0) {
            $sql = "
                DELETE FROM `{$this->config['table_name']}`
                WHERE token IN ('" . implode("', '", $this->_deletes) . "');
            ";
            $db = \Dang\Quick::mysql($this->config['database'], "master");    
            $db->executeSql($sql);
            
            $this->_deletes = array();
        }
        
        if(count($this->_puts) > 0) {
            $sql = "
                INSERT INTO `{$this->config['table_name']}`(token, count_ham, count_spam)
                VALUES " . implode(', ', $this->_puts) . ';
            ';
            $db = \Dang\Quick::mysql($this->config['database'], "master");    
            $db->executeSql($sql);

            $this->_puts = array();
            
        }
        
        if(count($this->_updates) > 0) {
            $sql = "
                INSERT INTO `{$this->config['table_name']}`(token, count_ham, count_spam)
                VALUES " . implode(', ', $this->_updates) . "
                ON DUPLICATE KEY UPDATE
                    `{$this->config['table_name']}`.count_ham = VALUES(count_ham),
                    `{$this->config['table_name']}`.count_spam = VALUES(count_spam);
            ";
            $db = \Dang\Quick::mysql($this->config['database'], "master");    
            $db->executeSql($sql);
                
            $this->_updates = array();
            
        }
        
    }
    
}

?>