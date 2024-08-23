<?php

class DB {
	
	protected $db_name = 'goallanka_new';
	protected $db_user = 'root';
	protected $db_pass = '';
	protected $db_host = 'localhost';
	

// 	protected $db_name = 'besthote_lasantha_distributors';
// 	protected $db_user = 'root';
// 	protected $db_pass = '';
// 	protected $db_host = 'localhost';

	// protected $db_name = 'salespad_db_demo';
	// protected $db_user = 'root';
	// protected $db_pass = '';
	// protected $db_host = 'localhost';
	
	
	// private $db_name = 'salespad_db_lakmeeexports';
	// private $db_user = 'root';
	// private $db_pass = 'Admin123$';
	// private $db_host = 'localhost';
	// private $connect_db = null;

	// private $db_name = 'salespad_db_gandr';
	// private $db_user = 'root';
	// private $db_pass = '';
	// private $db_host = 'localhost';
	// private $connect_db = null;
	
	
	public function connect() {
	
		$connect_db = new mysqli( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );
		
		if ( mysqli_connect_errno() ) {
			printf("Connection failed: %s", mysqli_connect_error());
			exit();
		}
		return $connect_db;
		
	}

}