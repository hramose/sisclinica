<?php
	use App\Movimiento;
	use Illuminate\Support\Facades\DB;
?>
<table border="1">
	<tr>
		<td></td>
		<td style="background-color: #B9FCBB" colspan="{{ 8 + count($egresos) }}">CAJA DIARIA</td>
	</tr>
    <tr>
    	<td></td>
    	<td style="background-color: #FFE7AC">INGRESOS DEL DIA</td>
    	<td style="background-color: #FFE7AC">INGRESOS DE VUELTOS</td>
    	<td style="background-color: #FFE7AC">OTROS INGRESOS</td>
    	<td style="background-color: #FFE7AC">TOTAL</td>
        @foreach($egresos as $egreso)
			<td style="background-color: #B9FCBB">{{ $egreso->nombre }}</td>
        @endforeach
        <td style="background-color: #B9FCBB">TOTAL MASTER</td>
        <td style="background-color: #B9FCBB">TOTAL VISA</td>
        <td style="background-color: #B9FCBB">TOTAL</td>
        <td style="background-color: #C39CFF">TOTAL DÓLARES</td>
    </tr>
    @foreach ($aperturas as $apertura)

    <?php 

    	$totalventas = 0;
    	$totalvuelto = 0;
    	$totalotrosingresos = 0;
    	$totalvisa = 0;
        $totalmaster = 0;
    	$totaldolares = 0;

    	//Cierre de la presente caja

        $cierre = Movimiento::select('id')
                ->where('conceptopago_id', 2)
                ->where('caja_id', $caja_id)
                ->where('sucursal_id', $sucursal_id)
                ->where('id' , '>', $apertura->id)
                ->limit(1)->first();

        //Pagos de tickets sin dólares
                
        $listaventas = Movimiento::leftjoin('movimiento as m2','m2.movimiento_id','=','movimiento.id')
                ->leftjoin('movimiento as m3','movimiento.movimiento_id','=','m3.id')
                ->where('m3.numeroserie2', '!=', 'DOLAR')
        		->where('m2.situacion', 'N')
        		->where('movimiento.ventafarmacia','=','N')
                ->where('movimiento.sucursal_id', '=', $sucursal_id)
                ->whereBetween('movimiento.id', [$apertura->id,(int)$cierre['id']])
                ->where('movimiento.caja_id', '=', $caja_id)
                ->select(DB::raw('SUM(movimiento.total) as tot'), DB::raw('SUM(m2.totalpagadovisa) as totvisa'),DB::raw('SUM(m2.totalpagadomaster) as totmaster'))
                ->get();

        //Pagos de tickets solo dólares

        $listadolares = Movimiento::leftjoin('movimiento as m2','m2.movimiento_id','=','movimiento.id')
                ->leftjoin('movimiento as m3','movimiento.movimiento_id','=','m3.id')
                ->where('m3.numeroserie2', '=', 'DOLAR')
                ->where('m2.situacion', 'N')
                ->where('movimiento.ventafarmacia','=','N')
                ->where('movimiento.sucursal_id', '=', $sucursal_id)
                ->whereBetween('movimiento.id', [$apertura->id,(int)$cierre['id']])
                ->where('movimiento.caja_id', '=', $caja_id)
                ->select(DB::raw('SUM(movimiento.total) as tot'))
                ->get();

        //Solo para cuotas

        $listacuotas = Movimiento::where('movimiento.tipomovimiento_id', '=', 2)
                ->where('movimiento.tipodocumento_id', '=', 2)
                ->where('movimiento.situacion2','=','Z')
                ->where('movimiento.situacion','=','N')
                ->where('movimiento.sucursal_id', '=', $sucursal_id)
                ->whereBetween('movimiento.id', [$apertura->id,(int)$cierre['id']])
                ->where('movimiento.caja_id', '=', $caja_id)
                ->select(DB::raw('SUM(movimiento.total) as tot'), DB::raw('SUM(movimiento.totalpagadovisa) as totvisa'),DB::raw('SUM(movimiento.totalpagadomaster) as totmaster'))
                ->get();

        //Solo para ingresos varios

        $listaingresosvarios = Movimiento::join('conceptopago','conceptopago.id','=','movimiento.conceptopago_id')
                ->where('movimiento.tipomovimiento_id', '=', 2)
                ->where('movimiento.situacion','=','N')
                ->where('movimiento.sucursal_id', '=', $sucursal_id)
                ->where('movimiento.caja_id', '=', $caja_id)
                ->whereBetween('movimiento.id', [$apertura->id,(int)$cierre['id']])
                ->where('movimiento.situacion2', '=', 'Q')
                ->where('conceptopago.tipo', '=', 'I')
                ->select('conceptopago.id', DB::raw('SUM(movimiento.total) as tot'))
                ->groupBy('conceptopago.id')
                ->get();

        //Solo para egresos

        $listaegresos        = Movimiento::join('conceptopago','conceptopago.id','=','movimiento.conceptopago_id')
            ->where('movimiento.situacion','=','N')
            ->where('movimiento.caja_id', '=', $caja_id)
            ->where('movimiento.sucursal_id','=',$sucursal_id)
            ->whereBetween('movimiento.id', [$apertura->id,(int)$cierre['id']])
            ->whereNull('movimiento.cajaapertura_id')
            ->whereNotIn('movimiento.conceptopago_id',[31, 2])
            ->where('conceptopago.tipo', '=', 'E')
            ->where('movimiento.situacion2', '=', 'Q')
            ->select('conceptopago.id', DB::raw('SUM(movimiento.total) as tot'))
            ->groupBy('conceptopago.id')
            ->orderBy('conceptopago.id', 'DESC')
            ->get();

        if(count($listaventas) > 0){
            foreach ($listaventas as $row) { 
                $totalventas += $row->tot;
                $totalvisa += $row->totvisa;
                $totalmaster += $row->totmaster;
	        }  
        }

        if(count($listadolares) > 0){
            foreach ($listadolares as $row) { 
                $totaldolares += $row->tot;
            }  
        }

        if(count($listacuotas)>0){
            foreach ($listacuotas as $row) {
            	$totalventas += $row->tot;
		    	$totalvisa += $row->totvisa;
		    	$totalmaster += $row->totmaster;
            }                                  
        }

        if(count($listaingresosvarios)>0){
            foreach ($listaingresosvarios as $row) {
            	if($row->id == 4) {
    				$totalotrosingresos += $row->tot;
            	} else if($row->id == 88) {
            		$totalvuelto += $row->tot;
            	}
            }            
        } 
    ?>

    <tr>
    	<td>{{ date("d/m/Y", strtotime($apertura->fecha)) }}</td>
    	<td>{{ $totalventas != 0 ? $totalventas : '' }}</td>
    	<td>{{ $totalvuelto != 0 ? $totalvuelto : '' }}</td>
    	<td>{{ $totalotrosingresos != 0 ? $totalotrosingresos : '' }}</td>
    	<td style="background-color: #FFE7AC">{{ $totalventas + $totalvuelto + $totalotrosingresos }}</td>
    	<?php $totalegresos = 0; ?>
    	@foreach($egresos as $egreso)
            <?php $esta = false; ?>
    		@if(count($listaegresos) > 0)
    			<?php for ($a = 0; $a < count($listaegresos); $a++) { ?>
    				@if($egreso->id == $listaegresos[$a]->id)
    					<td>{{ $listaegresos[$a]->tot != 0 ? $listaegresos[$a]->tot : '' }}</td>
    					<?php $totalegresos += $listaegresos[$a]->tot; $esta = true; break; ?>
    				@endif
    			<?php } ?>	
                @if($esta == false)
                    <td></td>
                @endif
			@else 
				<td></td>
			@endif	
        @endforeach
        <td>{{ $totalmaster != 0 ? $totalmaster : '' }}</td>
		<td>{{ $totalvisa != 0 ? $totalvisa : '' }}</td>
		<td style="background-color: #B9FCBB">{{ $totalegresos + $totalvisa + $totalmaster }}</td>
        <td style="background-color: #C39CFF">{{ $totaldolares }}</td>
    </tr>

    @endforeach

</table>