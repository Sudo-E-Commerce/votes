<?php

namespace Sudo\Vote\Http\Controllers\Admin;

use Sudo\Base\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use ListData;
use Form;
use DB;
class VoteController extends AdminController
{
	function __construct() {
        $this->models = new \Sudo\Vote\Models\Vote;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Đánh giá sao';
        $this->has_seo = false;
        $this->has_locale = false;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests) {
    	$listdata = new ListData($requests, $this->models, 'Vote::admin.table', $this->has_locale);
        // Build Form tìm kiếm
        // $listdata->search('name', 'Tên', 'string');
        $array_value = [1=>'1 sao', 2=>'2 sao', 3=>'3 sao', 4=>'4 sao', 5=>'5 sao',];
        $array_type  = config('SudoVotes.type');
        // dump($array_type);die;
        $listdata->search('value', 'số lượng sao', 'array', $array_value);
        $listdata->search('status', 'Trạng thái', 'array', config('app.status'));
        $listdata->search('type', 'Loại', 'array', $array_type);
        // Build các button hành động
        $listdata->btnAction('status', 1, __('Table::table.active'), 'primary', 'fas fa-edit');
        $listdata->btnAction('status', 0, __('Table::table.no_active'), 'warning', 'fas fa-edit');
        $listdata->btnAction('delete', -1, __('Table::table.trash'), 'danger', 'fas fa-trash');
        // Build bảng
        $listdata->add('value', 'Số lượng sao đánh giá', 0);
        $listdata->add('', 'Tiêu đề trang được đánh giá', 0);
        $listdata->add('type', 'Loại', 1);
        $listdata->add('', 'Thời gian', 0, 'time');
        $listdata->add('status', 'Trạng thái', 1, 'status');
        $listdata->add('', 'Sửa', 0, 'edit');
        $listdata->add('', 'Xóa', 0, 'delete');
        return $listdata->render();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        \Asset::addDirectly([asset('admin_assets/js/votes/script.js?v=2')], 'scripts', 'bottom');
        
        $type = config('SudoVotes.type');
        $type[] = 'Chọn loại';
        $type = array_reverse($type);
        $type_id = [0=>'Tiêu đề trang được đánh giá sao'];
        // Khởi tạo form
        $form = new Form;
        $form->select('type', 1, 1, 'Chọn Loại', $type, 0);
        $form->select('type_id', 0, 1, 'Tiêu đề trang được đánh giá sao', $type_id, 0);
        $form->text('value', '', 1, 'Số lượng sao đánh giá');
        $form->checkbox('status', 1, 1, 'Trạng thái');
        $form->action('add');
        // Hiển thị form tại view
        return $form->render('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $requests) {
        // Xử lý validate
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);

        if($type == "0" || $type_id == "0"){
            return back()->with([
                'type' => 'danger',
                'message' => 'Thêm mới không thành công! Thiếu dữ liệu gửi đi.'
            ]);
        }
        if($value < 1 || $value > 5){
            return back()->with([
                'type' => 'danger',
                'message' => 'Thêm mới không thành công! Số lượng sao không được nhỏ hơn 1 và lớn hơn 5'
            ]);
        }
        
        // Thêm vào DB
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $compact = compact('type','type_id','value','status','created_at','updated_at');
        $id = $this->models->createRecord($requests, $compact, $this->has_seo, true);
        // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.index', $id))->with([
            'type' => 'success',
            'message' => __('Core::admin.create_success')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
    	return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        // Dẽ liệu bản ghi hiện tại
        $data_edit = $this->models->where('id', $id)->first();
        // Khởi tạo form
        $form = new Form;
        $form->text('value', $data_edit->value, 1, 'Số lượng sao đánh giá');
        $form->checkbox('status', $data_edit->status, 1, 'Trạng thái');
        // lấy link xem
        $link = (config('app.page_models')) ? config('app.page_models')::where('id', $id)->first()->getUrl() : '';
        $form->action('edit', $link);
        // Hiển thị form tại view
        return $form->render('edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requests, $id) {
        // Lấy bản ghi
        $data_edit = $this->models->where('id', $id)->first();
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);

        //Check số sao đánh giá phải >=1 và <=5
        if($value < 1 || $value > 5){
            return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
                'type' => 'danger',
                'message' => 'Cập nhật không thành công! Số lượng sao không được nhỏ hơn 1 và lớn hơn 5'
            ]);
        }
        // Chuẩn hóa lại dữ liệu
        // Các giá trị thay đổi
        $updated_at = date('Y-m-d H:i:s');
        $compact = compact('value','updated_at');
        // Cập nhật tại database
        $this->models->updateRecord($requests, $id, $compact, $this->has_seo);
        // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
            'type' => 'success',
            'message' => __('Core::admin.update_success')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
    	//
    }

    public function getTypeId(Request $requests){
        $type = $requests->type;
        $data = DB::table($type)->where('status',1)->get();
        $array_data = [];
        foreach($data as $value){
            $array_data[$value->id] = $value->name;
        }
        if($requests->ajax()){
            $result['html'] = view('Form::base.select')->with([
                'name' => 'type_id', 
                'value'=>0,
                'required'=> 1,
                'label'=>'Tiêu đề trang được đánh giá sao',
                'options'=>$array_data,
                'select2'=> 1,
                'disabled'=>[]
            ])->render();
            return json_encode($result);
        }
    }
}