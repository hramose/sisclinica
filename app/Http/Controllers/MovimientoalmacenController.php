<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Venta;
use App\Almacen;
use App\Producto;
use App\Distribuidora;
use App\Tipodocumento;
use App\Detallemovimiento;
use App\Kardex;
use App\Movimiento;
use App\Detallemovcaja;
use App\Movimientoalmacen;
use App\Lote;
use App\Stock;
use App\Cuenta;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;
use Elibyy\TCPDF\Facades\TCPDF;

ini_set('memory_limit', '512M'); //Raise to 512 MB
ini_set('max_execution_time', '60000'); //Raise to 512 MB 

class MovimientoalmacenController extends Controller
{
    protected $folderview      = 'app.movimientoalmacen';
    protected $tituloAdmin     = 'Movimientos Almacen';
    protected $tituloRegistrar = 'Registrar movimiento almacen';
    protected $tituloModificar = 'Modificar movimiento almacen';
    protected $tituloVer       = 'Ver movimiento almacen';
    protected $tituloEliminar  = 'Eliminar movimiento almacen';
    protected $rutas           = array('create' => 'movimientoalmacen.create', 
            'edit'   => 'movimientoalmacen.edit',
            'show'   => 'movimientoalmacen.show', 
            'delete' => 'movimientoalmacen.eliminar',
            'search' => 'movimientoalmacen.buscar',
            'index'  => 'movimientoalmacen.index',
            'corregir' => 'movimientoalmacen.corregir',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Movimientoalmacen';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        //sucursal_id
        $sucursal_id = Session::get('sucursal_id');
        $cboAlmacen      = Almacen::where('sucursal_id', '=', $sucursal_id)->get()->pluck('nombre', 'id');
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'cboAlmacen', 'titulo_registrar', 'ruta'));
    }

    public function buscarproducto(Request $request)
    {
        $entidad          = 'Producto';
        $title            = 'Agregar Productos';
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipo          = array("" => "Todos","P" => "Producto", "I" => "Insumo", "O" => "Otros");  
        $tipo2 = $request->input('tipo');
        return view($this->folderview.'.producto')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboTipo','tipo2'));
    }

     /**
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Movimientoalmacen';
        $fechainicio             = Libreria::getParam($request->input('fechainicio'));
        $fechafin             = Libreria::getParam($request->input('fechafin'));

        //sucursal_id
        $sucursal_id = Session::get('sucursal_id');

        $user=Auth::user();
        if($sucursal_id == 1) {
            if($user->usertype_id == 11) {
                $almacen_id = 1;
            } else {
                $almacen_id = 2;
            }
        } else {
            if($user->usertype_id == 11) {
                $almacen_id = 3;
            } else {
                $almacen_id = 4;
            }
        }

        //$almacen_id  = $request->input('almacen_id');

        $resultado        = Movimientoalmacen::where('tipomovimiento_id', '=', '5')
                            ->where('sucursal_id','=',$sucursal_id)
                            ->where('almacen_id','=',$almacen_id)
                            ->where(function($query) use ($fechainicio,$fechafin){   
                                if (!is_null($fechainicio) && $fechainicio !== '') {
                                    $begindate   = Date::createFromFormat('d/m/Y', $fechainicio)->format('Y-m-d');
                                    $query->where('fecha', '>=', $begindate);
                                }
                                if (!is_null($fechafin) && $fechafin !== '') {
                                    $enddate   = Date::createFromFormat('d/m/Y', $fechafin)->format('Y-m-d');
                                    $query->where('fecha', '>=', $enddate);
                                }
                            })->select('movimiento.*');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Id', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro Comprobante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_ver  = $this->tituloVer;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'titulo_ver', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    public function listarproducto(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Producto';
        $nombre             = Libreria::getParam($request->input('nombre'));
        $resultado        = Producto::where('nombre', 'LIKE', '%'.strtoupper($nombre).'%')->where(function ($query) use($request){
                        if ($request->input('tipo') !== null && $request->input('tipo') !== '') {
                            $query->where('tipo', '=', $request->input('tipo'));
                        }
                    })->orderBy('nombre', 'ASC');
        $lista            = $resultado->get();
        $tipo2             = Libreria::getParam($request->input('tipo2'));
        //$cboDistribuidora        = Distribuidora::lists('nombre', 'id')->all();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Codigo SUNASA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Concentracion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Forma', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Presentacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'R.Sanitario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Stock', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Precio Venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad', 'numero' => '1');
        if ($tipo2 == 'I') {
            $cabecera[]       = array('valor' => 'Lote', 'numero' => '1');
            $cabecera[]       = array('valor' => 'Fecha Venc', 'numero' => '1');
        }
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.listproducto')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','cboDistribuidora','tipo2'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    public function agregarcarritomovimientoalmacen(Request $request)
    {
        //$lista = array();
        $cadena = '';
        /*if ($request->session()->get('carritomovimientoalmacen') !== null) {
            $lista          = $request->session()->get('carritomovimientoalmacen');
            $cantidad       = Libreria::getParam($request->input('cantidad'));
            $producto_id       = Libreria::getParam($request->input('producto_id'));
            $precio       = Libreria::getParam($request->input('precio'));
            $preciokayros       = Libreria::getParam($request->input('preciokayros'));
            $precioventa       = Libreria::getParam($request->input('precioventa'));
            $fechavencimiento       = Libreria::getParam($request->input('fechavencimiento'));
            $lote       = Libreria::getParam($request->input('lote'));
            $distribuidora_id       = Libreria::getParam($request->input('distribuidora_id'));
            $producto   = Producto::find($producto_id);
            $estaPresente   = false;
            $indicepresente = '';
            for ($i=0; $i < count($lista); $i++) { 
                if ($lista[$i]['producto_id'] == $producto_id) {
                    $estaPresente   = true;
                    $indicepresente = $i;
                }
            }
            if ($estaPresente === true) {
                $lista[$indicepresente]  = array('cantidad' => $cantidad, 'precio' => $precio, 'productonombre' => $producto->nombre,'producto_id' => $producto_id,'fechavencimiento' => $fechavencimiento,'lote' => $lote,'distribuidora_id' => $distribuidora_id, 'codigobarra' => $producto->codigobarra, 'preciokayros' => $preciokayros, 'precioventa' => $precioventa);
            }else{
                $lista[]  = array('cantidad' => $cantidad, 'precio' => $precio, 'productonombre' => $producto->nombre,'producto_id' => $producto_id,'fechavencimiento' => $fechavencimiento,'lote' => $lote,'distribuidora_id' => $distribuidora_id, 'codigobarra' => $producto->codigobarra, 'preciokayros' => $preciokayros, 'precioventa' => $precioventa);
            }
            
            $cadena   .= '<table style="width: 100%;" border="1">';
            $cadena   .= '<thead>
                                <tr>
                                    <th bgcolor="#E0ECF8" class="text-center">Producto</th>
                                    <th bgcolor="#E0ECF8" class="text-center">Cantidad</th>
                                    <th bgcolor="#E0ECF8" class="text-center">Precio</th>
                                    <th bgcolor="#E0ECF8" class="text-center">Subtotal</th>
                                    <th bgcolor="#E0ECF8" class="text-center">Quitar</th>                            
                                </tr>
                            </thead>';
            
            $total = 0;
            
            for ($i=0; $i < count($lista); $i++) {
                $subtotal = round(($lista[$i]['cantidad']*$lista[$i]['precio']), 2);
                $total    += $subtotal;
                $cadena   .= '<tr><td class="text-center" style="width:750px;">'.$lista[$i]['productonombre'].'</td>';
                $cadena   .= '<td class="text-center" style="width:100px;">'.$lista[$i]['cantidad'].'</td>';
                $cadena   .= '<td class="text-center" style="width:100px;">'.$lista[$i]['precio'].'</td>';
                $cadena   .= '<td class="text-center" style="width:90px;">'.$subtotal.'</td>';
                $cadena   .= '<td class="text-center"><a class="btn btn-xs btn-danger" onclick="quitar(\''.$i.'\');">Quitar</a></td></tr>';
            }
            $cadena  .= '<tr><th colspan="3" style="text-align: right;">TOTAL</th><td class="text-center">'.$total.'<input type ="hidden" id="totalcompra" readonly=""  name="totalcompra" value="'.$total.'"></td></tr></tr>';
            $cadena .= '</table>';
            $request->session()->put('carritomovimientoalmacen', $lista);

        }
        else{*/
            //$cantidad          = Libreria::getParam($request->input('cantidad'));
            $cantidades1       = Libreria::getParam(str_replace(",", "", $request->input('cantidad'))); 
            $cantidades2       = explode("F", $cantidades1);
            $producto_id       = Libreria::getParam($request->input('producto_id'));
            $precio            = Libreria::getParam($request->input('precio'));
            $preciokayros      = Libreria::getParam($request->input('preciokayros'));
            $precioventa       = Libreria::getParam($request->input('precioventa'));
            $producto          = Producto::find($producto_id);
            $fechavencimiento  = Libreria::getParam($request->input('fechavencimiento'));
            $lote              = Libreria::getParam($request->input('lote'));
            $distribuidora_id  = Libreria::getParam($request->input('distribuidora_id'));
            $mensajecantidad   = '';
            $elemento          = Libreria::getParam($request->input('elemento'));
            $lotess = '';

            if($elemento != 'N') {
                //Calculamos el total  
                $totallote = 0;
                $cantidadeslote = explode(";", $elemento);
                $lotess = '';
                $cantidadesloteindiv = '';
                $elementos = (count($cantidadeslote) - 1)/2;
                if($producto->fraccion != 1) {                
                    for ($i=0; $i < $elementos; $i++) { 
                        $cantidadesloteindiv = explode("F", $cantidadeslote[$i]);
                        $totallote += $cantidadesloteindiv[0]*$producto->fraccion + $cantidadesloteindiv[1];  
                        $objLote = Lote::find($cantidadeslote[$i + $elementos]); 
                        $lotess .= $objLote->nombre;               
                        if($i != $elementos - 1) {
                           $lotess .= '/';
                        }
                    }
                    $parte1 = floor($totallote/$producto->fraccion);
                    $parte2 = $totallote - $parte1*$producto->fraccion;
                    $cantidades2 = explode("F", (String) $parte1 . 'F' . (String) $parte2);
                } else {
                    for ($i=0; $i < $elementos; $i++) { 
                        $totallote += $cantidadeslote[$i];
                        $objLote = Lote::find($cantidadeslote[$i + $elementos]); 
                        $lotess .= $objLote->nombre;
                        if($i != $elementos - 1) {
                           $lotess .= '/';
                        }
                    }
                    $cantidades2 = explode("F", (String) $totallote);
                }
            }

            if($producto->fraccion != 1 && count($cantidades2) == 2) {
                if(!is_numeric($cantidades2[0]) || !is_numeric($cantidades2[1])) {
                    return '0-0';
                }
                $cantidadpresentacion1 = (float) $cantidades2[0];
                $cantidadpresentacion2 = (float) $cantidades2[1];
                $cantidadunidades = ($producto->fraccion*$cantidadpresentacion1)+$cantidadpresentacion2;
                $mensajecantidad .= (String) $cantidadpresentacion1 . ' ' . $producto->presentacion->nombre . 'S, ' . (String) $cantidadpresentacion2 . ' UNIDADES';
            } else if($producto->fraccion == 1 && count($cantidades2) == 1) {            
                if(!is_numeric($cantidades2[0])) {
                    return '0-0';
                }
                $cantidadunidades = $producto->fraccion * $cantidades2[0]; 
                $mensajecantidad .= (String) $cantidadunidades . ' UNIDADES';
            } else {
                return '0-0';
            }

            if($request->input('tipo') == '9') {
                if($request->input('stock') < $cantidadunidades || $request->input('stock') == 0) {
                    return '0-1';
                }
            }

            $subtotal          = round(($cantidadunidades*$precio), 2);

            $cadena .= '<td class="numeration3"></td>
                    <td class="text-center infoProducto">
                        <span style="display: block; font-size:.9em">'.$producto->nombre.'</span>
                        <input type ="hidden" class="productonombre" value="'.$producto->nombre.'">
                        <input type ="hidden" class="fechavencimiento" value="'.$fechavencimiento.'">
                        <input type ="hidden" class="lote" value="'.$lote.'">
                        <input type ="hidden" class="distribuidora_id" value="'.$distribuidora_id.'">
                        <input type ="hidden" class="cantidad" value="'.$cantidadunidades.'">
                        <input type ="hidden" class="subtotal" value="'.$subtotal.'">
                        <input type ="hidden" class="producto_id"  value="'.$producto_id.'">
                        <input type ="hidden" class="codigobarra" value="'.$producto->codigobarra.'">
                        <input type ="hidden" class="precioventa" value="'.number_format($precioventa,2,'.','').'">
                        <input type ="hidden" class="preciokayros" value="'.number_format($preciokayros,2,'.','').'">';
            if($elemento != 'N') {
                $cadena .= '<input type ="hidden" class="datoLote" value="'.$elemento.'">';                
            }

            $cadena .= '<input type ="hidden" class="precio" value="'.$precio.'">
                    </td>';
            if($lote == '') {
                $lote = '-';
            }
            if($elemento != 'N') {
                $lote = $lotess;
            }
            $cadena .= '<td class="text-center">
                        <span style="display: block; font-size:.9em">'.$lote.'</span>                    
                    </td>';
            $cadena .= '<td class="text-center">
                        <span style="display: block; font-size:.9em">'.$mensajecantidad.'</span>                    
                    </td>';
            $cadena .= '<td class="text-center">
                        <span style="display: block; font-size:.9em">'.number_format($precio,2,'.', '').'</span>                    
                    </td>';
            $cadena .= '<td class="text-center">
                        <span style="display: block; font-size:.9em">'.number_format($subtotal,2,'.', '').'</span>                    
                    </td>';
            $cadena .= '<td class="text-center">
                        <span style="display: block; font-size:.9em">
                        <a class="btn btn-xs btn-danger quitarFila">Quitar</a></span>
                    </td>';
            //$lista[]  = array('cantidad' => $cantidad, 'precio' => $precio, 'productonombre' => $producto->nombre,'producto_id' => $producto_id,'fechavencimiento' => $fechavencimiento,'lote' => $lote,'distribuidora_id' => $distribuidora_id, 'codigobarra' => $producto->codigobarra, 'preciokayros' => $preciokayros, 'precioventa' => $precioventa);
            //$request->session()->put('carritomovimientoalmacen', $lista);
        /*}*/
        return $cadena; 
    }

    public function quitarcarritomovimientoalmacen(Request $request)
    {
        $id       = $request->input('valor');
        $cantidad = count($request->session()->get('carritomovimientoalmacen'));
        $lista2   = $request->session()->get('carritomovimientoalmacen');
        $lista    = array();
        $producto_id = '';
        for ($i=0; $i < $cantidad; $i++) {
            if ($i != $id) {
                $lista[] = $lista2[$i];
            }else{
                $producto_id = $lista2[$i]['producto_id'];
            }
        }
        $cadena   = '<table style="width: 100%;" class="table-condensed table-striped">';
        $cadena   .= '<thead>
                            <tr>
                                <th bgcolor="#E0ECF8" class="text-center">Producto</th>
                                <th bgcolor="#E0ECF8" class="text-center">Cantidad</th>
                                <th bgcolor="#E0ECF8" class="text-center">Precio</th>
                                <th bgcolor="#E0ECF8" class="text-center">Subtotal</th>
                                <th bgcolor="#E0ECF8" class="text-center">Quitar</th>                            
                            </tr>
                        </thead>';
            
            $total = 0;
            
            for ($i=0; $i < count($lista); $i++) {
                $subtotal = round(($lista[$i]['cantidad']*$lista[$i]['precio']), 2);
                $total    += $subtotal;
                $cadena   .= '<tr><td class="text-center" style="width:750px;">'.$lista[$i]['productonombre'].'</td>';
                $cadena   .= '<td class="text-center" style="width:100px;">'.$lista[$i]['cantidad'].'</td>';
                $cadena   .= '<td class="text-center" style="width:100px;">'.$lista[$i]['precio'].'</td>';
                $cadena   .= '<td class="text-center" style="width:90px;">'.$subtotal.'</td>';
                $cadena   .= '<td class="text-center"><a class="btn btn-xs btn-danger" onclick="quitar(\''.$i.'\');">Quitar</a></td></tr>';
            }
            $cadena  .= '<tr><th colspan="3" style="text-align: right;">TOTAL</th><td class="text-center">'.$total.'<input type ="hidden" id="totalmovimientoalmacen" readonly=""  name="totalmovimientoalmacen" value="'.$total.'"></td></tr></tr>';
            $cadena .= '</table>';
        $request->session()->put('carritomovimientoalmacen', $lista);
        return  $cadena;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad  = 'Movimientoalmacen';
        $movimientoalmacen = null;
        //$cboDocumento        = Tipodocumento::lists('nombre', 'id')->all();
        $cboDocumento = array();
        $listdocument = Tipodocumento::where('tipomovimiento_id','=','5')->get();
        foreach ($listdocument as $key => $value) {
            $cboDocumento = $cboDocumento + array( $value->id => $value->nombre);
        }
        $cboTipo        = array("8" => 'Ingreso', '9' => 'Salida');
        $formData = array('movimientoalmacen.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar'; 
        $request->session()->forget('carritomovimientoalmacen');

        
        //sucursal_id
        $sucursal_id = Session::get('sucursal_id');
        $cboAlmacen      = Almacen::where('sucursal_id', '=', $sucursal_id)->get()->pluck('nombre', 'id');

        return view($this->folderview.'.mant')->with(compact('movimientoalmacen', 'cboAlmacen', 'formData', 'entidad', 'boton', 'listar','cboDocumento','cboTipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
                'numerodocumento'                  => 'required',
                'fecha'                 => 'required'
                );
        $mensajes = array(
            'numerodocumento.required'         => 'Debe ingresar un numero de documento',
            'fecha.required'         => 'Debe ingresar fecha'
            );
        

        //if (is_null($request->session()->get('carritomovimientoalmacen')) || count($request->session()->get('carritomovimientoalmacen')) === 0) {
        if ($request->input('cantproductos') == 0) {            
            $error = array(
                'cantproductos' => array(
                    'Debe agregar al menos un producto'
                    ));
            return json_encode($error);
        }

        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        $sucursal_id = Session::get('sucursal_id');

        $user=Auth::user();
        if($sucursal_id == 1) {
            if($user->usertype_id == 11) {
                $almacen_id = 1;
            } else {
                $almacen_id = 2;
            }
        } else {
            if($user->usertype_id == 11) {
                $almacen_id = 3;
            } else {
                $almacen_id = 4;
            }
        }

        $dat=array();
        $error = DB::transaction(function() use($request,&$dat, $sucursal_id, $almacen_id){
            //$lista = $request->session()->get('carritomovimientoalmacen');
            $lista = $request->input('cantproductos');
            $total = $request->input('totalmovimiento');
            $movimientoalmacen                    = new Movimientoalmacen();
            $movimientoalmacen->tipodocumento_id  = $request->input('tipo');
            $movimientoalmacen->tipomovimiento_id = 5;
            $movimientoalmacen->almacen_id        = $almacen_id;
            $movimientoalmacen->sucursal_id       = $sucursal_id;
            //$movimientoalmacen->persona_id = $request->input('person_id');
            $movimientoalmacen->comentario   = Libreria::obtenerParametro($request->input('comentario'));
            $movimientoalmacen->numero = $request->input('numerodocumento');
            $movimientoalmacen->fecha  = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
            $movimientoalmacen->total = $total;
            
            $user = Auth::user();
            $movimientoalmacen->responsable_id = $user->person_id;
            $movimientoalmacen->save();
            $movimiento_id = $movimientoalmacen->id;

            //for ($i=0; $i < count($lista); $i++) {
            
            for ($i=1; $i <= $lista; $i++) {

                //Edito atributos de producto

                $producto = Producto::find($request->input('producto_id'.$i));
                $producto->preciokayros = $request->input('preciokayros' . $i);
                $producto->preciocompra = $request->input('precio' . $i);
                $producto->precioventa = $request->input('precioventa' . $i);
                $producto->save();

                $cantidad  = str_replace(',', '',$request->input('cantidad'.$i));
                $precio    = str_replace(',', '',$request->input('precio'.$i));
                $subtotal  = round(($cantidad*$precio), 2);
                $detalleVenta = new Detallemovimiento();
                $detalleVenta->cantidad = $cantidad;
                $detalleVenta->precio = $precio;
                $detalleVenta->subtotal = $subtotal;
                $detalleVenta->movimiento_id = $movimiento_id;
                $detalleVenta->producto_id = $request->input('producto_id'.$i);
                $detalleVenta->save();
                $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $request->input('producto_id'.$i))->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();
                //$ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->where('promarlab_id', '=', $lista[$i]['promarlab_id'])->where('kardex.almacen_id', '=',1)->orderBy('kardex.id', 'DESC')->first();

                // Creamos el lote para el producto
                if ($request->input('tipo') == '8') {
                    $lote = new Lote();
                    $lote->nombre  = $request->input('lote'.$i);
                    $lote->fechavencimiento  = Date::createFromFormat('d/m/Y', $request->input('fechavencimiento'.$i))->format('Y-m-d');
                    $lote->cantidad = $cantidad;
                    $lote->queda = $cantidad;
                    $lote->producto_id = $request->input('producto_id'.$i);
                    $lote->almacen_id = $almacen_id;
                    $lote->save();

                    $stock = Stock::where('producto_id', $request->input('producto_id'.$i))->where('almacen_id', $almacen_id)->first();
                    if (count($stock) == 0) {
                        $stock = new Stock();
                        $stock->producto_id = $request->input('producto_id'.$i);
                        $stock->almacen_id = $almacen_id;
                    }
                    $stock->cantidad += $cantidad;
                    $stock->save();

                    $detalleVenta->lote = $lote->id . ';' . $cantidad . '@';

                }elseif ($request->input('tipo') == '9') {
                    //Algoritmo para ver de qué lote reduzco xd
                    //Solo cuando el producto no tiene lote

                    $lotcant = '';

                    if($producto->lote == 'NO') {
                        $lotes = Lote::where('producto_id','=',$request->input('producto_id'.$i))->where('almacen_id', $almacen_id)->where('queda','>','0')->orderBy('fechavencimiento','ASC')->get();

                        foreach ($lotes as $key => $value) {
                            $aux = $cantidad;
                            if ($value->queda >= $aux) {
                                $queda = $value->queda-$aux;
                                $value->queda = $queda;
                                $value->save();
                                $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $request->input('producto_id'.$i))->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();
                                $stockanterior = 0;
                                $stockactual = 0;
                                // ingresamos nuevo kardex
                                if ($ultimokardex === NULL) {
                                    
                                    
                                }else{
                                    $stockanterior = $ultimokardex->stockactual;
                                    $stockactual = $ultimokardex->stockactual-$aux;
                                    $kardex = new Kardex();
                                    $kardex->tipo = 'S';
                                    $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                                    $kardex->stockanterior = $stockanterior;
                                    $kardex->stockactual = $stockactual;
                                    $kardex->cantidad = $aux;
                                    $kardex->precioventa = $precio;
                                    $kardex->almacen_id = $almacen_id;
                                    $kardex->detallemovimiento_id = $detalleVenta->id;
                                    $kardex->lote_id = $value->id;
                                    $kardex->save();    

                                    $lotcant .= $value->id . ';' . $aux . '@';

                                }
                                break;
                            }else{
                                $aux = $aux-$value->queda;
                                $aux2 = $value->queda;
                                $value->queda = 0;
                                $value->save();
                                
                                $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $request->input('producto_id'.$i))->where('movimiento.almacen_id', '=', $almacen_id )->orderBy('kardex.id', 'DESC')->first();
                                $stockanterior = 0;
                                $stockactual = 0;
                                // ingresamos nuevo kardex
                                if ($ultimokardex === NULL) {
                                    
                                    
                                }else{
                                    $stockanterior = $ultimokardex->stockactual;
                                    $stockactual = $ultimokardex->stockactual-$aux;
                                    $kardex = new Kardex();
                                    $kardex->tipo = 'S';
                                    $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                                    $kardex->stockanterior = $stockanterior;
                                    $kardex->stockactual = $stockactual;
                                    $kardex->cantidad = $aux;
                                    $kardex->precioventa = $precio;
                                    $kardex->almacen_id = $almacen_id;
                                    $kardex->detallemovimiento_id = $detalleVenta->id;
                                    $kardex->lote_id = $value->id;
                                    $kardex->save();    

                                    $lotcant .= $value->id . ';' . $aux2 . '@';

                                }
                            }
                        }


                    } else {
                        $cadenalotes = $request->input('datoLote'.$i);
                        $cadenalotes = explode(';', $cadenalotes);
                        $elementos = (int) (count($cadenalotes) - 1)/2;

                        for ($e=0; $e < $elementos; $e++) {
                            $idlote = $cadenalotes[$e + $elementos];

                            if($producto->fraccion == 1) {
                                $cantidadareducir = $cadenalotes[$e];
                            } else {
                                $cant = explode('F', $cadenalotes[$e]);
                                $cantidadareducir = $cant[0]*$producto->fraccion + $cant[1];
                            }                           

                            //Algoritmo para reducir kardex

                            $lote = Lote::find($idlote);
                            $queda = $lote->queda-$cantidadareducir;
                            $lote->queda = $queda;
                            $lote->save();

                            $lotcant .= $lote->id . ';' . $cantidadareducir . '@';

                            $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $request->input('producto_id'.$i))->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();
                            $stockanterior = 0;
                            $stockactual = 0;
                            // ingresamos nuevo kardex
                            if ($ultimokardex === NULL) {
                            }else{
                                $stockanterior = $ultimokardex->stockactual;
                                $stockactual = $ultimokardex->stockactual-$cantidadareducir;
                                $kardex = new Kardex();
                                $kardex->tipo = 'S';
                                $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                                $kardex->stockanterior = $stockanterior;
                                $kardex->stockactual = $stockactual;
                                $kardex->cantidad = $cantidadareducir;
                                $kardex->precioventa = $precio;
                                $kardex->almacen_id = $almacen_id;
                                $kardex->detallemovimiento_id = $detalleVenta->id;
                                $kardex->lote_id = $lote->id;
                                $kardex->save();  
                            }
                        }
                    }

                    $stock = Stock::where('producto_id', $request->input('producto_id'.$i))->where('almacen_id', $almacen_id)->first();
                    if (count($stock) == 0) {
                        $stock = new Stock();
                        $stock->producto_id = $request->input('producto_id'.$i);
                        $stock->almacen_id = $almacen_id;
                    }
                    $stock->cantidad -= $cantidad;
                    $stock->save();

                    $detalleVenta->lote = $lotcant;
                    $detalleVenta->save();
                }

                $stockanterior = 0;
                $stockactual = 0;

                if ($request->input('tipo') == '8') {
                    if ($ultimokardex === NULL) {
                        $stockactual = $cantidad;
                        $kardex = new Kardex();
                        $kardex->tipo = 'I';
                        $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                        $kardex->stockanterior = $stockanterior;
                        $kardex->stockactual = $stockactual;
                        $kardex->cantidad = $cantidad;
                        $kardex->preciocompra = $precio;
                        $kardex->almacen_id = $almacen_id;
                        $kardex->detallemovimiento_id = $detalleVenta->id;
                        $kardex->lote_id = $lote->id;
                        $kardex->save();
                        
                    }else{
                        $stockanterior = $ultimokardex->stockactual;
                        $stockactual = $ultimokardex->stockactual+$cantidad;
                        $kardex = new Kardex();
                        $kardex->tipo = 'I';
                        $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                        $kardex->stockanterior = $stockanterior;
                        $kardex->stockactual = $stockactual;
                        $kardex->cantidad = $cantidad;
                        $kardex->preciocompra = $precio;
                        $kardex->almacen_id = $almacen_id;
                        $kardex->detallemovimiento_id = $detalleVenta->id;
                        $kardex->lote_id = $lote->id;
                        $kardex->save();

                    }
                }elseif ($request->input('tipo') == '9') {
                    /*$stockanterior = $ultimokardex->stockactual;
                    $stockactual = $ultimokardex->stockactual-$cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'S';
                    $kardex->fecha = Date::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
                    $kardex->stockanterior = $stockanterior;
                    $kardex->stockactual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->precioventa = $precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detallemovimiento_id = $detalleVenta->id;
                    
                    //$kardex->lote_id = $lote->id;
                    $kardex->save(); */
                }
            }

            $dat[0]=array("respuesta"=>"OK","compra_id"=>$movimientoalmacen->id, "ind" => 0, "second_id" => 0);
        });
        return is_null($error) ? json_encode($dat) : $error;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $movimientoalmacen = Movimientoalmacen::find($id);
        $entidad             = 'Movimientoalmacen';
        $cboDocumento        = Tipodocumento::lists('nombre', 'id')->all();
        $cboTipo        = array("I" => 'Ingreso', 'S' => 'Salida');    
        $formData            = array('movimientoalmacen.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton               = 'Modificar';
        $detalles = Detallemovimiento::where('movimiento_id','=',$movimientoalmacen->id)->get();

        return view($this->folderview.'.mantView')->with(compact('movimientoalmacen', 'formData', 'entidad', 'boton', 'listar','cboDocumento','cboTipo','detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function eliminar($id,$listarLuego)
    {
        //
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movimiento::find($id);
        $entidad  = 'Movimientoalmacen';
        $formData = array('route' => array('movimientoalmacen.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        $movalmaven = 'S';
        return view('app.confirmar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function destroy($id)
    {
        $error = DB::transaction(function() use($id){

            $sucursal_id = Session::get('sucursal_id');
            $almacen_id = 1;
            if($sucursal_id ==  2) {
                $almacen_id = 3;
            }

            $movimiento = Movimiento::find($id);

            $tipodoc = $movimiento->tipodocumento_id;
            
            $detalles = Detallemovimiento::where('movimiento_id','=',$movimiento->id)->get();
            foreach ($detalles as $key => $value) {
                $consultakardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('movimiento.id', '=',$movimiento->id)->where('producto_id', '=', $value->producto_id)->select('kardex.*')->get();

                foreach ($consultakardex as $key2 => $value2) {
                    $lote = Lote::find($value2->lote_id);
                    if($tipodoc == 8) {
                        $lote->queda -= $value2->cantidad;
                    } else if($tipodoc == 9) {
                        $lote->queda += $value2->cantidad;
                    }
                    
                    $lote->save();
                    $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $value->producto_id)->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();

                    $stockanterior = 0;
                    $stockactual = 0;
                    // ingresamos nuevo kardex
                    if ($ultimokardex === NULL) {
                        
                        
                    }else{
                        $stockanterior = $ultimokardex->stockactual;

                        if($tipodoc == 8) {
                            $stockactual = $ultimokardex->stockactual-$value2->cantidad;
                        } else if($tipodoc == 9) {
                            $stockactual = $ultimokardex->stockactual+$value2->cantidad;
                        }                        
                        $kardex = new Kardex();
                        if($tipodoc == 8) {
                            $kardex->tipo = 'S';
                        } else if($tipodoc == 9) {
                            $kardex->tipo = 'I';
                        }                        
                        $kardex->fecha = date('Y-m-d');
                        $kardex->stockanterior = $stockanterior;
                        $kardex->stockactual = $stockactual;
                        $kardex->cantidad = $value2->cantidad;
                        $kardex->precioventa = $value2->precio;
                        $kardex->almacen_id = $almacen_id;
                        $kardex->detallemovimiento_id = $value->id;
                        $kardex->lote_id = $lote->id;
                        $kardex->save();    

                    }
                }

                //Repongo Stock
                $cant = $value->cantidad;
                $stocks = Stock::where('producto_id', $value->producto_id)->where('almacen_id', $almacen_id)->first();
                if(count($stocks) == 0){
                    $stocks = new Stock();
                    $stocks->producto_id = $value->producto_id;
                    $stocks->almacen_id = $value->almacen_id;
                }
                if($tipodoc == 8) {
                    $stocks->cantidad -= $cant;
                } else if($tipodoc == 9) {
                    $stocks->cantidad += $cant;
                }
                
                $stocks->save();
            }
            
            $movimiento->situacion = 'A';
            $movimiento->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function corregir($listarLuego)
    {
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = null;
        $entidad  = 'Movimientoalmacen';
        $formData = array('route' => array('movimientoalmacen.cuadrarstock'), 'method' => 'Acept', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Corregir';
        return view('app.confirmar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function cuadrarstock(){
        //almacen
        $resultado = Movimiento::where('tipomovimiento_id','=','5')
                            ->where('fecha','>=','2018-01-02')
                            ->get();        
        foreach($resultado as $key=>$value){
            $detalles = Detallemovimiento::where('movimiento_id','=',$value->id)->get();
            foreach($detalles as $k=>$v){
                $lote = new Lote();
                $lote->nombre  = '';
                $lote->fechavencimiento  = "2018-12-31";
                $lote->cantidad = $v->cantidad;
                $lote->queda = $v->cantidad;
                $lote->producto_id = $v->producto_id;
                $lote->almacen_id = $almacen_id;
                $lote->save();

                $precio = $v->precio;
                $cantidad = $v->cantidad;

                $stockanterior = 0;
                $stockactual = 0;

                $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $v->producto_id)->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();

                if ($ultimokardex === NULL) {
                    $stockactual = $cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'I';
                    $kardex->fecha = $value->fecha;
                    $kardex->stockanterior = $stockanterior;
                    $kardex->stockactual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->preciocompra = $precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detallemovimiento_id = $v->id;
                    $kardex->lote_id = $lote->id;
                    $kardex->save();
                }else{
                    $stockanterior = $ultimokardex->stockactual;
                    $stockactual = $ultimokardex->stockactual+$cantidad;
                    $kardex = new Kardex();
                    $kardex->tipo = 'I';
                    $kardex->fecha = $value->fecha;
                    $kardex->stockanterior = $stockanterior;
                    $kardex->stockactual = $stockactual;
                    $kardex->cantidad = $cantidad;
                    $kardex->preciocompra = $precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detallemovimiento_id = $v->id;
                    $kardex->lote_id = $lote->id;
                    $kardex->save();    
                }

            }
        }

        //compras
        $resultado1 = Movimiento::where('tipomovimiento_id','=','3')
                            ->where('fecha','>=','2018-01-03')
                            ->get();        
        if(count($resultado1)>0){
            foreach($resultado1 as $key=>$value){
                $detalles = Detallemovimiento::where('movimiento_id','=',$value->id)->get();
                foreach($detalles as $k=>$v){
                    $lote = new Lote();
                    $lote->nombre  = '';
                    $lote->fechavencimiento  = "2018-12-31";
                    $lote->cantidad = $v->cantidad;
                    $lote->queda = $v->cantidad;
                    $lote->producto_id = $v->producto_id;
                    $lote->almacen_id = $almacen_id;
                    $lote->save();

                    $precio = $v->precio;
                    $cantidad = $v->cantidad;

                    $stockanterior = 0;
                    $stockactual = 0;

                    $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $v->producto_id)->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();

                    if ($ultimokardex === NULL) {
                        $stockactual = $cantidad;
                        $kardex = new Kardex();
                        $kardex->tipo = 'I';
                        $kardex->fecha = $value->fecha;
                        $kardex->stockanterior = $stockanterior;
                        $kardex->stockactual = $stockactual;
                        $kardex->cantidad = $cantidad;
                        $kardex->preciocompra = $precio;
                        $kardex->almacen_id = $almacen_id;
                        $kardex->detallemovimiento_id = $v->id;
                        $kardex->lote_id = $lote->id;
                        $kardex->save();
                    }else{
                        $stockanterior = $ultimokardex->stockactual;
                        $stockactual = $ultimokardex->stockactual+$cantidad;
                        $kardex = new Kardex();
                        $kardex->tipo = 'I';
                        $kardex->fecha = $value->fecha;
                        $kardex->stockanterior = $stockanterior;
                        $kardex->stockactual = $stockactual;
                        $kardex->cantidad = $cantidad;
                        $kardex->preciocompra = $precio;
                        $kardex->almacen_id = $almacen_id;
                        $kardex->detallemovimiento_id = $v->id;
                        $kardex->lote_id = $lote->id;
                        $kardex->save();    
                    }

                }
            }      
        }
        //ventas
        $resultado2 = Movimiento::where('tipomovimiento_id','=','4')
                            ->where('fecha','>=','2018-01-02')
                            ->where('situacion','=','N')
                            ->where('created_at','>=','2018-01-02 20:20:00')
                            ->get();        
        foreach($resultado2 as $key2=>$value2){
            $detalles = Detallemovimiento::where('movimiento_id','=',$value2->id)->get();
            foreach($detalles as $k2=>$v2){
                $precio = $v2->precio;
                $cantidad = $v2->cantidad;

                $lotes = Lote::where('producto_id','=',$v2->producto_id)->where('queda','>','0')->orderBy('fechavencimiento','ASC')->get();
                foreach ($lotes as $key => $value) {
                    $aux = $cantidad;
                    if ($value->queda >= $aux) {
                        $queda = $value->queda-$aux;
                        $value->queda = $queda;
                        $value->save();
                        $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $v2->producto_id)->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();
                        $stockanterior = 0;
                        $stockactual = 0;
                        // ingresamos nuevo kardex
                        if ($ultimokardex === NULL) {
                            
                            
                        }else{
                            $stockanterior = $ultimokardex->stockactual;
                            $stockactual = $ultimokardex->stockactual-$aux;
                            $kardex = new Kardex();
                            $kardex->tipo = 'S';
                            $kardex->fecha = $value2->fecha;
                            $kardex->stockanterior = $stockanterior;
                            $kardex->stockactual = $stockactual;
                            $kardex->cantidad = $aux;
                            $kardex->precioventa = $precio;
                            $kardex->detallemovimiento_id = $v2->id;
                            $kardex->lote_id = $value->id;
                            $kardex->save();    

                        }
                        break;
                    }else{
                        $aux = $aux-$value->queda;
                        $value->queda = 0;
                        $value->save();
                        
                        $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $v2->producto_id)->where('movimiento.almacen_id', '=', $almacen_id)->orderBy('kardex.id', 'DESC')->first();
                        $stockanterior = 0;
                        $stockactual = 0;
                        // ingresamos nuevo kardex
                        if ($ultimokardex === NULL) {
                            
                            
                        }else{
                            $stockanterior = $ultimokardex->stockactual;
                            $stockactual = $ultimokardex->stockactual-$aux;
                            $kardex = new Kardex();
                            $kardex->tipo = 'S';
                            $kardex->fecha = $value2->fecha;
                            $kardex->stockanterior = $stockanterior;
                            $kardex->stockactual = $stockactual;
                            $kardex->cantidad = $aux;
                            $kardex->precioventa = $precio;
                            $kardex->almacen_id = $almacen_id;
                            $kardex->detallemovimiento_id = $v2->id;
                            $kardex->lote_id = $value->id;
                            $kardex->save();    

                        }
                    }
                }
                

            }
        }      


    }

    public function pdfComprobante($id){
        $entidad          = 'Movimiento';
        $dato              = Movimientoalmacen::find($id);
        
        $pdf = new TCPDF();
        $pdf::SetTitle('Comprobante');
        $pdf::AddPage('');
        $pdf::SetFont('helvetica','B',12);
        $pdf::Cell(0,7,$dato->tipodocumento->nombre.' '.$dato->numero,0,0,'C');        
        $pdf::Ln();
        $pdf::SetFont('helvetica','B',10);
        $pdf::Cell(15,7,"Fecha: ",0,0,'L');        
        $pdf::SetFont('helvetica','',10);
        $pdf::Cell(80,7,date("d/m/Y",strtotime($dato->fecha)),0,0,'L');        
        $pdf::SetFont('helvetica','B',10);
        $pdf::Cell(25,7,"Responsable: ",0,0,'L');        
        $pdf::SetFont('helvetica','',10);
        $pdf::Cell(40,7,$dato->responsable->nombres,0,0,'L');        
        $pdf::Ln();
        $pdf::SetFont('helvetica','B',10);
        $pdf::Cell(25,7,"Comentario: ",0,0,'L');        
        $pdf::SetFont('helvetica','',10);
        $pdf::Cell(80,7,$dato->comentario,0,0,'L');        
        $pdf::Ln();
        $pdf::SetFont('helvetica','B',9);
        $pdf::Cell(10,6,"Nro.",1,0,'C');
        $pdf::Cell(20,6,"Cant.",1,0,'C');
        $pdf::Cell(90,6,"Producto",1,0,'C');
        $pdf::Cell(25,6,"Precio",1,0,'C');
        $pdf::Cell(25,6,"Subtotal",1,0,'C');
        $pdf::Ln();
        $detalles = Detallemovimiento::where('movimiento_id','=',$dato->id)->get();
        $c=0;
        foreach($detalles as $key => $value){$c=$c+1;
            $pdf::SetFont('helvetica','',9);
            $pdf::Cell(10,6,$c,1,0,'R');
            $pdf::Cell(20,6,$value->cantidad,1,0,'C');
            $pdf::Cell(90,6,$value->producto->nombre,1,0,'L');
            $pdf::Cell(25,6,$value->precio,1,0,'C');
            $pdf::Cell(25,6,$value->subtotal,1,0,'C');
            $pdf::Ln();
        }
        $pdf::Output('DocAlmacen.pdf');
    }

    public function consultarlotes($producto_id) {
        $sucursal_id = Session::get('sucursal_id');
        $user = Auth::user();        
        if($sucursal_id == 2) {
            if($user->usertype_id == 11) {
                $almacen_id = 3;
            } else {
                $almacen_id = 4;
            }            
        } else {
            if($user->usertype_id == 11) {
                $almacen_id = 1;
            } else {
                $almacen_id = 2;
            }
        }
        $producto = Producto::find($producto_id);
        $lotes = Lote::select('lote.*', 'producto.fraccion', 'lote.id as loteid', 'producto.id as productoid')
                ->where('producto_id','=',$producto_id)
                ->join('producto', 'producto.id', '=', 'lote.producto_id')
                ->where('almacen_id','=',$almacen_id)
                ->where('queda','>','0')
                ->orderBy('fechavencimiento','ASC')
                ->get();
        return view($this->folderview.'.lotes')->with(compact('producto', 'lotes'));
    }

    public function addcarritolote($cantidad, $stocklote, $producto_id, $fraccion) {
        $cantidades = explode("F", $cantidad);
        if($fraccion != 1 && count($cantidades) == 2) {
            if(!is_numeric($cantidades[0]) || !is_numeric($cantidades[1])) {
                return '0-1';
            }
            $cantidadpresentacion1 = (float) $cantidades[0];
            $cantidadpresentacion2 = (float) $cantidades[1];
            $cantidadunidades = ($fraccion*$cantidadpresentacion1)+$cantidadpresentacion2;
        } else if($fraccion == 1 && count($cantidades) == 1) {            
            if(!is_numeric($cantidades[0])) {
                return '0-1';
            }
            $cantidadunidades = $fraccion * $cantidades[0]; 
        } else {
            return '0-0';
        }

        if($stocklote < $cantidadunidades || $stocklote == 0) {
            return '0-2';
        }

        return $cantidad;
    }

    /*public function anularmovimiento($id) {

        $obj = Movimientoalmacen::find($id);

        //Anular Venta en Caja de Farmacia

        $sucursal_id = Session::get('sucursal_id');
        $almacen_id = 1;
        if($sucursal_id ==  2) {
            $almacen_id = 3;
        }

        $tipodoc = null;

        $movimientopago = Movimiento::find($obj->movimiento_id);
        if ($movimientopago !== NULL) {
            $movimientopago->situacion = 'A';
            $movimientopago->save();

            $tipodoc = $movimientopago->tipodocumento_id;
        } else {
            return 0;
        }

        $detalles = Detallemovimiento::where('movimiento_id','=',$obj->id)->get();
        foreach ($detalles as $key => $value) {
            $consultakardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('movimiento.id', '=',$obj->id)->where('producto_id', '=', $value->producto_id)->select('kardex.*')->get();

            foreach ($consultakardex as $key2 => $value2) {
                $lote = Lote::find($value2->lote_id);
                if($tipodoc == '8') {
                    $lote->queda -= $value2->cantidad;
                } else if($tipodoc == '9') {
                    $lote->queda += $value2->cantidad;
                }
                
                $lote->save();
                $ultimokardex = Kardex::join('detallemovimiento', 'kardex.detallemovimiento_id', '=', 'detallemovimiento.id')->join('movimiento', 'detallemovimiento.movimiento_id', '=', 'movimiento.id')->where('producto_id', '=', $value->producto_id)->where('movimiento.almacen_id', '=',$almacen_id)->orderBy('kardex.id', 'DESC')->first();

                $stockanterior = 0;
                $stockactual = 0;
                // ingresamos nuevo kardex
                if ($ultimokardex === NULL) {
                    
                    
                }else{
                    $stockanterior = $ultimokardex->stockactual;
                    if($tipodoc == '8') {
                        $stockactual = $ultimokardex->stockactual-$value2->cantidad;
                    } else if($tipodoc == '9') {
                        $stockactual = $ultimokardex->stockactual+$value2->cantidad;
                    }
                    
                    $kardex = new Kardex();
                    $kardex->tipo = 'I';
                    $kardex->fecha = date('Y-m-d');
                    $kardex->stockanterior = $stockanterior;
                    $kardex->stockactual = $stockactual;
                    $kardex->cantidad = $value2->cantidad;
                    $kardex->precioventa = $value2->precio;
                    $kardex->almacen_id = $almacen_id;
                    $kardex->detallemovimiento_id = $value->id;
                    $kardex->lote_id = $lote->id;
                    $kardex->save();    

                }
            }

            //Repongo Stock
            $cant = $value->cantidad;
            $stocks = Stock::where('producto_id', $value->producto_id)->where('almacen_id', $almacen_id)->first();
            if($tipodoc == '8') {
                $stocks->cantidad -= $cant;
            } else if($tipodoc == '9') {
                $stocks->cantidad += $cant;
            }
            
            $stocks->save();
        }

        $obj->situacion='U';
        $obj->save();
        
        $movimientopago = Movimiento::find($obj->movimiento_id);
        if ($movimientopago !== NULL) {
            $movimientopago->situacion = 'A';
            $movimientopago->save();
        }
        ////////////////////////////
    }*/
}
