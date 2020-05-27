$('document').ready(function(){
	$('select#type').on('change', function(){
		var type = $(this).val();
		$.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            dataType: "json",
            type: "POST",
            data: {type:type},
            url: "/admin/votes/get-typeid",
            beforeSend:function(){
            	activeProgress(0);
            },
            success: function (data) {
                activeProgress(99);
                $('body select#type_id').parents('.form-group').remove();
                $('input#value').parents('.form-group').before(data.html);
            },
            error: function () {
            	activeProgress(99);
                alert("Có lỗi sảy ra");
            },
        });
	});
});