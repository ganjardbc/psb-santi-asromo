<?php

function ang_rom($angka) {
    $hsl = "";
    if ($angka < 1 || $angka > 5000) { 
        $hsl = "Batas Angka 1 s/d 5000";
    } else {
        while ($angka >= 1000) {
            $hsl .= "M"; 
            $angka -= 1000;
        }
    }
    if ($angka >= 500) {
        // statement di atas akan bernilai true / benar
        // Jika var angka lebih dari sama dengan 500
        if ($angka > 500) {
            if ($angka >= 900) {
                $hsl .= "CM";
                $angka -= 900;
            } else {
                $hsl .= "D";
                $angka-=500;
            }
        }
    }
    while ($angka>=100) {
        if ($angka>=400) {
            $hsl .= "CD";
            $angka -= 400;
        } else {
            $angka -= 100;
        }
    }
    if ($angka>=50) {
        if ($angka>=90) {
            $hsl .= "XC";
            $angka -= 90;
        } else {
            $hsl .= "L";
            $angka-=50;
        }
    }
    while ($angka >= 10) {
        if ($angka >= 40) {
            $hsl .= "XL";
            $angka -= 40;
        } else {
            $hsl .= "X";
            $angka -= 10;
        }
    }
    if ($angka >= 5) {
        if ($angka == 9) {
            $hsl .= "IX";
            $angka-=9;
        } else {
            $hsl .= "V";
            $angka -= 5;
        }
    }
    while ($angka >= 1) {
        if ($angka == 4) {
            $hsl .= "IV"; 
            $angka -= 4;
        } else {
            $hsl .= "I";
            $angka -= 1;
        }
    }
    return ($hsl);
}