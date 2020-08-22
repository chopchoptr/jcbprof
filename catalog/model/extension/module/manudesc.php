<?php
class ModelExtensionModuleScheme extends Model 
{
    public function get_manufacturers()
    {
        $query = $this->db->query("SELECT * FROM `oc_manufacturer`");
        return($query->rows); 
    }
}
?>