<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Questions;
use App\Form\SatisfactionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    #[Route(path: '/', name: 'app_main', methods: ['GET'])]
    public function getForm(): Response
    {

        $questionForm = $this->createForm(
            type: SatisfactionFormType::class,
            options:[
                'action' => $this->generateUrl(route:'app_main_post'),
                'method' => 'POST',
            ]
        );

        return $this->render(view: 'main/index.html.twig', parameters: [
            'questionForm' => $questionForm->createView()
        ]);
    }

    #[Route(path: '/', name: 'app_main_post', methods: ['POST'])]
    public function postForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $questionForm = $this->createForm(
            type: SatisfactionFormType::class,
        );

        $questionForm->handleRequest($request);

        // make service of form
        if ($questionForm->isValid() === false) {
            $formErrorService->saveSubmittedDataInSession($questionForm->getData());

            $formErrorService->addFormErrorsInSession($questionForm);

            return $this->redirectToRoute('app_main');
        }


        return $this->render(view: 'main/index.html.twig', parameters: [
            'questionForm' => $questionForm->createView()
        ]);
    }
}
