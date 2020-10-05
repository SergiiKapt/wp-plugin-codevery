<?php

class FranchiseSearch
{

    /**
     * @param $franchises_string string
     * @param $region_mappings_string string
     */
    function initialize($franchises_string, $region_mappings_string)
    {
        // Assign $region_mappings
        // Assign $franchises
    }

    /**
     * Should return an ordered array (by name) of franchises for this postal code with all their information
     * @param $postal_code integer
     * @return array
     */
    static function search($postal_code)
    {
        if ($postal_code && get_option('ksa_sto_api_code') == KSA_STO_P_API_SUCCESS) {
            if ($postal_code > pow(10, get_option('ksa_sto_post_code_min_length') - 1)) {
                $data = $_SESSION['ksa_sto_data'];
                $sto = json_decode($data, true);

                // check api get status success
                if ($sto["success"]) {
                    $scuderia = array_search($postal_code, array_column($sto['region_mappings_string'], 'postal_code'));
                    if ($scuderia) {
                        $franchises = [];
                        foreach ($sto["auto_service"] as $key => $franchise) {
                            $name[$key] = $franchise['franchise_name'];
                            if (strpos($franchise['region_codes'], $sto['region_mappings_string'][$scuderia]['region_code'])) {
                                $franchises[] = $franchise;
                            }
                            if (count($franchises) == 3)
                                break;
                        }
                        if (!count($franchises)) {
                            return [
                                'status' => 'error',
                                'message' => 'Not found franchise'
                            ];
                        }

                        $name = array_column($franchises, 'franchise_name');
                        array_multisort($name, SORT_STRING, $franchises);

                        return [
                            'status' => 'success',
                            'data' => $franchises
                        ];
                    } else {
                        return [
                            'status' => 'error',
                            'message' => 'Not found post code'
                        ];
                    }
                } else {
                    remove_option('ksa_sto_api_code');
                }
            }

        }
        return [
            'status' => 'error',
            'message' => 'Error data'

        ];
    }

    static function connectApi($status = false)
    {
        $url = get_option('ksa_sto_api_url');
        $key = get_option('ksa_sto_api_key');
        $httpCode = false;
        if ($url && $key) {
            $ch = curl_init($url);
            $headers = [
                'Api-Key:' . $key,
                'Accept: application/json'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            if (!curl_errno($ch)) {
                $info = curl_getinfo($ch);
                update_option('ksa_sto_api_code', $info['http_code']);
                $httpCode = $info['http_code'];
            } else {
                delete_option('ksa_sto_api_code');
            }
            curl_close($ch);

            if($status)
                return $httpCode;

            return $data;
        }

        return false;
    }

    static function getApiStatus()
    {
        return self::connectApi(true);
    }
}