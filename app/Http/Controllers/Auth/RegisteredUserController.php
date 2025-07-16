<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Portefeuille;
use App\Models\Parrainage;
use App\Models\CreditBoost;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'pays' => 'string|required',
            'numero' => 'string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'code' => ['nullable', 'string', 'exists:parrainages,code'],
        ], [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'email.unique' => 'Cette adresse email existe déjà.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'code.exists' => 'Ce code de parrainage est invalide.',
        ]);

        $profilePath = null;

        if ($request->hasFile('photo_profil')) {
            $profile = $request->file('photo_profil');
            $profileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $profile->getClientOriginalName());
            $profilePath = $profile->storeAs('images/profils', $profileName, 'public');
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'keyuser' => Str::uuid()->toString(),
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'numero' => $request->numero,
                'pays' => $request->pays,
                'photo_profil' => $profilePath ? Storage::url($profilePath) : '/storage/images/profils/default_profile_ab.jpg',
                'typeUser' => '2',
                'statut' => 'actif'
            ]);

            // 2. Portefeuille
            Portefeuille::create([
                'keyportefeuille' => Str::uuid(),
                'solde' => 0.00,
                'statut' => 'actif',
                'id_user' => $user->id,
                'createdby' => $user->id,
            ]);

            // 3. Code de parrainage
            $emailPart = strtoupper(Str::slug(Str::before($user->email, '@')));
            $codeParrainage = strtoupper(substr($emailPart, 0, 3)) . strtoupper(Str::random(4)) . date('Y');
            Parrainage::create([
                'keyparrainage' => Str::uuid(),
                'code' => $codeParrainage,
                'statut' => 'actif',
                'id_user' => $user->id,
                'createdby' => $user->id
            ]);

            // 4. Crédit boost initial
            CreditBoost::create([
                'keycredit' => Str::uuid(),
                'point' => 0,
                'statut' => 'actif',
                'id_user' => $user->id,
                'createdby' => $user->id
            ]);

            // 5. Si code utilisé
            if ($request->filled('code')) {
                $parrainage = Parrainage::where('code', $request->code)
                                ->where('statut', 'actif')
                                ->first();

                if ($parrainage && $parrainage->user && $parrainage->user->credit) {
                    $parrainage->user->credit->increment('point', 100);
                    $user->credit->increment('point', 50);
                }
            }

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', "Une erreur est survenue lors de l'inscription.");
        }
    }


}
