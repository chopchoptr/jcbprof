<?php
class ModelExtensionModuleScheme extends Model 
{
    public function get_categories()
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme_categories`");
        return($query->rows); 
    }

    public function get_schemes($cat, $engine)
    {
        if ((int) $engine > 0)
            $filter = "WHERE `engine` = ". $engine ." AND `category_id` = ". $cat;
        else
            $filter = "WHERE `category_id` = ". $cat;
        $query = $this->db->query("SELECT * FROM `oc_scheme` ". $filter);
        return($query->rows); 
    }

    public function get_scheme($id)
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme` WHERE `id` = ". $id);
        return($query->rows[0]); 
    }

    public function get_points($id)
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme_point` WHERE `scheme_id` = ". $id);
        return($query->rows); 
    }
}
?>