<?php

namespace App\Services;

use App\Models\Distance;
use GuzzleHttp\Client;
use Log;
use Exception;

use Morrislaptop\Firestore\Factory;
use Kreait\Firebase\ServiceAccount;


class FirestoreService
{

    /**
     * The instance of the realtime database
     * 
     * @var Google\Cloud\Firestore\FirestoreClient
     */
    protected $firestore;


    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(base_path(config('firebase.projects.app.credentials.file')));

        $this->firestore = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->createFirestore();
    }

    public function getFirestore()
    {
        return $this->firestore;
    }
}
