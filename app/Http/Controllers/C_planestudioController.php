<?php

namespace App\Http\Controllers;
use App\Models\C_planestudio;
use Illuminate\Http\Request;

class C_planestudioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware(['permission:admin.planestudio.index'],['only'=>'index']);
        $this->middleware(['permission:admin.planestudio.create'],['only'=>'create']);
        $this->middleware(['permission:admin.planestudio.edit'],['only'=>['create','store']]);
        $this->middleware(['permission:admin.planestudio.destroy'],['only'=>'destroy']);
    }

    function cmp($a, $b){
    return strcmp($a->name, $b->name);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $offset=0)
    {
        //
        $this->seccionTitulo = 'Planestudios';
        $resultSet = C_planestudio::getForPagination($offset, $limit=10);
        $planestudios = $resultSet['data'];
        $totalData = $resultSet['countData'];

        if($request->ajax())
        {
          return [
            'planestudios' => $planestudios,
            'totalData' => $totalData
          ];
        }
        $data = [
          'seccion_titulo' => $this->seccionTitulo,
          'planestudios' => $planestudios,
          'totalData' => $totalData
        ];

        return view('c_planestudio.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo "create function";
        
        return view('c_planestudio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this -> validate($request, [

            'nombre' => 'required|unique:c_planestudios,nombre',
            'nombre_corto' => 'required|unique:c_planestudios,nombre_corto',
            'estatus' => 'required|unique:c_planestudios,estatus',
            'num_creditos_total' => 'required|unique:c_planestudios,num_creditos_total',
            'num_creditos_min' => 'required|unique:c_planestudios,num_creditos_min',
            'num_creditos_max' => 'required|unique:c_planestudios,num_creditos_max',
            'fcreacion' => 'required|unique:c_planestudios,fcreacion',
            'fmodificacion' => 'required|unique:c_planestudios,fmodificacion',
            'idcarrera' => 'required|unique:c_planestudios,idcarrera',

          ]);
    
          try {
            $planestudio = new C_planestudio;
            $planestudio->nombre = $request->all()['nombre'];
            $planestudio->nombre_corto = $request->all()['nombre_corto'];
            $planestudio->estatus = $request->all()['estatus'];
            $planestudio->num_creditos_total = $request->all()['num_creditos_total'];
            $planestudio->num_creditos_min = $request->all()['num_creditos_min'];
            $planestudio->num_creditos_max = $request->all()['num_creditos_max'];
            $planestudio->fcreacion = $request->all()['fcreacion'];
            $planestudio->fmodificacion = $request->all()['fmodificacion'];
            $planestudio->idcarrera = $request->all()['idcarrera'];
            $result = $planestudio->save();
    
            return response()->json([
              'result'=>$result,
              'message'=>'',
            ]);
          }catch(\Illuminate\Database\QueryException $exception){
            $errorCode = $exception->erroInfo[1];
            if($errorCode == 1062){
              //We have a duplicate entry problem
            }
            return response()->json([
              'result'=>FALSE,
              'message'=>$exception->getMessage(),
            ]);
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $c_planestudio = C_planestudio::find($id);
        return view('c_planestudio.edit')->with('c_planestudio',$c_planestudio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id ,C_planestudio $planestud)
    {
        //
        $result = TRUE;
        $message = "";
  
        try{
          $id = $request->all()['id'];
          $this->validate($request,[
            'nombre'=>'required|unique:c_planestudios,nombre'.$id,
            'nombre_corto'=>'required|unique:c_planestudios,nombre_corto'.$nombre_corto,
            'estatus'=>'required|unique:c_planestudios,estatus'.$estatus,
            'num_creditos_total'=>'required|unique:c_planestudios,num_creditos_total'.$num_creditos_total,
            'num_creditos_min'=>'required|unique:c_planestudios,num_creditos_min'.$num_creditos_min,
            'num_creditos_max'=>'required|unique:c_planestudios,num_creditos_max'.$num_creditos_max,
            'fcreacion'=>'required|unique:c_planestudios,fcreacion'.$fcreacion,
            'fmodificacion'=>'required|unique:c_planestudios,fmodificacion'.$fmodificacion,
            'idcarrera'=>'required|unique:c_planestudios,idcarrera'.$idcarrera,
          ]);
          $result = $planestud -> update($request->all());
          $message = ($result)?"":"Reintente por favor";
  
        }catch (\Illuminate\Database\QueryException $exception){
          $result = FALSE;
          $message = $exception->getMessage();
        }
        return response()->json([
          'result'=>$result,
          "message" => $message
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $c_planestudio = C_planestudio::find($id);

        $c_planestudio->delete();
  
        return redirect('/planestudio');
    }
}
