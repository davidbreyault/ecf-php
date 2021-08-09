<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\SearchMemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/profile/members/search", name="search_members")
     */
    // public function search_members(Request $request, UserRepository $userRepository)
    // {
    //     $searchMemberForm = $this->createForm(SearchMemberType::class);

    //     $searchMemberForm->handleRequest($request);

    //     if ($searchMemberForm->isSubmitted() && $searchMemberForm->isValid()) {
    //         $settings = $searchMemberForm->getData();
    //         //dd($settings);
    //         $profiles = $userRepository->searchProfile($settings);
    //         //dd($profiles);
    //     }

    //     return $this->render('member/search.html.twig', [
    //         'search_member_form'       => $searchMemberForm->createView()
    //     ]);
    // }
    public function search_members(Request $request)
    {
        $searchMemberForm = $this->createForm(SearchMemberType::class);

        $searchMemberForm->handleRequest($request);

        if ($searchMemberForm->isSubmitted() && $searchMemberForm->isValid()) {
            $settings = $searchMemberForm->getData();
            //dd($settings);
            $profiles = $this->entityManager->getRepository(User::class)->searchProfile($settings);
            dd($profiles);
        }
            

        return $this->render('member/search.html.twig', [
            'search_member_form'       => $searchMemberForm->createView()
        ]);
    }
}
