<?php
    /**
     * @param string $url
     * @return mixed
     */
    function doGet($url){
        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        return $output;
    }

    /**
     * @param string $url
     * @param array $post_data
     * @param array | boolean $header
     * @return mixed
     */
    function doPost($url,$post_data,$header){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 请求头，可以传数组
        //        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        /*
         * 20180131批注：
         * 请求https://ip.xxx时会报302错误，或请求不到数据。
         * 解决方法：
         * curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         * 关闭ssl检查ssl加密算法
         * */

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /*
     * @param string $url 远程图片地址
     * @param string $filename 保存文件名称
     * @param string $ext 文件后缀名
     * @return string 文件路径，保存到新浪云stroage
     * */
    function download($url, $filename='', $ext='jpg') {
        if(empty($filename)){
            $filename = substr(md5(time().rand(10,99)),10, 8);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $s = new SaeStorage();
        $save = 'XXX/'.$filename.'.'.$ext;
        $s->write('xxx', $save, $file_content);
        return $s->getUrl('xxx', $save);
    }
?>