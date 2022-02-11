<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\NoteRequest;

class NoteController extends Controller
{
    /** @var  noteRepository */
    private $noteRepository;
    public function __construct(NoteRepository $noteRepo, FoodOrderRepository $foodOrderRepository, CartRepository $cartRepo, PaymentRepository $paymentRepo, NotificationRepository $notificationRepo, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepo;

    }
    public function store(NoteRequest $request)
    {
        try {
            $this->noteRepository->create($request->all());
            return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
    }
}
