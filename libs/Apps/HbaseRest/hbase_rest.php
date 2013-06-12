<?
class hbase_rest {
	/*
	Basic Usage here more details in notes with each functions

	// open a new connection to rest server. Hbase Master default port is 60010
	$hbase = new hbase_rest($ip, $port);

	// get list of tables
	$tables = $hbase->list_tables();

	// get table column family names and compression stuff
	$table_info = $hbase->table_schema("search_index");

	// get start and end row keys of each region
	$regions = $hbase->regions($table);

	// select data from hbase
	$results = $hbase->select($table,$row_key);

	// insert data into hbase the $column and $data can be arrays with more then one column inserted in one request
	$hbase->insert($table,$row,$column(s),$data(s));

	// delete a column from a row. Can not use * at this point to remove all I thank there is plans to add this.
	$hbase->remove($table,$row,$column);

	// start a scanner on a set range of table
	$handle = $hbase->scanner_start($table,$cols,$start_row,$end_row);

	// pull the next row of data for a scanner handle
	$results = $hbase->scanner_get($handle);

	// delete a scanner handle
	$hbase->scanner_delete($handle);

	Example of useing a scanner this will loop each row until it out of rows.

	include(hbase_rest.php);
	$hbase = new hbase_rest($ip, $port);
	$handle = $hbase->scanner_start($table,$cols,$start_row,$end_row);
	$results = true;
	while ($results){
		$results = $hbase->scanner_get($handle);
		if ($results){
			foreach($results['column'] as $key => $value){

				....
				code here to work with the $key/column name and the $value of the column
				....

			} // end foreach
		} // end if
	}// end while
	$hbase->scanner_delete($handle);



	*/
	var $host;
	var $port;
	var $connection;
	var $error_num;
	var $error_str;

	// Constructor
	function __construct($host, $port) {
		$this->host = $host;
		$this->port = $port;
	} // end function

