<?php
add_action('admin_init', 'ksa_sto_script_admin');
function ksa_sto_script_admin()
{
    wp_enqueue_style('ksa-sto-admin-style', KSA_STO_P_URI . 'assets/css/admin.css');

    wp_enqueue_script('ksa-sto-admin', KSA_STO_P_URI . 'assets/js/admin.js', array('jquery'), null,true);
}