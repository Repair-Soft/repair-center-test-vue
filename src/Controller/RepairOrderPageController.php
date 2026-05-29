<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepairOrderPageController extends AbstractController
{
    // Les deux routes rendent la même SPA Vue ; le routing fin est géré côté front
    // (vue-router). Garder ces routes permet de rafraîchir / partager une URL de détail.
    #[Route('/', name: 'app_home')]
    #[Route('/repair-orders/{id}', name: 'app_repair_order', requirements: ['id' => '\d+'])]
    public function index(): Response
    {
        return $this->render('repair_order/index.html.twig');
    }
}
