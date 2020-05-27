<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="/assets/votes/css/style.css">
	<link rel="stylesheet" href="/assets/css/font-awesome.css">
</head>
<body>
	@include('Vote::votes.show', ['type'=>'products', 'type_id'=>1])
</body>
</html>
<script src="/assets/js/jquery.min.js" type="text/javascript"></script>
<script src="/assets/votes/js/script.js" type="text/javascript"></script>