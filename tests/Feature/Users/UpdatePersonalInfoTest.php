<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Mocks\FakeStorageUrlGenerator;
use Tests\TestCase;
use Tests\TestResponse;

class UpdatePersonalInfoTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $uploadedPhoto;

    protected function setUp()
    {
        parent::setUp();
        Storage::fake('tests');
        config()->set('filesystems.disks.tests', [
            'driver'     => 'local',
            'root'       => Storage::disk('tests')->getAdapter()->getPathPrefix(),
        ]);
        config()->set('medialibrary.disk_name', 'tests');
        config()->set('medialibrary.url_generator', FakeStorageUrlGenerator::class);

        $this->user = create(User::class);
    }

    private function updatePersonalInfo($attributes = [], User $user = null): TestResponse
    {
        $user = $user ?? $this->user;
        $data = [
            'new_photo' => $attributes['new_photo'] ?? $this->uploadedPhoto = UploadedFile::fake()->image('avatar.png'),
            'name'      => $attributes['name'] ?? 'Some Random Name',
            'email'     => $attributes['email'] ?? 'some.random.email@test.com',
        ];
        $uri  = route('users.personal-info-update', ['user' => $user]);

        return $this->putJson($uri, $data);
    }

    /** @test */
    public function it_should_allow_a_user_to_update_his_personal_info()
    {
        $response = $this->actingAs($this->user)
            ->updatePersonalInfo()
            ->assertSuccessful();

        $user = $response->original;
        $this->assertFileExists($user->getFirstMedia('photo')->getPath());
    }

    /** @test */
    public function it_should_not_allow_a_user_to_update_someone_elses_personal_info()
    {
        $anotherUser = create(User::class);
        $this->actingAs($this->user)
            ->updatePersonalInfo([], $anotherUser)
            ->assertForbidden();

        $this->assertNull($anotherUser->getFirstMedia('photo'));
    }

    /** @test */
    public function it_validates_new_photo()
    {
        $this->actingAs($this->user)->updatePersonalInfo(['new_photo' => ''])
            ->assertJsonHasFragmentError('new_photo', __('validation.file', ['attribute' => 'foto']));
        $this->assertNull($this->user->getFirstMedia('photo'));

        $this->actingAs($this->user)->updatePersonalInfo(['new_photo' => UploadedFile::fake()->create('resume.pdf')])
            ->assertJsonHasFragmentError('new_photo', 'A foto deve ser um arquivo do tipo: jpeg, jpg, png.');
        $this->assertNull($this->user->getFirstMedia('photo'));
    }

    /** @test */
    public function it_validates_name()
    {
        $this->actingAs($this->user)->updatePersonalInfo(['name' => ''])
            ->assertJsonHasFragmentError('name', __('validation.required', ['attribute' => 'nome']));
        $this->actingAs($this->user)->updatePersonalInfo(['name' => str_random(256)])
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
        $this->actingAs($this->user)->updatePersonalInfo(['name' => str_random()])
            ->assertSuccessful();
    }

    /** @test */
    public function it_validates_email()
    {
        $this->actingAs($this->user)->updatePersonalInfo(['email' => ''])
            ->assertJsonHasFragmentError('email', __('validation.required', ['attribute' => 'e-mail']));
        $this->actingAs($this->user)->updatePersonalInfo(['email' => str_random()])
            ->assertJsonHasFragmentError('email', __('validation.email', ['attribute' => 'e-mail']));
        $this->actingAs($this->user)->updatePersonalInfo(['email' => str_random(256)])
            ->assertJsonHasFragmentError('email', 'O campo e-mail não pode ser superior a 255 caracteres.');

        // Test uniqueness and ignore it when email is not being changed...
        $user = create(User::class);
        $this->actingAs($this->user)->updatePersonalInfo(['email' => $user->email])
            ->assertJsonHasFragmentError('email', __('validation.unique', ['attribute' => 'e-mail']));
        $this->actingAs($this->user)->updatePersonalInfo(['email' => $this->user->email])
            ->assertSuccessful();
    }
}
