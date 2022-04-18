<?php

use App\Models\Day;
use App\Repositories\DayRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DayRepositoryTest extends TestCase
{
    use MakeDayTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DayRepository
     */
    protected $dayRepo;

    public function setUp()
    {
        parent::setUp();
        $this->dayRepo = App::make(DayRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDay()
    {
        $day = $this->fakeDayData();
        $createdDay = $this->dayRepo->create($day);
        $createdDay = $createdDay->toArray();
        $this->assertArrayHasKey('id', $createdDay);
        $this->assertNotNull($createdDay['id'], 'Created Day must have id specified');
        $this->assertNotNull(Day::find($createdDay['id']), 'Day with given id must be in DB');
        $this->assertModelData($day, $createdDay);
    }

    /**
     * @test read
     */
    public function testReadDay()
    {
        $day = $this->makeDay();
        $dbDay = $this->dayRepo->find($day->id);
        $dbDay = $dbDay->toArray();
        $this->assertModelData($day->toArray(), $dbDay);
    }

    /**
     * @test update
     */
    public function testUpdateDay()
    {
        $day = $this->makeDay();
        $fakeDay = $this->fakeDayData();
        $updatedDay = $this->dayRepo->update($fakeDay, $day->id);
        $this->assertModelData($fakeDay, $updatedDay->toArray());
        $dbDay = $this->dayRepo->find($day->id);
        $this->assertModelData($fakeDay, $dbDay->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDay()
    {
        $day = $this->makeDay();
        $resp = $this->dayRepo->delete($day->id);
        $this->assertTrue($resp);
        $this->assertNull(Day::find($day->id), 'Day should not exist in DB');
    }
}
