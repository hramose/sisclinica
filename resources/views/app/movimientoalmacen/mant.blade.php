<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimientoalmacen, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('total', '0', array( 'id' => 'total')) !!}
	<input type="hidden" name="cantproductos" id="cantproductos" value="0">
	<div class="col-lg-4 col-md-4 col-sm-4">
		<div class="form-group" style="height: 12px;display: none;">
			{!! Form::label('almacen_id', 'Almacen :', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::select('almacen_id', $cboAlmacen, null, array('style' => 'background-color: #D4F0FF;' ,'class' => 'form-control input-xs', 'id' => 'almacen_id', 'onclick' => 'generarNumero(this.value);')) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px;">
			{!! Form::label('tipo', 'Tipo:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::select('tipo', $cboTipo, null, array('style' => 'background-color: #D4F0FF;' ,'class' => 'form-control input-xs', 'id' => 'tipo', 'onclick' => 'generarNumero(this.value);', 'onchange' => 'gestionlotes(this.value, 1);')) !!}
			</div>
		</div>
		<div class="form-group" id="divDescuentokayros" style="height: 12px;">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<div class='input-group input-group-xs' id='divfecha'>
					{!! Form::text('fecha', date('d/m/Y'), array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese fecha')) !!}
					
				</div>
			</div>
		</div>
		
		<div class="form-group" style="height: 12px;">
			{!! Form::label('numerodocumento', 'Nro Doc:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::text('numerodocumento', "-", array('class' => 'form-control input-xs', 'id' => 'numerodocumento', 'placeholder' => 'numerodocumento', 'readonly' => 'readonly')) !!}
			</div>

		</div>
		<div class="form-group" style="height: 12px;">
			{!! Form::label('numerodocumento', 'Comentario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::text('comentario', null, array('class' => 'form-control input-xs', 'id' => 'comentario', 'placeholder' => 'comentario')) !!}
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="form-group" style="height: 12px;">
			{!! Form::label('nombreproducto', 'Producto:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::text('nombreproducto', null, array('class' => 'form-control input-xs', 'id' => 'nombreproducto', 'placeholder' => 'Ingrese nombre','onkeyup' => 'buscarProducto($(this).val())')) !!}
			</div>
			<div class="col-lg-0 col-md-0 col-sm-0">
                {!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info btn-xs', 'onclick' => 'modal (\''.URL::route('producto.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Producto\', this);', 'title' => 'Nuevo Producto')) !!}
    		</div>
			{!! Form::hidden('producto_id', null, array( 'id' => 'producto_id')) !!}
			{!! Form::hidden('tienelote', null, array( 'id' => 'tienelote')) !!}
			{!! Form::hidden('pfraccion', null, array( 'id' => 'pfraccion')) !!}
			{!! Form::hidden('stock', null, array('id' => 'stock')) !!}
		</div>

		<div class="form-group" id="divProductos" style="overflow:auto; height:180px; padding-right:10px; border:1px outset">
			<table class='table-condensed table-hover' border='1'>
				<thead>
					<tr>
						<th class='text-center' style='width:230px;'><span style='display: block; font-size:.7em'>P. Activo</span></th>
						<th class='text-center' style='width:300px;'><span style='display: block; font-size:.7em'>Nombre</span></th>
						<th class='text-center' style='width:70px;'><span style='display: block; font-size:.7em'>Present.</span></th>
						<th class='text-center' style='width:20px;'><span style='display: block; font-size:.7em'>Fracción</span></th>
						<th class='text-center' style='width:20px;'><span style='display: block; font-size:.7em'>Stock</span></th>
						<th class='text-center' style='width:20px;'><span style='display: block; font-size:.7em'>P.Kayros</span></th>
						<th class='text-center' style='width:20px;'><span style='display: block; font-size:.7em'>P.Venta</span></th>
						<th class='text-center' style='width:20px;'><span style='display: block; font-size:.7em'>Lote</span></th>
					</tr>
				</thead>
				<tbody id='tablaProducto'>
					<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>
				</tbody>
			</table>
		</div>

		<div class="form-group" id="datosproducto">
			<table>
			<tr>
				<td><b>P.Kayros</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('preciokayros', null, array('class' => 'form-control input-xs', 'id' => 'preciokayros', 'size' => '3')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td><b>P. Compra</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('preciocompra', null, array('class' => 'form-control input-xs', 'id' => 'preciocompra','size' => '3')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td><b>P.Venta</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('precioventa', null, array('class' => 'form-control input-xs', 'id' => 'precioventa', 'size' => '3', 'onkeypress'=>'return solo_numero(event);')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td class="cantidad"><b>Cantidad</b></td>
				<td>&nbsp</td>
				<td class="cantidad">{!! Form::text('cantidad', null, array('class' => 'form-control input-xs', 'id' => 'cantidad', 'size' => '3', 'onkeyup' => "javascript:this.value=this.value.toUpperCase();")) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td class="fechavencimiento"><b>F.Vencimiento</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('fechavencimiento', null, array('class' => 'form-control input-xs fechavencimiento', 'id' => 'fechavencimiento', 'size' => '6')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td class="lote"><b>Lote</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('lote', null, array('class' => 'form-control input-xs lote', 'id' => 'lote', 'size' => '6')) !!}</td>
				<td>&nbsp</td>
				<td><button class="btn btn-primary btn-xs botonlotes" style="display: none;" onclick="" type="button"><i class="glyphicon glyphicon-list-alt"></i> Seleccionar Lotes</button></td>
			</tr>
				
			</table>
			
		</div>

		<div class="form-group">
			<div class="col-lg-12 col-md-12 col-sm-12 text-right">
				<!--<div align="center" class="col-lg-3 ">
		       {-- Form::button('<i class="glyphicon glyphicon-plus"></i> Agregar', array('class' => 'btn btn-info btn-xs', 'id' => 'btnAgregar', 'onclick' => 'ventanaproductos();')) --}   
		    	
		    	</div>-->
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarMovimiento(\''.$entidad.'\', this)')) !!}
				{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
			</div>
		</div>
		
	</div>
	<div class="form-group" style="display: none;">
		<div class="col-lg-12 col-md-12 col-sm-12" >
			{!! Form::label('codigo', 'Comprobar Productos:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese codigo')) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="divDetail" class="table-responsive" style="overflow:auto; height:220px; padding-right:10px; border:1px outset">
		        <table style="width: 100%;" class="table-condensed table-striped" border="1">
		            <thead>
		                <tr>
		                    <th bgcolor="#E0ECF8" class='text-center'>N°</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:670px;">Producto</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:100px;">Lote</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:95px;">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:95px;">Precio Unit</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:90px;">Subtotal</th>
		                    <th bgcolor="#E0ECF8" class='text-center'>Quitar</th>
		                </tr>
		            </thead>
		            <tbody id="detallesMovimiento">
		            </tbody>
		            <tbody border="1">
		            	<tr>
		            		<th colspan="5" style="text-align: right;">TOTAL</th>
		            		<td class="text-center">
		            			<center id="totalmovimiento2">0.00</center><input type="hidden" id="totalmovimiento" readonly="" name="totalmovimiento" value="0.00">
		            		</td>
		            	</tr>
		            </tbody>
		        </table>
		    </div>
		</div>
	 </div>
    <br>
	
	
{!! Form::close() !!}
<style type="text/css">
tr.resaltar {
    background-color: #D4F0FF;
    cursor: pointer;
}
</style>
<script type="text/javascript">
var valorbusqueda="";
var indice = -1;
var anterior = -1;
$(document).ready(function() {
	configurarAnchoModal('1300');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'B', '{!! $entidad !!}');

		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="total"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: ",", groupSize: 3, digits: 2 });

		
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="fecha"]').inputmask("dd/mm/yyyy");
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="fecha"]').datetimepicker({
			pickTime: false,
			language: 'es'
		});

	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="fechavencimiento"]').inputmask("dd/mm/yyyy");
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="fechavencimiento"]').datetimepicker({
			pickTime: false,
			language: 'es'
		});

	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="codigo"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				comprobarproducto ();
			}
		});

	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="conveniofarmacia"]').focus(function(){
			abrirconvenios();
		});

	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="afecto"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});

	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="numerodias"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});

	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="serie"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="numerodocumento"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="preciokayros"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="preciocompra"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precioventa"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				if($('#tipo').val() == '9') {
					addpurchasecart();
				} else {
					var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}					
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="fechavencimiento"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				if($('#lote').attr('readonly') == 'readonly') {
					addpurchasecart();
				} else {
					var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			}
		});
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="lote"]').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				if($(this).val() == '') {
					return false;
					$(this).focus();
				}
				/*e.preventDefault();
				var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
				inputs.eq( inputs.index(this)+ 1 ).focus();*/
				addpurchasecart();				
				indice = -1;				
			}
		});


	var personas = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'person/providersautocompleting/%QUERY',
				filter: function (personas) {
					return $.map(personas, function (movie) {
						return {
							value: movie.value,
							id: movie.id
						};
					});
				}
			}
		});
		personas.initialize();
		$('#nombrepersona').typeahead(null,{
			displayKey: 'value',
			source: personas.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#person_id').val(datum.id);
			$('#cajafarmacia').focus();
		});

	var doctores = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'person/doctorautocompleting/%QUERY',
				filter: function (doctores) {
					return $.map(doctores, function (movie) {
						return {
							value: movie.value,
							id: movie.id
						};
					});
				}
			}
		});
		doctores.initialize();
		$('#nombredoctor').typeahead(null,{
			displayKey: 'value',
			source: doctores.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#doctor_id').val(datum.id);
		});


	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombreproducto"]').on( 'keydown', function () {
        var e = window.event; 
        var keyc = e.keyCode || e.which;
        console.log(this.value);
        console.log(valorbusqueda);
        if(this.value.length>2 && keyc == 13 && valorbusqueda!=this.value){
            buscarProducto(this.value);
            valorbusqueda=this.value;
            this.focus();
            return false;
        }
        if(keyc == 38 || keyc == 40 || keyc == 13 || keyc == 27) {
            var tabladiv='tablaProducto';
			var child = document.getElementById(tabladiv).rows;
			//var indice = -1;
			var i=0;
            /*$('#tablaProducto tr').each(function(index, elemento) {
                if($(elemento).hasClass("tr_hover")) {
    			    $(elemento).removeClass("par");
    				$(elemento).removeClass("impar");								
    				indice = i;
                }
                if(i % 2==0){
    			    $(elemento).removeClass("tr_hover");
    			    $(elemento).addClass("impar");
                }else{
    				$(elemento).removeClass("tr_hover");								
    				$(elemento).addClass('par');
    			}
    			i++;
    		});*/		 
			// return
			//if(keyc == 13) { // enter
			if(keyc == 27) { // esc  				
			     if(indice != -1){
					var seleccionado = '';			 
					if(child[indice].id) {
					   seleccionado = child[indice].id;
					} else {
					   seleccionado = child[indice].id;
					}		 		
					seleccionarProducto(seleccionado);
				}
			} else {
				// abajo
				if(keyc == 40) {
					if(indice == (child.length - 1) ) {
					   indice = 1;
					} else {
					   if(indice==-1) indice=0;
	                   indice=indice+1;
					} 
				// arriba
				} else if(keyc == 38) {
					indice = indice - 1;
					if(indice==0) indice=-1;
					if(indice < 0) {
						indice = (child.length - 1);
					}
				}	
				
				child[indice].className = child[indice].className+' tr_hover';

				if (indice != -1) {
					var element = '#'+child[indice].id;
					$(element).addClass("resaltar");
					if (anterior  != -1) {
						element = '#'+anterior;
						$(element).removeClass("resaltar");
					}
					anterior = child[indice].id;
				}
			}
        }
    });


	//cambiotipoventa();
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();

	generarNumero(8);

}); 

