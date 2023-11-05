<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/listeauthor', name: 'findall')]
    public function liste(AuthorRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $list,
        ]);
    }
    //d'une facon statique 
    #[Route('/add-static-author', name: 'add_static_author')]
    public function addStaticAuthor(ManagerRegistry $Manager): Response
    {
        // Créez un auteur avec des données statiques
        $author = new Author();
        $author->setUsername('Auteur Statique');
        $author->setEmail('auteur.statique@example.com');

      $em=$Manager->getManager();
        // Persistez l'auteur dans la base de données
        //executer les requtes insert delete update 
        $em->persist($author);//créer la requéte 
        $em->flush(); //réelement exécuter la requéte 
            return new Response ('author added');
       // return $this->redirectToRoute('app_Affiche');
    }
    //ajouter al'aide d'un formulaire 
    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($req); //tejbed les données eli ktebthom f west l formulaire 
        if($form-> isSubmitted()){
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            $em->persist($author); //tabaath l base tkollou hadher ligne  
            $em->flush();//tsajel fl base 
            return $this->redirectToRoute('findall'); 
        }

        return $this->renderForm('author/add.html.twig', [
            'f' => $form, 'a'=>$author
        ]);
    }
    //modifier les donnes d'un auteur 
    #[Route('/editauthor/{id}', name: 'editauthor')]
   
    public function editproduit(AuthorRepository $repo,$id,Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $author=$repo->find($id);
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($req); //tejbed les données eli ktebthom f west l formulaire 
        if($form-> isSubmitted()){
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            //tabaath l base tkollou hadher ligne  
            $em->flush();//tsajel fl base 
        }

        return $this->renderForm('author/add.html.twig', [
            'f' => $form, 'p'=>$author
        ]);
    }
    //supprimer un auteur 
    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
   
    public function deleteauthor(AuthorRepository $repo,$id,Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $author=$repo->find($id);
        
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            //tabaath l base tkollou hadher ligne  
            //tsajel fl base 
            $em->remove($author);
            $em->flush(); 
            return $this->redirectToRoute("findall");
        }


}
