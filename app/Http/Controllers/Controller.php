<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Tag;
use View;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function __construct()
  {
    $tags = Tag::all('id','name');
	foreach ($tags as $tag) {
		$tag->label = $tag->name;//lable needed for vselect 
	}

    View::share('tags', $tags);
  }

}
