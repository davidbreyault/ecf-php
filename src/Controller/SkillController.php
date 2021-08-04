<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Category;
use App\Form\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SkillController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/skills", name="skills")
     * 
     * @IsGranted(ROLE_EMPLOYEE)
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
     * 
     * @IsGranted(ROLE_EMPLOYEE)
     */
    public function add_skill(Request $request): Response
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
     * 
     * @IsGranted(ROLE_EMPLOYEE)
     */
    public function delete_skill($id): Response
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($id);

        $this->entityManager->persist($skill);
        $this->entityManager->remove($skill);
        $this->entityManager->flush();
        return $this->redirectToRoute('skills');

        return $this->render('profile/skill/delete.html.twig');
    }

    /**
     * @Route("profile/skill/update/{id}", name="update_skill")
     * 
     */
    public function update_skill(Request $request, $id): Response
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
}
