<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="wrapper">

            <div class="content">
                <table class="table">
					<thead>
						<tr>
							<td>title ({{ $anchors->total() }}) </td>
							<td>site</td>
						</tr>
					</thead>
					<tbody>
						@foreach($anchors as $anchor)
						<tr>
							<td><a href="detail/{{ $anchor->id }}">{{ $anchor->link }}</a></td>
							<td>{{ $anchor->type }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				{{ $anchors->links() }}
            </div>
        </div>
    </body>
</html>

