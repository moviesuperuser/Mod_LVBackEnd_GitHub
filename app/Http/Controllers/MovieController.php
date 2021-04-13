<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
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
  public function editMovie(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "id" => 'required|numeric',
        "Title" => 'required|string',
        "Episodes" => 'required|numeric',
        "IMDB" => 'required|string',
        "Description" => 'required|string',
        "urlCover" => 'required|string',
        "ViewCount" => 'required|numeric',
        "Quality" => 'required|string',
        "Length" => 'required|string',
        "Slug" => 'required|string',
        "Year" => 'required|numeric',
        "ShowHide" => 'required|numeric',
        "VideoLink" => 'required|string',
        "Actors" => 'required|string',
        "Director" => 'required|string',
        "created_at" => 'required|string',
        "updated_at" => 'required|string',
        "GenreName" => 'required|string',
        "Rating" => 'required|string'
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $updateComment = DB::table('Movies')
      ->where('id', $request['id'])
      ->update([
        "Title" => $request['Title'],
        "Episodes" => $request['Episodes'],
        "IMDB" => $request['IMDB'],
        "Description" => $request['Description'],
        "urlCover" => $request['urlCover'],
        "ViewCount" => $request['ViewCount'],
        "Quality" => $request['Quality'],
        "Length" => $request['Length'],
        "Slug" => $request['Slug'],
        "Year" => $request['Year'],
        "ShowHide" => $request['ShowHide'],
        "VideoLink" => $request['VideoLink'],
        "Actors" => $request['Actors'],
        "Director" => $request['Director'],
        "created_at" => $request['created_at'],
        "updated_at" => $request['updated_at'],
        "GenreName" => $request['GenreName'],
        "Rating" => $request['Rating']
      ]);
    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
  }
  public function ShowMovieDetail($slug)
  {
    $movie = Movie::where('Slug', $slug)->first();
    return $this->createJsonResult($movie);
  }
  public function showMovie(Request $request)
  {
    // $this->request = $this->reformatRequest(Request::capture()->all());
    if ($request['page']) {
      $current_page = $request['page'];
      // dd($current_page);
    } else {
      $current_page = 1;
    }
    if ($request['movienumber']) {
      $show_product = $request['movienumber'];
      // dd($show_product);
    } else {
      $show_product = 20;
    }
    $skip_product_in_page = ($current_page - 1) * $show_product;
    $movie = Movie::orderBy('created_at', 'desc')->skip($skip_product_in_page)->take($show_product)->get();
    $movieNum = Movie::count();
    $resultJson = array(
      'currentPage' => $current_page,
      'movieNumber' => $show_product,
      'totalPage' => ceil($movieNum / $show_product),
      'result' => $movie
    );
    return $this->createJsonResult($resultJson);
  }
}
