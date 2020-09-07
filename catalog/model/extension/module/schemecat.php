<?php
class ModelExtensionModuleSchemecat extends Model 
{
    public function get_cats() 
    {
      $rows = $this->db->query("SELECT * FROM `oc_scheme_categories`");
      $rows = $this->rebuild_array($rows->rows);
      return ($rows);
    }
    private function rebuild_array($rows)
    {
      $data = array();
      foreach ($rows as $row)
      {
        $query = $this->db->query("SELECT `id` from `oc_scheme` WHERE `category_id` =". $row['id']);
        $data['rows'][] = [
          "id" => $row['id'],
          "name" => $row['name'],
          "image" => $row['image'],
          "cat_id" => $row['cat_id'],
          "count" => $query->num_rows
        ];
      }
      return($data['rows']);
    }
}
?>