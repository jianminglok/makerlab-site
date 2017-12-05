<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ $csrf_token }}">
  <title>资讯管理</title>
  
  <link rel="stylesheet" href="{{mix('css/index.css', 'zhiyicx/plus-component-news/asstes')}}">
  <!-- global config. -->
  <script type="text/javascript">
      window.NEWS = {!!
          json_encode([
              'token' => $token,
              'csrfToken' => $csrf_token,
              'baseURL' => $base_url,
              'api' => $api
          ])
      !!};
  </script>
</head>

<body>
  <div id="app"></div>
<script type="text/javascript" src="{{ mix('js/manifest.js', 'zhiyicx/plus-component-news/asstes') }}"></script>
<script type="text/javascript" src="{{ mix('js/vendor.js', 'zhiyicx/plus-component-news/asstes') }}"></script>
<script type="text/javascript" src="{{ mix('js/index.js', 'zhiyicx/plus-component-news/asstes') }}"></script>
</body>

</html>
