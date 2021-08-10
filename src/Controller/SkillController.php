<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Skill;
use App\Form\UserType;
use App\Form\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SkillController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/skills", name="skills")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $skills = $user->getSkill()->toArray();

        return $this->render('profile/skill/index.html.twig', [
            'user'              => $user,
            'skills'            => $skills
        ]);
    }

    /**
     * @Route("/profile/skill/add", name="add_skill")
     */
    public function add(Request $request): Response
    {
        $skill = new Skill;
        $user = $this->getUser();

        $form = $this->createForm(SkillType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill = $form->getData();
            // Ajout des données sur la table intermédiaire 'user_skill'
            $user->addSkill($skill);
            $this->entityManager->persist($skill);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compétence a bien été prise en compte.');
            return $this->redirectToRoute('skills');
        }

        return $this->render('profile/skill/add.html.twig', [
            'user'              => $user,
            'skill_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("profile/skill/delete/{id}", name="delete_skill")
     */
    public function delete($id): Response
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($id);

        $this->entityManager->persist($skill);
        $this->entityManager->remove($skill);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre compétence a bien été suprimée.');
        return $this->redirectToRoute('skills');
    }

    /**
     * @Route("profile/skill/update/{id}", name="update_skill")
     * 
     */
    public function update(Request $request, $id): Response
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($id);
        $user = $this->getUser();

        $form = $this->createForm(SkillType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill = $form->getData();
            // Ajout des données sur la table intermédiaire 'user_skill'
            $user->addSkill($skill);
            $this->entityManager->persist($skill);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compétence a bien été modifiée');
            return $this->redirectToRoute('skills');
        }

        return $this->render('profile/skill/update.html.twig', [
            'user'              => $user,
            'skill_form'        => $form->createView()
        ]);
    }







    /**
     * @Route("/profile/member/{id}/skill/add", name="add_skill_member")
     */
    public function add_skill(Request $request, int $id): Response
    {
        $skill = new Skill;
        $profile = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(SkillType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill = $form->getData();

            $profile->addSkill($skill);
            $this->entityManager->persist($skill);
            $this->entityManager->flush();
            $this->addFlash('success', 'La compétence a bien été ajoutée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/skill/add.html.twig', [
            'profile'           => $profile,
            'skill_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/skill/update", name="update_skill_member")
     */
    public function update_skill(Request $request, int $id): Response
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($id);
        $profile_id = $skill->getUsers()[0]->getId();
        $profile = $this->entityManager->getRepository(User::class)->find($profile_id);

        $form = $this->createForm(SkillType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill = $form->getData();

            $profile->addSkill($skill);
            $this->entityManager->persist($skill);
            $this->entityManager->flush();
            $this->addFlash('success', 'La compétence a bien été modifiée sur le profil de ' . $profile->getFirstname());
            return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
        }

        return $this->render('member/skill/add.html.twig', [
            'skill'             => $skill,
            'profile'           => $profile,
            'skill_form'        => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/member/{id}/skill/delete", name="delete_skill_member")
     */
    public function delete_skill(Request $request, int $id): Response
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($id);
        $profile_id = $skill->getUsers()[0]->getId();
        $profile = $this->entityManager->getRepository(User::class)->find($profile_id);

        $profile->removeSkill($skill);
        $this->entityManager->persist($skill);
        $this->entityManager->remove($skill);
        $this->entityManager->flush();
        $this->addFlash('success', 'La compétence de '. $profile->getFirstname() .' a bien été supprimée.');
        return $this->redirectToRoute('card_member', ['id' => $profile->getId()]);
    }
}
