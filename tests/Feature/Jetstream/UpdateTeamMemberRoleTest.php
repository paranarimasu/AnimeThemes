<?php

declare(strict_types=1);

namespace Jetstream;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class UpdateTeamMemberRoleTest
 * @package Jetstream
 */
class UpdateTeamMemberRoleTest extends TestCase
{
    use RefreshDatabase;

    public function testTeamMemberRolesCanBeUpdated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
            ->set('managingRoleFor', $otherUser)
            ->set('currentRole', 'editor')
            ->call('updateRole');

        static::assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }

    public function testOnlyTeamOwnerCanUpdateTeamMemberRoles()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
            ->set('managingRoleFor', $otherUser)
            ->set('currentRole', 'editor')
            ->call('updateRole')
            ->assertStatus(403);

        static::assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'admin'
        ));
    }
}