function buscarProducto(valor){
    if(valor.length >= 3){
        $.ajax({
            type: "POST",
            url: "venta/buscandoproducto",
            data: "nombre="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombreproducto"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
                //$("#divProductos").html("<table class='table table-bordered table-condensed table-hover' border='1' id='tablaProducto'><thead><tr><th class='text-center'>P. Activo</th><th class='text-center'>Nombre</th><th class='text-center'>Presentacion</th><th class='text-center'>Stock</th><th class='text-center'>P.Kayros</th><th class='text-center'>P.Venta</th></tr></thead></table>");
                $("#divProductos").css("overflow-x",'hidden');
                var pag=parseInt($("#pag").val());
                var d=0;
                var a = '';
                if(datos.length > 0) {
	                for(c=0; c < datos.length; c++){
	                	//Algoritmo para stock
	                	var stock = datos[c].stock;
	                	if(datos[c].fraccion != 1) {
	                		var pres1 = 1;
	                		pres1 = Math.trunc(parseFloat(datos[c].stock)/parseFloat(datos[c].fraccion));
	                		entero = parseFloat(pres1);
	                		pres2 = parseFloat(datos[c].stock) - entero*parseFloat(datos[c].fraccion);
	                		stock = pres1.toString() + 'F' + pres2.toString();
	                	}
	                    a+="<tr class='escogerFila' style='cursor:pointer;' id='"+datos[c].idproducto+"' onclick=\"seleccionarProducto('"+datos[c].idproducto+"')\"><td align='center'><span style='display: block; font-size:.7em'>"+datos[c].principio+"</span></td><td><span style='display: block; font-size:.7em'>"+datos[c].nombre+"</span></td><td align='right'><span style='display: block; font-size:.7em'>"+datos[c].presentacion+"</span></td><td align='right'><span style='display: block; font-size:.7em'>"+datos[c].fraccion+"</span></td><td align='right'><span style='display: block; font-size:.7em'>"+stock+"</span></td><td align='right'><span style='display: block; font-size:.7em'>"+datos[c].preciokayros+"</span></td><td align='right'><span style='display: block; font-size:.7em'>"+datos[c].precioventa+"</span></td><td class='collotes' align='right'><span style='display: block; font-size:.7em'>"+datos[c].lote+"</span></td></tr>";	                               
	                }
	            } else {
	            	a +="<tr><td align='center' colspan='8'>Productos no encontrados.</td></tr>";
	            }
	            $("#tablaProducto").html(a);
                $('#tablaProducto').DataTable({
                    "paging":         false,
                    "ordering"        :false
                });
                $('#tablaProducto_filter').css('display','none');
                $("#tablaProducto_info").css("display","none");
                gestionlotes($('#tipo').val());
    	    }
        });
    } else {
    	$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
    }
}

