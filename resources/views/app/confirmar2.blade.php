<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($modelo, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
{!! $mensaje or '<blockquote><p class="text-danger">¿Esta seguro de realizar la accion?</p></blockquote>' !!}
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::hidden('id', $id) !!}
		{!! Form::text('comentarioa', null, ['class' => 'form-control', 'placeholder' => 'Motivo']) !!}
		@if(!isset($ticket))
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		@else
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this);anularmovimiento();')) !!}
		@endif
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal((contadorModal - 1));')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		configurarAnchoModal('350');
	}); 

	@if(isset($ticket))
		function anularmovimiento() {
			$.ajax({
				url: '{{ url('caja/anularmovimiento', $id) }}',
			})
		}				
	@endif
</script>