<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
URL::forceScheme('https');

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::group(
  [
    'middleware' => 'api',
    'namespace'  => 'App\Http\Controllers',
    'prefix'     => 'movie',
  ],
  function ($router) {
    Route::get('ShowMovieDetail/{slug}', 'MovieController@ShowMovieDetail')->name('ShowMovieDetail');
    Route::get('showMovie', 'MovieController@showMovie')->name('showMovie');
    Route::post('editMovie', 'MovieController@editMovie')->name('editMovie');
  }
);
//C
Route::get('/', function () {
    
  // $files = Storage::disk("google")->allFiles();
  // $firstFileName= $files[0];
  // $link = 'https://drive.google.com/uc?export=view&id='.$firstFileName;
  // echo '<img src='.$link .' alt="Girl in a jacket" width="500" height="600">';
  // dump("Filename : ". $firstFileName);
  // $details = Storage::disk('google')->getMetaData($firstFileName);
  // dump($details);
  // $url = Storage::disk('google')->Url($firstFileName);
  // dump('Download url : '. $url);
  // Storage::disk('google')->setVisibility($firstFileName,'public');
  // $visibility = Storage::disk('google')->getVisibility($firstFileName);
  // dump('Visibility : '. $visibility);
  // $response = Storage::disk('google')->download($firstFileName,'black.jpg');
  // $response->send();
  return view('welcome');
});

Route::post('/upload', function (Request $request) {
    dd($request->file("thing"));
  // $filename = $request->file("thing")->store("1pc05ZmozeUN-ofSgO8ugg9wG7cQU2BL1","google") ;
  // dump($filename);
  // $googleDriveStorage = Storage::disk('google');
  // $dir = '/';
  // $recursive = true; // Get subdirectories also?
  // $file = collect(Storage::disk('google')->listContents($dir, $recursive))
  //     ->where('type', '=', 'file')
  //     ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
  //     ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
  //     ->sortBy('timestamp')
  //     ->last();
  // dump($file);
  // dump(Storage::Disk('google')->url($file['path']));
  // $link = Storage::Disk('google')->url($file['path']);
  // #echo '<img src='.$link .' alt="Girl in a jacket">';
  // echo '<video>  <source src='. $link .' type="video/mp4"> </video>';

});

