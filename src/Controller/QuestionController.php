<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question")
     */
    public function index(): Response
    {
        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

        return $this->render('question/index.html.twig', [
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/create", name="createQuestion")
     */
    public function create(Request $request)
    {
        $question = new Question;
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($question);
            $manager->flush();

            return $this->redirectToRoute("question");
        }
        return $this->render('question/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/update", name="updateQuestion")
     *
     * @param Question $question
     * @param Request $request
     * @return void
     */
    public function update(Question $question, Request $request)
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($question);
            $manager->flush();

            return $this->redirectToRoute("question");
        }

        return $this->render("question/update.html.twig",[
            'form' => $form->createView()
        ]);
    }
}
