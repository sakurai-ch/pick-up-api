<?php

namespace App\Models;

use App\Http\Controllers\CoordinatesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;

class coordinates extends Model
{
    use HasFactory;

    public static function get_all()
    {
        $datas = Coordinates::get();
        $items = [];
        foreach($datas as $data){
            $item = array(
                "id" => $data->id,
                "target_name" => $data->target_name,
                "north_latitude" => $data->north_latitude,
                "east_longitude" => $data->east_longitude,
                "position" => array("lat" => (float)($data->north_latitude), "lng" => (float)($data->east_longitude))
            );
            array_push($items, $item);
        }
        return $items;
    }

    public static function post_target($request)
    {
        $text = $request->text;
        $position = Coordinates::preg_match_position_data($text);

        if($position==null){
            $url_text = Coordinates::search_url($text);
            if($url_text!=null){
                $position = Coordinates::preg_match_position_data($url_text);
            }
        }

        if($position==null){
            return null;
        }else{
            $now = Carbon::now();
            $param = [
                "target_name" => $request->target_name,
                "north_latitude" => $position["lat"],
                "east_longitude" => $position["lng"],
                "created_at" => $now,
            ];
            Coordinates::insert($param);
            return $param;
        }
    }

    public static function preg_match_position_data($text){
        $north_latitude = null;
        $east_longitude = null;
        if (preg_match('/[3][5-9]\.[0-9]+/', $text, $matches)) {
            $north_latitude = $matches[0];
        }
        if (preg_match('/[1][34][01289]\.[0-9]+/', $text, $matches)) {
            $east_longitude = $matches[0];
        }

        if($north_latitude!=null && $east_longitude!=null){
            $position = array("lat" => $north_latitude, "lng" => $east_longitude);
            return $position;
        }else{
            return null;
        }
    }

    public static function search_url($text){
        if (preg_match('/http[\S]+/', $text, $matches)) {
            try {
                $url = $matches[0];
                $headers = get_headers($url, 1, null);
                echo ($headers['Location']);
                $url_text = array_pop($headers['Location']);
            } catch (Exception $e) {
                echo $e->getMessage();
                $url_text = $e->getMessage();
            }
        }
        return $url_text;
    }
}
