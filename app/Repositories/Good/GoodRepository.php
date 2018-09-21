<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Good;


use App\Model\Good;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GoodRepository implements GoodRepositoryInterface
{

    /**
     * @param $good_id
     * @return mixed
     * 获取商品详情
     */
    public function getGood($good_id)
    {
        try{
            $goods = Good::where('uid',$good_id)
                ->first();

            if(!$goods){
                throw new NotFoundHttpException('商品不存在');
            }

            $goods->combo_list = \DB::table('good_combos')
                ->select('uid','name','unit_price','original_unit_price')
                ->where('good_id',$good_id)
                ->get()->toArray();
            $goods->color_list = \DB::table('good_colors')
                ->select('uid','name')
                ->where('good_id',$good_id)
                ->get()->toArray();
            $goods->detail_imgs = \DB::table('good_details')
                ->select("url")
                ->where('good_id',$good_id)
                ->orderBy('order_by')
                ->pluck('url');
            $goods->banner_imgs = \DB::table('good_banners')
                ->select("url")
                ->where('good_id',$good_id)
                ->orderBy('order_by')
                ->pluck('url');

            return response_format($goods);
        }catch (NotFoundHttpException $exception){
            return response_format([],0,"商品不存在");
        } catch (Exception $e){
            return response_format([],0,$e->getMessage());
        }
    }

    public function find($good_id)
    {
        return Good::findOrFail($good_id)->first();
    }
}