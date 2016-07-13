<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/11
 * Time: 17:15
 */

namespace Home\Controller;


use Think\Controller;

class OrderInfoController extends Controller
{
    /**
     * @var \Home\Model\OrderInfoModel
     */
    private $_model = null;

    protected function _initialize() {
        $this->_model = D('OrderInfo');
        //分类数据和帮助文章列表数据不会频繁发生变化,但是请求又比较平凡,所以就进行缓存
        if(!$goods_categories=S('goods_categories')){
            //如果没有缓存的时候,就去获取
            //获取商品分类列表
            $goods_category_model=D('GoodsCategory');
            $goods_categories=$goods_category_model->getList('id,name,parent_id');
            //获取出来后并进行缓存
            S('goods_categories',$goods_categories,3600);
        }
        $this->assign('goods_categories',$goods_categories);
        //缓存帮助文章数据
        if(!$help_articles=S('help_articles')){
            //获取帮助文章数据
            $article_model=D('Article');
            $help_articles=$article_model->getHelpList();
            //将文章数据进行缓存
            S('help_articles',$help_articles,3600);
        }
        $this->assign('help_articles',$help_articles);
    }

    /**
     * 添加订单
     */
    public function add()
    {
        if(IS_POST){
            if($this->_model->create()===false){
                $this->error(getError($this->_model));
            }
            if($this->_model->addOrder()===false){
                $this->error(getError($this->_model));
            }
            $this->success('创建订单成功',U('Cart/flow3'));
        }else{
            $this->error('拒绝直接访问');
        }
    }

    /**
     * 展示订单详情
     */
    public function index()
    {
        $this->display('order');
    }

}