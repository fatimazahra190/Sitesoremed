@extends('layouts.admin')
@section('admin-title', 'Paramètres Système')
@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Paramètres Système</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.system-settings.update') }}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Clé</th>
                    <th>Valeur</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->key }}</td>
                    <td><input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-control"></td>
                    <td>{{ $setting->description }}</td>
                </tr>
                @endforeach
                <!-- Paramètres par défaut si la table est vide -->
                @if($settings->isEmpty())
                <tr>
                    <td>site_name</td>
                    <td><input type="text" name="settings[site_name]" value="SOREMED" class="form-control"></td>
                    <td>Nom du site</td>
                </tr>
                <tr>
                    <td>contact_email</td>
                    <td><input type="email" name="settings[contact_email]" value="contact@soremed.com" class="form-control"></td>
                    <td>Email de contact</td>
                </tr>
                <tr>
                    <td>rgpd_enabled</td>
                    <td><input type="checkbox" name="settings[rgpd_enabled]" value="1" checked></td>
                    <td>RGPD activé</td>
                </tr>
                <tr>
                    <td>security_mode</td>
                    <td><select name="settings[security_mode]" class="form-select"><option value="standard">Standard</option><option value="strict">Strict</option></select></td>
                    <td>Niveau de sécurité</td>
                </tr>
                <tr>
                    <td>maintenance_mode</td>
                    <td><input type="checkbox" name="settings[maintenance_mode]" value="1"></td>
                    <td>Mode maintenance</td>
                </tr>
                @endif
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</div>
@endsection
