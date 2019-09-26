<?php
if ( !class_exists('WP_EX_PAGE_ON_THE_FLY') ) {
  /**
  * WP_EX_PAGE_ON_THE_FLY
  * @author Ohad Raz
  * @since 0.1
  * Class to create pages "On the FLY"
  * Usage: 
  *   $args = array(
  *   'slug' => 'fake_slug',
  *   'post_title' => 'Fake Page Title',
  *   'post content' => 'This is the fake page content'
  *   );
  *   new WP_EX_PAGE_ON_THE_FLY($args);
  */
  class WP_EX_PAGE_ON_THE_FLY {

    public $slug ='';
    public $args = array();

    /**
     * __construct
     * @param array $arg post to create on the fly
     * @author Ohad Raz 
     * 
     */
    function __construct($args){
      add_filter('the_posts',array($this,'fly_page'));
      $this->args = $args;
      $this->slug = $args['slug'];
    }

    /**
     * fly_page 
     * the Money function that catches the request and returns the page as if it was retrieved from the database
     * @param  array $posts 
     * @return array 
     * @author Ohad Raz
     */
    public function fly_page($posts) {
      global $wp,$wp_query, $skb_options;
      
      $page_slug = $this->slug;

      $comp_slug = ""; $parent = array_key_exists("parent",$this->args) ? $this->args['parent'] : "";

      if( is_object($wp) && is_array($wp->query_vars) && array_key_exists('pagename', $wp->query_vars) ) {
 
        $comp = explode("/", $wp->query_vars['pagename']);

        if( count($comp) > 1 ) {
          $comp_slug = $comp[1];
          $parent = $comp[0];

        } else {
          $comp_slug = $comp[0];
        }
      }

      //check if user is requesting our fake page
      if( is_object($wp) && count($posts) == 0 && (strtolower($wp->request)) == $page_slug || $comp_slug == $page_slug ) {

        //create a fake post
        $post = new stdClass;
        $post->post_author = $skb_options['skb-btf-author'];
        if($parent !== "") {
          $p = skb_get_page_by_slug($parent);
          $post->post_parent = $p->ID;
        }
        $post->post_name = $page_slug;
        $post->guid = get_bloginfo('wpurl' . $page_slug);
        $post->post_title = $this->args['page_title'];
        //put your custom content here
        $post->post_content = $this->args['page_content'];
        //just needs to be a number - negatives are fine
        $post->ID = -42;
        $post->post_status = 'static';
        $post->comment_status = 'closed';
        $post->ping_status = 'closed';
        $post->comment_count = 0;
        //dates may need to be overwritten if you have a "recent posts" widget or similar - set to whatever you want
        $post->post_date = current_time('mysql');
        $post->post_date_gmt = current_time('mysql',1);

        $post = (object) array_merge((array) $post, (array) $this->args);
        wp_set_post_categories($post->ID, "butterflies", true);
        $posts = NULL;
        $posts[] = $post;

        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        $wp_query->is_category = false;
        unset($wp_query->query["error"]);
        $wp_query->query_vars["error"]="";
        $wp_query->is_404 = false;
      }

      return $posts;
    }
  }//end class
}//end if