function cambiar() {
	var credito = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="credito"]').val();
	if (credito == 'S') {
		$('#numerodias').focus();
		$("#numerodias").prop('readonly', false);
		/*$('#divcuota').show();
		$('#divnumerocuota').show();
		$('#divdias').show();
		$("#inicial").prop('readonly', false);*/
	}else{
		$("#numerodias").prop('readonly', true);
		$('#serie').focus();
	}
	
}

function cambiar2() {
	var cajafarmacia = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="cajafarmacia"]').val();
	if (cajafarmacia == 'S') {
		$('#nombredoctor').focus();
		$("#nombredoctor").prop('readonly', false);
	}else{
		$("#nombredoctor").prop('readonly', true);
		$('#nombreproducto').focus();
	}
	
}

function seleccionarProducto(idproducto){
	//alert(idproducto);
	var _token =$('input[name=_token]').val();
	$.post('{{ URL::route("venta.consultaproducto")}}', {idproducto: idproducto,_token: _token} , function(data){
		//$('#divDetail').html(data);
		//calculatetotal();
		var datos = data.split('@');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="producto_id"]').val(datos[0]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="preciokayros"]').val(datos[1]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precioventa"]').val(datos[2]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="stock"]').val(datos[3]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="preciocompra"]').val(datos[4]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="tienelote"]').val(datos[5]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="pfraccion"]').val(datos[6]);
		if(datos[5] == 'SI') {
			$('#lote').attr('readonly', false);
		} else {
			$('#lote').attr('readonly', true);
		}
		gestionlotes($('#tipo').val());
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="cantidad"]').focus();
	});	
}

