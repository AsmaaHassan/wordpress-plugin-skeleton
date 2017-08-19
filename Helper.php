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
		private $file = null;

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

		public function uploadFileForm(){
			$this->html = file_get_contents('partialViews/uploadFile.html', true);
			return $this->html;
		}

		public function fetchReadFile(){
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				// upload file 
				$filename = sanitize_text_field($_FILES["file"]["name"]);
				$deprecated = null;
				$bits = file_get_contents($_FILES["file"]["tmp_name"]);
				$time = current_time('mysql');

				$upload = wp_upload_bits($filename, $deprecated, $bits, $time);

				global $current_user;
				get_currentuserinfo();
				$upload_dir = wp_upload_dir(); 
				$user_dirname = $upload_dir['basedir'] . '/' . $current_user->user_login;
				if(!file_exists($user_dirname)) wp_mkdir_p($user_dirname);

				$this->file = $upload_dir['path'].'/'.$filename;

				$upload = wp_upload_dir();
				$upload_dir = $upload['basedir'] . $directory_path;
				$permissions = 0755;
				$oldmask = umask(0);
				if (!is_dir($upload_dir)) mkdir($upload_dir, $permissions);
				$umask = umask($oldmask);
				$chmod = chmod($upload_dir, $permissions);
				
				// read records from csv file with this ofrmat card_number, company_name
				$row = 0; // count rows
				$this->html = '<h1>Records in file</h1><br/>';
				if (($handle = fopen($this->file, "r")) !== FALSE) {
				    while (($data = fgetcsv($handle)) !== FALSE) {
				    	if($row == 0) { $row++; continue;} // csv headings, ignore this iteration
				        $columns = count($data);
				        $this->html .= "<p> $columns fields in line $row:</p>\n";
				        for ($i=0; $i < $columns; $i++) {
				            $this->html .= $data[$i];
				            if ($i < $columns-1){$this->html .= ' - ';}
				        }
				        $row++;
				    }
				    fclose($handle);
				    $this->html .= '<br/><h1>This file contains: '. $row . '</h1>';
				}
				return $this->html;
			}
		}
	}
?>