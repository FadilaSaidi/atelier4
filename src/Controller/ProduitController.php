<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $list,
        ]);
    }
    //fonction ajouter a l'aide de formulaire 
    #[Route('/addproduit', name: 'app_addproduit')]
    public function addproduit(Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $produit=new Produit();
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($req); //tejbed les données eli ktebthom f west l formulaire 
        if($form-> isSubmitted()){
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            $em->persist($produit); //tabaath l base tkollou hadher ligne  
            $em->flush();//tsajel fl base 
        }

        return $this->renderForm('produit/add.html.twig', [
            'f' => $form, 'p'=>$produit
        ]);
    }
    //modifier 
    #[Route('/editproduit/{id}', name: 'app_editproduit')]
    public function editproduit(ProduitRepository $repo,$id,Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $produit=$repo->find($id);
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($req); //tejbed les données eli ktebthom f west l formulaire 
        if($form-> isSubmitted()){
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            //tabaath l base tkollou hadher ligne  
            $em->flush();//tsajel fl base 
        }

        return $this->renderForm('produit/add.html.twig', [
            'f' => $form, 'p'=>$produit
        ]);
    }
    //remove produit 
    #[Route('/deleteproduit/{id}', name: 'app_deleteproduit')]
    public function removeproduit($id,ProduitRepository $repo , ManagerRegistry $mgr )
    {    //manger update et add et suprission il faut la mettre 
        
        $produit=$repo->find($id);
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            $em->remove($produit);
            $em->flush(); 
            return $this->redirectToRoute("app_produit");
        

      
    }


}
