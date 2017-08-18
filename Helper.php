<?php
	class Helper {

		private $html = null;

		
		public function addDummymetaField(){
			$this->html = file_get_contents('partialViews/dummymetaInputFieldUsersForm.html', true);
			return $this->html;
		}
	}
?>