<link rel="stylesheet" href="/assets/css/font-awesome.css">
<script src="/assets/votes/js/script.js" type="text/javascript"></script>
<div class="votes-star" data-type="{{ $type }}" data-typeid="{{ $type_id }}">
	@php
		$vote_star = 0;
		if(isset($_COOKIE['vote_'.$type.'_'.$type_id])){
			$vote_star = $_COOKIE['vote_'.$type.'_'.$type_id];
		}
	@endphp
	<ul>
		@for($i = 1; $i <= 5; $i++)
			<li class="item-star item-star-{{ $i }} @if($i <= $vote_star) active @endif" data-star="{{ $i }}">
				<i class="fa fa-star" aria-hidden="true"></i>
			</li>
		@endfor
	</ul>
</div>