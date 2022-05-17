<?php

namespace App\Http\Controllers\Operations;

use App\DataTables\Operations\AdvertisementDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use Illuminate\Http\Request;
use Flash;
use App\Models\Advertisement;
use App\Models\AdvertisementCompany;
use App\Repositories\AdvertisementCompanyRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Prettus\Validator\Exceptions\ValidatorException;


class AdvertisementController extends Controller
{
        /** @var  AdvertisementRepository */
        private $advertismentRepository ;
          /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
 /**
     * @var UploadRepository
     */
    private $uploadRepository;
        /**
     * @var AdvertisementCompanyRepository
     */
    private $advertisementcompanyRepository;
        public function __construct(AdvertisementRepository $advertismentRepo , CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo,
        AdvertisementCompanyRepository $advertisementcompanyRepo )
        {
            parent::__construct();
            $this->advertismentRepository = $advertismentRepo;
            $this->customFieldRepository = $customFieldRepo;
            $this->uploadRepository = $uploadRepo;
            $this->advertisementcompanyRepository = $advertisementcompanyRepo;
          
        }

    public function index(AdvertisementDataTable $advertisementDataTable)
    {
        return $advertisementDataTable->render('operations.advertisement.index');
    }
    public function create()
    {
      
        $adv_Company = $this->advertisementcompanyRepository->pluck('name', 'id');
        $adv_CompanySelected = [];
   
        $hasCustomField = in_array($this->advertismentRepository->model(), setting('custom_field_models', []));
       
        if ($hasCustomField) {
         
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertismentRepositoryeloquent->model());
            $html = generateCustomField($customFields);
        }
        return view('operations.advertisement.create')->with("customFields", isset($html) ? $html : false)->with("adv_Company", $adv_Company)->with("adv_CompanySelected", $adv_CompanySelected);

    }
     /**
     * Store a newly created Category in storage.
     *
     * @param CreateAdvertisementRequest $request
     *
     * @return FacadesResponse
     */
    public function store(CreateAdvertisementRequest $request)
    {
      
        $input = $request->all();
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertismentRepository->model());
        try {
            $adv = $this->advertismentRepository->create($input);
            $adv->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($adv, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.advertisement')]));

        return redirect(route('operations.advertisement.index'));
    }
     /**
     * Show the form for editing the specified Restaurant.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {

        $advertisement = $this->advertismentRepository->findorFail($id);
        $adv_Company = $this->advertisementcompanyRepository->pluck('name', 'id');
        $adv_CompanySelected  = DB::table('advertisement')->where('id', $id)->value('advertisement_company_id');
    
   
        
  
        if (empty($advertisement)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.advertisement')]));
            return redirect(route('operations.advertisement.index'));
        }
        $customFieldsValues = $advertisement->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertismentRepository->model());
        $hasCustomField = in_array($this->advertismentRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('operations.advertisement.edit2')
        ->with('advertisement', $advertisement)
        ->with('adv_Company', $adv_Company)
        ->with('adv_CompanySelected', $adv_CompanySelected)
        ->with("customFields", isset($html) ? $html : false);
    }
     /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateAdvertisementRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdvertisementRequest $request)
    {
        $advertisement = $this->advertismentRepository->findorFail($id);

        if (empty($advertisement)) {
            Flash::error('Advertisment not found');
            return redirect(route('operations.advertisement.index'));
        }
        $input = $request->all();
      
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertismentRepository->model());
        try {
            $advertisement = $this->advertismentRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                return ('a');
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($advertisement, 'image');
            
            }
        
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $advertisement->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.advertisement')]));

        return redirect(route('operations.advertisement.index'));
    }
    public function destroy($id)
    {
        $advertisement = $this->advertismentRepository->find($id);

        if (empty($advertisement)) {
            Flash::error('advertisement not found');

            return redirect(route('operations.advertisement.index'));
        }

        $this->advertismentRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.advertisement')]));

        return redirect(route('operations.advertisement.index'));
    }

}
