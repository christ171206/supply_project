@extends('layouts.admin')

@section('title', 'Ajouter Mot Bannissant')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.banned-words.index') }}" class="btn btn-link">← Retour</a>
        <h1 class="h3 d-inline-block">➕ Ajouter Mot Bannissant</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.banned-words.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><strong>Mot</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="word" class="form-control @error('word') is-invalid @enderror" 
                                   placeholder="Ex: spam, interdit, etc..." required value="{{ old('word') }}">
                            @error('word')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Description</strong></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Raison du bannissement...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Sévérité</strong> <span class="text-danger">*</span></label>
                            <select name="severity" class="form-select @error('severity') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="low" @selected(old('severity') === 'low')>🔵 Basse (Avertissement)</option>
                                <option value="medium" @selected(old('severity') === 'medium')>🟡 Moyenne (Blocage)</option>
                                <option value="high" @selected(old('severity') === 'high')>🔴 Haute (Urgente)</option>
                            </select>
                            @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   value="1" @checked(old('is_active', true))>
                            <label class="form-check-label" for="is_active">
                                <strong>Activer immédiatement</strong>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100">➕ Ajouter le mot</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>💡 Conseils</h5>
                    <ul>
                        <li>Spécifiez le mot exact à bannir</li>
                        <li>La recherche est insensible à la casse</li>
                        <li>Considérez les variantes du mot</li>
                        <li>Définissez une description claire</li>
                        <li>Sélectionnez la sévérité appropriée</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
