<?php
/**
 * Created by PhpStorm.
 * User: JasonQSY
 * Date: 5/20/16
 * Time: 10:21 PM
 */

namespace App\Libraries;

use Illuminate\Http\Response;

final class JsonGeneral
{
    /**
     * @var array
     */
    private $json_data = [
        'version' => '1.0'
    ];

    /**
     * JsonGeneral constructor.
     */
    public function __construct()
    {
        // do something
    }

    /**
     * @param string $msg
     * @return mixed
     */
    public function show_success($msg = 'success')
    {
        $this->json_data['error'] = 0;
        $this->json_data['msg'] = $msg;
        return response()->json($this->json_data);
    }

    /**
     * @param string $msg
     * @return mixed
     */
    public function show_error($msg = 'error')
    {
        $this->json_data['error'] = 1;
        $this->json_data['msg'] = $msg;
        return response()->json($this->json_data);
    }

}