function ventanaproductos() {
	var tipoventa = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="tipoventa"]').val();
	var descuentokayros = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descuentokayros"]').val();
	var copago = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="copago"]').val();
	modal('{{URL::route('venta.buscarproducto')}}'+'?tipoventa='+tipoventa+'&descuentokayros='+descuentokayros+'&copago='+copago, '');
}


function abrirconvenios() {
	modal('{{URL::route('venta.buscarconvenio')}}', '');
}


function generarNumero(valor){
    $.ajax({
        type: "POST",
        url: "venta/generarNumeroDocMovAlmacen",
        data: "tipodocumento_id="+valor+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val()+"&almacen_id="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="almacen_id"]').val(),
        success: function(a) {
            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="numerodocumento"]').val(a);
        }
    });
}


function setValorFormapago (id, valor) {
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="' + id + '"]').val(valor);
}

function getValorFormapago (id) {
	var valor = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="' + id + '"]').val();
	return valor;
}

function cambiotipoventa() {
	var tipoventa = $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="tipoventa"]').val();
	if (tipoventa == 'C') {
		//$('#divConvenio').show();
		//$('#divDescuentokayros').show();
		//$('#divCopago').show();
		modal('{{URL::route('venta.busquedacliente')}}', '');

	}else if (tipoventa == 'N') {
		$('#divConvenio').hide();
		//$('#divDescuentokayros').hide();
		//$('#divCopago').hide();
	}
}

