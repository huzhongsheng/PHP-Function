<?php
/***********常用gd库函数************/
    /*
     * 裁剪图片
     * @param string $jpgPath 图片路径
     * @param int $target_width 裁剪区域宽度
     * @param int $target_height 裁剪区域高度
     * @param string $filename 另存的文件名
     * @param string $ext 文件名后缀
     * */
    function cutJpg($jpgPath, $target_width, $target_height, $filename,  $ext='jpg'){

        $s = new SaeStorage();
        $source_path = $s->getUrl('static2',$jpgPath);
        $source_info = getimagesize($source_path);
        $source_width = $source_info[0];
        $source_height = $source_info[1];
        $source_mime = $source_info['mime'];
        $source_ratio = $source_height / $source_width;
        $target_ratio = $target_height / $target_width;

        // 源图过高
        if ($source_ratio > $target_ratio) {
            $cropped_width = $source_width;
            $cropped_height = $source_width * $target_ratio;
            $source_x = 0;
            $source_y = ($source_height - $cropped_height) / 2;
        }elseif ($source_ratio < $target_ratio){// 源图过宽
            $cropped_width = $source_height / $target_ratio;
            $cropped_height = $source_height;
            $source_x = ($source_width - $cropped_width) / 2;
            $source_y = 0;
        }else {// 源图适中
            $cropped_width = $source_width;
            $cropped_height = $source_height;
            $source_x = 0;
            $source_y = 0;
        }

        switch ($source_mime) {
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;

            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;

            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;

            default:
                return false;
                break;
        }

        $target_image = imagecreatetruecolor($target_width, $target_height);
        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

        // 裁剪
        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
        // 缩放
        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);


        //直接在浏览器输出图片(两者选一)
        //        header('Content-Type: image/jpeg');

        ob_start();
        imagejpeg($target_image);
        $imgstr = ob_get_contents();
        $jpgPath = 'cut/'.$filename.'.'.$ext;
        $s->write('static2', $jpgPath, $imgstr);
        ob_end_clean();
        imagedestroy($target_image);
        return $jpgPath;
    }
    /*
     * 合并图片图片
     * @param string $pngPath 图片路径
     * @param string $jpgPath 图片路径
     * @param string $filename 另存的文件名
     * @param string $ext 文件名后缀
     * */
    function pngJpeg($pngPath, $jpgPath, $filename, $ext = 'jpg'){
        $s = new SaeStorage();
        $pngRes = $s->getUrl('static2', $pngPath);
        $dl = imagecreatefrompng($pngRes);
        imagesavealpha($dl,true);

        $background = imagecreatefromjpeg($jpgPath);
        imagecopyresized($background, $dl, 0, 0, 0, 0, 750,1448, imagesx($dl), imagesy($dl));

        //        header("content-type:image/jpeg");
        ob_start();
        imagejpeg($background);
        $imgstr = ob_get_contents();
        $jpgPath = 'jpg/'.$filename.'.'.$ext;
        $s->write('static2', $jpgPath, $imgstr);
        //        imagedestroy($target_image);
        ob_end_clean();
        return $jpgPath;
    }
    /***png图片透明处理***/
    $background = imagecreatefrompng('xxx/xxx.png');//必须是png图片
    imagesavealpha($background, true);//透明通道开启

    $thumb = imagecreatetruecolor(750, 1220);
    imagealphablending($thumb, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
    imagesavealpha($thumb, true);//这里很重要,意思是不要丢了$thumb图像的透明色;

    imagecopyresampled($thumb, $background, 0, 0, 0, 0, 750, 1448, 750, 1448);
    /******************/

    /***图片上加入文字***/
    $font = dirname(__FILE__) . '/fzxkjw.ttf';//字体文件
    $col = imagecolorallocate('画布', 0, 0, 0);//颜色
    imagefttext('画布', '字体大小', '倾斜度', 'x轴坐标', 'y轴坐标', $col, $font, '文字内容');
    /******************/

?>