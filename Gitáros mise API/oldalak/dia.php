<?php

class Dia {

    public static function getDia($text) {
        $fontSize = 16.5;
        
        // Create Image From Existing File
        $jpg_image = imagecreatefromjpeg('dia_generalas/hatter_800x600.jpg');

        // Allocate A Color For The Text
        $black = imagecolorallocate($jpg_image, 0, 0, 0);

        // Set Path to Font File
        $font_path = 'dia_generalas/arial.ttf';

        // Set Text to Be Printed On Image
        $textBox = imagettfbbox($fontSize, 0, $font_path, $text);

//        $adatok = array(
//            'kép' => array(
//                'szélesség' => imagesx($jpg_image),
//                'magasság' => imagesy($jpg_image)
//            ),
//            'szöveg' => array(
//                'bal alsó' => $textBox[0] . ', ' . $textBox[1],
//                'jobb felső' => $textBox[4] . ', ' . $textBox[5],
//                'bal felső' => $textBox[6] . ', ' . $textBox[7],
//                'jobb alsó' => $textBox[2] . ', ' . $textBox[3],
//                'szélesség' => $textBox[4] - $textBox[0],
//                'magasság' => $textBox[1] - $textBox[5]
//            )
//        );

//        Communication::writeJsonResponse($app, $adatok);
        $x = ceil((imagesx($jpg_image) - ($textBox[4] - $textBox[0])) / 2);
        $y = ceil((imagesy($jpg_image) - ($textBox[1] - $textBox[5])) / 2) + $fontSize;

//        echo json_encode($adatok, JSON_PRETTY_PRINT). "\nx = $x  y = $y\n";
//        echo imagesx($jpg_image) . ' - ' . ($text_box[4] - $text_box[0]) . ' = ' 
//                . (imagesx($jpg_image) - ($text_box[4] - $text_box[0])) . "\n";
//        echo ' / 2 = ' . ((imagesx($jpg_image) - ($text_box[4] - $text_box[0])) / 2) . "\n";
//        echo "ceil: $x    (y: $y)\n";
        // Print Text On Image
        imagettftext($jpg_image, $fontSize, 0, $x, $y, $black, $font_path, $text);

        // Send Image to Browser
//        imagejpeg($jpg_image);
        ob_start();
        imagejpeg($jpg_image, null, 100);
        $contents =  ob_get_contents();
        ob_end_clean();
//        echo 'teszt';
        $base64 = 'data:image/jpeg;base64,' . base64_encode($contents);
        // Clear Memory
        imagedestroy($jpg_image);
        
        return $base64;
    }
    
    public static function createDia($app) {
        $text = Communication::getRequestBodyData($app, 'text');
        
        Communication::writeJsonResponse($app, array(
            'image' => Dia::getDia($text)
        ));
    }

}
