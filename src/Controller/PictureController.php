<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class PictureController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/picture", name="picture")
     */
    public function index(Request $request): Response
    {
        /**
         *  
         * @param string $str
         * 
         * @return string unaccented string
         * 
         */
        function unaccent($str)
        {
            $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
            $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
            $str = str_replace($search, $replace, $str);
            return $str;
        }
        $user = $this->getUser();
        if (!is_null($user->getPicture())) {
            $picture = $this->entityManager->getRepository(Picture::class)->find($user->getPicture()->getId());
        } else {
            $picture = new Picture();
            $form = $this->createForm(PictureType::class, $picture);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('name')->getData();
                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = 'img-'.strtolower(unaccent($user->getFirstname())).'-'.strtolower(unaccent($user->getLastname())).'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            $this->getParameter('upload_directory_pictures'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'newFilename' property to store the PDF file name, instead of its contents
                    $picture->setName($newFilename);
                    $picture->setUser($user);
                }
                // ... persist the $upload variable or any other work
                $this->entityManager->persist($picture);
                $this->entityManager->flush();
                $this->addFlash('success', 'Merci ! Votre fichier a bien été pris en compte !');
                return $this->redirectToRoute('profile');
            }
            return $this->render('picture/index.html.twig', [
                'user'          => $user,
                'upload_form'   => $form->createView(),
            ]);
        }
        
        return $this->render('picture/index.html.twig', [
            'user'           => $user,
            'picture'        => $picture,
        ]);
    }

    /**
     * @Route("/profile/picture/{id}/delete", name="delete_picture")
     * 
     * Supprime la photo de profil de l'utilsateur
     */
    public function delete(Request $request, int $id): Response
    {
        $picture = $this->entityManager->getRepository(Picture::class)->find($id);
        $picture_user = $picture->getUser();
        $picture->setUser(NULL);

        // Suppression du fichier dans le dossier
        $fileName = $picture->getName();
        $filesystem = new Filesystem();
        $filesystem->remove(['uploads/pictures/'.$fileName]);
        // Suppression du fichier dans la base de données
        $this->entityManager->remove($picture);
        // Si le propriétaire du document et l'utilisateur connecté correspondent à la même personne
        $picture_user === $this->getUser()
            ? $this->addFlash('success', 'Votre photo de profil a bien été supprimé.') 
            : $this->addFlash('success', 'La photo de ' . $picture_user->getFirstname() . ' a bien été supprimée.');
        $this->entityManager->flush();

        return $picture_user === $this->getUser() 
            ? $this->redirectToRoute('profile') 
            : $this->redirectToRoute('card_member', ['id' => $picture_user->getId()]);
    }
}
