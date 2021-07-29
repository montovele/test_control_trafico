<?php

namespace App\Http\Controllers;

use App\Models\Aerolinea;
use Illuminate\Http\Request;
use DataTables;

class AirController extends Controller
{
    /**
     * Muestra la lista de datos en index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $books = Aerolinea::latest()->get();

        if ($request->ajax()) {
            $data = Aerolinea::latest()->get();
            return Datatables::of($data)//Retorna el valor de la classe y lo inserta en la tabla mediante ajax
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    //Aqui rellenamos la tabla con sus respectivos botones EDIT Y DELETE
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit  editAirLine">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('aerolineas', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Aerolinea::updateOrCreate(
            ['id' => $request->AirLine_id],
            ['tipo' => $request->tipo, 'tamanio' => $request->tamanio]
        );

        return response()->json(['success' => 'Guardado correctamente.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AEROLINEAS  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Aerolinea::find($id);
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AEROLINEAS  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Aerolinea::find($id)->delete();

        return response()->json(['success' => 'Se actualizo correctamente.']);
    }
}
