<div class="row">
	{!! Form::model($movimiento, $formData) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		<table class="table table-bordered table-responsive table-condensed table-hover dataTable no-footer" border="1" role="grid" style="width: 100%;">
			<thead>
				<tr>
					<td colspan="4"></td>
					<td>
						<select class="form-control input-xs" name="tipodescuento" id="tipodescuento" onchange="inicializarPrecios()">
							<option value="0">%</option>
							<option value="1">S/.</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<th>Cant.</th>
					<th>Medico</th>
					<th>Descrip.</th>
					<th>Precio</th>
					<th>Desc.</th>
					<th>Subtot.</th>
				</tr>					
			</thead>
			<tbody>
				<?php $i = 0; ?>				
				@foreach($detalles as $detalle)
					<tr>
						<td>{{ (integer) $detalle->cantidad }}</td>
						<td style="font-size: 10px">{{ $detalle->persona->nombres }} {{ $detalle->persona->apellidopaterno }}</td>
						<td style="font-size: 10px">{{ $detalle->nombre }}</td>
						<td><input name="precio{{ $i }}" id="precio{{ $i }}" class="form-control input-xs precio" type="text" value="{{ $detalle->precio }}" onkeyup="inicializarPrecios();soloDecimal('{{$i}}');"></td>
						<td><input name="descuento{{ $i }}" id="descuento{{ $i }}" class="form-control input-xs" type="text" value="0"></td>
						<td><input name="subtotal{{ $i }}" id="subtotal{{ $i }}" class="form-control input-xs subtotal" type="text" readonly=""></td>
					</tr>
					<?php $i++; ?>
				@endforeach
				<tr>
					<th colspan="5" class="text-right">Pago</th>
					<td><input name="total" id="total" class="form-control input-xs" type="text" readonly=""></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6">			
		<!-- DATOS DEL TICKET -->
		<div id="divMensajeError{!! $entidad !!}"></div>
		<div class="form-group">
		    {!! Form::label('numero', 'Nro. Doc.', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
		    {!! Form::label('numero', $movimiento->serie.'-'.$movimiento->numero, array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left;text-align:left')) !!}
		</div>
		<div class="form-group">
			{!! Form::label('siniestro', 'Siniestro', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			{!! Form::label('siniestro', $movimiento->comentario, array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left')) !!}
		</div>
		<div class="form-group">
			{!! Form::label('fecha', 'Fecha', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			{!! Form::label('siniestro', $movimiento->fecha, array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left')) !!}
		</div>
		<div class="form-group">
			{!! Form::label('paciente', 'Paciente', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			{!! Form::label('siniestro', $movimiento->persona->nombres . ' ' . $movimiento->persona->apellidopaterno . ' ' . $movimiento->persona->apellidomaterno, array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left')) !!}
		</div>
		<div class="form-group" style="display: none">
			{!! Form::label('tipomovimiento', 'Tipo de movimiento') !!}
			{!! Form::label('siniestro', $movimiento->tipomovimiento->nombre) !!}
		</div>
		<div class="form-group" style="display:none">
			{!! Form::label('total', 'Total') !!}
			{!! Form::label('siniestro', $movimiento->total) !!}
		</div>
		<div class="form-group">
			{!! Form::label('doctor', 'Referido', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			@if(!is_null($movimiento->doctor))
				{!! Form::label('siniestro', ($movimiento->doctor->nombres . ' ' . $movimiento->doctor->apellidopaterno . ' ' . $movimiento->doctor->apellidomaterno), array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left')) !!}
			@endif
		</div>
		<div class="form-group">
			{!! Form::label('plan', 'Plan', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			{!! Form::label('siniestro', $movimiento->plan->nombre, array('class' => 'col-lg-8 col-md-8 col-sm-8 control-label', 'style' => 'font-weight:normal;text-align:left')) !!}
		</div>
		<!-- OPCIONES -->
		<div class="form-group">
	        {!! Form::label('plan', 'Generar:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label')) !!}
			<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::hidden('comprobante', 'S', array('id' => 'comprobante')) !!}
	            <input readonly="readonly" disabled="disabled" checked="checked" type="checkbox" onchange="mostrarDatoCaja(0,this.checked)" id="boleta" class="col-lg-2 col-md-2 col-sm-2 control-label" />
	            {!! Form::label('boleta', 'Comprobante', array('class' => 'col-lg-10 col-md-10 col-sm-10 control-label')) !!}
	            {!! Form::hidden('pagar', 'S', array('id' => 'pagar')) !!}    
				<input readonly="readonly" disabled="disabled" checked="checked" type="checkbox" onchange="mostrarDatoCaja(this.checked,0)" id="pago" class="col-lg-2 col-md-2 col-sm-2 control-label datocaja" />
	            {!! Form::label('pago', 'Pago', array('class' => 'col-lg-10 col-md-10 col-sm-10 control-label datocaja')) !!}
			</div>
	        {!! Form::label('formapago', 'Forma Pago:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label datocaja caja')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::select('formapago', $cboFormaPago, null, array('class' => 'form-control input-xs datocaja caja', 'id' => 'formapago', 'onchange'=>'validarFormaPago(this.value);')) !!}
			</div>
			<br><br><br>
	        {!! Form::label('caja_id', 'Caja:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label datocaja caja')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<select name="caja_id" id="caja_id" class="form-control input-xs datocaja caja">
					@foreach($cboCaja as $caja)
					<option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
					@endforeach
				</select>
			</div>	
			{!! Form::label('tipodocumento', 'Tipo de documento:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label caja')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				<select name="tipodocumento" id="tipodocumento" class="col-lg-8 col-md-8 col-sm-8 control-label input-xs form-control caja">
					<option value="Boleta">Boleta</option>
					<option value="Factura">Factura</option>
					<option value="Ticket">Ticket</option>
				</select>
			</div>	
	    </div>
		<div class="form-group datocaja" id="divTarjeta" style="display: none">
	        {!! Form::label('tipotarjeta', 'Tarjeta:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::select('tipotarjeta', $cboTipoTarjeta, null, array('class' => 'form-control input-xs', 'id' => 'tipotarjeta')) !!}
			</div>
	        {!! Form::label('tipotarjeta2', 'Tipo Tarjeta:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label', 'style' => 'display:none')) !!}
			<!--<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::select('tipotarjeta2', $cboTipoTarjeta2, null, array('class' => 'form-control input-xs', 'id' => 'tipotarjeta2', 'style' => 'display:none')) !!}
			</div>-->
	        {!! Form::label('nroref', 'Nro. Op.:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label')) !!}
	        <div class="col-lg-4 col-md-4 col-sm-4">
	            {!! Form::text('nroref', null, array('class' => 'form-control input-xs', 'id' => 'nroref')) !!}
	        </div>
		</div>
		{!! Form::hidden('id', $movimiento->id) !!}
		<div class="text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'data-a' => 'true', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>		
	</div>	
	{!! Form::close() !!}
</div>
<script type="text/javascript">
	$(document).ready(function() {
		configurarAnchoModal('1200');
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'B', '{!! $entidad !!}');
		inicializarPrecios();
	}); 

	function mostrarDatoCaja(check,check2){
	    if(check==0){
	        check = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pago"]').is(":checked");
	    }
	    if(check2==0){
	        check2 = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="boleta"]').is(":checked");
	    }
	    if(check2){//CON BOLETA
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="comprobante"]').val('S');
	        $(".datocaja").css("display","");
	        if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="tipodocumento"]').val()=="Factura"){
	            $(".datofactura").css("display","");
	        }else{
	            $(".datofactura").css("display","none");
	        }
	        if(check){
	            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pagar"]').val('S');
	            $(".caja").css("display","");
	            $(".descuento").css("display","none");
	            $(".descuentopersonal").css('display','none');
	            $("#descuentopersonal").val('N');
	        }else{
	            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pagar"]').val('N');
	            $(".caja").css("display","none");
	            $(".descuento").css("display","");
	        }
	        validarFormaPago($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="formapago"]').val());
	    }else{
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="comprobante"]').val('N');
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pagar"]').val('N');
	        $(".datocaja").css("display","none");
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pago"]').attr("checked",true);
	        validarFormaPago($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="formapago"]').val());
	    }
	}

	function validarFormaPago(forma){
	    if(forma=="Tarjeta"){
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} div[id="divTarjeta"]').css("display","");
	    }else{
	        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} div[id="divTarjeta"]').css("display","none");
	    }
	}

	function inicializarPrecios() {
		var total = 0;
		var cont = 0;
		var subtotal = 0;
		$(".subtotal").each(function(){
			if($('#precio' + cont).val() == '') {
				subtotal = 0;
				subtotal = subtotal.toFixed(3);
			} else {
				subtotal = parseFloat($('#precio' + cont).val()).toFixed(3);
			}			
			$(this).val(subtotal);	
			cont++;
		});
		$(".subtotal").each(function(){
			total += parseFloat($(this).val());
		});
		$('#total').val(total.toFixed(3));
	}

	function soloDecimal(numero, e) {
		var key = e.charCode;
	    if((key >= 48 && key <= 57) || key == 46) {
	    	$('#precio' + numero).val($('#precio' + numero).val() + key)
	    }
	}
</script>