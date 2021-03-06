<?php
    date_default_timezone_set('America/Lima');
    $fechahoy = date('j-m-Y');
?>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>{{ config('app.name', 'SIGHO') }}</title>
        <link rel="icon" href="{{ asset('dist/img/user2-160x160.jpg') }}" sizes="16x16 32x32 48x48 64x64" type="image/vnd.microsoft.icon">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Bootstrap  example.">
        {!! Html::style('bootstrap/css/bootstrap.min.css') !!}

        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            html,body {
                padding:0;
                margin:0;
                height:87%;
                min-height:100%;
            }
            .quad{
                //width:50%;
                height:100%;
                float:left;
                border-style: double;
                border-color: #2ECC71;
            }
            .line{
                height:100%;
                width:100%;
            }              

            .equal, .equal > div[class*='col-'] {  
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                flex:1 0 auto;
            }

            .centrado {
                display:none; 
                width:150px; 
                height:50px; 
                position:absolute; 
                top:5%; 
                left:50%; 
                margin-top:-25px; 
                margin-left:-75px;
                border-style: double;
                z-index: 2;
                padding:10px;
                background-color: #28B463;
                color: white;
                font-size: 20px;
                text-align: center;
                border-color: yellow;
            }

            td, th {
                padding-top: 4px;
                padding-bottom: 4px;
                padding-left: 4px;
                padding-right: 4px;
            }

        </style>
    </head>
    
    <!-- HTML code from Bootply.com editor -->
    
    <body style="">
        <div class="centrado">
            <?php echo $fechahoy; ?>
        </div>
        <div class="line">
            <div class="col-md-3 quad" id="listadoConsultas"></div>
            <div class="col-md-3 quad" id="listadoEmergencias"></div>
            <div class="col-md-3 quad" id="listadoOjos"></div>
            <div class="col-md-3 quad" id="listadoLectura"></div>
        </div>
        
    {!! Html::script('plugins/jQuery/jquery-2.2.3.min.js') !!}
    <!-- Bootstrap 3.3.6 -->
    {!! Html::script('bootstrap/js/bootstrap.min.js') !!}

    <script>
        buscar2();
        function buscar2(){
            $.ajax({
                type: "POST",
                url: "ventaadmision/cola",
                data: "_token=<?php echo csrf_token(); ?>",
                dataType: 'json',
                success: function(a) {
                    $("#listadoConsultas").html(a.consultas);
                    $("#listadoEmergencias").html(a.emergencias);
                    $("#listadoOjos").html(a.ojos);
                    $("#listadoLectura").html(a.lectura);
                }
            });
            setInterval( 
                function(){
                     $('.llamando').fadeTo(500, .1).fadeTo(500, 1) 
                }
            , 1000) ;
        }
        setInterval(buscar2, 3000);
    </script> 
    </body>
</html>