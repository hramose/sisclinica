<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		{{ $title }}
		{{-- <small>Descripci�n</small> --}}
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<div class="row">
						<div class="col-xs-12">
							{!! Form::open(['method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
						    
							<div class="form-group">
								{!! Form::label('fechainicial', 'Fecha Inicial:') !!}
								{!! Form::date('fechainicial', date('Y-m-d',strtotime("now",strtotime("-1 week"))), array('class' => 'form-control input-xs', 'id' => 'fechainicial')) !!}
							</div>
                            <div class="form-group">
								{!! Form::label('fechafinal', 'Fecha Final:') !!}
								{!! Form::date('fechafinal', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechafinal')) !!}
							</div>

							<div class="form-group" @if(Auth::user()->usertype_id != 1) style="display: none;" @endif id="cajas"></div>

							{!! Form::button('<i class="glyphicon glyphicon-file"></i> Consolidado PDF', array('class' => 'btn btn-danger btn-xs', 'onclick' => 'imprimirDetalleF(\'\')')) !!}

							{!! Form::button('<i class="glyphicon glyphicon-file"></i> Por cajas PDF', array('class' => 'btn btn-warning btn-xs', 'onclick' => 'imprimirDetalleF(\'2\')')) !!}

							@if($user->usertype_id==1 || $user->usertype_id==14 || $user->usertype_id==8)
								{!! Form::button('<i class="glyphicon glyphicon-print"></i> Movilidad', array('class' => 'btn btn-warning btn-xs', 'onclick' => 'imprimirMovilidadF()')) !!}
								<!--{! Form::button('<i class="glyphicon glyphicon-file"></i> Excel', array('class' => 'btn btn-success btn-xs', 'onclick' => 'imprimirExcelF()')) !!}-->
								{!! Form::button('<i class="glyphicon glyphicon-file"></i> Egresos', array('class' => 'btn btn-danger btn-xs', 'onclick' => 'egresosExcel()')) !!}
							@endif
							{!! Form::button('<i class="glyphicon glyphicon-file"></i> Consolidado Excel', array('class' => 'btn btn-success btn-xs','onclick' => 'pdfDetalleCierreExcelF(\'\')')) !!}
							{!! Form::button('<i class="glyphicon glyphicon-file"></i> Por cajas Excel', array('class' => 'btn btn-success btn-xs','onclick' => 'pdfDetalleCierreExcelF(\'2\')')) !!}
							@if($user->usertype_id==1 || $user->usertype_id==23)
								{!! Form::button('<i class="glyphicon glyphicon-file"></i> Detalle de Egresos', array('class' => 'btn btn-info btn-xs','onclick' => 'pdfDetalleEgresos()')) !!}
							@endif
							@if($user->usertype_id==1 || $user->usertype_id==11 )
								{!! Form::button('<i class="glyphicon glyphicon-print"></i> Por Producto', array('class' => 'btn btn-primary btn-xs', 'id' => 'btnBuscar', 'onclick' => 'detallePorProducto();')) !!}
							@endif
							<!--
							{! Form::button('<i class="glyphicon glyphicon-file"></i> Exportar Excel', array('class' => 'btn btn-success btn-xs','onclick' => 'pdfDetalleCierreExcelF()')) !!}
							-->
							{!! Form::close() !!}
						</div>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" id="listado{{ $entidad }}">
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- /.content -->	
<script>
	function cajas(){
		$.ajax({
			type:'GET',
			url:"rpts/cajas",
			data:'',
			success: function(a) {
				$('#cajas').html(a);
			}
		});
	}

	cajas();

	function imprimirDetalleF(tipo){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
        if ($('#Medico').val() != 6 && $('#Medico').val() != 7) {
        	window.open('caja/pdfDetalleCierreF' + tipo + '?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        } else {
        	@if($user->usertype_id==1 || $user->usertype_id==14 || $user->usertype_id==8)
        		window.open('cajatesoreria/pdfDetalleCierreF' + tipo + '?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        	@endif
        }
    }

	@if($user->usertype_id==1 || $user->usertype_id==11 )
		
	function detallePorProducto(){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
		window.open('caja/pdfDetallePorProducto?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
	}

	@endif

    function imprimirMovilidadF(){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
        if ($('#Medico').val() != 6) {
        	//window.open('caja/pdfDetalleCierreF?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        } else {
        	window.open('cajatesoreria/pdfMovilidadF?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        }
    }

	function pdfDetalleCierreExcelF(tipo){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
        //if ($('#Medico').val() != 6) {
        	//window.open('caja/pdfDetalleCierreF?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        //} else {
        	window.open('caja/pdfDetalleCierreExcelF' + tipo + '?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
        //}
    }

    function pdfDetalleEgresos(){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
        window.open('caja/pdfDetalleEgresos?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
    }

    function egresosExcel(){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
      	window.open('cajatesoreria/egresosExcel?caja_id='+$('#Medico').val()+'&fi='+fi+'&ff='+ff,"_blank");
    }

	function Genera(){
		var fi = $('#fechainicial').val();
		var ff = $('#fechafinal').val();
		if (ff != "") {
			var med = '';
			if ($('#Medico').val() != null) {
				med = '&med='+$('#Medico').val();
			}
			var link = 'reporte.php?rep=6&fi='+fi+'&ff='+ff+''+med;
			var link2 = 'reporte.php?rep=61&fi='+fi+'&ff='+ff+'';
			if($('#Medico').val() != 4){
				window.open(link,'_blank');
			} else {
				window.open(link2,'_blank');
			}
		}
	}
</script>