function generarSaldototal () {
	var total = retornarFloat(getValorFormapago('total'));
	var inicial = retornarFloat(getValorFormapago('inicial'));
	var saldototal = (total - inicial).toFixed(2);
	if (saldototal < 0.00) {
		setValorFormapago('inicial', total);
		setValorFormapago('saldo', '0.00');
	}else{
		setValorFormapago('saldo', saldototal);
	}
}

function retornarFloat (value) {
	var retorno = 0.00;
	value       = value.replace(',','');
	if(value.trim() === ''){
		retorno = 0.00; 
	}else{
		retorno = parseFloat(value)
	}
	return retorno;
}

$(document).on('click', '.quitarFila', function(event) {
	event.preventDefault();
	$(this).parent('span').parent('td').parent('tr').remove();
	calculatetotal();
});

function quitar (valor) {
	/*var _token =$('input[name=_token]').val();
	$.post('{ URL::route("movimientoalmacen.quitarcarritomovimientoalmacen")}}', {valor: valor,_token: _token} , function(data){
		$('#divDetail').html(data);
		calculatetotal();
		//generarSaldototal ();
		// var totalpedido = $('#totalpedido').val();
		// $('#total').val(totalpedido);
	});*/
}

function calculatetotal () {
	/*var _token =$('input[name=_token]').val();
	var valor =0;
	$.post('{ URL::route("venta.calculartotal")}}', {valor: valor,_token: _token} , function(data){
		valor = retornarFloat(data);
		$("#total").val(valor);
		//generarSaldototal();
		// var totalpedido = $('#totalpedido').val();
		// $('#total').val(totalpedido);
	});*/
	var i = 1;
	var total = 0;
	$('#detallesMovimiento tr .numeration3').each(function() {
		$(this).html(i);
		i++;
	});
	i = 1;

	$('#detallesMovimiento tr .infoProducto').each(function() {
		$(this).find('.producto_id').attr('name', '').attr('name', 'producto_id' + i);
		$(this).find('.productonombre').attr('name', '').attr('name', 'productonombre' + i);
		$(this).find('.cantidad').attr('name', '').attr('name', 'cantidad' + i);
		$(this).find('.fechavencimiento').attr('name', '').attr('name', 'fechavencimiento' + i);
		$(this).find('.lote').attr('name', '').attr('name', 'lote' + i);
		$(this).find('.distribuidora_id').attr('name', '').attr('name', 'distribuidora_id' + i);
		$(this).find('.codigobarra').attr('name', '').attr('name', 'codigobarra' + i);
		$(this).find('.preciokayros').attr('name', '').attr('name', 'preciokayros' + i);
		$(this).find('.precio').attr('name', '').attr('name', 'precio' + i);
		$(this).find('.precioventa').attr('name', '').attr('name', 'precioventa' + i);
		$(this).find('.subtotal').attr('name', '').attr('name', 'subtotal' + i);
		$(this).find('.datoLote').attr('name', '').attr('name', 'datoLote' + i);
		total += parseFloat($(this).find('.subtotal').val());
		i++;
	});
	$('#cantproductos').val(i-1);
	$('#totalmovimiento2').html(parseFloat(total).toFixed(2));
	$('#totalmovimiento').val(parseFloat(total).toFixed(2));
}

function comprobarproducto () {
	var _token =$('input[name=_token]').val();
	var valor =$('input[name=codigo]').val();
	$.post('{{ URL::route("venta.comprobarproducto")}}', {valor: valor,_token: _token} , function(data){
		
		if (data.trim() == 'NO') {
			$('input[name=codigo]').val('');
			bootbox.alert("Este Producto no esta en lista de venta");
            setTimeout(function () {
                $('#codigo').focus();
            },2000) 
		}else{
			$('input[name=codigo]').val('');
			$('#codigo').focus();
		}
	});
}

function seleccionarCliente(id) {
	var _token =$('input[name=_token]').val();
	$.post('{{ URL::route("venta.clienteid")}}', {id: id,_token: _token} , function(data){
		var datos = data.split('-'); 
		$('#person_id').val(datos[0]);
		$('#nombrepersona').val(datos[1]);
		
		cerrarModal();
		var tipoventa = $('#tipoventa').val();
		if (tipoventa == 'N') {
			$('#nombreproducto').focus();
		}else{
			//$('#conveniofarmacia').focus();
			abrirconvenios();
		}
	});
	
}

