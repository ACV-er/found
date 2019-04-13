<?php
    function compress($src_img) {
        ini_set('memory_limit','256M');
        $dst_w = 900;
        $dst_h = 600;
        list($src_w,$src_h)=getimagesize($src_img);  // 获取原图尺寸

        $dst_scale = $dst_h/$dst_w; //目标图像长宽比
        $src_scale = $src_h/$src_w; // 原图长宽比

        if($src_scale>=$dst_scale){  // 过高
            $w = intval($src_w);
            $h = intval($dst_scale*$w);

            $x = 0;
            $y = ($src_h - $h)/3;
        }
        else{ // 过宽
            $h = intval($src_h);
            $w = intval($h/$dst_scale);

            $x = ($src_w - $w)/2;
            $y = 0;
        }

// 剪裁
        $type = exif_imagetype($src_img);

        switch($type) {
            case IMAGETYPE_JPEG :
                $source = imagecreatefromjpeg($src_img);
                break;
            case IMAGETYPE_PNG :
                $source = imagecreatefrompng($src_img);
                break;
            case IMAGETYPE_GIF :
                $source = imagecreatefromgif($src_img);
                break;
            default:
                $source=imagecreatefromjpeg($src_img);
                break;
        }
        $croped=imagecreatetruecolor($w, $h);
        imagecopy($croped,$source,0,0,$x,$y,$src_w,$src_h);
        imagedestroy($source);
// 缩放
        $scale = $dst_w/$w;
        $target = imagecreatetruecolor($dst_w, $dst_h);
        $final_w = intval($w*$scale);
        $final_h = intval($h*$scale);
        imagecopyresampled($target,$croped,0,0,0,0,$final_w,$final_h,$w,$h);
        imagedestroy($croped);
// 保存
        unlink($src_img);
        switch($type) {
            case IMAGETYPE_JPEG :
                imagejpeg($target, $src_img); // 存储图像
                break;
            case IMAGETYPE_PNG :
                imagepng($target, $src_img);
                break;
            case IMAGETYPE_GIF :
                imagegif($target, $src_img);
                break;
            default:
                imagejpeg($target, $src_img);
                break;
        }
        imagedestroy($target);
    }

