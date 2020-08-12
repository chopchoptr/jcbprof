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
            `filter` text COLLATE utf8mb4_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function drop_db_table()
    {
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme_categories`");
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme`");
        $this->db->query("DROP TABLE IF EXISTS `oc_scheme_point`");
    }

    // category funcs

    public function add_category()
    {

    }

    public function get_category()
    {
        
    }

    public function change_category()
    {

    }

    public function delete_category()
    {

    }

    // scheme funcs
    public function add_scheme()
    {

    }

    public function get_scheme()
    {
        
    }

    public function change_scheme()
    {
        
    }

    public function delete_scheme()
    {
        
    }

    //point funcs
    public function add_point()
    {

    }

    public function get_point()
    {
        
    }

    public function change_pont()
    {
        
    }

    public function delete_point()
    {
        
    }
}
?>