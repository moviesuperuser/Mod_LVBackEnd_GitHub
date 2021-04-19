<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;

class GenreController extends Controller
{
  // private function reformatRequest($request)
  // {
  //   return [
  //     'page' => $this->array_get($request, 'page', null),
  //     'movienumber' => $this->array_get($request, 'movienumber', null),

  //   ];
  // }
  private function createJsonResult($response)
  {
    $result = response()->json($response, 200);
    return $result
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  }

  private function StringToArray($string)
  {
    $result = explode(",", $string);
    return json_decode(json_encode($result), FALSE);
  }
  public function deleteGenre(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "GenreId" => 'required|numeric',
        "confirm" => 'required|boolean'
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    if ($request['confirm']) {
      $genre = Genre::find($request['GenreId']);
      $genre->delete();
      return "successful";
    }
  }
  public function showGenre(Request $request)
  {
    // $this->request = $this->reformatRequest(Request::capture()->all());
    if ($request['page']) {
      $current_page = $request['page'];
      // dd($current_page);
    } else {
      $current_page = 1;
    }
    if ($request['genrenumber']) {
      $show_product = $request['genrenumber'];
      // dd($show_product);
    } else {
      $show_product = 7;
    }
    $skip_product_in_page = ($current_page - 1) * $show_product;
    $genre = Genre::orderBy('id', 'asc')->skip($skip_product_in_page)->take($show_product)->get();
    $genreNum = Genre::count();
    $resultJson = array(
      'currentPage' => $current_page,
      'genreNumber' => count($genre),
      'genreMaxNumber' => $show_product,
      'totalPage' => ceil($genreNum / $show_product),
      'result' => $genre
    );
    return $this->createJsonResult($resultJson);
  }

  public function createGenre(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "GenreName" => 'required|string',
        "GenreDescription" => 'sometimes|string|max:255|nullable',
        "Slug" => 'required|string',
        "ShowHide" => 'required|numeric|nullable',
        "DateCreate" => 'required|date',
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $genre = new Genre();
    $genre->GenreName = $request['GenreName'];
    $genre->GenreDescription = $request['GenreDescription'];
    $genre->Slug = $request['Slug'];
    $genre->ShowHide = $request['ShowHide'];
    $genre->created_at = $request['DateCreate'];
    $genre->updated_at = $request['DateCreate'];
    $genre->save();
    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
  }
  public function editGenre(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "id" => 'required|numeric',
        "GenreName" => 'required|string',
        "GenreDescription" => 'sometimes|string|max:255|nullable',
        "Slug" => 'required|string',
        "ShowHide" => 'required|numeric',
        "created_at" => 'required|date',
        "updated_at" => 'required|date',
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $genre = Genre::find($request['id']);
    $genre->id = $request['id'];
    $genre->GenreName = $request['GenreName'];
    $genre->GenreDescription = $request['GenreDescription'];
    $genre->Slug = $request['Slug'];
    $genre->created_at = $request['created_at'];
    $genre->updated_at = $request['updated_at'];
    $genre->update();
    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
  }
}
