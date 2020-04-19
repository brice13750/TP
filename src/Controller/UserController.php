<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Password;
use App\Form\UserEditType;
use App\Form\PasswordEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function register(\Swift_Mailer $mailer, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        
         // Encodage du mot de passe
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        //role user par défaut
        $roles = array('ROLE_USER');
        $user->setRoles($roles);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

            $email = $user->getEmail();
            

            $message = (new \Swift_Message($email))
            ->setFrom('runmap@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'user/contact.html.twig',[
                    'email' => $email,
                    'user' => $user
                    ]),
                    'text/html'
                );

            $mailer->send($message);

        $this->addFlash('success', 'utilisateur crée');
        return $this->redirectToRoute('app_login');
    }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
     * @IsGranted("ROLE_USER")
     */
    public function profil()
    {
        $user = $this->getUser();
        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/profil", name="edit_profil")
     * @IsGranted("ROLE_USER")
     */
    public function edit_profil(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('warning', 'utilisateur modifié');
            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user/edit_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/password", name="edit_password")
     * @param Request $request
     * @return Response
     */
    public function edit_password(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $newPassword = new Password();

        $user = $this->getUser();
        $form = $this->createForm(PasswordEditType::class, $newPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // https://www.php.net/manual/fr/function.password-verify.php
            if  (!password_verify($newPassword->getOldPassword(), $user->getPassword())){
                $form->get('oldPassword')->addError(new FormError('Le mot de passe que vous avez tapé n\'est pas votre mot de passe actuel'));
            }
            else{
            $new = $newPassword->getNewPassword();
            $hash = $encoder->encodePassword($user, $new);

            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('warning', 'Mot de passe modifié');
            return $this->redirectToRoute('user_profil');
            }
        }
        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);

}
}
