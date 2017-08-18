<?php 
	class dbHelper {
		/*
         * This class contains functions to create
         * and drop tables via wpdb
         * Functions included in this class
         * 1- cteateDummyTable
         * 2- dropDummyTable
        */
        private $dummyTable = null;
        
        function __construct(){
        	global $wpdb;
        	$this->dummyTable = $wpdb->prefix .'dummyTable';
        }
        
        public function cteateDummyTable(){
        	global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $sql =   "--"
                    ."-- Table structure for table `companies`"
                    ."--"
                    ."DROP TABLE IF EXISTS ". $this->dummyTable .";"
                    ."CREATE TABLE ". $this->dummyTable ."("
                    ."`dummy_id` int NOT NULL AUTO_INCREMENT,"
                    ."`dummy_name` varchar(255) DEFAULT NULL,"
                    ."`dummy_number` int(11) DEFAULT NULL,"
                    ."PRIMARY KEY (`dummy_id`)"
                    .")$charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }

        public function dropDummyTable(){
        	global $wpdb;
            $sql = "DROP TABLE IF EXISTS ".$this->dummyTable.";";
            $wpdb->query($sql);
        }

        public static function getColumnsNames( $table) {
            global $wpdb;
            $table = $wpdb->prefix . $table;
            return $wpdb->get_col("DESC {$table}", 0);
        }
	}
?>