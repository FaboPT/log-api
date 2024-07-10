<?php

namespace App\Controller;

use App\Repository\LogEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LogController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/count', name: 'app_count', methods: ['GET'])]
    public function count(Request $request, LogEntryRepository $entryRepository): JsonResponse
    {
        $criteria = [];

        if ($serviceName = $request->query->get('serviceName')) {
            $criteria['serviceName'] = $serviceName;
        }

        if ($statusCode = $request->query->get('statusCode')) {
            $criteria['statusCode'] = $statusCode;
        }

        if ($startDate = $request->query->get('startDate')) {
            $criteria['timestamp']['$gte'] = new \DateTime($startDate);
        }

        if ($endDate = $request->query->get('endDate')) {
            $criteria['timestamp']['$lte'] = new \DateTime($endDate);
        }

        $count = $entryRepository->countByCriteria($criteria);

        return new JsonResponse(['count' => $count]);
    }
}
