<?php

namespace App\Service;

final class Variables
{


    private $webmaster= 'webmaster@my-domain.org';
    
    private ?string $folder ="avatars";



    public function getWebmaster(): ?string 
    {
        return $this->webmaster;
    }

    public  function getFolder(): ?string
    {
        return $this->folder;
    }

    



}
