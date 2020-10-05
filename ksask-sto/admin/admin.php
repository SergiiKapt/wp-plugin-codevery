<?php

add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu()
{
    add_options_page('KSA STO Options', 'KSA STO', 'manage_options', 'ksa-sto-plugin', 'ksa_sto_admin_page');
}

function ksa_sto_admin_page()
{
    if (isset($_POST['api_save']) && $_POST['api_save'] == 'save') {
        echo "<div class='updated'>Save settings!<br></div><br><br>";
        isset($_POST['ksa_sto_api_url']) ? update_option('ksa_sto_api_url', $_POST['ksa_sto_api_url']) : false;
        isset($_POST['ksa_sto_api_key']) ? update_option('ksa_sto_api_key', $_POST['ksa_sto_api_key']) : false;
        isset($_POST['ksa_sto_post_code_min_length']) ? update_option('ksa_sto_post_code_min_length', $_POST['ksa_sto_post_code_min_length']) : 3;
    }

    ?>
    <div class="wrap ksa_sto_admin_setting">
    <h2 class="ksa_sto_title"><?php echo get_admin_page_title() ?><span class="separator"> | </span><small> Added widget "KSA STO Search"</small></h2>
    <div class='ksa_sto_message'>
    <?php
    if(get_option('ksa_sto_api_code') != KSA_STO_P_API_SUCCESS)
        echo "<div class='error'><h2>Error! Not configured to connect to API</h2></div>";
    ?>
    </div>
    <form id="ksa_sto_form_api_settings" action="" method="POST">
        <table class="form-table">
            <thead>
            <tr>
                <td><b><i>API settings</i></b></td>
            </tr>
            </thead>
            <tbody>
            <tr class="ksa_sto_option">
                <th scope="row"><label for="ksa_sto_api_url">Url</label></th>
                <td class="">
                    <input id="ksa_sto_api_url" type="text" name="ksa_sto_api_url"
                           value="<?php echo (get_option('ksa_sto_api_url') == true) ? get_option('ksa_sto_api_url') : '' ?>">
                </td>
            </tr>
            <tr class="ksa_sto_option">
                <th scope="row"><label for="ksa_sto_api_key">Key</label></th>
                <td class="">
                    <input id="ksa_sto_api_key" type="text" name="ksa_sto_api_key"
                           value="<?php echo (get_option('ksa_sto_api_key') == true) ? get_option('ksa_sto_api_key') : '' ?>">
                </td>
            </tr>
            <tr class="ksa_sto_option">
                <th scope="row"><label for="ksa_sto_post_code_min_length">Minimum number of numbers in a postcode</label><br>
                    <i>10400 - 5 digits in the number</i>
                </th>
                <td class="">
                    <input id="ksa_sto_post_code_min_length" type="number" min="<?php echo KSA_STO_P_POST_CODE_SIZE ?>" max="10" name="ksa_sto_post_code_min_length"
                           value="<?php echo (get_option('ksa_sto_post_code_min_length') == true) ? get_option('ksa_sto_post_code_min_length') : KSA_STO_P_POST_CODE_SIZE ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" name="api_save" value="save">

        <?php

        settings_fields("opt_group");
        do_settings_sections("opt_page");
        submit_button(); ?>
    </form>

    <?php
    if (get_option('ksa_sto_api_url') && get_option('ksa_sto_api_key')) { ?>
        <form id="ksa_sto_form_check_api" action="" method="POST">
            <input type="hidden" name="api_check" value="api_check">
        </form>
        <p class="submit"><input type="submit" name="submit" id="ksa_sto_submit_check_api" class="button button-primary"
                                 value="Check API connect"></p>
        <div id="ksa_sto_admin_loader_wrap">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/assets/img/loading.gif'?>" alt="" id="ksa_sto_admin_loader_img">
        </div>
        </div>
        <?php
    }
}