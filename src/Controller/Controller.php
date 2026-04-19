<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RDVRepository;
class Controller extends AbstractController
{
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    public function index(){
    
        $nom="Ghazouani";
        return $this->render ("home.html.twig", [
            "nom"=>$nom
        ]);        
    }


}
