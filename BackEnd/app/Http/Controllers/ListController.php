<?php
/**
 * Created by PhpStorm.
 * User: JasonQSY
 * Date: 5/20/16
 * Time: 7:20 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Act_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Input;
use App\Libraries\JsonGeneral;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

final class ListController extends Controller
{
    /**
     * @var \App\Http\Controllers\UserController
     */
    private $userController;

    /**
     * @var JsonGeneral
     */
    private $jsonGeneral;

    /**
     * ListController constructor.
     *
     * @param \App\Http\Controllers\UserController $userController
     * @param JsonGeneral $jsonGeneral
     */
    public function __construct(UserController $userController, JsonGeneral $jsonGeneral)
    {
        $this->userController = $userController;
        $this->jsonGeneral = $jsonGeneral;
    }

    /**
     * 获取所有活跃的订单
     *
     * API: /list/get
     */
    public function index()
    {
        $returnList = [];
        $list = Act_model::where('state', 0)->get();
        foreach ($list as $item) {
            $creator = $this->userController->get_username_by_uid($item->creator_uid);
            $people = [];
            if ($item->people1_uid !== -1) {
                $people[] = $this->userController->get_username_by_uid($item->people1_uid);
            }
            if ($item->people2_uid !== -1) {
                $people[] = $this->userController->get_username_by_uid($item->people2_uid);
            }
            if ($item->people3_uid !== -1) {
                $people[] = $this->userController->get_username_by_uid($item->people3_uid);;
            }
            $returnList[] = [
                'act_id' => $item->act_id,
                'creator' => $creator,
                'name' => $item->name,
                'people' => $people,
                'from' => $item->from,
                'to' => $item->to,
                'expectedNum' => $item->expectedNumber,
                'state' => $item->state
            ];
        }
        return $this->jsonGeneral->show_success($returnList);
    }

    /**
     * 获取一个订单的具体信息
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $item = Act_model::find($id);
        if (empty($item)) {
            return $this->jsonGeneral->show_error('id not found');
        }

        $creator = $this->userController->get_username_by_uid($item->creator_uid);
        $people = [];
        if ($item->people1_uid !== -1) {
            $people[] = $this->userController->get_username_by_uid($item->people1_uid);
        }
        if ($item->people2_uid !== -1) {
            $people[] = $this->userController->get_username_by_uid($item->people2_uid);
        }
        if ($item->people3_uid !== -1) {
            $people[] = $this->userController->get_username_by_uid($item->people3_uid);;
        }
        $returnItem = [
            'creator' => $creator,
            'name' => $item->name,
            'people' => $people,
            'from' => $item->from,
            'to' => $item->to,
            'expectedNum' => $item->expectedNumber,
            'state' => $item->state
        ];
        return $this->jsonGeneral->show_success($returnItem);
    }

    /**
     * 创建一个订单
     *
     * @param $request
     * @return bool->status
     */
    public function add(Request $request)
    {
        $act = new Act_model();
        $act->creator_uid = $request->user()->uid;
        $act->name = $request->input('name');
        $act->from = $request->input('from');
        $act->to = $request->input('to');
        $act->expectedNumber = $request->input('expectedNum');
        $act->people1_uid = -1;
        $act->people2_uid = -1;
        $act->people3_uid = -1;
        $act->state = 0;
        $act->save();
        return $this->jsonGeneral->show_success();
    }

    /**
     * 更新一个订单
     *
     * @param $request
     */
    public function creatorUpdate(Request $request)  //Post
    {
        $act_id = $request->input('act_id');
        $result = Act_model::where('act_id', $act_id)->get()->first();
        if (!$result) {
            return $this->jsonGeneral->show_error('Invalid act id');
        }
        $item = Act_model::find($act_id);
        $name = Input::get('name');
        $from = Input::get('from');
        $to = Input::get('to');
        $expectedNumber = Input::get('expectedNum');
        $state = Input::get('state');
        if ($name) {
            DB::table('act')->where('id',$id)->update(['name' => $name]);
        }
        if ($from) {
            DB::table('act')->where('id',$id)->update(['from' => $from]);
        }
        if ($to) {
            DB::table('act')->where('id',$id)->update(['to' => $to]);
        }
        if ($expectedNumber >= $item->expectedNumber){
            DB::table('act')->where('id',$id)->update(['expectedNumber' => $expectedNumber]);
        }
        if ($state===0 || $state===1) {
            DB::table('act')->where('id',$id)->update(['state' => $state]);
        }
        $act = Act_model::find($act_id);
        return $this->jsonGeneral->show_success($act);
    }

    /**
     * 离开订单
     *
     * @param Request $request
     * @return mixed
     */
    public  function peopleDropout(Request $request)
    {
        $act_id = $request->input('act_id');
        $people_uid = $request->user()->uid;
        $act = Act_model::find($act_id);
        if (empty($act)) {
            return $this->jsonGeneral->show_error();
        }

        switch ($people_uid) {
            case $item->people1_uid :
                DB::table('act')->where('id',$id)->update(['people1_uid' => -1]);
                break;
            case $item->people2_uid :
                DB::table('act')->where('id',$id)->update(['people2_uid' => -1]);
                break;
            case $item->people3_uid :
                DB::table('act')->where('id',$id)->update(['people3_uid' => -1]);
                break;
            default :
                return $this->jsonGeneral->show_error("Invalid User");
        }

        $act = Act_model::find($act_id);
        return $this->jsonGeneral->show_success($act);
    }

    /**
     * 移除一个订单
     *
     * 刚开始有什么好移除的吗,我懒得写了
     *
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        return $this->jsonGeneral->show_error('Not supported');

        // remove according to id.
        /*
        try {
            $res = DB::delete('delete from act WHERE act_id = ?', [$id]);  //The other act_id(s) will not change
        } catch (Exception $e) {
            return $this->jsonGeneral->show_error("Database error");
        }
        if ($res) {
            return $this->jsonGeneral->show_success(); //Use redirect and sessions?
        } else {
            return $this->jsonGeneral->show_error("Invalid act id");
        }*/
    }
}