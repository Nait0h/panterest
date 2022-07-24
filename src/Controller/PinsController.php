<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt'=>'DESC']);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin): Response
    {

        return $this->render('pins/show.html.twig', compact('pin')); //grâce au bundle sensio/framework-extra-bundle sinon on aura utilisé un repository avec la méthode find($id)
    }

     /**
     * @Route("/pins/edit/{id<[0-9]+>}", name="app_pins_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {
        
        $form = $this->createForm(PinType::class, $pin);
        
        
        $form->handleRequest($request); //récupérer les données du formulaire via $request
        
        if ($form->isSubmitted() && $form->isValid()) {
            
         $em->persist($pin); // on n'a plus besoin de faire un $form->getData() car l'objet passé au createFormBuilder est récupéré bel et bien et a été mis à jour par les données entrées au niveau du formulaire
         $em->flush();
 
         return $this->redirectToRoute('app_pins_show',['id' => $pin->getId()]);
 
        }
       
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'monFormulaire' => $form->createView()
           ]);
    }

    /**
     * @Route("/pins/delete/{id<[0-9]+>}", name="app_pins_delete", methods={"GET", "POST", "DELETE"})
     */

     public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
     {
      
        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('anarana_csrf_token')))
        {
            $em->remove($pin);
            $em->flush();
        }
                
        return $this->redirectToRoute('app_home');

     }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em) :Response
    {
        $pin = new Pin;
        
        $form = $this->createForm(PinType::class, $pin);
     
        $form->handleRequest($request); //récupérer les données du formulaire via $request
 
        if ($form->isSubmitted() && $form->isValid()) {
         
         $em->persist($pin); // on n'a plus besoin de faire un $form->getData() car l'objet passé au createFormBuilder est récupéré bel et bien et a été mis à jour par les données entrées au niveau du formulaire
         $em->flush();
 
         return $this->redirectToRoute('app_pins_show',['id' => $pin->getId()]);
 
        }
 
        return $this->render('pins/create.html.twig', [
         'monFormulaire' => $form->createView()
        ]);
    }
}