function seleccionarParticular(value) {
	$('#nombrepersona').val(value);
	cerrarModal();
	$('#nombreproducto').focus();
}

function agregarconvenio(id){

	var kayros = $('#txtKayros').val();
	var copago = $('#txtCopago').val();
	var convenio_id = id;

	var _token =$('input[name=_token]').val();
	if(kayros.trim() == '' ){
		bootbox.alert("Ingrese precio kayros");
            setTimeout(function () {
                $('#txtKayros').focus();
            },2000) 
	}else if(copago.trim() == '' ){
		bootbox.alert("Ingrese copago");
            setTimeout(function () {
                $('#txtCopago').focus();
            },2000) 
	}else{
		$.post('{{ URL::route("venta.agregarconvenio")}}', {kayros: kayros,copago: copago, convenio_id: convenio_id,_token: _token} , function(data){
			dat = data.split('-');
			$('#copago').val(copago);
			$('#descuentokayros').val(kayros);
			$('#conveniofarmacia').val(dat[0]);
			$('#nombreconvenio').val(dat[0]);
			$('#conveniofarmacia_id').val(dat[1]);

			cerrarModal();
			$('#descuentokayros').focus();
			/*$('#divDetail').html(data);
			calculatetotal();
			bootbox.alert("Producto Agregado");
            setTimeout(function () {
                $('#txtPrecio' + elemento).focus();
            },2000) */
			
		});
	}
	}

	function agregarempresa(id){

	var ruc = $('#ruc').val();
	var direccion = $('#direccion').val();
	var telefono = $('#telefono').val();
	var empresa_id = id;

	var _token =$('input[name=_token]').val();
	/*if(kayros.trim() == '' ){
		bootbox.alert("Ingrese precio kayros");
            setTimeout(function () {
                $('#txtKayros').focus();
            },2000) 
	}else if(copago.trim() == '' ){
		bootbox.alert("Ingrese copago");
            setTimeout(function () {
                $('#txtCopago').focus();
            },2000) 
	}else{*/
		$.post('{{ URL::route("venta.agregarempresa")}}', {ruc: ruc,direccion: direccion,telefono: telefono, empresa_id: empresa_id,_token: _token} , function(data){
			dat = data.split('-');
			$('#nombrepersona').val(dat[0]);
			$('#empresa_id').val(dat[1]);

			cerrarModal();
			$('#nombreproducto').focus();
			/*$('#divDetail').html(data);
			calculatetotal();
			bootbox.alert("Producto Agregado");
            setTimeout(function () {
                $('#txtPrecio' + elemento).focus();
            },2000) */
			
		});
	//}
	}

	function addpurchasecart(elemento = 'N'){
		var fraccion = $('#pfraccion').val();
		var tipo = $('#tipo').val();
		var cantidad = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="cantidad"]').val();
		cantidad = cantidad.replace(",", "");
		var precio = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="preciocompra"]').val();
		precio = precio.replace(",", "");
		var precioventa = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventa"]').val();
		precioventa = precioventa.replace(",", "");
		var preciokayros = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="preciokayros"]').val();
		preciokayros = preciokayros.replace(",", "");
		var product_id = $('#producto_id').val();
		var fechavencimiento = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="fechavencimiento"]').val();
		var lote = $('#lote').val();
		var stock = $('#stock').val();
		var tipoventa = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="tipoventa"]').val();
		var descuentokayros = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="descuentokayros"]').val();
		var copago = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="copago"]').val();

		var _token =$('input[name=_token]').val();
		if(cantidad.trim() === '' && tipo === '8' && fraccion === '1'){
			bootbox.alert("Ingrese Cantidad");
	            setTimeout(function () {
	                $('#cantidad').focus();
	            },2000) 
		}else if(cantidad.trim() === 0 && tipo === '8' && fraccion === '1'){
			bootbox.alert("la cantidad debe ser mayor a 0");
	            setTimeout(function () {
	                $('#cantidad').focus();
	            },2000) 
		}else if(precio.trim() === '' ){
			bootbox.alert("Ingrese Precio");
	            setTimeout(function () {
	                $('#preciocompra').focus();
	            },2000) 
		}else if(precio.trim() === 0){
			bootbox.alert("el precio debe ser mayor a 0");
	            setTimeout(function () {
	                $('#preciocompra').focus();
	            },2000) 
		}else if(fechavencimiento.trim() === '' && $('#tipo').val() === '8'){
			bootbox.alert("Ingrese Fecha Vencimiento");
            setTimeout(function () {
                $('#fechavencimiento').focus();
            },2000) 
		}/*else if(parseFloat(cantidad.trim()) > parseFloat(stock)){
			bootbox.alert("No puede vender una cantidad mayor al stock actual");
	            setTimeout(function () {
	                $('#cantidad').focus();
	            },2000) 
		}*/else{
			$.post('{{ URL::route("movimientoalmacen.agregarcarritomovimientoalmacen")}}', {cantidad: cantidad,precio: precio, producto_id: product_id, tipoventa: tipoventa, descuentokayros: descuentokayros, copago: copago, precioventa: precioventa, preciokayros: preciokayros, lote: lote, fechavencimiento: fechavencimiento,_token: _token,tipo:tipo,stock:stock, elemento: elemento} , function(data){
				//$('#divDetail').html(data);
				if(data === '0-0') {
					bootbox.alert('No es un formato válido de cantidad.');
					$('#cantidad').val('').focus();
					return false;
				} else if(data === '0-1') {
					bootbox.alert('No puedes sacar más de lo que tienes.');
					$('#cantidad').val('').focus();
					return false;
				} else {
					var producto_id = $('#producto_id').val();
					if ($("#Product" + producto_id)[0]) {
						$("#Product" + producto_id).html(data);
					} else {
						$('#detallesMovimiento').append('<tr id="Product' + producto_id + '">' + data + '</tr>');
					}	
					$("#Product" + producto_id).css('display', 'none').fadeIn(1000);		
					calculatetotal();
					/*bootbox.alert("Producto Agregado");
		            setTimeout(function () {
		                $(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="nombre"]').focus();
		            },2000) */
					//var totalpedido = $('#totalpedido').val();
					//$('#total').val(totalpedido);
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="preciocompra"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precioventa"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="preciokayros"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="fechavencimiento"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="lote"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
					$('.escogerFila').css('background-color', 'white');
					$('.botonlotes').css('display', 'none');
				}
			});
		}
	}

	function guardarHistoria (entidad, idboton) {
		var idformulario = IDFORMMANTENIMIENTO + entidad;
		var data         = submitForm(idformulario);
		var respuesta    = '';
		var btn = $(idboton);
		btn.button('loading');
		data.done(function(msg) {
			respuesta = msg;
		}).fail(function(xhr, textStatus, errorThrown) {
			respuesta = 'ERROR';
		}).always(function() {
			btn.button('reset');
			if(respuesta === 'ERROR'){
			}else{
			  //alert(respuesta);
	            var dat = JSON.parse(respuesta);
				if (dat[0]!==undefined && (dat[0].respuesta=== 'OK')) {
					cerrarModal();
	                //$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="historia_id"]').val(dat[0].id);
	                //$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="numero_historia"]').val(dat[0].historia);
	                //$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="person_id"]').val(dat[0].person_id);
	                //$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombrepersona"]').val(dat[0].paciente);
	                $('#person_id').val(dat[0].person_id);
					$('#nombrepersona').val(dat[0].paciente);
					cerrarModal();
	                var tipoventa = $('#tipoventa').val();
					if (tipoventa == 'N') {
						$('#nombreproducto').focus();
					}else{
						//$('#conveniofarmacia').focus();
						abrirconvenios();
					}
				} else {
					mostrarErrores(respuesta, idformulario, entidad);
				}
			}
		});
	}

	function guardarEmpresa (entidad, idboton) {
		var idformulario = IDFORMMANTENIMIENTO + entidad;
		var data         = submitForm(idformulario);
		var respuesta    = '';
		var btn = $(idboton);
		btn.button('loading');
		data.done(function(msg) {
			respuesta = msg;
		}).fail(function(xhr, textStatus, errorThrown) {
			respuesta = 'ERROR';
		}).always(function() {
			btn.button('reset');
			if(respuesta === 'ERROR'){
			}else{
			  //alert(respuesta);
	            var dat = JSON.parse(respuesta);
				if (dat[0]!==undefined && (dat[0].respuesta=== 'OK')) {
					cerrarModal();
	                $('#empresa_id').val(dat[0].empresa_id);
					$('#nombrepersona').val(dat[0].nombre);
					cerrarModal();
	                var tipoventa = $('#tipoventa').val();
					if (tipoventa == 'N') {
						$('#nombreproducto').focus();
					}else{
						//$('#conveniofarmacia').focus();
						abrirconvenios();
					}
				} else {
					mostrarErrores(respuesta, idformulario, entidad);
				}
			}
		});
	}

