<table>
	<thead>
		<tr>
			<td>title</td>
			<td>price</td>
			<td>address</td>
		</tr>
	</thead>
	<tbody>
		@foreach($contents as $content)
		<tr>
			<td>{{ $content->title }}</td>
			<td>{{ $content->price }}</td>
			<td>{{ $content->address }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $contents->links() }}