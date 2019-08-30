<?php

namespace app\admin\controller;
use think\Config;
use think\Controller;
use think\Request;

class File extends Controller
{

    /**
     * @time 2019/08/30
     * 图片上传
     * @param  \think\Request  $request
     * @return \think\Response
     * @author fyk
     */
    public function image(Request $request)
    {
        $file = $request->file('image');
        $imgPath = 'public' . DS . 'upload' . DS . 'image';
        $info =  $file->validate(['size'=>10485760,'ext'=>'jpg,png,gif,jpeg'])
            ->move(ROOT_PATH . $imgPath);
        if($info){
            $fileName = str_replace("\\","/",$info->getSaveName());
            //pp($fileName);die;
            //获取文件路径
            $filePath = Config::get('front_pic_domain').'upload/image/'.$fileName;
            $data['fileName'] = $fileName;
            $data['filePath'] = $filePath;
            return json(['code'=>1,'msg'=>'上传成功','data' =>$data]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>0,'msg' => $file->getError()]);
        }
    }
    /**
     * @time 2019/08/30
     * 视频上传
     * @param  \think\Request  $request
     * @return \think\Response
     * @author fyk
     */
    public function video(Request $request)
    {
        $file = $request->file('video');
        $info =  $file->validate(['size'=>104857600,'ext'=>'flv,wmv,rmvb,mp4'])
            ->move( './upload/video');
        if($info){
            $fileName = str_replace("\\","/",$info->getSaveName());
            //获取文件路径
            $filePath = Config::get('front_pic_domain').'upload/video/'.$fileName;
            $data['fileName'] = $fileName;
            $data['filePath'] = $filePath;
            return json(['code'=>1,'msg'=>'上传成功','data' =>$data]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>0,'msg' => $file->getError()]);
        }
    }

    /**
     * @time 2019/08/30
     * 文档上传
     * @param  \think\Request  $request
     * @return \think\Response
     * @author fyk
     */
    public function file(Request $request)
    {
        $file = $request->file('file');
        // $name = iconv('utf-8','gbk',$file->getInfo()['name']);//中文转码
        $info =  $file->validate(['size'=>104857600])
            // ->move( './upload/file/',$name);
            ->move( './upload/file/');
        if($info){
            $fileName = str_replace("\\","/",$info->getSaveName());
            // $fileName = iconv('gbk','utf-8',$fileName);//编码转回
            //获取文件路径
            $filePath = Config::get('front_pic_domain').'upload/file/'.$fileName;
            $data['fileName'] = $fileName;
            $data['filePath'] = $filePath;
            return json(['code'=>1,'msg'=>'上传成功','data' =>$data]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>0,'msg' => $file->getError()]);
        }
    }

}