function guardarMovimiento (entidad, idboton) {
	if($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="fecha"]').val()==""){
		alert("Debe ingresar una fecha");
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="fecha"]').focus();
		return false;
	} else if($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="totalmovimiento"]').val()==0){
		alert("Corrige los precios, no puedes registrar precio de compra, venta o kayros como 0");
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="nombreproducto"]').focus();
		return false;
	} else {
		var total = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="totalmovimiento"]').val();
		var mensaje = '<h3 align = "center">Total = '+total+'</h3>';
		/*if (typeof mensajepersonalizado != 'undefined' && mensajepersonalizado !== '') {
			mensaje = mensajepersonalizado;
		}*/
		bootbox.confirm({
			message : mensaje,
			buttons: {
				'cancel': {
					label: 'Cancelar',
					className: 'btn btn-default btn-sm'
				},
				'confirm':{
					label: 'Aceptar',
					className: 'btn btn-success btn-sm'
				}
			}, 
			callback: function(result) {
				if (result) {
					var idformulario = IDFORMMANTENIMIENTO + entidad;
					var data         = submitForm(idformulario);
					var respuesta    = '';
					var listar       = 'NO';
					
					var btn = $(idboton);
					btn.button('loading');
					data.done(function(msg) {
						respuesta = msg;
					}).fail(function(xhr, textStatus, errorThrown) {
						respuesta = 'ERROR';
					}).always(function() {
						btn.button('reset');
						if(respuesta === 'ERROR'){
						}else{
							var dat = JSON.parse(respuesta);
				            if(dat[0]!==undefined){
				                resp=dat[0].respuesta;    
				            }else{
				                resp='VALIDACION';
				            }
				            
							if (resp === 'OK') {
								cerrarModal();
				                buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
				                /*if(dat[0].pagohospital!="0"){
				                    window.open('/juanpablo/ticket/pdfComprobante?ticket_id='+dat[0].ticket_id,'_blank')
				                }else{
				                    window.open('/juanpablo/ticket/pdfPrefactura?ticket_id='+dat[0].ticket_id,'_blank')
				                }*/
				                //alert('hola');
				                /*if (dat[0].ind == 1) {
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].venta_id,'_blank');
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].second_id,'_blank');
				                }else{
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].venta_id,'_blank');
				                }*/
				                
							} else if(resp === 'ERROR') {
								alert(dat[0].msg);
							} else {
								mostrarErrores(respuesta, idformulario, entidad);
							}
						}
					});
				};
			}            
		}).find("div.modal-content").addClass("bootboxConfirmWidth");
		setTimeout(function () {
			if (contadorModal !== 0) {
				$('.modal' + (contadorModal-1)).css('pointer-events','auto');
				$('body').addClass('modal-open');
			}
		},2000);
	}
}

