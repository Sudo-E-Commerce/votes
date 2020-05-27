<td class="text_center" style="text-align: center;">
	<ul style="list-style-type: none;padding:0;margin: 10px 0 0;">
		@for($i = 1; $i<=5; $i++)
		<li style="display: inline;color: #9a9a9a; @if($i <= $value->value) color: #ff9727; @endif"><i class="fas fa-star" aria-hidden="true"></i></li>
		@endfor
	</ul>
</td>
@php
	$data = DB::table($value->type)->where('id', $value->type_id)->first();
@endphp
@include('Table::components.link',['text' => $data->name ?? 'Không xác định', 'url' => route('admin.votes.edit', $value->id)])
@include('Table::components.text',['text' => config('SudoVotes.type')[$value->type] ?? 'Không xác định'])