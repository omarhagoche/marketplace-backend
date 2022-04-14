<?php

namespace App\Http\Controllers\API;


use App\Models\Day;
use App\Repositories\DayRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class DayController
 * @package App\Http\Controllers\API
 */

class DayAPIController extends Controller
{
    /** @var  DayRepository */
    private $dayRepository;

    public function __construct(DayRepository $dayRepo)
    {
        $this->dayRepository = $dayRepo;
    }

    /**
     * Display a listing of the Day.
     * GET|HEAD /days
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->dayRepository->pushCriteria(new RequestCriteria($request));
            $this->dayRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $days = $this->dayRepository->all();

        return $this->sendResponse($days->toArray(), 'Days retrieved successfully');
    }

    /**
     * Display the specified Day.
     * GET|HEAD /days/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Day $day */
        if (!empty($this->dayRepository)) {
            $day = $this->dayRepository->findWithoutFail($id);
        }

        if (empty($day)) {
            return $this->sendError('Day not found');
        }

        return $this->sendResponse($day->toArray(), 'Day retrieved successfully');
    }
}
