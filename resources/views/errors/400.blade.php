<!DOCTYPE html>
<html>
<head>
    <title>Error 400</title>

    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>

    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Error 400: {{ substr($exception, strpos($exception, "with message '") + 14, (strpos($exception, "' in ") - strpos($exception, "with message '")) - 14) }}</div>
        {{--{{ (strpos($exception, "' in "))  }}--}}
{{--        {{ strpos($exception, "with message '") }}--}}
    </div>
</div>
</body>
</html>
