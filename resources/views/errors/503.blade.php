<!DOCTYPE html>
<html lang="en">
  <head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- title --}}
    <title>{{ 'Maintenance Mode | ' . config('app.name') }}</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    {{-- 503 css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/503.css') }}">
  </head>

  <body>
    <div class="container">
      <div class="content">
        <div class="row mt-4">
          <div class="col-lg-4 offset-lg-4">
            <div class="maintanance-img-wrapper">
              <img src="{{ asset('assets/img/' . $info->maintenance_img) }}" alt="image">
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-lg-8 offset-lg-2">
            <h3 class="maintanance-txt">{!! nl2br($info->maintenance_msg) !!}</h3>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
