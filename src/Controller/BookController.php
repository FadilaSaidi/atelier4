<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book')]
    public function index(BookRepository $repo): Response
    {
        // Récupérez la liste des livres publiés en utilisant le repository
        $publishedBooks = $repo->findBy(['published' => true]);
        
        // Récupérez la liste de tous les livres
        $allBooks = $repo->findAll();
        
        // Comptez le nombre de livres publiés et non publiés
        $numPublishedBooks = count($publishedBooks);
        $numUnpublishedBooks = count($allBooks) - $numPublishedBooks;
        
        // Vérifiez s'il n'y a aucun livre publié
        $noBooksFound = $numPublishedBooks === 0;
    
        return $this->render('book/index.html.twig', [
            'numUnpublishedBooks' => $numUnpublishedBooks,
            'numPublishedBooks' => $numPublishedBooks,
            'books'=>$allBooks,
            'noBooksFound' => $noBooksFound,
        ]);
    }
    
    //ajouter un livre a travers un formulaire 
    #[Route('/addbook', name: 'addbook')]
    public function addbook(Request $request, EntityManagerInterface $em): Response
    {
        // Créez une nouvelle instance de la classe Book et initialisez l'attribut "published" à true
        $book = new Book();
        $book->setPublished(true);
    
        // Créez un formulaire à partir de la classe BookType et associez-le à l'objet $book
        $form = $this->createForm(BookType::class, $book);
    
        // Traitez les données soumises dans la requête HTTP
        $form->handleRequest($request);
    
        if ($form->isSubmitted() ) {
            // Si le formulaire a été soumis et est valide :
    
            // Récupérez l'auteur associé au livre depuis l'entité Book
            $author = $book->getRelation();
    
            // Incrémentez l'attribut "nb_books" de l'entité Author s'il existe
            if ($author instanceof Author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }
    
            // Persistance de l'objet $book
            $em->persist($book);
    
            // Enregistrement des modifications en base de données
            $em->flush();
    
            // Redirigez l'utilisateur vers une autre route après l'ajout du livre (par exemple, 'app_book')
            return $this->redirectToRoute('book');
        }
    
        // Si le formulaire n'a pas encore été soumis ou n'est pas valide, affichez le formulaire à l'utilisateur
        return $this->renderForm('book/add.html.twig', [
            'f' => $form,
            'b' => $book
        ]);
    }
   
    #[Route('/editbook/{id}', name: 'editbook')]
    public function editbook(BookRepository $repo,$id,Request $req, ManagerRegistry $mgr ): Response
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $book=$repo->find($id);
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($req); //tejbed les données eli ktebthom f west l formulaire 
        if($form-> isSubmitted()){
            $em=$mgr->getManager();//fonction prédéfinie de managerregistry hya tamel ajout
            //tabaath l base tkollou hadher ligne  
            $em->flush();//tsajel fl base 
        }

        return $this->renderForm('book/add.html.twig', [
            'f' => $form, 'p'=>$book
        ]);
    }
    #[Route('/deletebook/{id}', name: 'deletebook')]
    public function deltebook(BookRepository $repo,$id, ManagerRegistry $mgr )
    {    //manger update et add et suprission il faut la mettre 
        //ajouter un objet 
        $book=$repo->find($id);
            $em=$mgr->getManager();
            $em->remove($book);
            $em->flush(); 
            return $this->redirectToRoute("book");//fonction prédéfinie de managerregistry hya tamel ajout
            
        }

    
        #[Route('/ShowBook/{id}', name: 'showbook')]

        public function showBook($id, BookRepository $repository)
        {
            $book = $repository->find($id);
            if (!$book) {
                return $this->redirectToRoute('book');
            }
    
            return $this->render('book/show.html.twig', ['b' => $book]);
    
    }



        
    }






