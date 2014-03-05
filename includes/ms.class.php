<?
	
	class MS{
		
		var $_db = null;


		//Constructor 
		function MS($db)
		{
			$this->_db = $db;
		}

		function showTable($tbl_pkid=""){
			$qry = "show tables";
			$rs = $this->_db->Execute($qry);
			while( $dataPlan = $rs->FetchRow()){
				$sel = ($tbl_pkid == $dataPlan[0])? "selected" : "";
				printf("<option value=\"%s\" %s>%s</option>\n", 
					$dataPlan[0], $sel, $dataPlan[0]);
			}			
			
		}
		
		
		##============= html selection ===================================
		function selectOptionNoTag($tbl_name, $field_id, $field_name, $tbl_pkid=0)
		{
			$qry = "select $field_id, $field_name from $tbl_name order by $field_id";
			//echo $qry;
			$rs = $this->_db->Execute($qry);
			while( $dataPlan = $rs->FetchRow()){
				$sel = ($tbl_pkid == $dataPlan[0])? "selected" : "";
				printf("<option value=\"%s\" %s>%s</option>\n", 
					$dataPlan[0], $sel, $dataPlan[1]);
			}					
		}

		function selectOptionByNameNoTag($tbl_name, $field_id, $field_name, $tbl_pkid=0)
		{
			$qry = "select $field_id, $field_name from $tbl_name order by binary $field_name";
			echo $qry;
			$rs = $this->_db->Execute($qry);
			while( $dataPlan = $rs->FetchRow($rs)){
				$sel = ($tbl_pkid == $dataPlan[0])? "selected" : "";
				printf("<option value=\"%s\" %s>%s</option>\n", 
					$dataPlan[0], $sel, $dataPlan[1]);
			}
			$this->_db->freeresult($rs);		
		}

		function selectOptionProvince($tbl_name, $tbl_pkid=0)
		{
			$qry = "select int_province_id, int_province_name from $tbl_name order by int_province_id";
			$rs = $this->_db->Execute($qry);
			while( $dataPlan = $rs->FetchRow()){
				$sel = ($tbl_pkid == $dataPlan[0])? "selected" : "";
				printf("<option value=\"%s\" %s>%s</option>\n", 
					$dataPlan[0], $sel, $dataPlan[1]);
			}		
		}
		function selectOptionProvince_en($tbl_name, $tbl_pkid=0)
		{
			$qry = "select int_province_id, int_province_name_en from $tbl_name order by int_province_id";
			$rs = $this->_db->Execute($qry);
			while( $dataPlan = $rs->FetchRow()){
				$sel = ($tbl_pkid == $dataPlan[0])? "selected" : "";
				printf("<option value=\"%s\" %s>%s</option>\n", 
					$dataPlan[0], $sel, $dataPlan[1]);
			}		
		} 
		##============= Get function ===================================
		//Get Name of related table of nn_books
	  	function selectTable($table,$fieldSelect, $tbl_id, $var_id){
			 $qry_selectTable = "select $fieldSelect from $table where $tbl_id='$var_id' ";
			// echo $qry_selectTable;
			$rs_selectTable = $this->_db->Execute( $qry_selectTable);
			$selectTable = $rs_selectTable->FetchRow();
		
		return $selectTable;
		}
		
		////////////////////////////////////////////////////////// End Class 
	}
?>