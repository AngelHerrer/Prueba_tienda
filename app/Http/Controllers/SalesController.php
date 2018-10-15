<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\T_ventas;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller {

    public function CreateSales(Request $request) {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $paramsArray = json_decode($json, true);

        $validate = \Validator::make($paramsArray, [
                    'id_t_cliente' => 'required|numeric',
                    'id_t_prenda' => 'required|numeric',
                    'credito_interno_usado' => 'required|numeric',
                    'total_venta' => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $id_t_cliente = (!is_null($json) && isset($params->id_t_cliente)) ? $params->id_t_cliente : null;
        $id_t_prenda = (!is_null($json) && isset($params->id_t_prenda)) ? $params->id_t_prenda : null;
        $credito_interno_usado = (!is_null($json) && isset($params->credito_interno_usado)) ? $params->credito_interno_usado : null;
        $total_venta = (!is_null($json) && isset($params->total_venta)) ? $params->total_venta : null;
        $fecha_registro = date('Y-m-d');

        $t_ventas = new T_ventas();
        try {
            $lastValue = DB::table('t_ventas')->orderBy('id_t_venta', 'desc')->first();
        } catch (Exception $ex) {
            var_dump($ex);
        }
        
        
        if(!$lastValue){
            $lastValue=0;
        }
        $t_ventas->id_t_venta = 1;
//        $t_ventas->id_t_venta = ($lastValue->id_t_venta) + 1;
        $t_ventas->id_t_cliente = $id_t_cliente;
        $t_ventas->id_t_penda = $id_t_prenda;
        $t_ventas->credito_interno_usado = $credito_interno_usado;
        $t_ventas->total_venta = $total_venta;
        $t_ventas->fecha_registro = $fecha_registro;
        var_dump($t_ventas);
        die();
        $t_ventas->save();

        if ($t_ventas) {
            $data = array(
                'success' => 'ok',
                'msg' => 'Venta registrada'
            );
        }

        return response()->json($data, 200);
    }

    public function GetQualification(Request $request, $id_t_usuarios) {

        $promedio = 0;

        $califiaciones = DB::table('t_calificaciones as c')
                ->where('c.id_t_usuarios', $id_t_usuarios)
                ->join('t_alumnos as a', 'c.id_t_usuarios', '=', 'a.id_t_usuarios')
                ->join('t_materias as m', 'c.id_t_materias', '=', 'm.id_t_materias')
                ->select('c.id_t_usuarios', 'a.nombre', 'a.ap_paterno', 'a.ap_materno', 'm.nombre as materia', 'c.calificacion', 'c.fecha_registro')
                ->get();
        foreach ($califiaciones as $califiacion) {

            $promedio += (int) $califiacion->calificacion;

            $alumno[] = array('id_t_usuarios' => $califiacion->id_t_usuarios,
                'nombre' => $califiacion->nombre,
                'ap_paterno' => $califiacion->ap_paterno,
                'ap_materno' => $califiacion->ap_materno,
                'materia' => $califiacion->materia,
                'calificacion' => $califiacion->calificacion,
                'fecha_registro' => date('d-m-Y', strtotime($califiacion->fecha_registro)),
            );
        }
        $finalPromedio = array('promedio' => $promedio / count($califiaciones));

        array_push($alumno, $finalPromedio);

        return response()->json($alumno, 200);
    }

    public function UpdateQualification($usuario, $materia, $calificacion) {

        $paramsArray = array(
            'usuario' => (int) $usuario,
            'materia' => (int) $materia,
            'calificacion' => (int) $calificacion
        );

        $validate = \Validator::make($paramsArray, [
                    'usuario' => 'required|numeric',
                    'materia' => 'required|numeric',
                    'calificacion' => 'required|numeric|max:10|min:0'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $updateQualification = DB::table('t_calificaciones')
                ->where('id_t_materias', $usuario)
                ->where('id_t_usuarios', $materia)
                ->update(['calificacion' => $calificacion]);

        if ($updateQualification) {
            $data = array(
                'success' => 'ok',
                'msg' => 'calificacion actualizada'
            );
        } else {
            $data = array(
                'success' => 'false',
                'msg' => 'no se pudo actualizar verifique que el alumno o materia sean las correctas'
            );
        }
        return response()->json($data, 200);
    }

    public function DeleteQualification($usuario, $materia) {

        $deleteQualification = DB::table('t_calificaciones')
                ->where('id_t_materias', $usuario)
                ->where('id_t_usuarios', $materia)
                ->delete();

        if ($deleteQualification) {
            $data = array(
                'success' => 'ok',
                'msg' => 'calificacion eliminada'
            );
        } else {
            $data = array(
                'success' => 'false',
                'msg' => 'verifique que el id sea el correcto'
            );
        }

        return response()->json($data, 200);
    }

}
