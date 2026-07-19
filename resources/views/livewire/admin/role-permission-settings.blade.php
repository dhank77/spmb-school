<div class="flex-1 p-lg max-w-[1280px] w-full mx-auto">
    <!-- Header Section -->
    <header class="flex justify-between items-end mb-xl">
        <div class="space-y-xs">
            <h2 class="font-display-lg text-headline-md lg:text-display-lg text-on-background">Role & Permission Settings</h2>
            <p class="font-body-lg text-body-md text-on-surface-variant max-w-2xl">Manage dynamic access levels and modular permissions for school staff.</p>
        </div>
        
        <button x-on:click="$flux.modal('create-role-modal').show()" class="flex items-center gap-xs bg-primary text-on-primary px-lg py-sm rounded-lg font-label-md font-bold hover:shadow-lg transition-all active:scale-95">
            <span class="material-symbols-outlined">person_add</span>
            Create New Role
        </button>
    </header>

    <flux:modal name="create-role-modal" class="max-w-lg" x-on:role-created.window="$flux.modal('create-role-modal').close()">
        <form wire:submit.prevent="createRole" class="space-y-6">
            <div>
                <flux:heading size="lg">Create New Role</flux:heading>
                <flux:subheading>Enter the name of the new role. It will be automatically saved in the database.</flux:subheading>
            </div>

            <flux:input wire:model="newRoleName" label="Role Name" placeholder="e.g. Admissions Coordinator" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">Create Role</flux:button>
            </div>
        </form>
    </flux:modal>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 bg-primary/10 border border-primary/30 text-primary px-4 py-3 rounded-lg relative">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div x-data="{ showSuccessMessage: false }" @permissions-saved.window="showSuccessMessage = true; setTimeout(() => showSuccessMessage = false, 3000)">
        
        <div x-show="showSuccessMessage" style="display: none;" class="mb-4 bg-primary/10 border border-primary/30 text-primary px-4 py-3 rounded-lg relative">
            <span class="block sm:inline">Permissions saved successfully.</span>
        </div>

        <!-- Bento Layout for Roles and Matrix -->
        <div class="grid grid-cols-12 gap-gutter">
            <!-- Roles List (Col 5) -->
            <section class="col-span-12 lg:col-span-5 flex flex-col gap-sm">
                <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant shadow-[0px_4px_20px_rgba(0,0,0,0.05)]">
                    <div class="flex items-center justify-between mb-md">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Existing Roles</h3>
                        <span class="bg-primary-fixed text-on-primary-fixed px-xs py-[2px] rounded-full text-label-sm">{{ count($roles) }} Active</span>
                    </div>
                    
                    <div class="space-y-sm">
                        @foreach($roles as $role)
                            <!-- Role Item -->
                            <div wire:click="selectRole({{ $role->id }})" 
                                class="group flex items-center justify-between p-sm rounded-lg transition-all cursor-pointer {{ $selectedRole && $selectedRole->id === $role->id ? 'border-2 border-primary bg-secondary-container/10 shadow-sm' : 'border border-outline-variant hover:border-primary hover:bg-surface-container-low' }}">
                                
                                <div class="flex items-center gap-sm">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $selectedRole && $selectedRole->id === $role->id ? 'bg-primary text-on-primary' : 'bg-primary-container/10 text-primary' }}">
                                        @if($role->name === 'super_admin')
                                            <span class="material-symbols-outlined">shield_person</span>
                                        @elseif($role->name === 'admissions_officer')
                                            <span class="material-symbols-outlined">assignment_ind</span>
                                        @elseif($role->name === 'finance_staff')
                                            <span class="material-symbols-outlined">account_balance_wallet</span>
                                        @elseif($role->name === 'cbt_proctor')
                                            <span class="material-symbols-outlined">laptop_mac</span>
                                        @else
                                            <span class="material-symbols-outlined">group</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-label-md font-bold text-on-surface">{{ str()->headline($role->name) }}</p>
                                        <p class="font-label-sm text-on-surface-variant">{{ $role->users_count }} Users Assigned</p>
                                    </div>
                                </div>
                                
                                @if($selectedRole && $selectedRole->id === $role->id)
                                    <span class="material-symbols-outlined text-primary">edit</span>
                                @else
                                    <button class="text-primary font-label-sm flex items-center gap-base opacity-0 group-hover:opacity-100 transition-opacity">
                                        Edit Permissions <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Informational Card -->
                <div class="bg-tertiary-container/10 rounded-xl p-md border border-tertiary-fixed-dim/30">
                    <div class="flex gap-sm">
                        <span class="material-symbols-outlined text-tertiary">info</span>
                        <div class="space-y-xs">
                            <h4 class="font-label-md font-bold text-tertiary">Security Tip</h4>
                            <p class="font-body-md text-label-sm text-on-surface-variant leading-relaxed">Changes to permissions are logged in the global audit trail. Ensure users are notified of access changes.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Permission Matrix (Col 7) -->
            <section class="col-span-12 lg:col-span-7">
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-[0px_4px_20px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col h-full">
                    
                    @if($selectedRole)
                        <!-- Matrix Header -->
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
                            <div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface">Editing: {{ str()->headline($selectedRole->name) }}</h3>
                                <p class="font-label-sm text-on-surface-variant">Update modular permissions for this specific role.</p>
                            </div>
                            <div class="flex gap-sm">
                                <button wire:click="selectRole({{ $selectedRole->id }})" class="px-md py-base font-label-md text-on-surface-variant hover:bg-surface-container-high rounded transition-all">Discard</button>
                                <button wire:click="savePermissions" class="px-md py-base font-label-md bg-primary text-on-primary rounded shadow-sm hover:opacity-90 transition-all">Save Changes</button>
                            </div>
                        </div>
                        
                        <!-- Matrix Scrollable Content -->
                        <div class="p-md space-y-lg custom-scrollbar overflow-y-auto max-h-[700px]">
                            @foreach($permissionsByCategory as $categoryName => $permissions)
                                <div>
                                    <div class="flex items-center gap-xs mb-md border-b border-outline-variant pb-xs">
                                        <span class="material-symbols-outlined text-primary">{{ $permissions[0]['icon'] ?? 'check_circle' }}</span>
                                        <h4 class="font-label-md font-bold uppercase tracking-wider text-primary">{{ $categoryName }}</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center justify-between p-sm bg-surface-container-low rounded-lg">
                                                <span class="font-label-md text-on-surface">{{ $permission['label'] }}</span>
                                                
                                                @php
                                                    $isChecked = in_array($permission['name'], $rolePermissions);
                                                @endphp
                                                <button 
                                                    type="button" 
                                                    wire:click="togglePermission('{{ $permission['name'] }}')"
                                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $isChecked ? 'bg-primary' : 'bg-surface-variant' }}"
                                                    role="switch" 
                                                    aria-checked="{{ $isChecked ? 'true' : 'false' }}">
                                                    <span 
                                                        aria-hidden="true" 
                                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out {{ $isChecked ? 'translate-x-5' : 'translate-x-0' }}">
                                                    </span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-xl text-center flex flex-col items-center justify-center h-full opacity-60">
                            <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-md">shield</span>
                            <h3 class="font-headline-sm text-on-surface">Select a Role</h3>
                            <p class="font-body-md text-on-surface-variant">Choose a role from the left panel to manage its permissions.</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
        
        <!-- Footer Anchor -->
        <footer class="w-full py-xl px-gutter flex flex-col md:flex-row justify-between items-center max-w-max-width-content mt-xl border-t border-outline-variant">
            <div class="flex flex-col md:flex-row gap-lg items-center">
                <p class="font-label-md font-bold text-on-background">Hitech School Admission System</p>
                <p class="font-body-md font-label-sm text-on-surface-variant">© 2024 Hitech School Admission System. Built for Institutional Innovation.</p>
            </div>
            <nav class="flex gap-md mt-md md:mt-0">
                <a class="font-label-sm text-on-surface-variant hover:text-primary transition-opacity" href="#">Privacy Policy</a>
                <a class="font-label-sm text-on-surface-variant hover:text-primary transition-opacity" href="#">Terms of Service</a>
                <a class="font-label-sm text-on-surface-variant hover:text-primary transition-opacity" href="#">Security Disclosure</a>
                <a class="font-label-sm text-on-surface-variant hover:text-primary transition-opacity" href="#">Support</a>
            </nav>
        </footer>
    </div>
</div>
