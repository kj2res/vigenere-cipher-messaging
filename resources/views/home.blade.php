@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Message Box
                </div>
                <div class="panel-body">
                    <ul class="chat">

                    </ul>
                </div>
                <div class="panel-footer">
                    <input type="hidden" name="user" id="user" value="{{Auth::user()->name}}">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <div class="input-group">
                        <input id="btn-input-keyword" type="text" class="form-control input-sm" placeholder="Keyword" />
                    </div>
                    <br>
                    <div class="input-group">

                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Message" />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {

            $("#btn-chat").click(function() {

                var message = $("#btn-input").val();
                var keyword = $("#btn-input-keyword").val();
                var user = $("#user").val();
                var token = $("#_token").val();

                if(message != "" && keyword != "") {
                    $.ajax({
                        url: "/message",
                        type: "POST",
                        data: {
                            keyword: keyword,
                            message: message,
                            _token: token
                        },
                        dataType: "json",
                        success : function(data) {
                            $(".chat").append("<div class='right clearfix' style='border-bottom: 1px solid #ddd;margin-bottom: 10px;'><span class='pull-right'><input type='text' id='key-"+data.data.id+"' value='' placeholder='keyword'/>&nbsp;<button class='btn-decode btn btn-sm btn-primary' id='"+ data.data.id +"'>Decode</button></span><strong class='pull-left primary-font'>"+user+" </strong><br><p id='text-"+data.data.id+"'>"+data.data.encrypted+"</p></li>");
                        }
                    });

                    $("#btn-input").val("");
                    $("#btn-input-keyword").val("");
                }
            });

            $(document).on("click", '.btn-decode', function() {

                var targetCode = $(this).attr('id');
                var targetKeyWord = $('#key-' + targetCode).val();
                var token = $("#_token").val();

                $.ajax({
                    url: "/message/decode",
                    type: "POST",
                    data: {
                        keyword: targetKeyWord,
                        messageId: targetCode,
                        _token: token
                    },
                    dataType: "json",
                    success : function(data) {
                        if(data.data != false) {
                            $('#text-'+targetCode).html('<span style="color:red">' + data.data.message + '</span>');
                            $('#key-' + targetCode).val("");
                        }
                        else {
                            alert('Invalid Keyword');
                        }
                    }
                });
            });

            $.ajax({
                url: "/message",
                type: "GET",
                dataType: "json",
                success : function(data) {
                    console.log(data);
                    $.each( data, function(index, value) {

                        var user = value.message_from.name;
                        var message = value.encrypted;

                        $(".chat").append("<div class='right clearfix' style='border-bottom: 1px solid #ddd;margin-bottom: 10px;'><span class='pull-right'><input type='text' id='key-"+value.id+"' value='' placeholder='keyword'/>&nbsp;<button class='btn-decode btn btn-sm btn-primary' id='"+ value.id +"'>Decode</button></span><strong class='pull-left primary-font'>"+user+" </strong><br><p id='text-"+value.id+"'>"+message+"</p></div>");
                    });
                }
            });
        });

        $(document).keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13') {
                $("#btn-chat").click();
            }
        });
    </script>
@endsection
