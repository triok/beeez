<?php

namespace Tests\Feature\Jobs;

use App\Models\Jobs\Categories;
use App\Models\Jobs\Jobs;
use App\Permission;
use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreJobTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User $user */
    protected $user;

    /** @var Jobs $job */
    protected $job;

    public function setUp()
    {
        parent::setUp();

        $this->job = make(Jobs::class)->toArray();
        $this->job['categories'] = [create(Categories::class)->id];
        $this->job['time_for_work'] = rand(1,3);

        $this->user = create(User::class);
        $permission = Permission::query()->create([
            'name' => 'read-jobs-manager',
            'display_name' => 'Read Jobs-manager',
            'description' => 'Read Jobs-manager'
        ]);
        $role = create(Role::class, [
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'admin'
        ]);

        $this->user->attachPermission($permission);
        $this->user->attachRole($role);
    }

    public function test_user_can_save_job_by_draft()
    {
        $this->signIn($this->user)
            ->postJson(route('jobs.store'), $this->job)
            ->assertStatus(302);

        $this->assertDatabaseHas('jobs', array_except( $this->job, ['categories']));
    }
}
