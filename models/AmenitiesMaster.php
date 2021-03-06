<?php

// Model integration for bank list
class AmenitiesMaster extends ActiveRecord\Model
{
    static $table_name = 'amenities_master';
    static $has_many = array(
        array('resi_projects', 'class_name' => "ResiProjectAmenities", "foreign_key" => "AMINITY_ID")
    );
    static function arrAmenitiesMaster() {
        $getList = AmenitiesMaster::find('all',array('order'=>'amenity_name asc'));
        $returnArr = array();
        foreach( $getList as $value ) {
            $returnArr[$value->amenity_id] = $value->amenity_name;
        }
        return $returnArr;
    }

    static $other_amenity_id = 99;
    static $children_play_area_id = 3;
    static $clubhouse_id = 4;
    static $power_backup_id = 13;
    static $security_id = 11;
}
