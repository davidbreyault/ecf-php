<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/companies", name="companies")
     */
    public function index(): Response
    {
        $companies = $this->entityManager->getRepository(Company::class)->findAll();
        $user = $this->getUser();

        return $this->render('company/index.html.twig', [
            'user'              => $user,
            'companies'         => $companies
        ]);
    }

    /**
     * @Route("/profile/company/add", name="add_company")
     */
    public function add(Request $request): Response
    {
        $company = new Company;
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();
            $company->setTown(strtoupper($company->getTown()));

            $this->entityManager->persist($company);
            $this->entityManager->flush();
            $this->addFlash('success', $company->getName() . ' a bien été ajouté à votre liste d\'entreprises.');
            return $this->redirectToRoute('companies');
        }

        return $this->render('company/add.html.twig', [
            'company_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/company/{id}/update", name="update_company")
     */
    public function update(Request $request, int $id): Response
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();
            $company->setTown(strtoupper($company->getTown()));

            $this->entityManager->persist($company);
            $this->entityManager->flush();
            $this->addFlash('success', $company->getName() . ' a bien été modifié dans votre liste d\'entreprises.');
            return $this->redirectToRoute('companies');
        }

        return $this->render('company/add.html.twig', [
            'company'           => $company,
            'company_form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/company/{id}/delete", name="delete_company")
     */
    public function delete(Request $request, int $id): Response
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);

        $this->entityManager->persist($company);
        $this->entityManager->remove($company);
        $this->entityManager->flush();
        $this->addFlash('success', 'L\'entreprise ' . $company->getName() . ' a bien été supprimée de votre liste.');
        return $this->redirectToRoute('companies');
    }
}
