<?php

namespace App\Http\Controllers\Operations;

use App\DataTables\Operations\AdvertisementCompanyDataTable;
use App\DataTables\Operations\AdvertisementDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdvertisementCompanyRequest;
use App\Http\Requests\CreateAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementCompanyRequest;
use Illuminate\Http\Request;
use Flash;
use App\Models\Advertisement;
use App\Models\AdvertisementCompany;
use App\Repositories\AdvertisementCompanyRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Prettus\Validator\Exceptions\ValidatorException;

class AdvertisementCompanyController extends Controller
{
    private $userRepository;
/**
* @var UploadRepository
*/
  /** @var  AdvertisementCompanyRepository */
  private $advertisementcompanyRepository ;
  /**
* @var CustomFieldRepository
*/
private $customFieldRepository;
/**
* @var UploadRepository
*/

private $uploadRepository;


public function __construct( AdvertisementRepository $advertismentRepo , CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo,
AdvertisementCompanyRepository $advertisementcompanyRepo ,UserRepository $userRepo )
{
    parent::__construct();
    $this->advertismentRepository = $advertismentRepo;
    $this->customFieldRepository = $customFieldRepo;
    $this->uploadRepository = $uploadRepo;
    $this->advertisementcompanyRepository = $advertisementcompanyRepo;
    $this->userRepository = $userRepo;

  
}
    public function index(AdvertisementCompanyDataTable $advertisementCompanyDataTable)
    {
     
        return $advertisementCompanyDataTable->render('operations.advertisement.advertisement_company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $manager_user_id = $this->userRepository->pluck('name', 'id');
        $manager_user_id_Selected = [];
        $hasCustomField = in_array($this->advertisementcompanyRepository->model(), setting('custom_field_models', []));
       
        if ($hasCustomField) {
         
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertismentRepositoryeloquent->model());
            $html = generateCustomField($customFields);
        }
          
            return view('operations.advertisement.advertisement_company.create')
            ->with("customFields", isset($html) ? $html : false)->with("manager_user_id", $manager_user_id)->with("manager_user_id_Selected", $manager_user_id_Selected);
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAdvertisementCompanyRequest $request)
    {
      
        $input = $request->all();
   
      
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertisementcompanyRepository->model());
        try {
            $adv = $this->advertisementcompanyRepository->create($input);
            $adv->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['logo']) && $input['logo']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['logo']);
                $mediaItem = $cacheUpload->getMedia('logo')->first();
                $mediaItem->copy($adv, 'logo');
           
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.advertisement_company')]));

        return redirect(route('operations.advertisement_company.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advertisement_Company = $this->advertisementcompanyRepository->findorFail($id);
        $manager_user_id = $this->userRepository->pluck('name', 'id');
        $manager_user_id_Selected  = DB::table('advertisement_company')->where('id', $id)->value('manager_user_id');
    
   
        
  
        if (empty($advertisement_Company)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.advertisement_company')]));
            return redirect(route('operations.advertisement_company.index'));
        }
        $customFieldsValues = $advertisement_Company->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertisementcompanyRepository->model());
        $hasCustomField = in_array($this->advertisementcompanyRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('operations.advertisement.advertisement_company.edit')
        ->with('advertisement_Company', $advertisement_Company)
        ->with('manager_user_id', $manager_user_id)
        ->with('manager_user_id_Selected', $manager_user_id_Selected)
        ->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( $id,UpdateAdvertisementCompanyRequest $request)
    {
        $advertisement_Company = $this->advertisementcompanyRepository->findorFail($id);

        if (empty($advertisement_Company)) {
            Flash::error('Advertisment Company not found');
            return redirect(route('operations.advertisement_company.index'));
        }
        $input = $request->all();
      
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->advertisementcompanyRepository->model());
        try {
            $advertisement_Company = $this->advertisementcompanyRepository->update($input, $id);

            if (isset($input['logo']) && $input['logo']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['logo']);
                $mediaItem = $cacheUpload->getMedia('logo')->first();
                $mediaItem->copy($advertisement_Company, 'logo');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $advertisement->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.advertisement_company')]));

        return redirect(route('operations.advertisement_company.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advertisement_Company = $this->advertisementcompanyRepository->find($id);

        if (empty($advertisement_Company)) {
            Flash::error('Advertisement Company not found');

            return redirect(route('operations.advertisement_company.index'));
        }

        $this->advertisementcompanyRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.advertisement_company')]));

        return redirect(route('operations.advertisement_company.index'));
    }
}
