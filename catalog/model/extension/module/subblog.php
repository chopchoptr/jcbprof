<?php
class ModelExtensionModuleSubblog extends Model 
{
    public function getPosts($limit)
    {
        $posts_id = $this->db->query("SELECT `post_id`, `image` FROM `oc_bm_post` ORDER BY `date_published` DESC LIMIT ".$limit);
        $posts = array();
        foreach ($posts_id->rows as $post)
        {
            $query = $this->db->query("SELECT `title`, `short_description` FROM `oc_bm_post_description` WHERE `post_id`=".$post['post_id']);
            $posts['posts'][] = [
                "title" => $query->row['title'],
                "desc"  => $query->row['short_description'],
                "image" => $post['image'],
                "link"  => "/?route=route=extension/d_blog_module/post&post_id=".$post['post_id'],
            ];
        }
        return($posts['posts']); 
    }
}
?>