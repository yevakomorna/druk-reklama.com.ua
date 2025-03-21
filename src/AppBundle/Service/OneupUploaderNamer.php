<?php
namespace AppBundle\Service;


use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OneupUploaderNamer implements NamerInterface
{
    private $tokenStorage;
    
    public function __construct(TokenStorage $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }
    
    
    public function name(FileInterface $file)
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        
        return sprintf('%s/%s.%s',
            $userId,
            uniqid(),
            $file->getExtension()
        );
    }
}



?>