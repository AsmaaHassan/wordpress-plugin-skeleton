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
		    $this->sql = "select * from". $wpdb->prefix ."dummyTable;";
		    return $wpdb->get_results($this->sql);
		}

		public function showAllDummyData(){
			$this->WpTable = new WPTable;
			$this->dbHelper = new dbHelper;
			$this->columns = $this->dbHelper->getColumnsNames("dummyTable");
			foreach ($this->columns as $key) {
				$this->html .= $key;
			}
			$this->html = $this->WpTable->wordpressHeadTable($this->columns, "Dummy Data");
			$this->allDummyData = $this->getAllDummyData();
		 //    foreach($this->allDummyData as $dummyData){
		 //    	$this->html .= $this->wordpressBodyTable($dummyData);
		 //    }
		 //    $this->html .= $this->wordpressFooterTable();
			return $this->html;
		}
	}
?>