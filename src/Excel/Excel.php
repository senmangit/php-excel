<?php
/**
 * Created by PhpStorm.
 * User: senman
 * Date: 2018/6/8
 * Time: 17:56
 */

namespace Excel;

class Excel
{

    // $tableheader = array('会员账号', '金额', '收款信息', '类型', '处理状态', '处理时间', '申请时间');
    // $sheetname ="测试";
    // $savefile ="保存的文件名";
    function export($data, $file_name, $fileheader, $sheetname, $is_save = 0, $save_path = "", $properties = [])
    {
        $objPHPExcel = new \PHPExcel();
        if (is_null($file_name)) {
            $file_name = time();
        } else {
            //防止中文命名，下载时ie9及其他情况下的文件名称乱码
            iconv('UTF-8', 'GB2312', $file_name);
        }

        // 设置excel的属性：

        if (!empty($properties)) {
            if (!empty($properties['creator'])) {
                //创建人
                $objPHPExcel->getProperties()->setCreator($properties['creator']);
            }

            if (!empty($properties['last_modified'])) {
                //最后修改人
                $objPHPExcel->getProperties()->setLastModifiedBy($properties['last_modified']);
            }

            if (!empty($properties['title'])) {
                //标题
                $objPHPExcel->getProperties()->setTitle($properties['title']);
            }

            if (!empty($properties['subject'])) {
                //题目
                $objPHPExcel->getProperties()->setSubject($properties['subject']);
            }

            if (!empty($properties['description'])) {
                //描述
                $objPHPExcel->getProperties()->setDescription($properties['description']);
            }

            if (!empty($properties['keywords'])) {
                //关键字
                $objPHPExcel->getProperties()->setKeywords($properties['keywords']);
            }

            if (!empty($properties['category'])) {
                //种类
                $objPHPExcel->getProperties()->setCategory($properties['category']);
            }
        }

        //设置excel属性
        $objActSheet = $objPHPExcel->getActiveSheet();
        //设置sheet的name
        $objActSheet->setTitle($sheetname);

//        //合并单元格
//        $objPHPExcel->getActiveSheet()->mergeCells('A18:E22');
//        //分离单元格
//        $objPHPExcel->getActiveSheet()->unmergeCells('A28:B28');


        //根据有生成的excel多少列，$letter长度要大于等于这个值
        // $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
        $letter = [];
        $header_count = count($fileheader);//获取头数量
        for ($i = 65; $i < 91; $i++) {
            if (count($letter) == $header_count) {
                break;
            } else {
                $letter[] = strtoupper(chr($i));//存入大写字母
            }
        }
        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //设置表头
        for ($i = 0; $i < count($fileheader); $i++) {
            //单元宽度自适应,1.8.1版本phpexcel中文支持勉强可以，自适应后单独设置宽度无效
            //$objActSheet->getColumnDimension("$letter[$i]")->setAutoSize(true);
            //设置表头值，这里的setCellValue第二个参数不能使用iconv，否则excel中显示false
            $objActSheet->setCellValue("$letter[$i]1", $fileheader[$i]);
            //设置表头字体样式
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setName('微软雅黑');
            //设置自动换行
            $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setWrapText(true);
            //设置表头字体大小
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setSize(12);
            //设置表头字体是否加粗
            $objActSheet->getStyle("$letter[$i]1")->getFont()->setBold(true);
            //设置表头文字垂直居中
            $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置align
            $objPHPExcel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //设置文字上下居中
            $objActSheet->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置表头外的文字垂直居中
            $objPHPExcel->setActiveSheetIndex(0)->getStyle($letter[$i])->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置border的颜色
            $objPHPExcel->getActiveSheet()->getStyle('D13')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
            $objPHPExcel->getActiveSheet()->getStyle('D13')->getBorders()->getTop()->getColor()->setARGB('FF993300');

            //填充颜色
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->getStartColor()->setARGB('FF808080');

            //保护cell
            $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true); // Needs to be set to true in order to enable any worksheet protection!
            $objPHPExcel->getActiveSheet()->protectCells('A3:E13', 'PHPExcel');

        }


