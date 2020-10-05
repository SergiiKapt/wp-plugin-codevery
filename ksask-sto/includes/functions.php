<?php


add_action('wp_ajax_ksa_sto_get_data', 'ksa_sto_get_data_callback');
add_action('wp_ajax_nopriv_ksa_sto_get_data', 'ksa_sto_get_data_callback');
function ksa_sto_get_data_callback()
{

    if (setSessionDataApi($_POST['param']))
        echo 'success';
    die;
}

function setSessionDataApi($post_code)
{
    if (isset($post_code) && get_option('ksa_sto_api_code') == KSA_STO_P_API_SUCCESS) {
        parse_str(trim($post_code, '?'), $output);
        if (isset($output['post_code'])) {
            if (!session_id()) {
                session_start();
            }
            if (!isset($_SESSION['ksa_sto_data'])) {
                $_SESSION['ksa_sto_data'] = FranchiseSearch::connectApi();

            }
            return $output['post_code'];
        }
    }
    delete_option('ksa_sto_api_code');

    return false;
}

add_action('wp_ajax_ksa_sto_search_post_code', 'ksa_sto_search_post_code_callback');
add_action('wp_ajax_nopriv_ksa_sto_search_post_code', 'ksa_sto_search_post_code_callback');
function ksa_sto_search_post_code_callback()
{
    $postCode = setSessionDataApi($_POST['param']);
    echo json_encode(FranchiseSearch::search($postCode));
    die;
}

add_action('wp_ajax_ksa_sto_admin_api_connect_check', 'ksa_sto_admin_api_connect_check_callback');
function ksa_sto_admin_api_connect_check_callback()
{
    if (isset($_POST['param'])) {
        parse_str(trim(($_POST['param']), '?'), $output);
        isset($output['ksa_sto_api_url']) ? update_option('ksa_sto_api_url', $output['ksa_sto_api_url']) : remove_option('ksa_sto_api_url');
        isset($output['ksa_sto_api_key']) ? update_option('ksa_sto_api_key', $output['ksa_sto_api_key']) : remove_option('ksa_sto_api_key');
    }

    switch (FranchiseSearch::getApiStatus()) {
        case 200:
            $response = [
                'code' => 200,
                'message' => "<div class='updated'><h4>API successfully connected!</h4></div>"
            ];
            break;
        case 404 :
            $response = [
                'code' => 404,
                'message' => "<div class='error'><h4>The Key is not correct!</h4></div>"
            ];
            break;
        case 418 :
            $response = [
                'code' => 418,
                'message' => "<div class='error'><h4>Key not specified!</h4></div>"
            ];
            break;
        case 401 :
            $response = [
                'code' => 401,
                'message' => "<div class='error'><h4>Key timed out!</h4></div>"
            ];
            break;
        default :
            $response = [
                'code' => 500,
                'message' => "<div class='error'><h4>Error!</h4></div>"
            ];
    }
    echo json_encode($response);
    die;
}


