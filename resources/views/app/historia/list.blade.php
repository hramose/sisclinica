@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<script>
	function tablaCita(historia_id, nombrepaciente){
		$.ajax({
			"method": "POST",
			"url": "{{ url('/historiaclinica/tablaCita') }}",
			"data": {
				"historia_id" : historia_id, 
				"_token": "{{ csrf_token() }}",
				}
		}).done(function(info){
			$('#exampleModal').modal('show');
			$('#tablaCitas').html(info);
			$('#nombrepaciente').html(nombrepaciente);
		});
	}

	function ver(cita_id){
		$.ajax({
			"method": "POST",
			"url": "{{ url('/historiaclinica/ver') }}",
			"data": {
				"cita_id" : cita_id, 
				"_token": "{{ csrf_token() }}",
				}
		}).done(function(info){
			$('#verCita').html(info);
			$('#exampleModal1').modal('show');
		});
	}	
	function anadirComentario(cita_id) {
		var comentario = $('#anadirComentario').val();
		if(comentario == '') {
			$('#anadirComentario').focus();
			return 0;
		}
		$.ajax({
			"method": "POST",
			"url": "{{ url('/historiaclinica/anadirComentario') }}",
			"data": {
				"cita_id" : cita_id, 
				"comentario" : $('#anadirComentario').val(),
				"_token": "{{ csrf_token() }}",
				}
		}).done(function(info){
			if(info == '1') {
				alert('Comentario Añadido');
			} else {
				alert('No se pudo añadir comentario');
			}			
		});
	}
</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <div class="modal-header" id="encabeCita"><h3 align="center">Historias de Citas de <font id="nombrepaciente" color="blue" style="font-weight: bold"></font></h3></div>
		    <div class="modal-body" id="tablaCitas"></div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
	        </div>
	    </div>
	</div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <div class="modal-body" id="verCita"></div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
	        </div>
	    </div>
	</div>
</div>

@if($vistamedico != "SI")
{!! $paginacion or '' !!}
@endif

<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<?php
			if($value->fallecido=="S"){
				$color="background-color: rgba(29, 119, 162, 0.52);";
				$title="Fallecido el ".$value->fechafallecido;
			}else{
				$color="";
				$title="";
			}
		?>
		<tr style="{{ $color }}" title="{{ $title }}">
			<td>{{ $contador }}</td>
			<td>{{ $value->numero }}</td>
            <td>{{ $value->persona->apellidopaterno.' '.$value->persona->apellidomaterno.' '.$value->persona->nombres }}</td>
            <td>{{ $value->persona->dni }}</td>
            <td align="center">{{ $value->tipopaciente }}</td>
            <td>{{ $value->persona->telefono }}</td>
            <td>{{ $value->persona->direccion }}</td>
            @if($vistamedico != "SI")
            @if($value->fallecido=="S")
				<td> - </td>
				<td>{!! Form::button('<i class="glyphicon glyphicon-search"></i>', array('class' => 'btn btn-success btn-xs', 'id' => 'btnSeguimiento', 'title' => 'Seguimiento', 'onclick' => 'seguimiento(\''.$entidad.'\','.$value->id.')')) !!}</td>
	            <td>{!! Form::button('<i class="glyphicon glyphicon-print"></i>', array('class' => 'btn btn-info btn-xs', 'id' => 'btnImprimir', 'title' => 'Imprimir', 'onclick' => 'imprimirHistoria(\''.$entidad.'\','.$value->id.')')) !!}</td>
	            <td>{!! Form::button('<i class="glyphicon glyphicon-print"></i>', array('class' => 'btn btn-success btn-xs', 'id' => 'btnImprimir2', 'title' => 'Imprimir Citas', 'onclick' => 'imprimirHistoria2(\''.$entidad.'\','.$value->id.')')) !!}</td>
				<td> - </td>
				<td> - </td>
            @else
            	<td>{!! Form::button('<i class="glyphicon glyphicon-screenshot"></i>', array('class' => 'btn btn-danger btn-xs', 'id' => 'btnFallecido', 'title' => 'Fallecido', 'onclick' => 'modal (\''.URL::route($ruta["fallecido"], array($value->id, 'SI')).'\', \'Fallecido\', this);')) !!}</td>
            	<td>{!! Form::button('<i class="glyphicon glyphicon-search"></i>', array('class' => 'btn btn-success btn-xs', 'id' => 'btnSeguimiento', 'title' => 'Seguimiento', 'onclick' => 'seguimiento(\''.$entidad.'\','.$value->id.')')) !!}</td>
	            <td>{!! Form::button('<i class="glyphicon glyphicon-print"></i>', array('class' => 'btn btn-info btn-xs', 'id' => 'btnImprimir', 'title' => 'Imprimir Historia', 'onclick' => 'imprimirHistoria(\''.$entidad.'\','.$value->id.')')) !!}</td>
	            <td>{!! Form::button('<i class="glyphicon glyphicon-print"></i>', array('class' => 'btn btn-success btn-xs', 'id' => 'btnImprimir2', 'title' => 'Imprimir Citas', 'onclick' => 'imprimirHistoria2(\''.$entidad.'\','.$value->id.')')) !!}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div>', array( 'title' => 'Historias Clínicas', 'onclick' => 'tablaCita(' . $value->id . ', "' . $value->persona->apellidopaterno.' '.$value->persona->apellidomaterno.' '.$value->persona->nombres . '");', 'class' => 'btn btn-xs btn-primary')) !!}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div>', array( 'title' => 'Editar', 'onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
				@if($user->usertype_id==1 || $user->usertype_id==2)
					<td>{!! Form::button('<div class="glyphicon glyphicon-trash"></div>', array( 'title' => 'Eliminar', 'onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
				@else
					<td> - </td>
				@endif				
			@endif
			@else
				<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div>', array( 'title' => 'Historias Clínicas', 'onclick' => 'tablaCita(' . $value->id . ', "' . $value->persona->apellidopaterno.' '.$value->persona->apellidomaterno.' '.$value->persona->nombres . '");', 'class' => 'btn btn-xs btn-primary')) !!}</td>
			@endif
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif