<?php

namespace App\Controller;

use App\Entity\Name;
use App\Entity\listSpeeches;
use App\Form\DeleteType;
use App\Form\DrawType;
use App\Form\NameType;
use App\Repository\ListSpeechesRepository;
use App\Repository\NameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class NameController extends AbstractController
{
    /**
     * @Route("/", name="name_index", methods={"GET", "POST"})
     */
    public function index(NameRepository $nameRepository, ListSpeechesRepository $listSpeechesRepository,
                          Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $nameRepository->findAll();
        $listSpeeche = $listSpeechesRepository->findAll();
        $listSpeeches = new ListSpeeches();

        $form = $this->createForm(DrawType::class, $listSpeeches);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $max = count($name);
            $randomId = rand(1, $max);
            $draw = $nameRepository->findOneBy(["id" => $randomId]);
            $listSpeeches->setName($draw->getName());
            $listSpeeches->setFirstName($draw->getFirstName());
            $entityManager->persist($listSpeeches);
            $entityManager->flush();
            $listSpeeche = $listSpeechesRepository->findAll();
        }

        $addName = new Name();
        $addForm = $this->createForm(NameType::class, $addName);
        $addForm->handleRequest($request);
        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $entityManager->persist($addName);
            $entityManager->flush();
            $this->addFlash('success', "L'élève à bien été ajoutée");

            return $this->redirectToRoute('name_index', [], Response::HTTP_SEE_OTHER);
        }

        $deleteForm = $this->createForm(DeleteType::class);
        $deleteForm->handleRequest($request);
        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
           $listSpeechesRepository->dropTable();
            $this->addFlash('danger', "Les élèves à bien été ajoutée");
            return $this->redirectToRoute('name_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('name/index.html.twig', [
            'names' => $name,
            'addNames' => $addName,
            'listSpeeche' => $listSpeeches,
            'listSpeeches' => $listSpeeche,
            'draw' => $draw ?? null,
            'form' => $form->createView(),
            'addForm' => $addForm->createView(),
            'deleteForm' => $deleteForm->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="name_show", methods={"GET"})
     */
    public function show(Name $name): Response
    {
        return $this->render('name/show.html.twig', [
            'name' => $name,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="name_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Name $name, EntityManagerInterface $entityManager): Response
    {
        $addForm = $this->createForm(NameType::class, $name);
        $addForm->handleRequest($request);

        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('name_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('name/edit.html.twig', [
            'name' => $name,
            'addForm' => $addForm,
        ]);
    }

    /**
     * @Route("/{id}", name="name_delete", methods={"POST"})
     */
    public function delete(Request $request, Name $name, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $name->getId(), $request->request->get('_token'))) {
            $entityManager->remove($name);
            $entityManager->flush();
        }

        return $this->redirectToRoute('name_index', [], Response::HTTP_SEE_OTHER);
    }
}
