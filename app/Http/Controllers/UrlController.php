<?php

namespace App\Http\Controllers;

use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    public function getUrl(Request $request, $slug)
    {
        if ($slug == 'shorten') {
            $v = Validator::make($request->all(), [
                'url' =>
                    'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
            ]);

            if ($v->fails()) {
                return response()->json(['error' => 'Url not valid'], 400);
            }

            $slug = $this->gen(env('SLUG_LENGTH'));

            Url::create([
                'url' => $request->url,
                'slug' => $slug,
                'count' => 0
            ]);

            return response()->json(
                [
                    'short_url' => 'pt5.at/' . $slug
                ],
                200,
                [],
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }

        Url::where(['slug' => $slug])->update([
            'count' => DB::raw('count+1'),
            'updated_at' => Carbon::now()
        ]);

        $url = Url::where(['slug' => $slug])->first();

        if ($url) {
            return redirect($url->url);
        } else {
            return redirect(env('REDIRECT_URL'));
        }
    }

    protected function gen($length)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }
}
