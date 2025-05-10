<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    #[Route('/{id}/toggle', name: 'transaction_toggle', methods: ['POST'])]
    public function toggle(Transaction $transaction, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['point'])) {
            $transaction->setPoint($data['point']);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false], 400);
    }

    #[Route(name: 'app_transaction_index', methods: ['GET'])]
public function index(Request $request, TransactionRepository $transactionRepository): Response
{
    $user = $this->getUser();
    $accountId = $request->query->get('accountId');

    $account = null; // Initialise la variable account

    if ($accountId) {
        $account = $this->accountRepository->find($accountId);
        
        // Vérifie que l'utilisateur a accès au compte
        if (!$account || $account->getUser() !== $user) {
            throw $this->createAccessDeniedException('Compte non autorisé.');
        }

        $transactions = $transactionRepository->findBy(['account' => $account]);
    } else {
        $transactions = [];
    }

    // Passe 'account' à la vue
    return $this->render('transaction/index.html.twig', [
        'transactions' => $transactions,
        'account' => $account, // Ajoute ici la variable account
    ]);
}

    #[Route('/transaction/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $transaction = new Transaction();

    // Récupérer le paramètre accountId de l'URL
    $accountId = $request->query->get('accountId');

    // Vérifier si accountId est présent
    if ($accountId) {
        $account = $this->accountRepository->find($accountId);

        // Si le compte n'existe pas ou si le compte n'appartient pas à l'utilisateur connecté, afficher une erreur
        if (!$account || $account->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Compte non autorisé.');
        }

        // Associer le compte à la transaction
        $transaction->setAccount($account);
    } else {
        // Si accountId n'est pas fourni, renvoyer une erreur
        throw $this->createNotFoundException('Account ID not provided');
    }

    // Initialiser les dates
    $now = new \DateTimeImmutable();
    $transaction->setCreatedAt($now);
    $transaction->setUpdatedAt(new \DateTime());

    // Créer le formulaire
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer la transaction dans la base de données
        $entityManager->persist($transaction);
        $entityManager->flush();

        // Rediriger vers la liste des transactions
        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }

    // Passer la variable `account` à la vue
    return $this->render('transaction/new.html.twig', [
        'transaction' => $transaction,
        'form' => $form,
        'account' => $account, // Ajoute cette ligne pour passer l'account à la vue
    ]);
}


    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transaction->getId(), $request->get('token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
