<?php

// Model integration for resi_project
require_once "support/objects.php";
class ResiProject extends Objects
{
    static $table_name = 'resi_project';
    static $default_scope = array("version" => "cms");
    static $virtual_primary_key = 'project_id';

    static $has_many = array(
        array('resi_amenities', 'class_name' => "ResiProjectAmenities", "foreign_key" => "PROJECT_ID"),
        array('audits', 'class_name' => "Audit", "foreign_key" => "PROJECT_ID"),
        array('call_projects', 'class_name' => "CallProject", "foreign_key" => "ProjectId"),
        array('options', 'class_name' => "ResiProjectOptions", "foreign_key" => "PROJECT_ID"),
        array('phases', 'class_name' => "ResiProjectPhase", "foreign_key" => "PROJECT_ID"),
   ); 
   static function projectStatusMaster() {
       $qry = "select * from project_status_master";
       $result = ResiProject::find_by_sql($qry);
       $arrStatus = array();
       foreach( $result as $value ) {
           $arrStatus[$value->id] = $value->display_name;
       }
       return $arrStatus;
   }
   static function projectAlreadyExist($txtProjectName, $builderId, $localityId, $projectId='') {
	if($projectId == '')
	 $conditionsProject = array("project_name = ? and builder_id = ? 
           and locality_id = ?",$txtProjectName, $builderId,$localityId);
	else
	$conditionsProject = array("project_name = ? and builder_id = ? 
           and locality_id = ? and project_id != ?" ,$txtProjectName, $builderId,$localityId,$projectId);
        
	$projectChk = ResiProject::virtual_find('all',
           array('conditions'=>$conditionsProject, "select" => "project_name, project_small_image"));
        return $projectChk;
   }
   static function projectUrlExist($projectURL, $projectId) {
       $conditionsProjectUrl = array("project_id != '$projectId' and project_url = '$projectURL'");
       $projectUrlChk = ResiProject::find('all',
           array('conditions'=>$conditionsProjectUrl));
        return $projectUrlChk;
   } 
   static function getAllSearchResult($arrSearch) {
       $arrSearchFields = '';
       $arrSearchFieldsValue = array();
       $date = '';
       $cnt = 0;  
       foreach($arrSearch as $key => $value ) {
           $cnt++;
           $and = ' in (?) and ';
           $comma = ' ,';
           if( count($arrSearch) == $cnt ) {
               $comma = '';
               $and = 'in (?) ';
           }
           if( count($arrSearch) < $cnt )
               $and = '';
           if( $key == 'expected_supply_date_between_from_to' ) {
               $twoDate = explode('_',$value);
               $arrSearchFields .= 'expected_supply_date >= ? and ';
               array_push($arrSearchFieldsValue, $twoDate[0]);  
               
               $arrSearchFields .= 'expected_supply_date <= ?';
               array_push($arrSearchFieldsValue, $twoDate[1]);  
           }
           else if( $key == 'expected_supply_date_from' ) {
               $date = "$value";
               $arrSearchFields .= 'expected_supply_date >= ?';
               array_push($arrSearchFieldsValue, $date);
           }
           else if( $key == 'expected_supply_date_to' ) {
               $date = "$value";
               $arrSearchFields .= 'expected_supply_date <= ?';
               array_push($arrSearchFieldsValue, $date);
           }
           else {
               $arrSearchFields .= "resi_project.$key $and";
               array_push($arrSearchFieldsValue, $value);
           }
       }
	
       $conditions = array_merge(array($arrSearchFields), $arrSearchFieldsValue);
	
       $join = " left join resi_builder b on resi_project.builder_id = b.builder_id
                 left join master_project_phases phases 
                    on resi_project.project_phase_id = phases.id
                 left join master_project_stages stages
                    on resi_project.project_stage_id = stages.id";
       $projectSearch = ResiProject::find('all',
           array('joins' => $join,'conditions'=>$conditions,'select' => 
                    'resi_project.*,b.builder_name,phases.name as phase_name,stages.name as stage_name'));
	
	
	
       return $projectSearch;
   }


    public function get_all_towers(){
        $phase_ids = array();
        $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $this->project_id)));
        foreach($phases as $phase) array_push($phase_ids, $phase->phase_id);
        return ResiProjectPhase::get_towers_for_phases($phase_ids);
    }
}
