<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Form\ApplicationType;
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

        return $this->render('member/index.html.twig', [
            'user'              => $user,
            'profiles'          => $profiles
        ]);
    }

    /**
     * @Route("/profile/members/search", name="search_members")
     */
    public function search(Request $request)
    {
        $searchMemberForm = $this->createForm(SearchMemberType::class);
        $profiles = null;
        $searchMemberForm->handleRequest($request);

        if ($searchMemberForm->isSubmitted() && $searchMemberForm->isValid()) {
            $settings = $searchMemberForm->getData();
            $profiles = $this->entityManager->getRepository(User::class)->searchProfile($settings);
        }

        return $this->render('member/search.html.twig', [
            'search_member_form'       => $searchMemberForm->createView(),
            'profiles'                 => $profiles
        ]);
    }

    /**
     * @Route("/profile/member/{id}", name="card_member")
     */
    public function card(Request $request, int $id)
    {
        $user = $this->getUser();
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        $skills = $profile->getSkill()->toArray();
        $experiences = $profile->getExperience()->toArray();

        return $this->render('member/card.html.twig', [
            'user'              => $user,
            'profile'           => $profile,
            'skills'            => $skills,
            'experiences'       => $experiences
        ]);
    }

    /**
     * @Route("/profile/member/{id}/experience/add", name="add_experience_member")
     */
    public function add_experience(Request $request, int $id): Response
    {
        $experience = new Experience;
        $profile = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();

            $profile->addExperience($experience);
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mission a bien été ajoutée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/experience/add.html.twig', [
            'profile'           => $profile,
            'experience_form'   => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/update", name="update_data_member")
     */
    public function update(Request $request, int $id): Response
    {
        $profile = $this->entityManager->getRepository(User::class)->find($id);
        $user = $this->getUser();

        $form = $this->createForm(ApplicationType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile = $form->getData();

            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            $this->addFlash('success', 'La disponibilité de ' . $profile->getFirstname() . ' a bien été mise à jour.');
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/update.html.twig', [
            'user'                  => $user,
            'profile'               => $profile,
            'availibility_form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/experience/update", name="update_experience_member")
     */
    public function update_experience(Request $request, int $id): Response
    {
        $experience = $this->entityManager->getRepository(Experience::class)->find($id);
        $profile_id = $experience->getUser()->getId();
        $profile = $this->entityManager->getRepository(User::class)->find($profile_id);

        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();

            $profile->addExperience($experience);
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mission a bien été modifiée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/experience/update.html.twig', [
            'profile'           => $profile,
            'experience_form'   => $form->createView()
        ]);
    }
}
