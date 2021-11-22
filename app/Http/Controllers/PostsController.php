<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\HTMLToMarkdown\HtmlConverter;

class PostsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function store(Request $request)
    {

        try {

            $post = Post::create(array_merge(
                $request->all(),
                ['user_id' => Auth::user()->id]
            ));
            $imageName = time().'.'.$request->image->extension();

            $request->image->move(public_path('images'), $imageName);

            $new_post = Post::find($post['id']);
            $new_post->image()->create(['image_name'=>$imageName,'post_id'=>$new_post['id']]);

            $client   = (new \GuzzleHttp\Client([
                'headers' => [
                     "Authorization" => 'Bearer '.Auth::user()->medium_key,
                    "Content-Type"  => "application/json"
                ]
            ]));
            $response = $client->post('https://api.medium.com/v1/users/'.Auth::user()->medium_id.'/posts',
                ['json' =>
                    [
                        "title"         => $post->title,
                        "contentFormat" => 'html',
                        "content"       => $post->content,
                        "publishStatus" => $post->publish_status
                    ]
                ]

            );
            $new_response = json_decode($response->getBody(),true);
            $new_post->update(['post_url' => $new_response['data']['url']]);

            return response()->json($new_response, 201);

        }
        catch (\Exception $e)
        {
            \Log::error('PostContoller | Post: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
            return response()->json($e->getMessage(), 400);
        }
    }
}
