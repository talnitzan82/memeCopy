<?php
/**
 * Created by PhpStorm.
 * User: wisher1
 * Date: 03/12/2014
 * Time: 19:27
 */

$con = mysqli_connect("50.87.144.128", "talnitza_meme", "Meme1234!", "talnitza_meme"); // HostGator -memeCopy

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}