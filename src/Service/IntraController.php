<?php

namespace App\Service;


class IntraController
{
    

    static function confirmEmail($user)
    {
        if(!$user == null){
            if($user->isVerified()  == false){
                return true;
            }
        }
    }
    static function completeCoordonnees($user)
    {
        if(!$user == null){
            if($user->isVerified() === true && $user->isFull() === false){
                return true;
            }
        }
    }

}
