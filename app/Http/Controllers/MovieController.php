<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;

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
  public function createMovie(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "Title" => 'required|string',
        "IMDB" => 'required|string',
        "Description" => 'required|string',
        "urlCover" => 'required|string',
        "Quality" => 'required|string',
        "Length" => 'required|string',
        "Slug" => 'required|string',
        "Year" => 'required|numeric',
        "ShowHide" => 'required|numeric',
        "VideoLink" => 'required|string',
        "Actors" => 'required|string',
        "Director" => 'required|string',
        "DateCreate" => 'required|date',
        "GenreName" => 'required|string'
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $movie = Movie::find($request['id']);
    $movie->Title = $request['Title'];
    $movie->Episodes = $request['Episodes'];
    $movie->IMDB = $request['IMDB'];
    $movie->Description = $request['Description'];
    $movie->urlCover = $request['urlCover'];
    $movie->ViewCount = $request['ViewCount'];
    $movie->Quality = $request['Quality'];
    $movie->Length = $request['Length'];
    $movie->Slug = $request['Slug'];
    $movie->Year = $request['Year'];
    $movie->ShowHide = $request['ShowHide'];
    $movie->VideoLink = $request['VideoLink'];
    $movie->Actors = $request['Actors'];
    $movie->Director = $request['Director'];
    $movie->created_at = $request['created_at'];
    $movie->updated_at = $request['updated_at'];
    $movie->GenreName = $request['GenreName'];
    $movie->Rating = $request['Rating'];
    $movie->update();

    $client = new Client("movies202-dev", 'JPhrE3mFxojlFRbEaxzQNQFubp9h73V8h3JtRokprr5Kd3b7uE8O54ZpZOwHB0oT');
    $requestRecombee = new Reqs\SetItemValues($request['id'], [
      'Title' => $request['Title'],
      'IMDB' => $request['IMDB'],
      'Description' => $request['Description'],
      'urlCover' => $request['urlCover'],
      'ViewCount' => $request['ViewCount'],
      'Slug' => $request['Slug'],
      'Year' => $request['Year'],
      'Actors' => $this->StringToArray($request['Actors']),
      'Director' => $request['Director'],
      'updated_at' => $request['updated_at'],
      'GenreName' => $this->StringToArray($request['GenreName']),
      'Rating' => $request['Rating']
    ], [
      'cascadeCreate' => true
    ]);
    $requestRecombee->setTimeout(5000);
    $client->send($requestRecombee);

    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
  }

  public function hideOldMovie(Request $request)
  {
    $movieId = $request['movieId'];
    $movie = Movie::find($movieId);
    $movie->ShowHide = 0;
    $movie->update();

    $client = new Client("movies202-dev", 'JPhrE3mFxojlFRbEaxzQNQFubp9h73V8h3JtRokprr5Kd3b7uE8O54ZpZOwHB0oT');
    $requestRecombee = new Reqs\SetItemValues($movieId, [
      'ShowHide' => false
    ], [
      'cascadeCreate' => false
    ]);
    $requestRecombee->setTimeout(5000);
    $client->send($requestRecombee);

    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
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
        "created_at" => 'required|date',
        "updated_at" => 'required|date',
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
    $movie = Movie::find($request['id']);
    $movie->Title = $request['Title'];
    $movie->Episodes = $request['Episodes'];
    $movie->IMDB = $request['IMDB'];
    $movie->Description = $request['Description'];
    $movie->urlCover = $request['urlCover'];
    $movie->ViewCount = $request['ViewCount'];
    $movie->Quality = $request['Quality'];
    $movie->Length = $request['Length'];
    $movie->Slug = $request['Slug'];
    $movie->Year = $request['Year'];
    $movie->ShowHide = $request['ShowHide'];
    $movie->VideoLink = $request['VideoLink'];
    $movie->Actors = $request['Actors'];
    $movie->Director = $request['Director'];
    $movie->created_at = $request['created_at'];
    $movie->updated_at = $request['updated_at'];
    $movie->GenreName = $request['GenreName'];
    $movie->Rating = $request['Rating'];
    $movie->update();

    $client = new Client("movies202-dev", 'JPhrE3mFxojlFRbEaxzQNQFubp9h73V8h3JtRokprr5Kd3b7uE8O54ZpZOwHB0oT');
    $requestRecombee = new Reqs\SetItemValues($request['id'], [
      'Title' => $request['Title'],
      'IMDB' => $request['IMDB'],
      'Description' => $request['Description'],
      'urlCover' => $request['urlCover'],
      'ViewCount' => $request['ViewCount'],
      'Slug' => $request['Slug'],
      'Year' => $request['Year'],
      'Actors' => $this->StringToArray($request['Actors']),
      'Director' => $request['Director'],
      'updated_at' => $request['updated_at'],
      'GenreName' => $this->StringToArray($request['GenreName']),
      'Rating' => $request['Rating']
    ], [
      'cascadeCreate' => false
    ]);
    $requestRecombee->setTimeout(5000);
    $client->send($requestRecombee);

    return $this->createJsonResult($request->all());
    return response()->json(
      [$request->all()],
      200
    );
  }
  private function StringToArray($string){
    $result = explode(",",$string);
    return json_decode(json_encode($result), FALSE);
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
    $movie = Movie::orderBy('id', 'desc')->where('Title','like','%'.$request['Title'].'%')->skip($skip_product_in_page)->take($show_product)->get();
    $movieTotal= Movie::where('Title','like','%'.$request['Title'].'%')->get();
    $movieNum = count($movieTotal);
    $resultJson = array(
      'currentPage' => $current_page,
      'movieNumber' =>count($movie),
      'movieMaxNumber' => $show_product,
      'totalPage' => ceil($movieNum / $show_product),
      'result' => $movie
    );
    return $this->createJsonResult($resultJson);
  }
}
