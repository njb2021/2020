<?php

namespace Tests\Unit\Repositories;

use App\Models\Budget;
use App\Models\Space;
use App\Models\Spending;
use App\Models\Tag;
use App\Repositories\BudgetRepository;
use Exception;
use Tests\TestCase;

class BudgetRepositoryTest extends TestCase
{
    private $budgetRepository;
    private $space;
    private $tag;

    public function setUp(): void
    {
        parent::setUp();

        //
        $this->budgetRepository = new BudgetRepository();

        $this->space = factory(Space::class)->create();

        $this->tag = factory(Tag::class)->create([
            'space_id' => $this->space->id
        ]);

        //
        $this->session(['space' => $this->space]);
    }

    public function testGetByIdMethod(): void
    {
        $this->assertNull($this->budgetRepository->getById(999)); // Probably doesn't exist (fingers crossed)

        //
        $budget = factory(Budget::class)->create();

        $this->assertEquals($budget->id, $this->budgetRepository->getById($budget->id)->id);
    }

    public function testGetSpentByIdMethod(): void
    {
        //
        $budget = factory(Budget::class)->create([
            'space_id' => $this->space->id,
            'tag_id' => $this->tag->id,
            'period' => 'monthly',
            'starts_on' => date('Y-m-d', strtotime('first day of this month'))
        ]);

        $this->assertEquals(0, $this->budgetRepository->getSpentById($budget->id));

        //
        factory(Spending::class)->create([
            'space_id' => $this->space->id,
            'tag_id' => $this->tag->id,
            'happened_on' => date('Y-m-d'),
            'amount' => 100
        ]);

        $this->assertEquals(100, $this->budgetRepository->getSpentById($budget->id));

        //
        factory(Spending::class)->create([
            'space_id' => $this->space->id,
            'tag_id' => null,
            'happened_on' => date('Y-m-d')
        ]);

        $this->assertEquals(100, $this->budgetRepository->getSpentById($budget->id));
    }

    public function testThatNonExistingIdThrowsException()
    {
        $this->expectException(Exception::class);

        $this->budgetRepository->getSpentById(999); // Again, probably doesn't exist
    }

    public function testThatUnknownPeriodThrowsException()
    {
        $this->expectException(Exception::class);

        $budget = factory(Budget::class)->create([
            'space_id' => $this->space->id,
            'tag_id' => $this->tag->id,
            'period' => 'not_supported'
        ]);

        $this->budgetRepository->getSpentById($budget->id);
    }
}
