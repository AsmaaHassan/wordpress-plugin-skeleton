<?php
	class WPTable {
		private $html = null;
		public function wordpressHeadTable($columnsTitles, $tableTitle){
			$this->html = '<div class="wrap">
							<h2>'.$tableTitle.'</h2>
							 <table class="wp-list-table widefat fixed striped">
								<thead>
								<tr>';
			foreach ($columnsTitles as $columnTitle) {
				$this->html	.=		'<th scope="col" id="" class="manage-column">'.$columnTitle.'</th>';
			}
			$this->html	.=		'</tr>
							</thead>
								<tbody>';
			return $this->html;
		}

		public function wordpressFooterTable($columnsTitles){
			$this->html = '</tbody>
							<tfoot>
								<tr>';
			foreach ($columnsTitles as $columnTitle) {
				$this->html	.=	'<th scope="col" id="" class="manage-column">'.$columnTitle.'</th>';
			}
			$this->html .=		'</tr>
							</tfoot>
						</table>
					</div>';
			return $this->html;
		}

		public function wordpressBodyTable($row){
			$this->html = null;
			foreach ($row as $data) {
				$this->html	.=	'<tr><td>'.$data.'</td></tr>';
			}
			return $this->html;
		}
	}
?>