<?php
class ModelExtensionModuleSchemecat extends Model 
{
    public function get_cats() 
    {
      $rows = $this->db->query("SELECT * FROM `oc_scheme_categories`");
      return ($rows->rows);
    }
}
?>