	function connect(){
		// Connections are one time request from what I have tested.
		// So each function must call $this->connect() before sending request to server
		$this->connection = @fsockopen($this->host, $this->port, $this->error_num, $this->error_str, $timeout = 60);
		if (!$this->connection){
			return sprintf("Not Connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if
	} // end function

	function close() {
		if($this->connection) {
			fclose($this->connection);
		} // end if
	} // end function

	function ping(){
		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			@fclose($this->connection);
			return false;
		} else {
			@fclose($this->connection);
			return true;
		} // end if

	} // end if
	function row_xml_array($data){
	// basic xml into array with
	// base_decode for colimn names
	// base_decode for data
	// trim timestamp to match unix_timestamp
		$xml = new SimpleXMLElement($data);
        $rows = array();
        for ($z=0, $count = count($xml->Row); $z<$count; $z++){
            foreach($xml->Row[$z]->attributes() as $n => $v) {
                $rows[$n] = base64_decode(trim($v));
            }
            for ($zz=0; $zz<count($xml->Row[$z]->Cell); $zz++){
                $cell = $xml->Row[$z]->Cell[$zz];
                foreach($cell->attributes() as $n => $v) {
                    $value = trim($v);
                    if($n == 'column'){
                        $value = base64_decode($value);
                    }
                    $rows['cell'][$zz][$n] = $value;
                }
                $rows['cell'][$zz]['value'] = base64_decode(trim($cell));
            }
        }

        return $rows;
	} // end function

	function list_tables(){
		// returns a array with table names in it for this hbase cluster

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if
		$header = "GET / HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-type: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";
		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while
		// close handle
		@fclose($this->connection);
		// look for a 200 responce code for success
		if (!ereg("HTTP/1.1 200",$buff)){
			return $buff;
		} else {
			// find starting point of xml to strip out headers to keep from breaksing the xml to array function
			$start = strpos($buff,'<?xml version="1.0" encoding="UTF-8"?>');
			$xml = new SimpleXMLElement(substr($buff,$start));
			for ($zz=0, $count = count($xml->table); $zz<$count; $zz++){
				$tables[] = trim($xml->table[$zz]);
			} // end for
			return $tables;
		} // end if
	} // end function

	function regions($table){
		// input table name
		// output array of start and stop row keys for each region
		// start of the first and end of the last region will be null

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if
		$header = "GET /".$table."/regions/ HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-type: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";
		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while

		// close connection handle
		@fclose($this->connection);

		$start = strpos($buff,'<?xml version="1.0" encoding="UTF-8"?>');
		$buff = substr($buff,$start);
		$xml = new SimpleXMLElement(str_replace("\n","",$buff));
		$data[0][0] = "";
		for ($count = count($xml->region), $zz=1; $zz<$count; $zz++){
			$tmp = trim($xml->region->$zz);
			$data[$zz-1][1] = $tmp;
			$data[$zz][0] = $tmp;
		} // end for
		$data[$zz-1][1] = "";
		return $data;
	} // end function

	function table_schema($table){
		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if
		$header = "GET /".$table." HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-type: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";
		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while
        var_dump($buff);
		// close connection handle
		fclose($this->connection);

		$start = strpos($buff,'<?xml version="1.0" encoding="UTF-8"?>');
		$xml = new SimpleXMLElement(substr($buff,$start));
		for ($count = count($xml->columnfamilies->columnfamily), $zz=0; $zz<$count; $zz++){
			foreach ($xml->columnfamilies->columnfamily[$zz] as $name => $value){
				$data[$zz][trim($name)] = trim($value);
			} //end foreach
		} // end for
		print_r($data);
		return;
		return $data;
	} // end function

	function select($table,$row,$cols=""){
		// input row will be urlencoded
		// output will be an array of all the data

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if
		if ($cols){
			$header = "GET /".$table."/".urlencode($row)."/".$cols." HTTP/1.1\r\n";
		} else {
			$header = "GET /".$table."/".urlencode($row)."/ HTTP/1.1\r\n";
		} // end if
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-type: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";
		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while

		// close connection handle
		fclose($this->connection);
		if (!ereg("HTTP/1.1 200",$buff)){
			return "no results";
		} else {
			$start = strpos($buff,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
            if($start){
                return $this->row_xml_array(substr($buff, $start));
            }
		} // end if
	} // end function

	function insert($table, $row, $cols){
		// output 'success' if inserted else returns headers from server

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

        /*
        $aaa = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><CellSet><Row key="MjEyMjIyMjM="><Cell column="YmFzZTpjb21wX2lk" timestamp="1371074445102">MjQ5MjA4OQ==</Cell><Cell column="YmFzZTpzdGF0dXM=" timestamp="1371074445102">Mg==</Cell><Cell column="YmFzZTp0aXRsZQ==" timestamp="1371074445102">5Ye65ZSu5oiq5rWB5byP6L+H5ruk5Zmo</Cell><Cell column="YmFzZTp1cGRhdGV0aW1l" timestamp="1371074445102">MTI1OTY1ODY1OA==</Cell></Row></CellSet>';
         *
         */
		// loop column/data array building xml to submit
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><CellSet><Row key="'.base64_encode($row).'">';
		foreach ($cols as $key => $value){
			//append each column to the xml filed
			$xml .= '<Cell column="'.base64_encode($key).'">'.base64_encode($value).'</Cell>';
		} // end for
		$xml .= '</Row></CellSet>';

		$header = "PUT /".$table."/".$row." HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-type: text/xml\r\n";
		$header .= "Content-length: ".strlen($xml)."\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= "$xml\r\n\r\n";

		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while

		// close connection handle
		fclose($this->connection);

		if (!ereg("HTTP/1.1 200",$buff)){
			return $buff;
		} else {
			return "success";
		} // end if
	} // end function

	function remove($table,$row,$cols=0){
		// output 'success' when deleted else returns headers from servers

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		if ($cols){
			if(is_array($cols)){
				$columns = "";
				foreach ($cols as $key => $value){
					$columns .= "column=".$value."&";
				} // end for
				// remove the last & off the query string
				$columns = substr($columns,0,-1);
			} else {
				$columns = "column=".$cols;
			} // end if
		} // end if

		if ($cols){
			$header = "DELETE /api/".$table."/row/".$row."?".$columns." HTTP/1.1\r\n";
		} else {
			$header = "DELETE /api/".$table."/row/".$row."/ HTTP/1.1\r\n";
		} // end if
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Connection: close\r\n\r\n";

		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while

		// close connection handle
		fclose($this->connection);

		if (!ereg("HTTP/1.1 202 Accepted",$buff)){
			return $buff;
		} else {
			return "success";
		} // end if
	} // end function

	function scanner_start($table,$cols,$start_row="",$end_row=""){
		// output will be part of the request url we will call the handle
		// you can use the handle to call the scanner_get and scanner_delete
		// table and cols must be supplyed cols can be an array of columns you want to return from the table for each row
		// start row can be null and that will make one scanner for the whole table
		// start row and end row can be set from the regions function above giveing you one thread per split of the table;

		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		if ($start_row || $end_row || $cols){
			$parms = "";
			if ($start_row){
				$parms .= "start_row=".urlencode($start_row)."&";
			} // end if
			if ($end_row){
				$parms .= "end_row=".urlencode($end_row)."&";
			} // end if
			if ($cols){
				if(is_array($cols)){
					foreach ($cols as $key => $value){
						$parms .= "column=".$value."&";
					} // end foreach
				} else {
					$parms .= "column=".$cols."&";
				} // end if
			} // end if
			// remove the last & off the query string
			$parms = substr($parms,0,-1);
		} // end if

		if ($parms){
			$header = "PUT /".$table."/scanner?".$parms." HTTP/1.1\r\n";
		} else {
			$header = "POST /".$table."/scanner HTTP/1.1\r\n";
		} // end if
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Connection: close\r\n\r\n";
        var_dump($header);

		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while
        var_dump($buff);
		// close connection handle
		fclose($this->connection);

		if (!ereg("HTTP/1.1 201",$buff)){
			return $buff;
		} else {
			$start = strpos($buff,"Location:");
			$data = explode("\n",substr($buff,$start));
			$data[0] = str_replace("Location: ","",trim($data[0]));
			return $data[0];
		} // end if
	} // end function

	function scanner_get($handle){
		// each row will be returned in a array with column data
		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		$header = "PUT ".$handle." HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Accept: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";

		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while
        var_dump($buff);

		// close connection handle
		fclose($this->connection);

		if (ereg("HTTP/1.1 200",$buff)){
			$start = strpos($buff,'<?xml version="1.0" encoding="UTF-8"?>');
			return $this->row_xml_array(substr($buff,$start));
		} else {
			return false;
		} // end if
	} // end function

	function scanner_delete($handle){
		// remove a scanner from the server scanner list when done with it.
		// make connection
		$this->connect();
		// check to make sure connection is there
		if(!$this->connection) {
			return sprintf("Could not connected: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		$header = "DELETE ".$handle." HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Accept: text/xml\r\n";
		$header .= "Connection: close\r\n\r\n";

		if (fputs($this->connection, $header, strlen($header)) === FALSE) {
			// if fputs fails then return error with error codes
			return sprintf("Connection Lost: (%d : %d)", $this->error_num, $this->error_str);
		} // end if

		//loop through the response from the server
		$buff = "";
		while(!feof($this->connection)){
			$buff .= fgets($this->connection, 1024);
		} // end while

		// close connection handle
		fclose($this->connection);

		if (ereg("HTTP/1.1 202",$buff) || ereg("HTTP/1.1 410",$buff)){
			return true;
		} else {
			echo $buff;
			return false;
		} // end if
	} // end function
} // end class

?>