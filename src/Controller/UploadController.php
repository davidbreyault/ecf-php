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
                    $newFilename = 'cv-'.strtolower($user->getFirstname()).'-'.strtolower($user->getLastname()).'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'brochureFilename' property to store the PDF file name, instead of its contents
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
        
        $this->entityManager->persist($upload);
        $this->entityManager->remove($upload);
        $this->entityManager->flush();

        return $this->redirectToRoute('files');
    }
}
