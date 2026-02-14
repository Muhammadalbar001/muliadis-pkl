<section class="space-y-6">
    <header>
        <p class="text-sm text-slate-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Harap unduh data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <div>
        <x-danger-button
            class="rounded-xl px-5 py-2.5 bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:-translate-y-0.5"
            x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Hapus Akun Saya') }}
        </x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('Apakah Anda yakin ingin menghapus akun?') }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    {{ __('Aksi ini tidak dapat dibatalkan. Masukkan password Anda untuk konfirmasi penghapusan permanen.') }}
                </p>
            </div>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Masukkan Password Anda') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl px-4 py-2">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="rounded-xl px-4 py-2 bg-red-600 hover:bg-red-700">
                    {{ __('Ya, Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>