<?php use App\Movimiento; ?>
<style>
    table tbody tr td {
        font-size: 12px;
    }
</style>
@if($conceptopago_id==1)
	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-info btn-xs', 'disabled' => 'true', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-success btn-xs', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger btn-xs', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}    
@elseif($conceptopago_id==2)
    {!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-info btn-xs', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-success btn-xs', 'disabled' => 'true', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger btn-xs' , 'disabled' => 'true', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
@else
    {!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-info btn-xs', 'disabled' => 'true', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-success btn-xs', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger btn-xs', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
@endif
<!--{! Form::button('<i class="glyphicon glyphicon-print"></i> Honorario', array('class' => 'btn btn-warning btn-xs', 'id' => 'btnHonorario', 'onclick' => 'imprimirHonorario();')) !!}-->
@if($tipousuario!=11)
    {!! Form::button('<i class="glyphicon glyphicon-list"></i> Tickets Pendientes', array('class' => 'btn btn-primary btn-xs', 'id' => 'btnTicketsPendientes', 'onclick' => 'modalCaja (\''.URL::route($ruta["ticketspendientes"], array('listar'=>'SI')).'\', \''.$titulo_ticketspendientes.'\', this);')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;Cuentas por Cobrar', array('class' => 'btn btn-success btn-xs', 'id' => 'btnCuentasPendientes', 'onclick' => 'modalCaja (\''.URL::route($ruta["cuentaspendientes"], array('listar'=>'SI')).'\', \''.$titulo_cuentaspendientes.'\', this);')) !!}    
@endif
{!! Form::button('<i class="glyphicon glyphicon-print"></i> Exportar PDF', array('class' => 'btn btn-warning btn-xs', 'id' => 'btnDetalle', 'onclick' => 'imprimirDetalle();')) !!}
{!! Form::button('<i class="glyphicon glyphicon-print"></i> Exportar Excel', array('class' => 'btn btn-success btn-xs', 'id' => 'btnDetalle', 'onclick' => 'imprimirDetalleExcel();')) !!}  
@if($tipousuario!=11)
    {!! Form::button('<i class="glyphicon glyphicon-plus-sign"></i>&nbsp;&nbsp;Cirugías/Procedimientos', array('class' => 'btn btn-info btn-xs', 'id' => 'btncirupro', 'onclick' => 'modal("caja/cirupro", "Cirugías/Procedimientos");')) !!}
@endif 
<?php 
$saldo = number_format($ingreso - $egreso - $visa - $master,2,'.','');
?>
{!! Form::hidden('saldo', $saldo, array('id' => 'saldo')) !!}   
<hr />
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<div class="table-responsive">
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
        $visa2 = 0;
        $master2 = 0;
		$totaldolares = 0;
		?>
		@foreach ($lista as $key => $value)
        <?php
        $color="";
        $color2="";
        $titulo="";

        if($value->conceptopago_id==15 || $value->conceptopago_id==14 || $value->conceptopago_id==16 || $value->conceptopago_id==17 || $value->conceptopago_id==18 || $value->conceptopago_id==19 || $value->conceptopago_id==20 || $value->conceptopago_id==21){
            if($value->conceptopago_id==14 || $value->conceptopago_id==16 || $value->conceptopago_id==18 || $value->conceptopago_id==20){//TRANSFERENCIA EGRESO TARJETA, CONVENIO, SOCIO, BOLETEO QUE ENVIA
                if($value->situacion2=='P' && $value->situacion!='A'){//PENDIENTE
                    $color='background:rgba(255,235,59,0.76)';
                    $titulo="Pendiente";
                }elseif($value->situacion2=='R' && $value->situacion!='A'){
                    $color='background:rgba(215,57,37,0.50)';
                    $titulo="Rechazado";
                }elseif($value->situacion2=='C' && $value->situacion!='A'){
                    $color='background:rgba(10,215,37,0.50)';
                    $titulo="Aceptado";
                }elseif($value->situacion2=='A' || $value->situacion=='A'){
                    $color='background:rgba(215,57,37,0.50)';
                    $titulo='Anulado'; 
                }
                //echo "hola".$value->situacion2;
            }else{
                if($value->situacion=='P'){
                    $color='background:rgba(255,235,59,0.76)';
                    $titulo="Pendiente";
                }elseif($value->situacion=='R'){
                    $color='background:rgba(215,57,37,0.50)';
                    $titulo="Rechazado";
                }elseif($value->situacion=="C"){
                    $color='background:rgba(10,215,37,0.50)';
                    $titulo="Aceptado";
                }elseif($value->situacion=='A'){
                    $color='background:rgba(215,57,37,0.50)';
                    $titulo='Anulado'; 
                } 
            }
        }else{

            $color=($value->situacion=='A')?'background:rgba(215,57,37,0.50)':'';
            $titulo=($value->situacion=='A')?'Anulado':'';            
        }
        if($value->conceptopago->tipo=='I'){
            $color2='color:green;font-weight: bold;';
        }else{
            $color2='color:red;font-weight: bold;';
        }
        $nombrepaciente = '';
        if($value->caja_id == 4){
            if ($value->persona_id !== NULL) {
                    //echo 'entro'.$value->paciente;break;
                $nombrepaciente = $value->paciente;

            }else{
                $nombrepaciente = trim($value->nombrepaciente);
            }
                            /*if ($value->tipodocumento_id == 5) {
                                
                                
                            }else{
                                $nombrepaciente = trim($value->empresa->nombre);
                            }*/
        }
        ?>
		<tr style="{{ $color }}" title="{{ $titulo }}">
            <td>{{ date('d/m/Y',strtotime($value->fecha)) }}</td>
            <td>{{ $value->ventafarmacia }}</td>
            <td>{{ $value->conceptopago->nombre }}</td>
            @if ($value->caja_id == 4)
                <td>{{ $nombrepaciente}}</td>
            @else
                @if($value->persona_id == '') 
                    <td>{{ $value->nombrepaciente}}</td>
                @else
                    <td>{{ $value->paciente}}</td>
                @endif
            @endif
           
            @if(!is_null($value->situacion) && $value->situacion<>'R' && !is_null($value->situacion2) && $value->situacion2<>'R')
                @if($value->conceptopago_id>0 && !is_null($value->conceptopago_id) && $value->conceptopago->tipo=="I")
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                    <td align="center">0.00</td>
                @else
                    <td align="center">0.00</td>
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                @endif
            @else
                @if($value->conceptopago->tipo=="I")
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                    <td align="center">0.00</td>
                @else
                    <td align="center">0.00</td>
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                @endif
            @endif 

            <?php

            $vnt = Movimiento::where('ventafarmacia', 'N')->where('id', $value->movimiento_id)->first();
            $formapago = '';

            if($vnt !== NULL) {
                $tkt = Movimiento::find($vnt->movimiento_id);
                if($tkt->numeroserie2 == 'DOLAR') {
                    $totaldolares += number_format($value->total,2,'.','');
                } else {
                    if($value->totalpagado!=0) {
                        $formapago .= 'Efectivo = ';
                        $formapago .= (String)number_format($value->totalpagado,2,'.','').'<br>';
                    }
                    if($value->totalpagadovisa!=0) {
                        $formapago .= 'Visa = '; 
                        $formapago .= (String)number_format($value->totalpagadovisa,2,'.','') .'<br>';
                        if($value->situacion == 'N') {
                            $visa2 += $value->totalpagadovisa;
                        }
                    }
                    if($value->totalpagadomaster!=0) {
                        $formapago .= 'Master = '; 
                        $formapago .= (String)number_format($value->totalpagadomaster,2,'.','') . '<br>';
                        if($value->situacion == 'N') {
                            $master2 += $value->totalpagadomaster;
                        }
                    }
                }
            } else {
                if($value->totalpagado!=0) {
                    $formapago .= 'Efectivo = ';
                    $formapago .= (String)number_format($value->totalpagado,2,'.','').'<br>';
                }
                if($value->totalpagadovisa!=0) {
                    $formapago .= 'Visa = '; 
                    $formapago .= (String)number_format($value->totalpagadovisa,2,'.','') .'<br>';
                    if($value->situacion == 'N') {
                        $visa2 += $value->totalpagadovisa;
                    }
                }
                if($value->totalpagadomaster!=0) {
                    $formapago .= 'Master = '; 
                    $formapago .= (String)number_format($value->totalpagadomaster,2,'.','') . '<br>';
                    if($value->situacion == 'N') {
                        $master2 += $value->totalpagadomaster;
                    }
                }


            } ?>

            <td align="center"><?php echo $formapago; ?></td>
            <td>{{ $value->comentario }}</td>
            <td>{{ $value->responsable }}</td>
            <?php //echo $value->conceptopago_id; ?>
            @if($value->conceptopago_id<>2 && $value->conceptopago_id<>1 && $value->situacion<>'A' && !is_null($value->situacion2)  && $value->situacion2<>'R')
                <td align="center">{!! Form::button('<div class="glyphicon glyphicon-print"></div>', array('onclick' => 'imprimirRecibo ('.$value->id.');', 'class' => 'btn btn-xs btn-warning', 'title' => 'Imprimir')) !!}</td>
            @elseif($value->conceptopago_id<>2 && $value->conceptopago_id<>1 && $value->situacion<>'A')
                <td align="center">{!! Form::button('<div class="glyphicon glyphicon-print"></div>', array('onclick' => 'imprimirRecibo ('.$value->id.');', 'class' => 'btn btn-xs btn-warning', 'title' => 'Imprimir')) !!}</td>
            @else
                <td align="center"> - </td>
            @endif
            <?php //echo $value->conceptopago_id.''; ?>
            @if($conceptopago_id<>2 && $value->situacion<>'A' && $value->conceptopago_id<>15 && $value->conceptopago_id<>17 && $value->conceptopago_id<>19 && $value->conceptopago_id<>21 && $value->conceptopago_id<>32)
                @if($value->conceptopago_id<>1 && $value->conceptopago_id<>2 && $value->conceptopago_id<>14 && $value->conceptopago_id<>16 && $value->conceptopago_id<>18 && $value->conceptopago_id<>20)
                    @if($user->usertype_id==8 || $user->usertype_id==1 || $user->usertype_id==7 || $user->usertype_id==23 || $user->usertype_id==11)
                        <td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_anular.'\', this);', 'class' => 'btn btn-xs btn-danger', 'title' => 'Anular')) !!}</td>
                    @else
                        <td align="center"> - </td>
                    @endif
                @elseif(($value->conceptopago_id==14 || $value->conceptopago_id==16 || $value->conceptopago_id==18 || $value->conceptopago_id==20) && !is_null($value->situacion2)  && $value->situacion2=='P')
                    @if($user->usertype_id==8 || $user->usertype_id==1 || $user->usertype_id==7 || $user->usertype_id==23)
                        <td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_anular.'\', this);', 'class' => 'btn btn-xs btn-danger', 'title' => 'Anular')) !!}</td>
                    @else
                        <td align="center"> - </td>
                    @endif
                @else
                    <td align="center"> - </td>
                @endif
                
                <td align="center"> - </td>
            @elseif(($value->conceptopago_id==15 || $value->conceptopago_id==17 || $value->conceptopago_id==19 || $value->conceptopago_id==21|| $value->conceptopago_id==32 || $value->conceptopago_id==100) && $value->situacion=='P')
                <?php //echo 'entro'; ?>
                <td align="center">{!! Form::button('<div class="glyphicon glyphicon-check"></div> Aceptar y Descargar', array('onclick' => 'modal (\''.URL::route($ruta["descarga"], array('movimiento_id'=>$value->id, 'SI')).'\', \'Aceptar\', this);', 'class' => 'btn btn-xs btn-success')) !!}{!! Form::button('<div class="glyphicon glyphicon-check"></div> Aceptar', array('onclick' => 'modal (\''.URL::route($ruta["acept"], array($value->id, 'SI')).'\', \'Aceptar\', this);', 'class' => 'btn btn-xs btn-success')) !!}</td>
                <td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Rechazar', array('onclick' => 'modal (\''.URL::route($ruta["reject"], array($value->id, 'SI')).'\', \'Rechazar\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
            @else
                <td align="center"> - </td>
                <td align="center"> - </td>
            @endif
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion or '' !!}
<table class="table-bordered table-striped table-condensed" align="center">
    <thead>
        <tr>
            <th class="text-center" colspan="2">Resumen de Caja</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>INGRESOS</th>
            <th class="text-right">{{ number_format($ingreso-$totaldolares,2,'.','') }}</th>
        </tr>
        <tr>
            <td>Efectivo</td>
            <td align="right">{{ number_format($efectivo-$visa2-$master2-$totaldolares,2,'.','') }}</td>
        </tr>
        <tr>
            <td>Master</td>
            <td align="right">{{ number_format($master+$master2,2,'.','') }}</td>
        </tr>
        <tr>
            <td>Visa</td>
            <td align="right">{{ number_format($visa+$visa2,2,'.','') }}</td>
        </tr>
        <tr>
            <th>Egresos</th>
            <th class="text-right">{{ number_format($egreso,2,'.','') }}</th>
        </tr>
        <tr>
            <th>SALDO (S/.)</th>
            <th class="text-right">{{ number_format($ingreso - $egreso - $visa - $master - $totaldolares,2,'.','') }}</th>
        </tr>
        <tr>
            <th>SALDO ($)</th>
            <th class="text-right">{{ number_format($totaldolares,2,'.','') }}</th>
        </tr>
        <tr>
            <th>CAJA (S/.)</th>
            <th class="text-right">{{ number_format($efectivo-$visa2-$master2-$totaldolares-($egreso),2,'.','') }}</th>
        </tr>
        <tr style="display:none;">
            <th>Garantia</th>
            <th class="text-right">{{ number_format($garantia,2,'.','') }}</th>
        </tr>
    </tbody>
</table>
</div>
@endif