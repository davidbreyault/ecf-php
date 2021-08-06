<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/profile/members", name="members")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $profiles = $this->entityManager->getRepository(User::class)->findAll();
        //dd($profiles);

        return $this->render('member/index.html.twig', [
            'user'              => $user,
            'profiles'          => $profiles
        ]);
    }
}
