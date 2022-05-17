<?php

namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

use App\Models\AdvertisementCompany;
use App\Validators\AdvertisementcompanyValidator;

/**
 * Class AdvertisementRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method AdvertisementCompany findWithoutFail($id, $columns = ['*'])
 * @method AdvertisementCompany find($id, $columns = ['*'])
 * @method AdvertisementCompany first($columns = ['*'])
 */
class AdvertisementCompanyRepository extends BaseRepository 
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AdvertisementCompany::class;
    }

    

   
}
