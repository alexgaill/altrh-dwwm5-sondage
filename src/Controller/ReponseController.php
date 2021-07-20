<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Form\ReponseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse")
     */
    public function index(): Response
    {
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findAll();
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    /**
     * @Route("/create", name="createReponse")
     */
    public function create(Request $request)
    {
        $reponse = new Reponse;

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse = $form->getData();
            $reponse->setScore(0);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute("reponse");
        }

        return $this->render("reponse/create.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/upScore/{id}/{sondageId}", name="upScore")
     */
    public function upScore(Reponse $reponse, int $sondageId)
    {
        $score = $reponse->getScore() + 1;
        $reponse->setScore($score);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($reponse);
        $manager->flush();

        return $this->redirectToRoute("singleSondage", [
            "id" => $sondageId
        ]);
    }

    /**
     * @Route("/downScore/{id}/{sondageId}", name="downScode")
     */
    public function downScore(Reponse $reponse, int $sondageId)
    {
        $score = $reponse->getScore() - 1;
        $reponse->setScore($score);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($reponse);
        $manager->flush();

        return $this->redirectToRoute("singleSondage", [
            "id" => $sondageId
        ]);
    }

    /**
     * @Route("/{id}/update", name="updateReponse")
     *
     * @param Reponse $reponse
     * @return void
     */
    public function updateReponse(Reponse $reponse, Request $request)
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse = $form->getData();
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute("reponse");
        }

        return $this->render("reponse/update.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
