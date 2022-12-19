<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Scraper!</title>
</head>
<body>

<div style="height: 100vh" class="container d-flex justify-content-center align-items-center">
    <div style="border-radius: 8px" class="bg-info w-50 p-4">
        <form method="POST" action="{{ route('scrape') }}">
            @csrf
            <div class="form-group">
                <label class="text-light" for="exampleFormControlSelect1">Category</label>
                <select name="category" class="form-control" id="exampleFormControlSelect1">
                    @foreach($categories as $category)
                        <option value="{{ json_encode($category) }}" {{ $category['path'] == 0 ? 'disabled' : '' }}>{{ $category['text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="text-light" for="exampleFormControlSelect1">Scroll Times</label>
                <input class="form-control" name="scroll_times" type="number">
            </div>
            <button class="btn btn-success btn-block">Result</button>
        </form>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
