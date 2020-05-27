## Hướng dẫn sử dụng Sudo Page ##

**Giới thiệu:** Đây là module đánh giá sao của SudoCms.

Mặc định package sẽ tạo ra giao diện quản lý cho module được đặt tại `/{admin_dir}/votes`, trong đó admin_dir là đường dẫn admin được đặt tại `config('app.admin_dir')`

### Cài đặt để sử dụng ###

- Package cần phải có base `sudo/core` để có thể hoạt động không gây ra lỗi
- Để có thể sử dụng Package cần require theo lệnh `composer require sudo/vote`
- Chạy `php artisan migrate` để tạo các bảng phục vụ cho package
- Thêm câu lệnh @include('Vote::web.show', ['type'=>$table, 'type_id'=>$table_id]) vào vị trí muốn hiển thị tính năng đánh giá
- Thêm cặp key => value trong mảng type tại file config/SudoVotes.php (File này được publics ra từ package) tương ứng với các trang có tính năng đánh giá 
	VD: 'type' => [
			'pages' => 'Trang đơn',
			'products' => 'Sản phẩm',
			'news' => 'Tin tức',
		]
### Cấu hình tại Menu ###

	[
    	'type' 		=> 'single',
		'name' 		=> 'Đánh giá sao',
		'icon' 		=> 'fas fa-star',
		'route' 	=> 'admin.votes.index',
		'role'		=> 'votes_index'
    ],
 
- Vị trí cấu hình được đặt tại `config/SudoMenu.php`
- Để có thể hiển thị tại menu, chúng ta có thể đặt đoạn cấu hình trên tại `config('SudoMenu.menu')`

### Cấu hình tại Module ###
	
	'votes' => [
		'name' 			=> 'Đánh giá sao',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],

- Vị trí cấu hình được đặt tại `config/SudoModule.php`
- Để có thể phân quyền, chúng ta có thể đặt đoạn cấu hình trên tại `config('SudoModule.modules')`
 
### Lưu ý ###

- Tính năng có sử dụng Font Awesome Icon nên yêu cầu sử dụng thư viện này
- Tính năng có chèn code jquery trực tiếp trong view nên yêu cầu load thư viện jquery trong cặp thẻ head để tránh lỗi.