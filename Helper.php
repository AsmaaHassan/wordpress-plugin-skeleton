<?php
	include_once("WPTable.php");  // include WPTable functions 
	include_once("dbHelper.php");  // include WPTable functions 
	class Helper {

		private $html = null;
		private $WpTable = null;
		private $allDummyData = null;
		private $sql = null;
		private $columns = null;
		private $dbHelper = null;

		public function addDummymetaField(){
			$this->html = file_get_contents('partialViews/dummymetaInputFieldUsersForm.html', true);
			return $this->html;
		}

		public function getAllDummyData(){
			global $wpdb;
		    $this->sql = "select * from ". $wpdb->prefix ."dummyTable;";
		    return $wpdb->get_results($this->sql, ARRAY_A); //return data as array
		}

		public function showAllDummyData(){
			$this->WpTable = new WPTable;
			$this->dbHelper = new dbHelper;
			$this->columns = $this->dbHelper->getColumnsNames("dummyTable");
			$this->html = $this->WpTable->wordpressHeadTable($this->columns, "Dummy Data");
			$this->allDummyData = $this->getAllDummyData();
		    foreach($this->allDummyData as $dummyData){
		    	$this->html .= $this->WpTable->wordpressBodyTable($dummyData);
		    }
		    $this->html .= $this->WpTable->wordpressFooterTable($this->columns);
			return $this->html;
		}
	}
?>