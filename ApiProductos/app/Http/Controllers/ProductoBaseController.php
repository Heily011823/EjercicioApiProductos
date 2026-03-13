<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoBaseController extends BaseController
{

    public function index(){

        $productos = Producto::all();

        return response()->json([
            'message'=> 'Listado de todos los productos',
            'data'=> $productos
        ]);
    }


    public function store(Request $request){

        $validated = $request->validate(
        [
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string'
        ],
        [
            'nombre.required' => 'El nombre del producto es obligatorio',
            'nombre.string' => 'El nombre debe ser texto',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',

            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un número',
            'precio.min' => 'El precio no puede ser negativo',

            'stock.required' => 'El stock es obligatorio',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock no puede ser negativo',

            'descripcion.string' => 'La descripción debe ser texto'
        ]);

        $producto = Producto::create($validated);

        return response()->json([
            'message' => 'Producto creado correctamente',
            'data'=> $producto,
        ], 201);
    }


    public function show(string $id){

        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([
                'message' => "No se encontro el producto solicitado con id ($id)"
            ], 404);
        }

        return response()->json([
            'message' => "Producto encontrado con id ($id)",
            'data'=> $producto
        ]);
    }


    public function update(Request $request, string $id){

        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([
                'message' => "No se encontro el producto solicitado con id ($id)"
            ], 404);
        }

        $validated = $request->validate(
        [
            'nombre' => 'sometimes|string|max:100',
            'precio' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'descripcion' => 'sometimes|nullable|string'
        ],
        [
            'nombre.string' => 'El nombre debe ser texto',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',

            'precio.numeric' => 'El precio debe ser un número',
            'precio.min' => 'El precio no puede ser negativo',

            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock no puede ser negativo',

            'descripcion.string' => 'La descripción debe ser texto'
        ]);

        $producto->update($validated);

        return response()->json([
            'message' => "Producto con id ($id) actualizado correctamente",
            'data'=> $producto
        ]);
    }


    public function destroy(string $id){

        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([
                'message' => "No se encontro el producto solicitado con id ($id)"
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'message' => "Producto con id ($id) ha sido eliminado"
        ]);
    }

}