<?php

namespace AminSamadzadeh\Vispobish\Tests;

class TreeableTest extends TestCase
{
    /** @test */
    public function createChildTest()
    {
        $cat = Category::find(3);
        $childCat = $cat->children()->create(['name' => 'child']);
        $count = Category::where([
            'name' => $childCat->name,
            'path' => $cat->path.'/'.$childCat->parent_id,
            'named_path' => $cat->named_path.'/'.$childCat->name
        ])->count();
        $this->assertEquals($count, 1);
    }

    /** @test */
    public function parentRelationTest()
    {
        $cat = Category::find(2);
        $this->assertEquals($cat->parent()->first()->name, 'root');
    }

    /** @test */
    public function childrenRelationTest()
    {
        $cat = Category::find(1);

        $this->assertEquals($cat->children()->count(), 1);
    }

    /** @test */
    public function descendantsTest()
    {
        $cat = Category::find(1);
        $this->assertEquals($cat->descendants()->count(), 2);
    }

    /** @test */
    public function ancestorsTest()
    {
        $cat = Category::find(3);
        $this->assertEquals($cat->ancestors()->count(), 2);
    }

    /** @test */
    public function parentIdsTest()
    {
        $cat = Category::find(3);
        $this->assertEquals(count($cat->parentIds()), 2);
    }

    /** @test */
    public function autoUpdateChildrenPathTest()
    {
        $super = Category::create(['name'=> 'super']);
        $cat = Category::find(1);
        $cat->update(['parent_id' => $super->id]);

        foreach (Category::whereNotIn('id',[$super->id])->get() as $category)
            $this->assertStringContainsString('/4', $category->path);
    }


}
