<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("profile/application", name="application")
     */
    public function index(Request $request): Response
    {
        function unaccent($str)
        {
            $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
            $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
            $str = str_replace($search, $replace, $str);
            return $str;
        }
        $user = $this->getUser();
        $form = $this->createForm(ApplicationType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // Transformation du nom de et de la ville de l'utilisateur en majuscule
            $user->setLastname(strtoupper(unaccent($user->getLastname())));
            $user->setTown(strtoupper(unaccent($user->getTown())));
            $user->setUpdatedAt(new \DateTimeImmutable());
            // Transfert en base de donnée
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Merci ! Votre candidature a bien été prise en compte !');
            return $this->redirectToRoute('profile');
        }

        return $this->render('application/index.html.twig', [
            'application_form' => $form->createView()
        ]);
    }
}
