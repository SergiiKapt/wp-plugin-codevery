<?php
add_action('widgets_init', 'ksa_sto_search');
function ksa_sto_search()
{
    register_widget('ksaStoSearch');
}

class ksaStoSearch extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'ksa_sto_search_widget',
            'KSA STO Search',
            [
                'description' => 'Search auto service on API data',
            ]
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if (get_option('ksa_sto_api_code') == KSA_STO_P_API_SUCCESS) {
            echo '<div class="ksa_sto_wrap">';
            if (!empty($title))
                echo $args['before_title'] . $title . $args['after_title'];
            ?>
            <div class="ksa_sto_form_wrap">
                <form autocomplete="off" action="" method="post">
                <div class="ksa_sto_search_wrap"><input id="ksa_sto_search" class="ksa_sto_search" type="text" placeholder="post code">
                    <img src="<?php echo KSA_STO_P_URI .'/assets/img/loading.gif'?>" alt="" class="ksa_sto_search_img">
                </div>
                </form>
                <div class="ksa_sto_search_res" id="ksa_sto_search_res"></div>
                <div class="ksa_sto_franchise" id="ksa_sto_franchise"></div>
            </div>
            </div>
            <?php
            echo $args['after_widget'];

            $this->scripts();
        }
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Franchises information';
        }
        ?>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo esc_attr($title); ?>"/>
        <br>
        <br>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

    function scripts()
    {
        wp_enqueue_style('ksa-sto-style', KSA_STO_P_URI . 'assets/css/style.css');
        wp_enqueue_script('ksa-sto', KSA_STO_P_URI . 'assets/js/script.js', array('jquery'), null, false);
        wp_localize_script('ksa-sto', 'ksaSto',
            array(
                'url' => admin_url('admin-ajax.php'),
                'sizePostCode' => KSA_STO_P_POST_CODE_SIZE,
                'urlImg' => get_option('ksa_sto_api_url')
            )
        );


    }
}


