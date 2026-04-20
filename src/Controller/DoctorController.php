<?php

namespace App\Controller;

// Form and Entity imports
use App\Form\DoctorType;
use App\Entity\Doctor;

// Symfony/Doctrine tools
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DoctorController extends AbstractController
{
    // src/Controller/DoctorController.php

#[Route('/doctor', name: 'app_doctor_dashboard')]
#[IsGranted('ROLE_DOCTOR')] // Security check
public function index(): Response
{
    $user = $this->getUser();
    $doctorProfile = $user->getSpecialty(); // This is the OneToOne relation

    return $this->render('doctor/index.html.twig', [
        'doctor' => $doctorProfile,
    ]);
}

#[Route('/doctor/edit', name: 'app_doctor_edit')]
#[IsGranted('ROLE_DOCTOR')]
public function edit(Request $request, EntityManagerInterface $em): Response
{
    $doctor = $this->getUser()->getSpecialty();
    $form = $this->createForm(DoctorType::class, $doctor);
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Profile updated successfully!');
        return $this->redirectToRoute('app_doctor_dashboard');
    }

    return $this->render('doctor/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
