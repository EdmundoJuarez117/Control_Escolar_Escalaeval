<?php

namespace App\Http\Controllers;
use App\Models\C_escalaeval;
use Illuminate\Http\Request;

class C_escalaevalController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware(['permission:admin.escalaeval.index'],['only'=>'index']);
        $this->middleware(['permission:admin.escalaeval.create'],['only'=>'create']);
        $this->middleware(['permission:admin.escalaeval.edit'],['only'=>['create','store']]);
        $this->middleware(['permission:admin.escalaeval.destroy'],['only'=>'destroy']);
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
        $this->seccionTitulo = 'Escalaevals';
        $resultSet = C_escalaeval::getForPagination($offset, $limit=10);
        $escalaevals = $resultSet['data'];
        $totalData = $resultSet['countData'];

        if($request->ajax())
        {
          return [
            'escalaevals' => $escalaevals,
            'totalData' => $totalData
          ];
        }
        $data = [
          'seccion_titulo' => $this->seccionTitulo,
          'escalaevals' => $escalaevals,
          'totalData' => $totalData
        ];

        return view('c_escalaeval.index',$data);
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
        
        return view('c_carrera.create');
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
            'nombre' => 'required|unique:c_escalaevals,nombre',
            'calificacion_min' => 'required|unique:c_escalaevals,calificacion_min',
            'calificacion_max' => 'required|unique:c_escalaevals,calificacion_max',
            'fcreacion' => 'required|unique:c_escalaevals,fcreacion',
            'fmodificacion' => 'required|unique:c_escalaevals,fmodificacion',

          ]);
    
          try {
            $escalaeval = new C_escalaeval;
            $escalaeval->nombre = $request->all()['nombre'];
            $escalaeval->calificacion_min = $request->all()['calificacion_min'];
            $escalaeval->calificacion_max = $request->all()['calificacion_max'];
            $escalaeval->fcreacion = $request->all()['fcreacion'];
            $escalaeval->fmodificacion = $request->all()['fmodificacion'];
            $result = $escalaeval->save();
    
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
        $c_escalaeval = C_escalaeval::find($id);
        return view('c_escalaeval.edit')->with('c_escalaeval',$c_escalaeval);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id ,C_escalaeval $escalaeva)
    {
        //
        $result = TRUE;
        $message = "";
  
        try{
          $id = $request->all()['id'];
          $this->validate($request,[
            'nombre'=>'required|unique:c_escalaevals,nombre'.$id,
            'calificacion_min'=>'required|unique:c_escalaevals,calificacion_min'.$calificacion_min,
            'calificacion_max'=>'required|unique:c_escalaevals,calificacion_max'.$calificacion_max,
            'fcreacion'=>'required|unique:c_escalaevals,fcreacion'.$fcreacion,
            'fmodificacion'=>'required|unique:c_escalaevals,fmodificacion'.$fmodificacion,            
          ]);
          $result = $escalaeva -> update($request->all());
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
        $c_escalaeval = C_escalaeval::find($id);

        $c_escalaeval->delete();
  
        return redirect('/escalaeval');
    }
}
