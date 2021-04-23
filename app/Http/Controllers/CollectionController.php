<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;

class CollectionController extends Controller
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

  public function showCollection(Request $request)
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
    $collection = Collection::orderBy('id', 'asc')->skip($skip_product_in_page)->take($show_product)->get();
    $collectionNum = count($collection);
    $resultJson = array(
      'currentPage' => $current_page,
      'genreNumber' => count($collection),
      'genreMaxNumber' => $show_product,
      'totalPage' => ceil($collectionNum / $show_product),
      'result' => $collection
    );
    return $this->createJsonResult($resultJson);
  }
  public function deleteCollection(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "CollectionId" => 'required|numeric',
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
      $Collection = Collection::find($request['CollectionId']);
      $Collection->delete();
      return "successful";
    }
  }
  public function createCollection(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "CollectionName" => 'required|string',
        "Description" => 'sometimes|string|max:255|nullable',
        "Priority" => 'sometimes|numeric|nullable',
        "ShowHide" => 'required|numeric',
        "DateCreate" => 'required|date',
        "Slug" => 'required|string',
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $Collection = new Collection();
    $Collection->CollectionName = $request['CollectionName'];
    $Collection->Description = $request['Description'];
    $Collection->ShowHide = $request['ShowHide'];
    $Collection->Priority = $request['Priority'];
    $Collection->Slug = $request['Slug'];
    $Collection->created_at = $request['DateCreate'];
    $Collection->updated_at = $request['DateCreate'];
    $Collection->save();
    return $this->createJsonResult($request->all());
  }
  public function editCollection(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "id" => 'required|numeric',
        "CollectionName" => 'required|string',
        "Description" => 'sometimes|string|max:255|nullable',
        "Priority" => 'sometimes|numeric|nullable',
        "ShowHide" => 'required|numeric',
        "created_at" => 'required|date',
        "updated_at" => 'required|date',
        "Slug" => 'required|string',
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $Collection = Collection::find($request['id']);
    $Collection->id = $request['id'];
    $Collection->CollectionName = $request['CollectionName'];
    $Collection->Description = $request['Description'];
    $Collection->ShowHide = $request['ShowHide'];
    $Collection->Priority = $request['Priority'];
    $Collection->Slug = $request['Slug'];
    $Collection->created_at = $request['created_at'];
    $Collection->updated_at = $request['updated_at'];
    $Collection->update();
    return $this->createJsonResult($request->all());
  }
}
