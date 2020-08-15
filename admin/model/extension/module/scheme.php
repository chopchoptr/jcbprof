<?php
class ModelExtensionModuleScheme extends Model
{
    // init funcs

    public function create_db_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `oc_scheme_categories` (
            `id` int(11) NOT NULL primary key AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        $this->db->query("CREATE TABLE IF NOT EXISTS `oc_scheme` (
            `id` int(11) NOT NULL primary key AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `engine` int(11) NOT NULL,
            `category_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        $this->db->query("CREATE TABLE IF NOT EXISTS `oc_scheme_point` (
            `id` int(11) NOT NULL primary key AUTO_INCREMENT,
            `scheme_id` int(11) NOT NULL,
            `x` int(11) NOT NULL,
            `y` int(11) NOT NULL,
            `num` int(11) NOT NULL,
            `desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `filter` text COLLATE utf8mb4_unicode_ci
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function drop_db_table()
    {
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme_categories`");
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme`");
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme_point`");
    }

    public function SaveSettings()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_scheme', $this->request->post);
    }

    public function LoadSettings()
    {
        return $this->config->get('module_scheme_status');
    }

    // category funcs

    public function add_category()
    {
        $name = $this->defend_str($this->request->post['name']);
        if(!empty($name))
            $this->db->query("INSERT INTO `oc_scheme_categories` (`name`) VALUES ('".$name."')");
    }

    public function get_category()
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme_categories`");
        return($query->rows);     
    }

    public function change_category()
    {
        $name = $this->defend_str($this->request->post['name']);
        if(!empty($name))
            $query = $this->db->query("UPDATE `oc_scheme_categories` SET `name` = '".$name."' WHERE `id` = ". $this->request->post['id']);
    }

    public function delete_category()
    {
        //TODO: change schemes WHERE cat id == delete cat id
        $this->db->query("DELETE FROM `oc_scheme_categories` WHERE `id` = ". $this->request->post['id']);
    }

    // scheme funcs
    public function add_scheme()
    {
        $name = $this->defend_str($this->request->post['scheme_name']);
        $engine = (int) $this->request->post['scheme_engine'] >= 0 ? $this->request->post['scheme_engine']  : 0 ;
        $cat = (int) $this->request->post['scheme_cat'] >= 0 ? $this->request->post['scheme_cat']  : 0 ;
        if (!empty($name) && isset($_FILES["scheme_image"]))
        {
            $image = $this->upload_image($_FILES["scheme_image"]);
            if ($image != -1)
                $this->db->query("INSERT INTO `oc_scheme` (`name`,`image`, `engine`, `category_id`) VALUES ('". $name ."', '". $image ."',". $engine .", ". $cat .")");
        }
    }

    public function get_scheme()
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme`");
        return($query->rows);   
    }

    public function change_scheme()
    {
        $name = $this->defend_str($this->request->post['scheme_name']);
        $engine = (int) $this->request->post['scheme_engine'] >= 0 ? $this->request->post['scheme_engine']  : 0 ;
        $cat = (int) $this->request->post['scheme_cat'] >= 0 ? $this->request->post['scheme_cat']  : 0 ;
        if (!empty($name))
        $query = $this->db->query("UPDATE `oc_scheme` SET `name` = '".$name."', `engine` = ". $engine .", `category_id` ". $cat ." WHERE `id` = ". $this->request->post['id']);
    }

    public function delete_scheme()
    {
        $query = $this->db->query("SELECT `image` FROM `oc_scheme` WHERE `id` = ".$this->request->post['id']);
        $file = $query->rows[0]['image'];
        $file = DIR_IMAGE."scheme/".$file;
        $this->db->query("DELETE FROM `oc_scheme` WHERE `id` = ". $this->request->post['id']);
        unlink($file);
    }

    public function get_scheme_by_id($id)
    {
        $query = $this->db->query("SELECT `name`, `image` FROM `oc_scheme` WHERE `id` = ".$id);
        return($query->rows[0]);
    }

    //point funcs
    public function update_points($id)
    {
        $data = $this->request->post;
        $upd = array();
        $new = array();
        $i = 0;
        while (isset($data['point'][$i])) 
        {
            $row = ['point' => $data['point'][$i],
                    'id'    => $data['id'][$i],
                    'x'     => $data['x-coord'][$i],
                    'y'     => $data['y-coord'][$i],
                    'desc'  => $data['desc'][$i]];
            if ($row['id'] == 0)
                array_push($new, $row);
            else
                array_push($upd, $row);
            $i++;
        }
        $old = $this->get_points($id);
        $del = $this->get_del_points_id($upd, $old);
        foreach ($del as $del_id) 
            $this->delete_point($del_id);
        foreach ($upd as $row) 
            $this->change_point_desc($row);
        foreach($new as $row)
            $this->add_point($row, $id);
    }

    private function add_point($row, $scheme_id)
    {
        $desc = $this->defend_str($row['desc']);
        $x = (int) $row['x'];
        $y = (int) $row['y'];
        $num = (int) $row['point'];
        $scheme = (int) $scheme_id;
        $this->db->query("INSERT INTO `oc_scheme_point` (`scheme_id`,`x`, `y`, `num`, `desc`) VALUES (". $scheme .", ". $x .",". $y .", ". $num .", '". $desc ."')");
    }

    public function get_filters()
    {
        $query = $this->db->query("select  a.name as filter, a.filter_id, a.filter_group_id, b.name as group_name from oc_filter_description a join oc_filter_group_description b on a.filter_group_id = b.filter_group_id ORDER BY a.filter_group_id");
        return ($query->rows);
    }

    public function get_points($id)
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme_point` WHERE `scheme_id` = ".$id);
        return($query->rows);
    }

    public function get_point($id)
    {
        $query = $this->db->query("SELECT * FROM `oc_scheme_point` WHERE `id` = ".$id);
        return($query->rows[0]);
    }

    public function update_point($id)
    {
        $data = implode(",", $this->request->post['filter_id']);
        $this->db->query("UPDATE `oc_scheme_point` SET `filter` = '". $data."' WHERE `id` = ". $id);

    }

    private function change_point_desc($data)
    {
        $this->db->query("UPDATE `oc_scheme_point` SET `num` = ". $data['point'] .", `desc` = '". $data['desc'] ."' WHERE `id` = ". $data['id']);
    }

    public function delete_point($id)
    {
        $this->db->query("DELETE FROM `oc_scheme_point` WHERE `id` = ". $id);
    }

    private function defend_str($string = "")
    {
      $str = trim($string);
      $str = stripslashes($str);
      $str = strip_tags($str);
      $str = htmlspecialchars($str);
      return ($str);
    }

    private function upload_image($img)
    {
        $mime = ["image/gif", "image/jpeg", "image/png"];
        $tmp = explode('.', $img['name']);
        $ext = end($tmp);
        $file = $img['tmp_name'];
        $info = getimagesize($file);
        if (!isset($info))
            return (-1);
        if ($info[0] > 0 && $info[1] > 0)
            if (in_array($info['mime'], $mime))
            {
                $name = md5(time().$img['name']).".".$ext;
                move_uploaded_file($img['tmp_name'], DIR_IMAGE."scheme/".$name);
                return ($name);
            }
        return (-1);
    }

    private function get_del_points_id($upd, $old)
    {
        $upd_id = array_column($upd, 'id');
        $old_id = array_column($old, 'id');
        $arr = array_diff($old_id, $upd_id);
        return ($arr);
    }
}
?>