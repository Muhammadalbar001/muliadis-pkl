<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $userId, $name, $username, $email, $password, $role = 'admin';
    public $isModalOpen = false;
    public $isEditMode = false;

    // Reset pagination ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'admin';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:pimpinan,supervisor,admin',
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        session()->flash('message', 'Akun pengguna berhasil dibuat.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Kosongkan agar tidak terubah jika tidak diisi

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|in:pimpinan,supervisor,admin',
            'password' => 'nullable|min:6', // Password boleh kosong saat update
        ]);

        $user = User::findOrFail($this->userId);
        
        $dataToUpdate = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // Hanya update password jika form diisi
        if (!empty($this->password)) {
            $dataToUpdate['password'] = Hash::make($this->password);
        }

        $user->update($dataToUpdate);

        session()->flash('message', 'Data akun berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        // Mencegah supervisor menghapus akunnya sendiri yang sedang dipakai
        if (auth()->id() == $id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('message', 'Akun pengguna telah dihapus permanen.');
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('role', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.user-index', [
            'users' => $users
        ])->layout('layouts.app');
    }
}