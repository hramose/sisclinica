<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Movimiento extends Model
{
	 use SoftDeletes;
    protected $table = 'movimiento';
    protected $dates = ['deleted_at'];
    
    public function responsable()
    {
        return $this->belongsTo('App\Person', 'responsable_id');
    }

    public function tipomovimiento()
    {
        return $this->belongsTo('App\Tipomovimiento', 'tipomovimiento_id');
    }
    
    public function persona()
    {
        return $this->belongsTo('App\Person', 'persona_id');
    }

    public function caja()
    {
        return $this->belongsTo('App\Caja', 'caja_id');
    }
    
    public function conceptopago()
    {
        return $this->belongsTo('App\Conceptopago', 'conceptopago_id');
    }
    
    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento', 'movimiento_id');
    }
    
    public function tipodocumento()
    {
        return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'plan_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Person', 'doctor_id');
    }

    public function conveniofarmacia()
    {
        return $this->belongsTo('App\Conveniofarmacia', 'conveniofarmacia_id');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Person', 'empresa_id');
    }

    public function area()
    {
        return $this->belongsTo('App\Area', 'area_id');
    }
    
    public function scopeNumeroSigue($query,$caja_id,$sucursal_id,$tipomovimiento_id,$tipodocumento_id=0,$serie=0,$manual='S'){
        if($tipodocumento_id==0){
            if($serie==0){
                $rs=$query->where('caja_id','=',$caja_id)->where('sucursal_id','=',$sucursal_id)->where('tipomovimiento_id','=',$tipomovimiento_id)->where('manual','like',$manual)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();
            }else{
                $rs=$query->where('caja_id','=',$caja_id)->where('sucursal_id','=',$sucursal_id)->where('tipomovimiento_id','=',$tipomovimiento_id)->where('manual','like',$manual)->where('serie','=',$serie)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();   
            }
        }else{
            if($serie==0){
                $rs=$query->where('caja_id','=',$caja_id)->where('sucursal_id','=',$sucursal_id)->where('tipomovimiento_id','=',$tipomovimiento_id)->where('manual','like',$manual)->where('tipodocumento_id','=',$tipodocumento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();
            }else{
                $rs=$query->where('caja_id','=',$caja_id)->where('sucursal_id','=',$sucursal_id)->where('tipomovimiento_id','=',$tipomovimiento_id)->where('manual','like',$manual)->where('tipodocumento_id','=',$tipodocumento_id)->where('serie','=',$serie)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();
            }
        }
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigue2($query, $caja_id, $sucursal_id, $tipomovimiento_id,$tipodocumento_id=0,$serie=0){    
        
        $rs=$query->where('tipomovimiento_id','=',$tipomovimiento_id)
            ->where('tipodocumento_id','=',$tipodocumento_id)
            ->where('caja_id','=',$caja_id)
            ->where('sucursal_id','=',$sucursal_id)
            ->where('serie','=',$serie)
            ->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))
            ->first();
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigueTesoreria($query,$tipomovimiento_id,$tipodocumento_id=0,$caja_id=0){
        if($tipodocumento_id==0){
            if($tipodocumento_id==0){
                if($caja_id==0){
                    $rs=$query->where('tipomovimiento_id','=',$tipomovimiento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();
                }else{
                    $rs=$query->where('tipomovimiento_id','=',$tipomovimiento_id)->where('caja_id','=',$caja_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();
                }
            }else{
                if($caja_id==0){
                    $rs=$query->where('tipodocumento_id','=',$tipodocumento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();   
                }else{
                    $rs=$query->where('tipodocumento_id','=',$tipodocumento_id)->where('caja_id','=',$caja_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();   
                }
            }
        }else{
            if($caja_id==0){
                $rs=$query->where('tipodocumento_id','=',$tipodocumento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();   
            }else{
                $rs=$query->where('tipodocumento_id','=',$tipodocumento_id)->where('caja_id','=',$caja_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))->first();   
            }
        }
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigueDocMovAlmacen($query,$sucursal_id,$tipomovimiento_id,$tipodocumento_id, $almacen_id){
        
        $rs=$query->where('sucursal_id', $sucursal_id)
                    ->where('tipomovimiento_id', $tipomovimiento_id)
                    ->where('tipodocumento_id', $tipodocumento_id)
                    ->where('almacen_id', $almacen_id)
                    ->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))
                    ->first();
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigueSerieCuota($query, $rescuotas_id){
        
        $rs=$query->where('movimiento_id', $rescuotas_id)
                    ->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))
                    ->first();
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigueCuota($query, $caja_id, $sucursal_id, $situacion, $tipomovimiento_id){
        
        $rs=$query->where('caja_id', $caja_id)
                    ->where('situacion','like',$situacion)
                    ->orWhere('situacion','like','A')
                    ->where('tipomovimiento_id', $tipomovimiento_id)
                    ->where('sucursal_id', $sucursal_id)
                    ->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))
                    ->first();
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

    public function scopeNumeroSigueResumenCuotas($query, $caja_id, $sucursal_id, $situacion, $comentario) {
        $rs=$query->where('caja_id','=',$caja_id)
                    ->where('sucursal_id','=',$sucursal_id)
                    ->where('situacion','=',$situacion)
                    ->where('comentario','=',$comentario)
                    ->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE numero END)*1) AS maximo"))
                    ->first();
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }

}
