<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-
    alpha/css/bootstrap.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

{{-- Flassmessage --}}
<script>
$(function(){
    // Owner情報更新用message
    @if(Session::has('success'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.success("{{ Session::get('success') }}");
    @elseif (Session::has('update'))
    toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.info("{{ Session::get('update') }}");
    @elseif (Session::has('trash'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.warning("{{ Session::get('trash') }}");
    @elseif (Session::has('delete'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.warning("{{ Session::get('delete') }}");
    @endif

    // shop,image,product情報
    @if (Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.info("{{ Session::get('info') }}");
    @elseif (Session::has('alert'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-top-center",

        }
        toastr.warning("{{ Session::get('alert') }}");
    @endif
});

</script>
