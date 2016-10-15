<?php
/**
 * Created by PhpStorm.
 * User: JasonQSY
 * Date: 5/20/16
 * Time: 10:18 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\User_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\JsonGeneral;
use App\Libraries\CurlLib;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Session;

final class UserController extends Controller
{
    /**
     * @var App\Libraries\JsonGeneral
     */
    private $jsonGeneral;

    /**
     * @var CurlLib
     */
    private $curl_lib;

    private $appid = '';
    private $appsecret = '';

    /**
     * UserController constructor.
     *
     * @param JsonGeneral $jsonGeneral
     * @param CurlLib $curlLib
     */
    public function __construct(JsonGeneral $jsonGeneral, CurlLib $curlLib)
    {
        $this->jsonGeneral = $jsonGeneral;
        $this->curl_lib = $curlLib;
    }

    /**
     * 登陆
     *
     * @param $request
     * @return mixed
     */
    public function login(Request $request)
    {
        if (empty($request->input('code'))) {
            // redirect
            $this_url = urlencode($request->path());
            $gate_url = "http://www.weixingate.com/api/v1/wgate_oauth?back=$this_url&force=1";
            redirect($gate_url);
        } else {
            // read
            $wechat_code = $request->input('code');
            $wechat_wgateid = $this->curl_lib->get_from("http://api.weixingate.com/v1/wgate_oauth/userinfo?code=$wechat_code");
            $result = User_model::where('wechat_openid', $wechat_wgateid)->get()->first();
            if (!empty($result)) {
                //Auth::loginUsingId($result->uid);
                Auth::login($result);
                $session = Session();
                $session->set('wx_id', $wechat_wgateid);
                return $this->jsonGeneral->show_success($result);
            } else {
                $new_user = $this->register($wechat_wgateid);
                $session = Session();
                $session->set('wx_id', $wechat_wgateid);
                return $this->jsonGeneral->show_success($new_user);
            }
        }
    }

    /**
     * 注册
     *
     * @param $wechat_wgateid
     * @return mixed
     */
    private function register($wechat_wgateid)
    {
        $user = new User_model();
        $user->wechat_openid = $wechat_wgateid;
        $user->save();
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $data = [];
        if (!empty($user->username)) {
            $data['username'] = $user->username;
        } else {
            $data['username'] = [];
        }
        if (!empty($user->email)) {
            $data['email'] = $user->email;
        } else {
            $data['email'] = [];
        }

        return $this->jsonGeneral->show_success($data);
    }

    public function update_profile(Request $request)
    {
        $user = $request->user();
        $new_user = User_model::find($user->uid);
        if (!empty($request->input('username'))) {
            $new_user->name = $request->input('username');
        }
        if (!empty($request->input('email'))) {
            $new_user->email = $request->input('email');
        }
        $new_user->save();
        return $this->jsonGeneral->show_success();
    }

    /**
     * 查不到 FALSE
     *
     * @param $uid
     * @return mixed
     */
    public function get_username_by_uid($uid)
    {
        $result = DB::select('select * from users where uid = ?', [$uid]);
        if (!$result) {
            return FALSE;
        }
        $username = $result[0]->username;
        return $username;
    }

    /**
     * 查不到 FALSE
     *
     * @param $username
     * @return bool | int
     */
    public function get_uid_by_username($username)
    {
        $result = DB::select('select * from users where username = ?', [$username]);
        if (!$result) {
            return false;
        }
        $uid = $result[0]->uid;
        return $uid;
    }

    /**
     * As the function name shows
     *
     * @param $wx_id
     * @return int
     */
    public function get_uid_by_wxid($wx_id)
    {
        $result = DB::select('select * from users where wechat_openid = ?', [$wx_id]);
        if (!$result) {
            return FALSE;
        }
        $uid = $result[0]->uid;
        return $uid;
    }
}
