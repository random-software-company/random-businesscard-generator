<?php
use Sunra\PhpSimple\HtmlDomParser;

class RandomController extends Controller
{

    function giphy($slug){
        $slug = str_slug($slug);
        
        if( empty($slug) ){
            $slug = 'boobs';
        }

        $found = [];
        $url = 'https://giphy.com/search/'.$slug;
        $cache_key = 'giphy_'.md5($url);
        if( Cache::has($cache_key) ){
            $html = Cache::get($cache_key);
        }else{
            $html = Remote::render($url);
            Cache::put($cache_key,$html,1000);
        }

        $dom = HtmlDomParser::str_get_html( $html );
        $images = $dom->find('li[data-gif=true] img');
        foreach($images as $image){
            if( isset($image->src) ){
                $found[] = $image->src;
            }
        }

        $random = $found[array_rand($found)];
        return redirect($random);
    }

}
