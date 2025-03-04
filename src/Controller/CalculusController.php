<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculusController extends AbstractController
{
    #[Route(path: '/calc/{ope}/{nb1}/{nb2}', name: 'app_home_calc')]
    public function calc(string $ope, mixed $nb1, mixed $nb2): Response
    {
        $total = "";
        if (is_numeric($nb1) && is_numeric($nb2)) {
            $total = match ($ope) {
                'add' => "The additon of $nb1 & $nb2 gives " . $nb1 + $nb2,
                'sub' => "The substraction of $nb2 to $nb1 gives " . $nb1 - $nb2,
                'prod' => "The product of $nb1 & $nb2 gives " . $nb1 * $nb2,
                'div' => $nb2 > 0 ? "The divison of $nb1 by $nb2 gives " . $nb1 / $nb2 : "You can't divide by zero!",
            };
        } else {
            $total = "One of the parameters is not a number.";
        }
        return $this->render(
            'calc.html.twig',
            [
                'nb1' => $nb1,
                'nb2' => $nb2,
                'ope' => $ope,
                'total' => $total
            ]
        );
    }
}