$(document).on('click', '.escogerFila', function(){
	$('.escogerFila').css('background-color', 'white');
	$(this).css('background-color', 'yellow');
});

function gestionlotes(valor, borrar = 0){	
	if(borrar === 1) {			
		$('#totalmovimiento2').html(parseFloat(0).toFixed(2));
		$('#totalmovimiento').val(parseFloat(0).toFixed(2));
		$('#detallesMovimiento').html('');
	}
	$('.botonlotes').css('display', 'none');
	if(valor === '9') {
		//SALIDA
		$('.fechavencimiento').css('display', 'none');
		$('#fechavencimiento').val('');		
		$('#lote').val('');
		$('.lote').css('display', 'none');		
		$('#cantidad').val('');		
		if($('#tienelote').val() !== '') {
			if($('#tienelote').val() === 'SI') {
				$('.botonlotes').css('display', 'block').attr('onclick', 'modal("movimientoalmacen/consultarlotes/' + $('#producto_id').val() + '","Seleccionar Lotes para Salida de Productos")');
				$('.cantidad').css('display', 'none');
			} else {
				$('.botonlotes').css('display', 'none');
				$('.cantidad').css('display', 'block').focus();
			}
		}			
	} else {
		//ENTRADA
		$('.cantidad').css('display', 'block');
		$('.fechavencimiento').css('display', 'block');
		$('.lote').css('display', 'block');
		$('.botonlotes').css('display', 'none');
	}
	if($('#tienelote').val() === 'NO') {
		$('#nombreproducto').focus();
	}
}

</script>