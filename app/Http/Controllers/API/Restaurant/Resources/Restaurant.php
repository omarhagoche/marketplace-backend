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

            $this->getDeliveryPrices()
        );
    }



    private function getDeliveryPrices()
    {
        return [
            'delivery_fee' => $this->getDeliveryPriceByType($this->delivery_price_type),
        ];
    }

    private function getDeliveryPriceByType($delivery_price_type)
    {
        switch($delivery_price_type) {
            case "fixed":
                return $this->delivery_fee;
                break;
            case "distance":
                return RestaurantDistancePrice::collection($this->distancesPrices);
                break;
            default:
                return "No delivery price? that is improbable";
                break;
        }
    }

}
