<?php

namespace Tests\Unit;

use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting child categories.
     *
     * @return void
     */
    public function test_get_child_categories()
    {
        // Create parent category
        $parentCategory = TransactionCategory::factory()->create([
            'name' => 'Food',
            'type' => 'debit',
            'parent_id' => null,
        ]);

        // Create child categories
        $groceries = TransactionCategory::factory()->create([
            'name' => 'Groceries',
            'type' => 'debit',
            'parent_id' => $parentCategory->id,
        ]);

        $restaurants = TransactionCategory::factory()->create([
            'name' => 'Restaurants',
            'type' => 'debit',
            'parent_id' => $parentCategory->id,
        ]);

        // Create unrelated category
        $entertainment = TransactionCategory::factory()->create([
            'name' => 'Entertainment',
            'type' => 'debit',
            'parent_id' => null,
        ]);

        // Get children of parent category
        $children = $parentCategory->children;

        // Assert that the children collection contains the correct categories
        $this->assertCount(2, $children);
        $this->assertTrue($children->contains($groceries));
        $this->assertTrue($children->contains($restaurants));
        $this->assertFalse($children->contains($entertainment));
    }

    /**
     * Test getting parent category.
     *
     * @return void
     */
    public function test_get_parent_category()
    {
        // Create parent category
        $parentCategory = TransactionCategory::factory()->create([
            'name' => 'Income',
            'type' => 'credit',
            'parent_id' => null,
        ]);

        // Create child category
        $childCategory = TransactionCategory::factory()->create([
            'name' => 'Salary',
            'type' => 'credit',
            'parent_id' => $parentCategory->id,
        ]);

        // Get parent of child category
        $parent = $childCategory->parent;

        // Assert that the parent is correct
        $this->assertNotNull($parent);
        $this->assertEquals($parentCategory->id, $parent->id);
        $this->assertEquals('Income', $parent->name);
    }

    /**
     * Test category hierarchy depth.
     *
     * @return void
     */
    public function test_category_hierarchy_depth()
    {
        // Create top-level category
        $topLevel = TransactionCategory::factory()->create([
            'name' => 'Expenses',
            'type' => 'debit',
            'parent_id' => null,
        ]);

        // Create second-level category
        $secondLevel = TransactionCategory::factory()->create([
            'name' => 'Housing',
            'type' => 'debit',
            'parent_id' => $topLevel->id,
        ]);

        // Create third-level category
        $thirdLevel = TransactionCategory::factory()->create([
            'name' => 'Rent',
            'type' => 'debit',
            'parent_id' => $secondLevel->id,
        ]);

        // Assert relationships
        $this->assertEquals($topLevel->id, $secondLevel->parent_id);
        $this->assertEquals($secondLevel->id, $thirdLevel->parent_id);

        // Assert hierarchy
        $this->assertEquals($topLevel->id, $thirdLevel->parent->parent_id);
    }
}
