# Livewire Components

This package uses Livewire 3 for interactive components. All components are optimized for Laravel 12.

## Component Location

In Livewire 3, components are located in `\App\Livewire` instead of `\App\Http\Livewire` as in previous versions.

## Available Components

### Dashboard Components

#### DashboardStats
Displays statistics on the dashboard.

```php
// Usage in Blade
<livewire:dashboard-stats />
```

#### RecentActivity  
Displays recent activities.

```php
// Usage in Blade
<livewire:recent-activity :limit="10" />
```

### User Management Components

#### UserTable
Interactive table for user management.

```php
// Usage in Blade
<livewire:user-table />
```

#### UserForm
Form for creating/editing users.

```php
// Usage in Blade
<livewire:user-form :user="$user" />
```

### Content Management Components

#### ContentEditor
Rich text editor for content editing.

```php
// Usage in Blade
<livewire:content-editor :content="$content" />
```

#### MediaLibrary
Media library for file management.

```php
// Usage in Blade
<livewire:media-library />
```

## FluxUI Integration

All components use FluxUI components for consistent styling:

### Buttons
```blade
<flux:button variant="primary" wire:click="save">
    Save
</flux:button>

<flux:button variant="secondary" wire:click="cancel">
    Cancel  
</flux:button>
```

### Forms
```blade
<flux:input 
    label="Name" 
    wire:model="name" 
    placeholder="Enter name"
/>

<flux:select 
    label="Status" 
    wire:model="status"
    :options="$statusOptions"
/>

<flux:checkbox 
    label="Active" 
    wire:model="active"
/>
```

### Tables
```blade
<flux:table>
    <flux:columns>
        <flux:column>Name</flux:column>
        <flux:column>Email</flux:column>
        <flux:column>Status</flux:column>
        <flux:column>Actions</flux:column>
    </flux:columns>
    
    <flux:rows>
        @foreach($users as $user)
            <flux:row wire:key="{{ $user->id }}">
                <flux:cell>{{ $user->name }}</flux:cell>
                <flux:cell>{{ $user->email }}</flux:cell>
                <flux:cell>
                    <flux:badge :variant="$user->active ? 'success' : 'danger'">
                        {{ $user->active ? 'Active' : 'Inactive' }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>
                    <flux:button size="sm" wire:click="edit({{ $user->id }})">
                        Edit
                    </flux:button>
                </flux:cell>
            </flux:row>
        @endforeach
    </flux:rows>
</flux:table>
```

### Modals
```blade
<flux:modal name="user-modal" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit User</flux:heading>
        </div>

        <div class="space-y-4">
            <flux:input 
                label="Name" 
                wire:model="form.name" 
            />
            
            <flux:input 
                label="Email" 
                type="email"
                wire:model="form.email" 
            />
        </div>

        <div class="flex space-x-2">
            <flux:button type="submit" variant="primary">
                Save
            </flux:button>
            
            <flux:button variant="ghost" x-on:click="$dispatch('modal-close', { name: 'user-modal' })">
                Cancel
            </flux:button>
        </div>
    </div>
</flux:modal>
```

## Component Structure

### Basic Component
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10;
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        return view('livewire.user-table', [
            'users' => $this->getUsers()
        ]);
    }
    
    private function getUsers()
    {
        return User::query()
            ->when($this->search, fn($query) => 
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->paginate($this->perPage);
    }
}
```

### Form Component with Validation
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class UserForm extends Component
{
    #[Validate('required|min:3')]
    public $name = '';
    
    #[Validate('required|email|unique:users,email')]
    public $email = '';
    
    #[Validate('boolean')]
    public $active = true;
    
    public function save()
    {
        $this->validate();
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'active' => $this->active,
        ]);
        
        session()->flash('message', 'User successfully created!');
        
        return redirect()->route('users.index');
    }
    
    public function render()
    {
        return view('livewire.user-form');
    }
}
```

## Real-time Updates

For real-time updates you can use Laravel Echo:

```php
// In your component
public function getListeners()
{
    return [
        'echo:users,UserCreated' => 'userCreated',
        'echo:users,UserUpdated' => 'userUpdated',
    ];
}

public function userCreated($event)
{
    $this->dispatch('$refresh');
}
```

## Testing

Test your Livewire components with Livewire's testing utilities:

```php
<?php

use Livewire\Livewire;
use App\Livewire\UserTable;

test('can search users', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    
    Livewire::test(UserTable::class)
        ->set('search', 'John')
        ->assertSee('John Doe');
});

test('can create user', function () {
    Livewire::test(UserForm::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();
        
    $this->assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});
```
