<?php

namespace Sudo\Vote\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class VoteController extends Controller
{
	public function testVote(){
		return view('Vote::votes.index');
	}
    public function vote(Request $request){
    	$data = $request->all();
    	$result['status'] = 0;
    	if(!isset($_COOKIE['vote_'.$data['type'].'_'.$data['type_id']])){
    		$created_at = $updated_at = date('Y-m-d H:i:s');
	    	$db_insert = [
	    		'type' => $data['type'],
	    		'type_id' => $data['type_id'],
	    		'value' => $data['star'],
	    		'status' => 1,
	    		'created_at' => $created_at,
	    		'updated_at' => $updated_at,
	    	];
	    	DB::table('votes')->insert($db_insert);
	    	setcookie('vote_'.$data['type'].'_'.$data['type_id'], $data['star'], time() + (365*24*60*60), "/");
	    	$result['message'] = 'Thành công! Cám ơn bạn đã đánh giá';
	    	$result['status'] = 1;
    	}else{
    		$result['message'] = 'Không thành công! Bạn chỉ được đánh giá 1 lần';
    	}
    	return json_encode($result);
    }
}