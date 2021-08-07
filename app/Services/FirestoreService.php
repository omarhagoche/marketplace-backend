<?php

namespace App\Services;

use App\Models\Distance;
use GuzzleHttp\Client;
use Log;
use Exception;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Google\Cloud\Firestore\FirestoreClient;



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
        $this->firestore = new FirestoreClient([
            'projectId' => config('firebase.projects.app.project_id'),
        ]);
    }

    public function getFirestore()
    {
        return $this->firestore;
    }
}