        foreach ($letter as $lk => $lv) {
            //单独设置D列宽度为15
            $objActSheet->getColumnDimension($lv)->setWidth(100);
        }
//
//        $objActSheet->getColumnDimension('G')->setWidth(20);
//        $objActSheet->getColumnDimension('E')->setWidth(20);
//        $objActSheet->getColumnDimension('F')->setWidth(20);
//        $objActSheet->getColumnDimension('D')->setWidth(20);
//        $objActSheet->getColumnDimension('C')->setWidth(100);
//        $objActSheet->getColumnDimension('B')->setWidth(15);
//        $objActSheet->getColumnDimension('A')->setWidth(30);
        //这里$i初始值设置为2，$j初始值设置为0，


        //加图片
//        $objDrawing = new \PHPExcel_Worksheet_Drawing();
//        $objDrawing->setName('Logo');
//        $objDrawing->setDescription('Logo');
//        $objDrawing->setPath('./images/officelogo.jpg');
//        $objDrawing->setHeight(36);
//        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//        $objDrawing = new \PHPExcel_Worksheet_Drawing();
//        $objDrawing->setName('Paid');
//        $objDrawing->setDescription('Paid');
//        $objDrawing->setPath('./images/paid.png');
//        $objDrawing->setCoordinates('B15');
//        $objDrawing->setOffsetX(110);
//        $objDrawing->setRotation(25);
//        $objDrawing->getShadow()->setVisible(true);
//        $objDrawing->getShadow()->setDirection(45);
//        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        for ($i = 2; $i <= count($data) + 1; $i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key => $value) {
                //不是图片时将数据加入到excel，这里数据库存的图片字段是img
                if ($key != 'img') {
                    $objActSheet->setCellValue("$letter[$j]$i", $value);
                }
                //是图片是加入图片到excel
                if ($key == 'img') {
                    if ($value != '') {
                        $value = iconv("UTF-8", "GB2312", $value); //防止中文命名的文件
                        // 图片生成
                        $objDrawing[$key] = new \PHPExcel_Worksheet_Drawing();
                        // 图片地址
                        $objDrawing[$key]->setPath('.\Uploads' . $value);
                        // 设置图片宽度高度
                        $objDrawing[$key]->setHeight('80px'); //照片高度
                        $objDrawing[$key]->setWidth('80px'); //照片宽度
                        // 设置图片要插入的单元格
                        $objDrawing[$key]->setCoordinates('D' . $i);
                        // 图片偏移距离
                        $objDrawing[$key]->setOffsetX(12);
                        $objDrawing[$key]->setOffsetY(12);
                        //下边两行不知道对图片单元格的格式有什么作用
                        //$objDrawing[$key]->getShadow()->setVisible(true);
                        //$objDrawing[$key]->getShadow()->setDirection(50);
                        $objDrawing[$key]->setWorksheet($objActSheet);
                    }
                }
                $j++;
            }
            //设置单元格高度，暂时没有找到统一设置高度方法
            //$objActSheet->getRowDimension($i)->setRowHeight('80px');
        }


        if ($is_save) {
            if (!empty($save_path)) {
                //判断目录是否存在,不存在则创建
                if (!file_exists($save_path)) {
                    mkdir($save_path, '0777', true);
                }
                // 保存excel在服务器上
                $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                //或者$objWriter = new PHPExcel_Writer_Excel5($excel);
                $objWriter->save($save_path . DIRECTORY_SEPARATOR . $file_name);
            } else {
                throw new \Exception("路径不存在");
            }
        } else {

            ob_end_clean();
            //下载的excel文件名称，为Excel5，后缀为xls，不过影响似乎不大
            header('Content-Disposition: attachment;filename="' . $file_name . '"');
            header('Cache-Control: max-age=0');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");;
            header("Content-Transfer-Encoding:binary");
            // 用户下载excel
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }


    }


}
