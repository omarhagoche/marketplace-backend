<?php

namespace App\Http\Controllers\API\Restaurant\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Restaurant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            $this->getDistance(),
            $this->getDeliveryPrices()
        );
    }

    private function getDistance()
    {
        if (request()->has(['myLon', 'myLat'])) {
            return  ['distance' =>  app('distance')->getDistance(request()->myLat, request()->myLon, $this->latitude, $this->longitude)];
        }
        return [];
    }


    private function getDeliveryPrices()
    {
        return [
            'delivery_fee' => $this->getDeliveryPriceByType($this->delivery_price_type),
        ];
    }

    private function getDeliveryPriceByType($delivery_price_type)
    {
        switch ($delivery_price_type) {
            case "fixed":
                return $this->delivery_fee;
            case "flexible":
                return 'flexible';
            case "distance":
                return RestaurantDistancePrice::collection($this->distancesPrices);
            default:
                return "No delivery price? that is improbable";
        }
    }
}
