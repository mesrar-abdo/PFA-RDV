<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Doctor;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        AppAuthenticator $authenticator, 
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Hash the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // 2. Extract the chosen role from the form
            $selectedRole = $user->getRole(); 

            // 3. Logic based on user type
            if ($selectedRole === 'doctor') {
                // Assign Symfony security role
                $user->setRoles(['ROLE_DOCTOR']);
                
                // Initialize the Doctor profile linked to this User
                $doctor = new Doctor();
                $doctor->setUser($user);
                $doctor->setSpecialty('To be completed');
                $doctor->setOfficeAddress('To be completed');
                $doctor->setBiography('Welcome to my profile.');
                $entityManager->persist($doctor);

            } elseif ($selectedRole === 'admin') {
                // Admin gets management permissions
                $user->setRoles(['ROLE_ADMIN']);
                
            } else {
                // Default is Patient
                $user->setRoles(['ROLE_USER']);
                $user->setRole('patient'); // Ensure the string field is set
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // 4. Authenticate and redirect
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}