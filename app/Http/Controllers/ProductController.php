<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwtauth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json([
            'data' => Product::create($request->only('name', 'price'))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return response()->json([
            'data' => Product::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $i  = Product::find($id);
        return response()->json([
            'data' => $i->update($request->only('name', 'price'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $i  = Product::findOrFail($id);

            $i->delete();
            return response()->json(null, 204);
        }
        catch (AuthenticationException $exception) {
            return response()->json($this->toArray('failed', 'not authorized.'),410);
        }
        catch (ModelNotFoundException $exception) {
            return response()->json($this->toArray('failed', 'This is product isn\'t existed.'),404);
        }


    }

    protected function toArray($status, $message, $data = null) {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
