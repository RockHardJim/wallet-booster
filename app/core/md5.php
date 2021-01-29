<?php
    $mypass = "Rainbow";
    $hash_val = md5($mypass);

    echo $hash_val; //always 32 characters

    $user_entered_pass = "rainbow";

    if(md5($user_entered_pass) == $hash_val){
      //The passwords are equal
    }
?>