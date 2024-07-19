<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MenuItem;
use Carbon\Carbon;
use DateTime;

class AnatomyService
{
    public function getAnatomyImage($toothId, $occulusal_condn, $palatal_condn, $mesial_condn, $distal_condn, $buccal_condn)
    {
        $image = null;
        $molar = [11, 12, 13, 21, 22, 23, 41, 42, 43, 31, 32, 33, 51, 52, 53, 61, 62, 63, 71, 72, 73, 81, 82, 83];
        $circle = [14, 15, 16, 17, 18, 24, 25, 26, 27, 28, 34, 35, 36, 37, 38, 44, 45, 46, 47, 48, 54, 55, 64, 65, 74, 75, 84, 85 ];
        
        if (in_array($toothId, $molar)) {
            $binaryRepresentation = $palatal_condn . $mesial_condn . $distal_condn . $buccal_condn;

        // Switch-case to handle all combinations
            switch ($binaryRepresentation) {
                case '0000':
                    $image = 'images/tooth/img1.png'; // Example image path for 0000 condition
                    break;
                case '0001':
                    $image = 'images/tooth/img2.png'; // Example image path for 0001 condition
                    break;
                case '0010':
                    $image = 'images/tooth/img5.png'; // Example image path for 0010 condition
                    break;
                case '0011':
                    $image = 'images/tooth/img8.png'; // Example image path for 0011 condition
                    break;
                case '0100':
                    $image = 'images/tooth/img3.png'; // Example image path for 0100 condition
                    break;
                case '0101':
                    $image = 'images/tooth/img6.png'; // Example image path for 0101 condition
                    break;
                case '0110':
                    $image = 'images/tooth/img13.png'; // Example image path for 0110 condition
                    break;
                case '0111':
                    $image = 'images/tooth/img10.png'; // Example image path for 0111 condition
                    break;
                case '1000':
                    $image = 'images/tooth/img4.png'; // Example image path for 1000 condition
                    break;
                case '1001':
                    $image = 'images/tooth/img7.png'; // Example image path for 1001 condition
                    break;
                case '1010':
                    $image = 'images/tooth/img15.png'; // Example image path for 1010 condition
                    break;
                case '1011':
                    $image = 'images/tooth/img16.png'; // Example image path for 1011 condition
                    break;
                case '1100':
                    $image = 'images/tooth/img12.png'; // Example image path for 1100 condition
                    break;
                case '1101':
                    $image = 'images/tooth/img9.png'; // Example image path for 1101 condition
                    break;
                case '1110':
                    $image = 'images/tooth/img14.png'; // Example image path for 1110 condition
                    break;
                case '1111':
                    $image = 'images/tooth/img11.png'; // Example image path for 1111 condition
                    break;
                default:
                    $image = 'images/tooth/img1.png'; // Default image or handle other conditions
                    break;
            }
        
        } else if (in_array($toothId, $circle)) {
            $binaryRepresentation = $occulusal_condn . $palatal_condn . $mesial_condn . $distal_condn. $buccal_condn ;

            // Switch-case to handle all combinations
            switch ($binaryRepresentation) {
                case '00000':
                    $image = 'images/tooth/tooth_img/img1.png'; // Example image path for 00000 condition
                    break;
                case '00001':
                    $image = 'images/tooth/tooth_img/img3.png'; // Example image path for 00001 condition
                    break;
                case '00010':
                    $image = 'images/tooth/tooth_img/img6.png'; // Example image path for 00010 condition
                    break;
                case '00011':
                    $image = 'images/tooth/tooth_img/img9.png'; // Example image path for 00011 condition
                    break;
                case '00100':
                    $image = 'images/tooth/tooth_img/img4.png'; // Example image path for 00100 condition
                    break;
                case '00101':
                    $image = 'images/tooth/tooth_img/img7.png'; // Example image path for 00101 condition
                    break;
                case '00110':
                    $image = 'images/tooth/tooth_img/img12.png'; // Example image path for 00110 condition
                    break;
                case '00111':
                    $image = 'images/tooth/tooth_img/img18.png'; // Example image path for 00111 condition
                    break;
                case '01000':
                    $image = 'images/tooth/tooth_img/img5.png'; // Example image path for 01000 condition
                    break;
                case '01001':
                    $image = 'images/tooth/tooth_img/img8.png'; // Example image path for 01001 condition  
                     break;
                case '01010':
                    $image = 'images/tooth/tooth_img/img14.png'; // Example image path for 01010 condition
                    break;
                case '01011':
                    $image = 'images/tooth/tooth_img/img20.png'; // Example image path for 01011 condition
                    break;
                case '01100':
                    $image = 'images/tooth/tooth_img/img11.png'; // Example image path for 01100 condition
                    break;
                case '01101':
                    $image = 'images/tooth/tooth_img/img17.png'; // Example image path for 01101 condition
                    break;
                case '01110':
                    $image = 'images/tooth/tooth_img/img28.png'; // Example image path for 01110 condition
                    break;
                case '01111':
                    $image = 'images/tooth/tooth_img/img23.png'; // Example image path for 01111 condition
                    break;
                case '10000':
                    $image = 'images/tooth/tooth_img/img2.png'; // Example image path for 10000 condition
                    break;
                case '10001':
                    $image = 'images/tooth/tooth_img/img10.png'; // Example image path for 10001 condition
                    break;
                case '10010':
                    $image = 'images/tooth/tooth_img/img16.png'; // Example image path for 10010 condition
                    break;
                case '10011':
                    $image = 'images/tooth/tooth_img/img22.png'; // Example image path for 10011 condition
                    break;
                case '10100':
                    $image = 'images/tooth/tooth_img/img13.png'; // Example image path for 10100 condition
                    break;
                case '10101':
                    $image = 'images/tooth/tooth_img/img19.png'; // Example image path for 10101 condition
                    break;
                case '10110':
                    $image = 'images/tooth/tooth_img/img30.png'; // Example image path for 10110 condition
                    break;
                case '10111':
                    $image = 'images/tooth/tooth_img/img25.png'; // Example image path for 10111 condition
                    break;
                case '11000':
                    $image = 'images/tooth/tooth_img/img15.png'; // Example image path for 11000 condition
                    break;
                case '11001':
                    $image = 'images/tooth/tooth_img/img21.png'; // Example image path for 11001 condition
                    break;
                case '11010':
                    $image = 'images/tooth/tooth_img/img32.png'; // Example image path for 11010 condition
                    break;
                case '11011':
                    $image = 'images/tooth/tooth_img/img26.png'; // Example image path for 11011 condition
                    break;
                case '11100':
                    $image = 'images/tooth/tooth_img/img29.png'; // Example image path for 11100 condition
                    break;
                case '11101':
                    $image = 'images/tooth/tooth_img/img24.png'; // Example image path for 11101 condition
                    break;
                case '11110':
                    $image = 'images/tooth/tooth_img/img31.png'; // Example image path for 11110 condition
                    break;
                case '11111':
                    $image = 'images/tooth/tooth_img/img27.png'; // Example image path for 11111 condition
                    break;
                default:
                    $image = 'images/tooth/tooth_img/img1.png'; // Default image or handle other conditions
                    break;
            }
        }

      return $image;
    }

}
