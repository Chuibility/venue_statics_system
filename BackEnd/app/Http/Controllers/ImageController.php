<?php
/**
 * Created by PhpStorm.
 * User: JasonQSY
 * Date: 5/20/16
 * Time: 7:20 PM
 */

namespace App\Http\Controllers;

use anlutro\cURL\Laravel\cURL;
use App\Http\Controllers\Controller;
use App\Http\Models\Image_model;
use App\Http\Models\Attribute_model;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use App\Libraries\JsonGeneral;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

final class ImageController extends Controller
{
    /**
     * @var JsonGeneral
     */
    private $jsonGeneral;

    /**
     * ListController constructor.
     *
     * @param JsonGeneral $jsonGeneral
     */
    public function __construct(JsonGeneral $jsonGeneral)
    {
        $this->jsonGeneral = $jsonGeneral;
    }

    /**
     * 增加一个头像数据
     *
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request) {
        // 读取数据
        $faces = json_decode($request->getContent(), true);
        //return $this->jsonGeneral->show_success($hello);
        foreach ($faces as $face) {
            $faceId = $face['faceId'];
            if ($faceId === "") {
                continue;
            }
            $faceAttributes = $face['faceAttributes'];
            $gender = trim($faceAttributes['gender']);
            $age = floatval($faceAttributes['age']);

            /* 存在则考虑笑容 */
            if (array_key_exists('smile', $faceAttributes)) {
                $smile = new Attribute_model();
                $smile->type = 'smile';
                $smile->property = floatval($faceAttributes['smile']);
                $smile->save();
            }

            // 后端检查
            if ($gender !== 'male' && $gender !== 'female') {
                return $this->jsonGeneral->show_error('gender unidentified.');
            }
            if ($age <= 0 || $age >= 200) {
                return $this->jsonGeneral->show_error('age error.');
            }

            // 检查 faceId 是否存在
            //$result = Image_model::where('faceId', $faceId)->get()->first();
            /*
            $people = DB::table('image')->get();
            foreach ($people as $single) {
                $curl = new \anlutro\cURL\cURL();
                $url = $curl->buildUrl('https://api.projectoxford.ai/face/v1.0/verify',
                    ["faceId1" => $single->faceId,
                    "faceId2" => $faceId]);
                $veriresponse = $curl->post($url, ["Content-Type" => "application/json",
                    "Ocp-Apim-Subscription-Key" => "2b83e4bd95f943ef8acfecb58ca11441"]);
                $result = json_decode($veriresponse->body, true);
                return $this->jsonGeneral->show_success($result);
                if ($result['isIdentical'] !== 'false') {
                    return $this->jsonGeneral->show_success('identical face');
                }
            }*/
            /*$people = DB::table('image')->get();
            if (count($people) > 3) {
                return $this->jsonGeneral->show_success('success2');
            }*/

            //if (!$result) {
                // Save
                $image = new Image_model();
                $image->faceId = $faceId;
                $image->gender = $gender;
                $image->age = $age;
                $image->save();
            //}
        }
        /*
        $faceId = $request->input('faceId');
        $faceAttributes = $request->input('faceAttributes');
        $gender = trim($faceAttributes['gender']);
        $age = floatval($faceAttributes['age']);

        // 后端检查
        if ($gender !== 'male' && $gender !== 'female') {
            return $this->jsonGeneral->show_error('gender unidentified.');
        }
        if ($age <= 0 || $age >= 200) {
            return $this->jsonGeneral->show_error('age error.');
        }

        // 检查 faceId 是否存在
        $result = Image_model::where('faceId', $faceId)->get()->first();
        if (!$result) {
            // Save
            $image = new Image_model();
            $image->faceId = $faceId;
            $image->gender = $gender;
            $image->age = $age;
            $image->save();
        }

        // response
        $item = [
            'faceId' => $faceId,
            'gender' => $gender,
            'age' => $age
        ];*/
        return $this->jsonGeneral->show_success('success');
    }

    public function get(Request $request)
    {
        $list = [];
        /*$list = [
            [
                'faceId' => '123123',
                'gender' => 'male',
                'age' => 27.1
            ],
            [
                'faceId' => '123213123',
                'gender' => 'female',
                'age' => 35.1
            ]
        ];*/
        $people = DB::table('image')->get();
        foreach ($people as $single) {
            $list[] = [
                'faceId' => $single->faceId,
                'gender' => $single->gender,
                'age' => $single->age
            ];
        }
        return response()->json($list)->header('Access-Control-Allow-Origin', '*');
    }

    public function get_smile(Request $request)
    {
        $list = [];
        // 现在只有 smile, 不搜索了
        $smiles = DB::table('attribute')->get();
        foreach ($smiles as $smile) {
            $list[] = $smile->property;
        }
        return response()->json($list)->header('Access-Control-Allow-Origin', '*');
    }
}
