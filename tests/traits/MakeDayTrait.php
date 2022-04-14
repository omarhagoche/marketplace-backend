<?php

use Faker\Factory as Faker;
use App\Models\Day;
use App\Repositories\DayRepository;

trait MakeDayTrait
{
    /**
     * Create fake instance of Day and save it in database
     *
     * @param array $dayFields
     * @return Day
     */
    public function makeDay($dayFields = [])
    {
        /** @var DayRepository $dayRepo */
        $dayRepo = App::make(DayRepository::class);
        $theme = $this->fakeDayData($dayFields);
        return $dayRepo->create($theme);
    }

    /**
     * Get fake instance of Day
     *
     * @param array $dayFields
     * @return Day
     */
    public function fakeDay($dayFields = [])
    {
        return new Day($this->fakeDayData($dayFields));
    }

    /**
     * Get fake data of Day
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDayData($dayFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $dayFields);
    }
}
