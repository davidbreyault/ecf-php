<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class UploadController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/files", name="files")
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
        if (!is_null($user->getUpload())) {
            $upload = $this->entityManager->getRepository(Upload::class)->find($user->getUpload()->getId());
        } else {
            $upload = new Upload();
            $form = $this->createForm(UploadType::class, $upload);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('name')->getData();
                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = 'cv-'.strtolower(unaccent($user->getFirstname())).'-'.strtolower(unaccent($user->getLastname())).'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            $this->getParameter('upload_directory_cv'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'newFilename' property to store the PDF file name, instead of its contents
                    $upload->setName($newFilename);
                    $upload->setUser($user);
                }
                // ... persist the $upload variable or any other work
                $this->entityManager->persist($upload);
                $this->entityManager->flush();
                $this->addFlash('success', 'Merci ! Votre fichier a bien été pris en compte !');
                return $this->redirectToRoute('files');
            }
            return $this->render('upload/index.html.twig', [
                'user'          => $user,
                'upload_form'   => $form->createView(),
            ]);
        }
        
        return $this->render('upload/index.html.twig', [
            'user'          => $user,
            'upload'        => $upload,
        ]);
    }

    /**
     * @Route("/profile/file/{id}/delete", name="delete_file")
     */
    public function add(Request $request, int $id): Response
    {
        $upload = $this->entityManager->getRepository(Upload::class)-> find($id);
        $upload->setUser(NULL);
        // Suppression du fichier dans le dossier
        $fileName = $upload->getName();
        $filesystem = new Filesystem();
        $filesystem->remove(['uploads/cv/'.$fileName]);
        // Suppression du fichier dans la base de données
        $this->entityManager->remove($upload);
        $this->entityManager->flush();

        return $this->redirectToRoute('files');
    }
}
