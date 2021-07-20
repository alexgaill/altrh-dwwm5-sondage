<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Form\SondageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/sondage")
 */
class SondageController extends AbstractController
{
    /**
     * @Route("/", name="sondage")
     */
    public function index(): Response
    {
        $sondages = $this->getDoctrine()->getRepository(Sondage::class)->findAll();
        return $this->render('sondage/index.html.twig', [
            'sondages' => $sondages
        ]);
    }

    /**
     * @Route("/create", name="createSondage")
     */
    public function create(Request $request)
    {
        $sondage = new Sondage;

        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondage = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($sondage);
            $manager->flush();

            return $this->redirectToRoute("sondage");
        }

        return $this->render("sondage/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="singleSondage")
     */
    public function single(Sondage $sondage)
    {
        foreach($sondage->getQuestions() as $question) { 
            $highScore = $this->getDoctrine()->getRepository(Reponse::class)->getHigh($question->getId());
            foreach ($highScore as $high) {
                foreach ($question->getReponses() as $reponse) {
                    if ($reponse->getScore() == $high) {
                        $reponse->setHighScore(true);
                    }
                }
            }
        }
        return $this->render("sondage/single.html.twig", [
            "sondage" => $sondage
        ]);
    }
}
