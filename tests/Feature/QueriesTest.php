<?php

namespace AminSamadzadeh\Vispobish\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use AminSamadzadeh\Vispobish\Treeable;

class QueriesTest extends TestCase
{
    use RefreshDatabase;
    protected $capsule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    /** @test */
    public function sampleTest()
    {
        $this->assertTrue(true);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
     /**
     * Setup the database schema.
     *
     * @return void
     */
    public function createSchema()
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();

        $this->capsule->schema()->create('categories', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('named_path')->unique();
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('cms_categories')
                ->onDelete('cascade');
        });
    }

    protected function seedData()
    {
        $data = [
            [
                "id" => 1,
                "name" => "root",
                "path" => null,
                "named_path" => "root",
                "parent_id" => null
           ],
           [
                "id" => 2,
                "name" => "child1",
                "path" => "1",
                "named_path" => "root/child1",
                "parent_id" => 1
            ],
            [
                "id" => 3,
                "name" => "child1.1",
                "path" => "1/2",
                "named_path" => "root/child1/child1.1",
                "parent_id" => 2
            ],
        ];

        Category::insert($data);
    }

}

class Category extends Model
{
    use Treeable;
}