<div class="row">
	{!! Form::model($movimiento, $formData) !!}
	{!! Form::hidden('cantdetalles', count($detalles)) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		<table class="table table-bordered table-responsive table-condensed table-hover dataTable no-footer" border="1" role="grid" style="width: 100%;">
			<thead>
				<tr>
					<td colspan="4"></td>
					<td>
						<select class="form-control input-xs" name="tipodescuento" id="tipodescuento" onchange="inicializarPrecios()">
							<option value="P">%</option>
							<option value="E">S/.</option>
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
					{!! Form::hidden('detalleid' . $detalle->id, $detalle->id) !!}
					<tr>
						<td>{{ (integer) $detalle->cantidad }}</td>
						<td style="font-size: 10px">
							{{ $detalle->persona->nombres }} {{ $detalle->persona->apellidopaterno }}
						</td>
						<td style="font-size: 10px">{{ $detalle->nombre }}</td>
						<td>
							<input name="precio{{ $i }}" id="precio{{ $i }}" class="form-control input-xs precio" type="text" value="{{ $detalle->precio }}" onkeypress="return filterFloat(event,this);" onkeyup="inicializarPrecios();">
						</td>
						<td>
							<input name="descuento{{ $i }}" id="descuento{{ $i }}" class="form-control input-xs descuento" type="text" value="0" onkeypress="return filterFloat(event,this);" onkeyup="inicializarPrecios();">
						</td>
						<td>
							<input name="subtotal{{ $i }}" id="subtotal{{ $i }}" class="form-control input-xs subtotal" type="text" readonly="">
						</td>
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
		    {!! Form::label('numero', 'Nro. Doc.', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
		    {!! Form::label('numero', $movimiento->numero, array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm', 'style' => 'font-weight:normal;text-align:left;text-align:left')) !!}		    	
			{!! Form::label('clasificacionconsulta', 'Tipo:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm')) !!}


			<select class="col-lg-2 col-md-2 col-sm-2 control-label input-xs" style="font-weight:normal;text-align:left" name="cclasconsulta" id="cclasconsulta">
                <option value="C">CONSULTA</option>
                <option value="E">EMERGENCIA</option>
                <option value="L">LECT. RESULTADOS</option>
                <option value="P">PROCEDIMIENTO</option>
                <option value="X">EXAMENES</option>
            </select>
			<!--
			@if($movimiento->clasificacionconsulta == 'C')
				{!! Form::label('clasificacionconsulta', 'CONSULTA', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@elseif($movimiento->clasificacionconsulta == 'E')
				{!! Form::label('clasificacionconsulta', 'EMERGENCIA', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@elseif($movimiento->clasificacionconsulta == 'L')
				{!! Form::label('clasificacionconsulta', 'LECT. RESULTADOS', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@elseif($movimiento->clasificacionconsulta == 'X')
				{!! Form::label('clasificacionconsulta', 'EXAMENES', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@else
				{!! Form::label('clasificacionconsulta', 'PROCEDIMIENTO', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@endif
			-->
			
			{!! Form::label('plan', 'Plan', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
			{!! Form::label('plan', $movimiento->plan->nombre, array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
		</div>
		<div class="form-group">
			{!! Form::label('paciente', 'Paciente', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
			{!! Form::label('paciente', $movimiento->persona->nombres . ' ' . $movimiento->persona->apellidopaterno . ' ' . $movimiento->persona->apellidomaterno, array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			{!! Form::label('doctor', 'Referido', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
			@if(!is_null($movimiento->doctor))
				{!! Form::label('doctor', ($movimiento->doctor->nombres . ' ' . $movimiento->doctor->apellidopaterno . ' ' . $movimiento->doctor->apellidomaterno), array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label input-sm', 'style' => 'font-weight:normal;text-align:left')) !!}
			@endif
		</div>
		<hr>
		<!-- OPCIONES -->
		<div class="form-group" id="genComp">
	        <!--{!! Form::label('plan', 'Generar:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm')) !!}
			<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::hidden('comprobante', 'S', array('id' => 'comprobante')) !!}
	            <input readonly="readonly" disabled="disabled" checked="checked" type="checkbox" onchange="mostrarDatoCaja(0,this.checked)" id="boleta" class="col-lg-2 col-md-2 col-sm-2 control-label input-sm" />
	            {!! Form::label('boleta', 'Comprobante', array('class' => 'col-lg-10 col-md-10 col-sm-10 control-label input-sm')) !!}
	            {!! Form::hidden('pagar', 'S', array('id' => 'pagar')) !!}    
				<input readonly="readonly" disabled="disabled" checked="checked" type="checkbox" onchange="mostrarDatoCaja(this.checked,0)" id="pago" class="col-lg-2 col-md-2 col-sm-2 control-label input-sm datocaja" />
	            {!! Form::label('pago', 'Pago', array('class' => 'col-lg-10 col-md-10 col-sm-10 control-label input-sm datocaja')) !!}
			</div>-->
			{!! Form::label('caja_id', 'Caja:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm datocaja caja')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<select name="caja_id" id="caja_id" class="form-control input-xs datocaja caja">
					<option value="{{ $cboCaja[0]->id }}">{{ $cboCaja[0]->nombre }}</option>
				</select>
			</div>	
			{!! Form::label('tipodocumento', 'Tipo de doc.:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label input-sm caja')) !!}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select name="tipodocumento" id="tipodocumento" class="form-control input-xs form-control caja" onchange="generarNumero();">
					<option value="Boleta">Boleta</option>
					<option value="Factura">Factura</option>
					<option value="Ticket">Ticket</option>
				</select>
			</div>
			{!! Form::label('numcomprobante', 'N°:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm')) !!}
			<div class="col-lg-2 col-md-2 col-sm-2">
    			{!! Form::text('serieventa', $serie, array('class' => 'form-control input-xs datocaja', 'id' => 'serieventa')) !!}
    		</div>
            <div class="col-lg-2 col-md-2 col-sm-2">
    		{!! Form::text('numeroventa', '', array('class' => 'form-control input-xs', 'id' => 'numeroventa', 'readonly' => 'true')) !!}
        		</div>	        
	    </div>
	    <div class="form-group" id="opcEmpresa" style="display: none;">
			{!! Form::label('ccruc', 'Ruc:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
    			{!! Form::text('ccruc','', array('class' => 'form-control input-xs datocaja', 'id' => 'ccruc', 'maxlength' => '11')) !!}
    		</div> 
    		{!! Form::label('ccrazon', 'Razón:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
    			{!! Form::text('ccrazon','', array('class' => 'form-control input-xs datocaja', 'id' => 'ccrazon', 'readonly' => 'readonly')) !!}
    		</div> 
    		{!! Form::label('ccdireccion', 'Direcc:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label input-sm')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
    			{!! Form::text('ccdireccion','-', array('class' => 'form-control input-xs datocaja', 'id' => 'ccdireccion')) !!}
    		</div> 	
	    </div>
	    <div class="form-group">
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::label('formapago', 'Forma Pago:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label datocaja caja input-sm')) !!}
				<label id="divcbx0" class="checkbox-inline" style="color:red" onclick="divFormaPago('0', '0')">
			      	<input style="display: none;" type="checkbox" id="cbx0">Efectivo
			    </label>
			    <label id="divcbx1" class="checkbox-inline" onclick="divFormaPago('1', '1')">
			      	<input style="display: none;" type="checkbox" id="cbx1">Visa
			    </label>
			    <label id="divcbx2" class="checkbox-inline" onclick="divFormaPago('2', '1')">
			      	<input style="display: none;" type="checkbox" id="cbx2">Master
			    </label>
			</div>        	
	    </div>
	    <div class="row">
		    <div class="col-lg-8 col-md-8 col-sm-8">	    	
			    <div class="input-group form-control">
					<span class="input-group-addon input-xs">EFECTIVO</span>
					<input onkeypress="return filterFloat(event,this);" onkeyup="calcularTotalPago();" name="efectivo" id="efectivo" type="text" class="form-control input-xs">
				</div>
				<div class="input-group form-control">
					<span class="input-group-addon input-xs">VISA.</span>
					<input onkeypress="return filterFloat(event,this);" onkeyup="calcularTotalPago();" name="visa" id="visa" type="text" class="form-control input-xs" readonly="">
					<span style="display:none;" class="input-group-addon input-xs">N°</span>
					<input style="display:none;" onkeypress="return filterFloat(event,this);" name="numvisa" id="numvisa" type="text" class="form-control input-xs" readonly="">
				</div>
				<div class="input-group form-control">
					<span class="input-group-addon input-xs">MAST.</span>
					<input onkeypress="return filterFloat(event,this);" onkeyup="calcularTotalPago();" name="master" id="master" type="text" class="form-control input-xs" readonly="">
					<span style="display:none;" class="input-group-addon input-xs">N°</span>
					<input style="display:none;" onkeypress="return filterFloat(event,this);" name="nummaster" id="nummaster" type="text" class="form-control input-xs" readonly="">
				</div>	
			</div>	
			<div class="col-lg-4 col-md-4 col-sm-4">	    	
			    <div class="input-group form-control">
					<span class="input-group-addon input-xs">TOTAL</span>
					<input name="total2" id="total2" type="text" class="form-control input-xs" readonly="" value="0.000">
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">	    	
			    <b id="mensajeMontos" style="color:red">Los montos no coinciden.</b>
			</div>		
		</div>
		{!! Form::hidden('id', $movimiento->id) !!}
		<div class="text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'data-a' => 'true', 'id' => 'btnGuardar', 'onclick' => 'enviar();')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>		
	</div>	
	{!! Form::close() !!}
</div>
<script type="text/javascript">
	$(document).on('change', '#tipodocumento', function(e) {
		e.preventDefault();
		if ($(this).val() == 'Factura') {
			$('#opcEmpresa').css('display', '');
			$('#ccruc').focus();
		} else {
			$('#opcEmpresa').css('display', 'none');
		}
	});

	$(document).on('keyup', '#ccruc', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		if ($(this).val().length == 11) {
			buscarEmpresa();
		}
	});

	$(document).on('change', '#cclasconsulta', function(e) {
		e.preventDefault();
		$.ajax({
			url: "caja/cambiartipocons/" + $('#cclasconsulta').val() + "/{{ $movimiento->id }}",
			type: "GET",
		}).fail(function() {
			alert('ERROR AL CAMBIAR TIPO.');
		});
	});

	$(document).ready(function() {
		configurarAnchoModal('1200');
		$('#cclasconsulta').val('{{ $movimiento->clasificacionconsulta }}');
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'B', '{!! $entidad !!}');
		inicializarPrecios();
		cargarEfectivo();
		calcularTotalPago();
		$('#serieventa').val(pad($('#serieventa').val(), 3));
    	$('#numeroventa').val(pad($('#numeroventa').val(), 8));
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="serieventa"]').inputmask("999");
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="numeroventa"]').inputmask("99999999");
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="numvisa"]').inputmask("9999999999999999");
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nummaster"]').inputmask("9999999999999999");
    	generarNumero();
	}); 

	function pad (str, max) {
  		str = str.toString();
  		return str.length < max ? pad("0" + str, max) : str;
	}

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
		var descuento = 0;
		$(".subtotal").each(function(){
			if($('#precio' + cont).val() == '') {
				subtotal = 0;
				subtotal = subtotal.toFixed(2);
			} else {
				descuento = $('#descuento' + cont).val();
				if($('#tipodescuento').val() == 'P') {
					descuento = $('#descuento' + cont).val() * $('#precio' + cont).val() / 100;
				}
				subtotal = parseFloat($('#precio' + cont).val() - descuento).toFixed(2);
			}			
			$(this).val(subtotal);	
			cont++;
		});
		$(".subtotal").each(function(){
			total += parseFloat($(this).val());
		});
		$('#total').val(total.toFixed(2));
		calcularTotalPago();
	}

	function calcularTotalPago() {
		var efectivo = $('#efectivo').val();
		var visa = $('#visa').val();
		var master = $('#master').val();
		var total = 0.000;
		if(efectivo == '') {
			efectivo = 0.000;
		} 
		if(visa == '') {
			visa = 0.000;
		}
		if(master == '') {
			master = 0.000;
		}
		total = parseFloat(efectivo) + parseFloat(visa) + parseFloat(master);
		$('#total2').val(total.toFixed(2));

		coincidenciasMontos();		
	}

	function divFormaPago(num, mostrar) {
		var m;
		if(mostrar == '0') {
			m = '1';
			$('#cbx' + num).attr('checked', false);
			$('#divcbx' + num).css('color', 'black');
			if(num == '0') {
				$('#efectivo').attr('readonly', true).val('');
			} else if(num == '1') {
				$('#visa').attr('readonly', true).val('');
				$('#numvisa').attr('readonly', true).val('');
			} else {
				$('#master').attr('readonly', true).val('');
				$('#nummaster').attr('readonly', true).val('');
			}
		} else {
			m = '0';
			$('#cbx' + num).attr('checked', true);
			$('#divcbx' + num).css('color', 'red');
			if(num == '0') {
				$('#efectivo').attr('readonly', false).focus();
			} else if(num == '1') {
				$('#visa').attr('readonly', false).focus();
				$('#numvisa').attr('readonly', false);
			} else {
				$('#master').attr('readonly', false).focus();
				$('#nummaster').attr('readonly', false);
			}
		}
		$('#divcbx' + num).attr("onclick", "divFormaPago('" + num + "', '" + m + "');");
		calcularTotalPago();
	}

	function coincidenciasMontos() {
		if(parseFloat($('#total').val()) == parseFloat($('#total2').val())) {
			$('#mensajeMontos').html('Los montos coindicen.').css('color', 'green');
			$('#genComp').css('display', '');
			return true;
		} else if(parseFloat($('#total').val()) > parseFloat($('#total2').val())) {
			$('#mensajeMontos').html('Es un monto menor.').css('color', 'orange');	
			$('#genComp').css('display', 'none');		
			return true;
		} else if(parseFloat($('#total').val()) < parseFloat($('#total2').val())) {
			$('#mensajeMontos').html('Es un monto mayor.').css('color', 'red');	
			$('#genComp').css('display', 'none');		
			return false;
		}
	}

	function camposNoVacios() {		
		if(!$('.descuento').val()) {
			$(this).focus();
			alert('Ingresa un descuento.');
			return false;
		} else if(!$('#numeroventa').val() || !$('#serieventa').val()) {
			$('#serieventa').focus();
			alert('Ingresa un numero de comprobante.');
			return false;
		} else {
			if(!$('#visa').attr('readonly')) {
				if($('#visa').val().length == 0) {
					$('#visa').focus();
					alert('Ingresa un monto para visa.');
					return false;
				}
				/*if($('#numvisa').val().length == 0) {
					$('#numvisa').focus();
					alert('Ingresa un numero para visa.');
					return false;
				}*/
			} 
			if(!$('#master').attr('readonly')) {
				if($('#master').val().length == 0) {
					$('#master').focus();
					alert('Ingresa un monto para master.');
					return false;
				}
				/*if($('#nummaster').val().length == 0) {
					$('#nummaster').focus();
					alert('Ingresa un numero para master.');
					return false;
				}*/
			}
			return true;
		}		
	}

	function enviar() {
		if ($('#tipodocumento').val() == 'Factura') {
			if($('#ccruc').val().length != 11 || $('#ccrazon').val() == '' || $('#ccdireccion').val() == '') {
				alert('No olvide digitar un RUC válido.');
				return false;
			}
		}
		form = $('#formMantenimientoMovimiento');
		if(!camposNoVacios()) {
			return false;
		} else {
			if(!coincidenciasMontos()) {
				$('#efectivo').focus();
				alert('Los montos no coinciden.');
				return false;
			} else {
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					beforeSend: function() {
						$('#btnGuardar').html('Cargando...').attr('disabled', true);
					},
					success: function(respuesta) {
						var dat = JSON.parse(respuesta);
						if(dat[0].respuesta=="OK"){
							if(dat[0].tipodocumento_id=="12"){
								imprimirTicket(dat[0].venta_id);
							}else{
								declarar1(dat[0].venta_id,dat[0].tipodocumento_id,dat[0].numero);
							}
						}
						cerrarModal();
						listatickestpendientes();
						buscar('Caja');
					},
				});
			}				
		}
	}

	function declarar1(idventa,idtipodocumento,numero){
		if(idtipodocumento==5){
			var funcion="enviarBoleta";
		}else{
			var funcion="enviarFactura";
		}
		$.ajax({
	        type: "GET",
	        url: "../clifacturacion/controlador/contComprobante.php?funcion="+funcion,
	        data: "idventa="+idventa+"&_token="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[name="_token"]').val(),
	        success: function(a) {
	            console.log(a);
	            imprimirVenta(numero);
		    }
	    });	
	}

	function imprimirTicket(id){
		$.ajax({
	        type: "POST",
	        url: "http://localhost/clifacturacion/controlador/contImprimir.php?funcion=ImprimirTicket",
	        data: "id="+id+"&_token="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[name="_token"]').val(),
	        success: function(a) {
	            console.log(a);
		    }
	    });
	}

	function imprimirVenta(numero){
		$.ajax({
	        type: "POST",
	        url: "http://localhost/clifacturacion/controlador/contImprimir.php?funcion=ImprimirVenta",
	        data: "numero="+numero+"&_token="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[name="_token"]').val(),
	        success: function(a) {
	            console.log(a);
		    }
	    });
	}

	function filterFloat(evt,input){
	    var key = window.Event ? evt.which : evt.keyCode;    
	    var chark = String.fromCharCode(key);
	    var tempValue = input.value+chark;
	    if(key >= 48 && key <= 57){
	        if(filter(tempValue)=== false){
	            return false;
	        }else{       
	            return true;
	        }
	    }else{
	          if(key == 8 || key == 13 || key == 0) {     
	              return true;              
	          }else if(key == 46){
	                if(filter(tempValue)=== false){
	                    return false;
	                }else{       
	                    return true;
	                }
	          }else{
	              return false;
	          }
	    }
	}
	function filter(__val__){
	    var preg = /^([0-9]+\.?[0-9]{0,3})$/; 
	    if(preg.test(__val__) === true){
	        return true;
	    }else{
	       return false;
	    }	    
	}

	function cargarEfectivo() {
		$('#efectivo').val($('#total').val()).focus();
	}

	function generarNumero(){
	    $.ajax({
	        type: "POST",
	        url: "ticket/generarNumero",
	        data: "tipodocumento="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="tipodocumento"]').val()+"&serie="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="serieventa"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val() + '&caja_id=' + $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="caja_id"]').val(),
	        success: function(a) {
	            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="numeroventa"]').val(a);
	            if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="tipodocumento"]').val()=="Factura"){
	                $(".datofactura").css("display","");
	            }else{
	                $(".datofactura").css("display","none");
	            }
	        }
	    });
	}

	function buscarEmpresa() {
    	ruc = $("#ccruc").val();     
        $.ajax({
            type: 'GET',
            url: "ticket/buscarEmpresa",
            data: "ruc="+ruc,
            beforeSend(){
                $("#ccruc").val('Comprobando...');
            },
            success: function (a) {
                if(a == '')  {
	        		buscarEmpresa2(ruc);
	        	} else {
	        		var e = a.split(';;');
	        		$("#ccruc").val(ruc);
	        		$('#ccrazon').val(e[0]);
	        		$('#ccdireccion').val(e[1]);
	        	}
            }
        });
    }

    function buscarEmpresa2(ruc){
        $.ajax({
            type: 'GET',
            url: "SunatPHP/demo.php",
            data: "ruc="+ruc,
            beforeSend(){
                $("#ccruc").val('Comprobando...');
            },
            success: function (data, textStatus, jqXHR) {
                if(data.RazonSocial == null) {
                    alert('El RUC ingresado no existe... Digite uno válido.');
	        		$("#ccruc").val('').focus();
                    $("#ccrazon").val('');
                    $("#ccdireccion").val('');
                } else {
                    $("#ccruc").val(ruc);
                    $("#ccrazon").val(data.RazonSocial);
                    $("#ccdireccion").val('-');
                }
            }
        });
    }
</script>