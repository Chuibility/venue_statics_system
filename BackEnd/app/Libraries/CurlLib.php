<?php
/**
 * Created by PhpStorm.
 * User: JasonQSY
 * Date: 5/22/16
 * Time: 6:46 PM
 */

namespace App\Libraries;

/**
 * Class CurlLib
 *
 * 感谢肖腿的代码
 */
final class CurlLib
{
    public function __construct()
    {
        // do something
    }

    public function get_from($url, $timeout = 10)
    {
        $url_ch = curl_init();
        curl_setopt($url_ch, CURLOPT_URL, $url);
        curl_setopt($url_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($url_ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($url_ch);
        curl_close($url_ch);
        $json = json_decode($data, true);
        return $json;
    }
}
